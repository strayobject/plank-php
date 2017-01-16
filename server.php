<?php
declare(strict_types=1);

chdir(dirname(__DIR__));

require_once 'vendor/autoload.php';

use Aerys\{Host, Root, Router, Request, Response, function websocket};

use Plank\Kanban\App\Controller\{ResponseHandler, ShowIndexController};
use Plank\Kanban\App\Responder\Responder;
use Plank\Kanban\App\Middleware\{BasicAuth};
use Plank\Kanban\Board\Controller\{AddBoardController, ListBoardsController, GetBoardController, WsBoardController};
use Plank\Kanban\Board\Controller\{ListBoardColumnsController, AddBoardColumnController};
use Plank\Kanban\Board\Controller\{ListBoardParticipantsController, AddBoardParticipantController};
use Plank\Kanban\Task\Controller\{AddTaskController, ListTasksController, GetTaskController};
use Plank\Shared\User\Controller\{AddUserController, GetUserController, LoginController, LogoutController};

use Plank\Kanban\Board\Entity\{BoardHydrator, BoardRepository, ColumnHydrator};
use Plank\Kanban\Task\Entity\{TaskHydrator, TaskRepository};
use Plank\Shared\User\Entity\{UserHydrator, UserRepository};
use Plank\Shared\User\Provider\LocalUserProvider;


use League\Fractal\{Manager, Serializer\DataArraySerializer};
use Ramsey\Uuid\UuidFactory;


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

$responder = new Responder($outputManager);

$columnHydrator = new ColumnHydrator(new UuidFactory());
$boardHydrator = new BoardHydrator(new UuidFactory(), $columnHydrator);
$taskHydrator = new TaskHydrator(new UuidFactory());

$userHydrator = '';

$boardRepo = new BoardRepository($dbConn, $boardHydrator);
$taskRepo = new TaskRepository($dbConn, $taskHydrator);
$userRepo = new UserRepository($dbConn, $userHydrator);

$localUserProvider = new LocalUserProvider($userRepo);
$basicAuthMiddleware = new BasicAuth($localUserProvider);

$router = new Router();
$router->route('GET', '/', new ShowIndexController($boardRepo, $responder));

$router->route('GET', '/b', new ResponseHandler(new ListBoardsController($boardRepo), $responder));
$router->route('POST', '/b', new ResponseHandler(new AddBoardController($boardRepo, $boardHydrator, $responder), $responder));
$router->route('GET', '/b/{bid}', new ResponseHandler(new GetBoardController($boardRepo), $responder));

$router->route('GET', '/b/{bid}/columns', new ResponseHandler(new ListBoardColumnsController($boardRepo, $responder), $responder));
$router->route('POST', '/b/{bid}/columns', new ResponseHandler(new AddBoardColumnController($boardRepo, $responder), $responder));

$router->route('GET', '/b/{bid}/participants', new ResponseHandler(new ListBoardParticipantsController($boardRepo, $responder), $responder));
$router->route('POST', '/b/{bid}/participants', new ResponseHandler(new AddBoardParticipantController($boardRepo, $responder), $responder));

$router->route('GET', '/ws/b/{bid}', websocket(new WsBoardController($boardRepo, $responder)));

$router->route('GET', '/b/{bid}/t', new ResponseHandler(new ListTasksController($taskRepo, $responder), $responder));
$router->route('POST', '/b/{bid}/t', new ResponseHandler(new AddTaskController($taskRepo, $responder, $taskHydrator), $responder));
$router->route('GET', '/b/{bid}/t/{tid}', new ResponseHandler(new GetTaskController($taskRepo, $responder), $responder));

$router->route('POST', '/u/login', new ResponseHandler(new LoginController($userRepo, $responder), $responder));
$router->route('POST', '/u/logout', new ResponseHandler(new LogoutController($userRepo, $responder), $responder));

$router->route('GET', '/u/{uid}', new ResponseHandler(new GetUserController($userRepo, $responder), $responder));
$router->route('POST', '/u', new ResponseHandler(new AddUserController($userRepo, $responder), $responder));


$rootDir = new Root(__DIR__.'/web');
$host = new Host();
$host
    ->name('localhost')
    ->expose('*', 7001)
    ->expose('*', 8080)
    ->use(new BasicAuth($localUserProvider))
    ->use($router)
    ->use($rootDir)
    ->use(function(Aerys\Request $req, Aerys\Response $res) {
        $res->setStatus(404);
        $res->end("404 - Not Found");
    })
;
