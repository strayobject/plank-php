<?php
declare(strict_types=1);

namespace Plank\Kanban\App\Controller;

use Aerys\{Request, Response};
use League\Fractal\{Resource\Item};
use Plank\Kanban\App\{Responder\ResponderInterface, Transformer\ExceptionTransformer};

class ResponseHandler
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
    public function __invoke(Request $request, Response $response, array $args)
    {
        try {
            $f = $this->controller;
            $resource = $f($request, $response, $args);
            /**
             * Every controller returns a resource. However, some controllers
             * are using "yield" and return an instance of Generator
             * that we must yield a resource from.
             */
            if ($resource instanceof \Generator) {
                $resource = yield from $resource;
            }
            $this->responder->createData($resource)->jsonFinish($response);
        }  catch (\Exception $e) {
            $resource = new Item($e, new ExceptionTransformer(), 'exception');
            $response->setStatus(400);
            $this->responder->createData($resource)->jsonFinish($response);
        } catch (\Throwable $e) {
            $resource = new Item($e, new ExceptionTransformer(), 'exception');
            $response->setStatus(500);
            $this->responder->createData($resource)->jsonFinish($response);
        }
    }
}
