<?php

//include( __DIR__ . '/cli/createTableUsers.php');
try {
    spl_autoload_register(function (string $className) {
        require_once __DIR__ . '/' . str_replace("\\", "/", $className) . '.php';
    });

    $route = $_GET['route'] ?? '';

    $routes = require __DIR__ . '/config/routes.php';

    $isRouteFound = false;

    foreach ($routes as $pattern => $currentRoute) {
        preg_match($pattern, $route, $matches);
        if (!empty($matches)) {
            $isRouteFound = true;
            break;
        }
    }
    
    if (!$isRouteFound) {
        throw new \exceptions\NotFoundException('Страница не найдена');
    }

    unset($matches[0]);

    $controllerName = $currentRoute[0];
    $actionName = $currentRoute[1];

    $controller = new $controllerName();
    $controller->$actionName(...$matches);
} catch (\exceptions\InvalidConfigException | \exceptions\DbException $e) {
    $view = new \view\View(__DIR__ . '/templates');
    $view->renderHtml('500.php', ['error' => $e->getMessage()], 500);
} catch (\exceptions\NotFoundException $e) {
    $view = new \view\View(__DIR__ . '/templates');
    $view->renderHtml('404.php', ['error' => $e->getMessage()], 404);
} catch (\exceptions\UnauthorizedException $e) {
    $view = new \view\View(__DIR__ . '/templates');
    $view->renderHtml('401.php', ['error' => $e->getMessage()], 401);
}