<?php
declare(strict_types=1);

chdir(dirname(__DIR__));

require_once 'vendor/autoload.php';

use Aerys\{Host, Root, Router, Request, Response, function websocket};
use Plank\Kanban\Board\Controller\{AddColumnController, ListBoardsController, ShowBoardController, WsBoardController};
use Plank\Kanban\App\Controller\ShowIndexController;
use Plank\Kanban\Task\Controller\AddTaskController;
use Plank\Kanban\Board\Entity\BoardRepository;
use League\Fractal\{Manager, Serializer\DataArraySerializer};
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

$dbConn = r\connect('rdb', 28015);
$dbConn->useDb('plank');

$outputManager = new Manager();
$outputManager->setSerializer(new DataArraySerializer());

$boardRepo = new BoardRepository($dbConn);

$router = new Router();
$router->route('GET', '/', new ShowIndexController($boardRepo, $outputManager));
$router->route('GET', '/b/?', new ListBoardsController($boardRepo, $outputManager));
$router->route('GET', '/b/{id}/?', new ShowBoardController($boardRepo, $outputManager));
$router->route('POST', '/b/{id}/categories', new AddColumnController($boardRepo, $outputManager));
$router->route('POST', '/b/{id}/tasks', new AddTaskController($boardRepo, $outputManager));
$router->route('GET', '/ws/b/{id}', websocket(new WsBoardController($boardRepo, $outputManager)));
// $router->get('/tasks/{id}', $task);
// $router->get('/tasks/?', $task);
// $router->post('/tasks', $task);

$rootDir = new Root(__DIR__.'/web');
$host = new Host();
$host
    ->name('localhost')
    ->expose('*', 7001)
    ->expose('*', 8080)
    ->use($router)
    ->use($rootDir)
;
