<?php
declare(strict_types=1);

chdir(dirname(__DIR__));

require_once 'vendor/autoload.php';

use Aerys\{Host, Root, Router, Request, Response};
use Phpkanban\Board\Controller\{AddCategoryController, ListBoardsController, ShowBoardController};
use Phpkanban\App\Controller\ShowIndexController;
use Phpkanban\Task\Controller\AddTaskController;
/**
 * Env
 */
date_default_timezone_set('UTC');

/**
 * Config
 */
const AERYS_OPTIONS = [
    "user" => "nobody",
    "keepAliveTimeout" => 60,
    //"deflateMinimumLength" => 0,
];

$task = function(Request $request, Response $response, array $args) {
    $response->end(sprintf('This is your task id: %s', $args['id']));
};


// You can add routes in two ways:
// using route($method, $path, $callable)
// using method functions e.g. get($path, $callable)
$router = new Router();
$router->route('GET', '/', new ShowIndexController());
$router->route('GET', '/boards/{name}', new ShowBoardController());
$router->route('GET', '/boards/?', new ShowBoardController());
$router->route('POST', '/boards/{boardUrl}/categories', new AddCategoryController());
$router->route('POST', '/boards/{boardUrl}/tasks', new AddTaskController());
$router->get('v1/boards', new ListBoardsController());
// $router->get('/tasks/{id}', $task);
// $router->get('/tasks/?', $task);
// $router->post('/tasks', $task);

$rootDir = new Root(dirname(__DIR__).'/web');
$host = new Host();
$host
    ->name('localhost')
    //->expose('*', 8899)
    ->expose('*', 8080)
    ->use($router)
    ->use($rootDir)
;
