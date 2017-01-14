<?php
declare(strict_types=1);

namespace Plank\Kanban\App\Controller;

use Aerys\{Request, Response};
use League\Fractal\{Resource\Item};
use Plank\Kanban\App\{Responder\ResponderInterface, Transformer\ExceptionTransformer};

class AppErrorHandler
{
    /**
     * @var ResponderInterface
     */
    private $responder;
    /**
     * @var \Callable
     */
    private $controller;

    public function __construct(Callable $controller, ResponderInterface $responder)
    {
        $this->controller = $controller;
        $this->responder = $responder;
    }
    public function __invoke(Request $request, Response $response, array $args): void
    {
        try {
            $f = $this->controller;
            $f($request, $response, $args);
        }  catch (\Exception $e) {
            $resource = new Item($e, new ExceptionTransformer(), 'exception');
            $response->setStatus(400);
            $this->responder->createAndFinish($response, $resource);
        } catch (\Throwable $e) {
            $resource = new Item($e, new ExceptionTransformer(), 'exception');
            $response->setStatus(500);
            $this->responder->createAndFinish($response, $resource);
        }
    }
}
