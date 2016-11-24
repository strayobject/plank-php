<?php
declare(strict_types=1);

namespace Plank\Kanban\App\Controller;

use Aerys\{Request, Response};
use League\Fractal\{Manager, Resource\Item};
use Plank\Kanban\Task\Entity\TaskRepository;
use Plank\Kanban\Task\Transformer\TaskTransformer;
use Plank\Kanban\App\Exception\{ItemNotFoundException};
use Plank\Kanban\App\Transformer\{ExceptionTransformer};

class AppErrorHandler
{
    /**
     * @var \Callable
     */
    private $responder;
    /**
     * @var Manager
     */
    private $outputManager;

    public function __construct(Manager $outputManager, Callable $responder)
    {
        $this->outputManager = $outputManager;
        $this->responder = $responder;
    }
    public function __invoke(Request $request, Response $response, array $args)
    {
        try {
            $f = $this->responder;
            $f($request, $response, $args);
        } catch (\Throwable $e) {
            $resource = new Item($e, new ExceptionTransformer(), 'exception');
            $response->setStatus(500);
        }

        $data = $this->outputManager->createData($resource)->toJson();
        $response->addHeader('Content-Type', 'application/json');
        $response->end($data);
    }
}
