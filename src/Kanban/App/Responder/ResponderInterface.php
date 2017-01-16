<?php
declare(strict_types=1);

namespace Plank\Kanban\App\Responder;

use Aerys\Response;
use League\Fractal\Resource\ResourceInterface;

interface ResponderInterface
{
    public function createData(ResourceInterface $resource);
}
