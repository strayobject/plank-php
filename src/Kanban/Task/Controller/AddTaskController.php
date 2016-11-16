<?php
declare(strict_types=1);

namespace Plank\Kanban\Task\Controller;

use Aerys\{Request, Response, function parseBody};
use Plank\Kanban\Task\Entity\Task;
use Plank\Kanban\Board\Entity\BoardStore;

class AddTaskController
{
    public function __construct()
    {

    }

    public function __invoke(Request $request, Response $response, array $args)
    {
        $boardUrl = $args['boardUrl'];
        $body = yield parseBody($request);
        $title = trim($body->get('title'));
        $desc = trim($body->get('description'));

        if (empty($title)) {
            $response->setStatus(400);
            $response->end("Title cannot be empty");
            return;
        }

        $task = new Task($title, $desc);
        $b = BoardStore::getBoard($boardUrl);

        $b->addTask($task);
        $response->setStatus(204);
        $response->end(null);
    }
}
