<?php
declare(strict_types=1);

namespace Plank\Kanban\App\Transformer;

use League\Fractal\{TransformerAbstract, Resource\Collection};

class ExceptionTransformer extends TransformerAbstract
{
    public function transform(\Exception $e): array
    {
        return [
            'message' => $e->getMessage(),
            'code' => $e->getCode()
        ];
    }
}
