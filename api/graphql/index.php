<?php
chdir('../../');

require_once 'vendor/autoload.php';

use Joforce\GraphQL;
use Joforce\JoHelper;

require_once 'includes/utils/utils.php';
require_once 'config/config.inc.php';
require_once 'includes/main/WebUI.php';

$app = new Slim\App();

$container = $app->getContainer();

$container['db'] = function() use ($adb)  {
    return $adb;
};

$container['errorHandler'] = function ($container) {
    return function ($request, $response, $exception) use ($container) {
        $response_data = ['success' => false, 'message' => $exception->getMessage()];
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

$container['graphql'] = function() use ($adb, $container) {
    $current_user = CRMEntity::getInstance('Users');
    $current_user->retrieveCurrentUserInfoFromFile($container['jwt']->data->userId);
    return new GraphQL(new JoHelper($adb, $current_user));
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
            'user_currency_id' => fetchCurrency($current_user->id),
            'date_format' => $current_user->date_format,
        ]
    ];

    $token = \Firebase\JWT\JWT::encode($jwt_data, $application_unique_key);

    $response_data = ['success' => true, 'token' => $token];
    return $response->withJson($response_data, 200);
});

$app->get('/me', function ($request, $response) use ($container) {

    global $site_URL;

    $user_info = $container['jwt']->data;

    $detailViewModel = \Head_DetailView_Model::getInstance('Users', $user_info->userId);
    $userModel = $detailViewModel->getRecord();

    $user_image = $userModel->getImageDetails();

    $user_profile_url = null;
    if($user_image) {
        if(isset($user_image[0]['id']) && !empty($user_image[0]['id'])) {
            $user_profile_url = $site_URL . $user_image[0]['path'] . '_' . $user_image[0]['name'];
        }
    }

    $user_info->user_profile_url = $user_profile_url;
    $user_info->user_currency_id = fetchCurrency($user_info->userId); 

    return $response->withJson($user_info, 200);
});

// Endpoint for graphql
$app->get('/', function ($request, $response) {

    $requested_data = $request->getQueryParams();

    $queryType = $this->graphql->generateQueryType($requested_data);

    $mutationType = $this->graphql->generateMutationType($requested_data);

    $schema = $this->graphql->schema($queryType, $mutationType);

    $graphql_response = $this->graphql->execute($schema, $requested_data);

    return $response->withJson($graphql_response, 200);
});

// Endpoint for graphql
$app->post('/', function ($request, $response) {

    $requested_data = $request->getParsedBody();

    $queryType = $this->graphql->generateQueryType($requested_data);

    $mutationType = $this->graphql->generateMutationType($requested_data);

    $schema = $this->graphql->schema($queryType, $mutationType);

    $graphql_response = $this->graphql->execute($schema, $requested_data);

    return $response->withJson($graphql_response, 200);
});

$app->run();
