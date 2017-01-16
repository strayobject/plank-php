<?php
declare(strict_types=1);

namespace Plank\Kanban\App\Responder;

use Aerys\Response;
use League\Fractal\{Manager, Resource\ResourceInterface, Scope};

/**
 * @todo we should be able to replace serializers without affecting code using
 * this class. Need to add that functionality, and make current serializer a default one.
 */
class Responder implements ResponderInterface
{
    /**
     * @var Manager
     */
    private $outputManager;
    /**
     * @var Scope
     */
    private $data;

    public function __construct(Manager $outputManager)
    {
        $this->outputManager = $outputManager;
    }

    public function createData(ResourceInterface $resource): Responder
    {
        $this->data = $this->outputManager->createData($resource);

        return $this;
    }

    public function getData(): Scope
    {
        return $this->data;
    }

    public function jsonFinish(Response $response): void
    {
        $response->addHeader('Content-Type', 'application/json');
        $data = $this->data->toJson();
        $this->data = null;
        $response->end($data);
    }
}
