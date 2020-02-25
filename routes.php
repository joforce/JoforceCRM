<?php
// Base URL of CRM
include_once('includes/utils/utils.php');
$base_url = parse_url($site_URL)['path'];
// CRM routing list
$routes = getRoutesArray();

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) use ($routes, $base_url) {
    foreach($routes as $route)  {
        $r->addRoute($route['method'], $base_url . $route['pattern'], 'route_handler');
    }
});

// Fetch method and URI
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        // Settings request values
        $_REQUEST = array_merge($_REQUEST, $routeInfo[2]);
        break;
}
