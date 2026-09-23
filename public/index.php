<?php

require_once __DIR__ . '/../bootstrap/app.php';
require_once __DIR__ . '/../routes/web.php';

// Request URI and Method
$requestUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$requestUri = '/' . trim($requestUri, '/');
$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';

$matchedRoute = null;
$routeParams = [];

foreach (Route::$routes as $route) {
    if ($route['method'] !== $requestMethod) {
        continue;
    }

    // Convert route pattern {param} to regex
    $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $route['uri']);
    $pattern = '#^' . $pattern . '$#';

    if (preg_match($pattern, $requestUri, $matches)) {
        array_shift($matches); // remove full match
        $matchedRoute = $route;
        $routeParams = $matches;
        break;
    }
}

if (!$matchedRoute) {
    http_response_code(404);
    echo '<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>404 - Not Found</title><link rel="stylesheet" href="/css/style.css"></head><body style="display:flex;align-items:center;justify-content:center;min-height:100vh;text-align:center;"><div class="container"><h1>404</h1><p style="margin: 16px 0; color: var(--text-muted);">Panna nahi mila (Page Not Found)</p><a href="/" class="btn btn-primary">Home Par Jayein</a></div></body></html>';
    exit;
}

$action = $matchedRoute['action'];
$response = null;

if (is_array($action)) {
    [$controllerClass, $method] = $action;
    $controller = new $controllerClass();
    $response = call_user_func_array([$controller, $method], $routeParams);
} elseif (is_callable($action)) {
    $response = call_user_func_array($action, $routeParams);
}

if ($response instanceof RedirectResponse) {
    $response->send();
} else {
    echo $response;
}
