<?php
chdir('../../');

require_once 'vendor/autoload.php';

use Joforce\JoHelper;

require_once 'includes/utils/utils.php';
require_once 'config/config.inc.php';
require_once 'includes/main/WebUI.php';

$app = new Slim\App();

$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];

$container = $app->getContainer($configuration);

$container['db'] = function() use ($adb)  {
    return $adb;
};

$container['errorHandler'] = function ($container) {
    return function ($request, $response, $exception) use ($container) {
        $response_data = ['success' => false, 'message' => 'Something went wrong!'];
        return $container['response']->withJson($response_data, 500);
    };
};

$container['notFoundHandler'] = function ($container) {
    return function ($request, $response) use ($container) {
        $response_data = ['success' => false, 'message' => 'Unknown route'];
        return $container['response']->withJson($response_data, 404);
    };
};

$container['notAllowedHandler'] = function ($container) {
    return function ($request, $response, $methods) use ($container) {
        $response_data = ['success' => false, 'message' => 'Unknown route for this method.'];
        return $container['response']->withJson($response_data, 405);
    };
};

$container['joforce'] = function() use ($adb, $container) {
    $current_user = CRMEntity::getInstance('Users');
    $current_user->retrieveCurrentUserInfoFromFile($container['jwt']->data->userId);
    return new JoHelper($adb, $current_user);
};

// JWT Middleware for authentication
$app->add(new \Slim\Middleware\JwtAuthentication([
    "path" => ["/"], // Path to check for authentication
    "passthrough" => ["/authorize"], // Skip authentication for this path
    "secret" => $application_unique_key, // Using application unique key as secret
    "secure" => false, // Allow HTTP call
    "callback" => function ($request, $response, $arguments) use ($container) {
        $container["jwt"] = $arguments["decoded"];
    },
    "error" => function ($request, $response, $arguments) {
        $data["success"] = false;
        $data["message"] = $arguments["message"];
        return $response->withJson($data, 401);
    }
]));

// Authorize routing
$app->post('/authorize', function ($request, $response, $args) use ($app, $container) {
    global $application_unique_key;
    $requested_data = $request->getParsedBody();
    // If credentials are not passed, return error
    if(empty($requested_data['username']) || empty($requested_data['password']))  {
        $response_data = ['success' => false, 'message' => 'Username and password is mandatory'];
        return $response->withJson($response_data, 401);
    }

    // Check credentials are valid
    $current_user = CRMEntity::getInstance('Users');
    $current_user->column_fields['user_name'] = $requested_data['username'];

    // If credentials are wrong, return
    if(!$current_user->doLogin($requested_data['password']))  {
        $response_data = ['success' => false, 'message' => 'Authentication invalid'];
        return $response->withJson($response_data, 401);
    }

    $current_user->id = $current_user->retrieve_user_id($requested_data['username']);
    $current_user->retrieveCurrentUserInfoFromFile($current_user->id);

    $issued_at = time();
    $jwt_data = [
        'iat'  => $issued_at,
        'jti'  => base64_encode(mcrypt_create_iv(32)),
        'iss'  => $container['environment']['SERVER_NAME'],
        'nbf'  => $issued_at + 1,
        'exp'  => $issued_at + 86400,
        'data' => [
            'userId'   => $current_user->id,
            'userName' => $requested_data['username'],
            'crm_timezone' => DateTimeField::getDBTimeZone(),
            'user_timezone' => $current_user->time_zone,
            'user_currency' => $current_user->currency_code,
            'date_format' => $current_user->date_format,
        ]
    ];

    $token = \Firebase\JWT\JWT::encode($jwt_data, $application_unique_key);

    $response_data = ['success' => true, 'token' => $token];
    return $response->withJson($response_data, 200);
});

$app->get('/me', function ($request, $response) use ($container) {

    $user_id = $container['jwt']->data->userId;

    return $response->withJson(['user_id' => $user_id], 200);

});

// Retrieve modules list
$app->get('/modules', function ($request, $response, $args) {

    $jo_response = $this->joforce->getJoModules();

    return $response->withJson($jo_response, 200);
});

// Retrieve module fields
$app->get('/{module:[A-Za-z]+}/fields', function ($request, $response, $args) {

    $jo_response = $this->joforce->getModuleFields($args['module']);

    return $response->withJson($jo_response, 200);
});

// Retrieve module filters
$app->get('/{module:[A-Za-z]+}/filters', function ($request, $response, $args) {

    $jo_response = $this->joforce->getUserFilters($args);

    return $response->withJson($jo_response, 200);
});

// Retrieve filter columns
$app->get('/{module:[A-Za-z]+}/filter/{id:[0-9]+}', function ($request, $response, $args) {

    $jo_response = $this->joforce->getFilterColumns($args);

    return $response->withJson($jo_response, 200);
});

// Retrieve a module record
$app->get('/{module:[A-Za-z]+}/{id:[0-9]+}', function ($request, $response, $args) {

    $jo_response = $this->joforce->retrieve($args['module'], $args['id']);

    return $response->withJson($jo_response, 200);
});

// Add a record
$app->post('/{module:[A-Za-z]+}', function ($request, $response, $args) {

    $requested_data = $request->getParsedBody();

    $jo_response = $this->joforce->syncRecord($requested_data, $args['module']);

    return $response->withJson($jo_response, 200);
});

// Update a record
$app->put('/{module:[A-Za-z]+}/{id:[0-9]+}', function ($request, $response, $args) {

    $requested_data = $request->getParsedBody();

    $jo_response = $this->joforce->syncRecord($requested_data, $args['module'], $args['id']);

    return $response->withJson($jo_response, 200);
});

// Delete a record
$app->delete('/{module:[A-Za-z]+}/{id:[0-9]+}', function ($request, $response, $args) {

    $jo_response = $this->joforce->deleteRecord($args['module'], $args['id']);

    return $response->withJson($jo_response, 200);
});

// Retrieve module records
$app->get('/{module:[A-Za-z]+}/list/{page:[0-9]+}', function ($request, $response, $args) {

    $params = $request->getQueryParams();

    $jo_response = $this->joforce->listRecords($params, $args);

    return $response->withJson($jo_response, 200);
});

// Search and retrieve the records
$app->get('/{module:[A-Za-z]+}/search/{search_key}/{search_value}', function ($request, $response, $args) {

    $params = $request->getQueryParams();

    $jo_response = $this->joforce->listRecords($params, $args);

    return $response->withJson($jo_response, 200);
});

// Retrieve module relations
$app->get('/{module:[A-Za-z]+}/relations', function ($request, $response, $args) {

    $jo_response = $this->joforce->returnRelatedModules($args['module']);

    return $response->withJson($jo_response, 200);
});

// Retrieve related module records
$app->get('/{module:[A-Za-z]+}/{id:[0-9]+}/{related_module:[A-Za-z]+}/{page:[0-9]+}', function ($request, $response, $args) {

    $jo_response = $this->joforce->returnRelatedRecords($args);

    return $response->withJson($jo_response, 200);
});

$app->run();
