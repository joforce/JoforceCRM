<?php
chdir('../../');

require_once 'vendor/autoload.php';

use Joforce\GraphQL;
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

$container['db'] = function() use ($adb) {
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

$container['graphql'] = function () use ($adb, $container) {
    $current_user = CRMEntity::getInstance('Users');
    $current_user->retrieveCurrentUserInfoFromFile($container['jwt']->data->userId);
    return new GraphQL(new JoHelper($adb, $current_user));
};

$container['JoHelper'] = function () use ($adb, $container) {
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
    if (empty($requested_data['username']) || empty($requested_data['password'])) {
        $response_data = ['success' => false, 'message' => 'Username and password is mandatory'];
        return $response->withJson($response_data, 401);
    }

    // Check credentials are valid
    $current_user = CRMEntity::getInstance('Users');
    $current_user->column_fields['user_name'] = $requested_data['username'];

    // If credentials are wrong, return
    if (!$current_user->doLogin($requested_data['password'])) {
        $response_data = ['success' => false, 'message' => 'Authentication invalid'];
        return $response->withJson($response_data, 401);
    }

    $current_user->id = $current_user->retrieve_user_id($requested_data['username']);
    $current_user->retrieveCurrentUserInfoFromFile($current_user->id);

    $issued_at = time();
    $jwt_data = [
        'iat'  => $issued_at,
        'jti'  => base64_encode(generateRandomString(30)),
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
	global $site_URL;
       	 $user_info = $jwt_data['data'];
	    $detailViewModel = \Head_DetailView_Model::getInstance('Users', $current_user->id);
            $userModel = $detailViewModel->getRecord();
            $user_image = $userModel->getImageDetails();
            $user_profile_url = null;
            if ($user_image) {
                if (isset($user_image[0]['id']) && !empty($user_image[0]['id'])) { 
                    $user_profile_url = $site_URL . $user_image[0]['path'] . '_' . $user_image[0]['name'];
                }
            }          

	    $user_info['fullname'] = $current_user->first_name.' '.$current_user->last_name;
            $user_info['user_profile_url'] = $user_profile_url;
            $user_info['user_currency_id'] = fetchCurrency($current_user->id);
            $user_info['version'] = 'v2.0';
            $notificationstatus=notificationstatus($current_user->id);
            $user_info['notificationstatus'] = $notificationstatus;

    $response_data = ['success' => true, 'data'=>$user_info, 'token' => $token ,'time_format' => $current_user->hour_format];
    return $response->withJson($response_data, 200);
});

$app->get('/me', function ($request, $response) use ($container) {

    global $site_URL;

    $user_info = $container['jwt']->data;

    $detailViewModel = \Head_DetailView_Model::getInstance('Users', $user_info->userId);
    $userModel = $detailViewModel->getRecord();

    $user_image = $userModel->getImageDetails();

    $user_profile_url = null;
    if ($user_image) {
        if (isset($user_image[0]['id']) && !empty($user_image[0]['id'])) {
            $user_profile_url = $site_URL . $user_image[0]['path'] . '_' . $user_image[0]['name'];
        }
    }

    $user_info->user_profile_url = $user_profile_url;
    $user_info->user_currency_id = fetchCurrency($user_info->userId);

    return $response->withJson($user_info, 200);
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

	$args['request_from'] = 'v1';

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

//function to generate random string.
function generateRandomString($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
  function notificationstatus($user_id){
        global $adb;
        $query = "select id,global,notificationlist from jo_notification_manager where id = ?";
        $result = $adb->pquery($query, array($user_id));
        $rows = $adb->num_rows($result);
       if($rows <= 0){
            $query = "select id,global,notificationlist from jo_notification_manager where id = ?";
            $result = $adb->pquery($query, array(0));
            $rows = $adb->num_rows($result);
       }
        for ($i=0; $i<$rows; $i++) {
            $row = $adb->query_result_rowdata($result, $i);
            $global_settings = $row['global'];
            $notification_settings = unserialize(base64_decode($row['notificationlist']));
        }
        return $global_settings;
    }

