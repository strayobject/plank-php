<?php
declare(strict_types=1);

namespace Plank\Kanban\Board\Transformer;

use Plank\Kanban\Board\Entity\Column;
use League\Fractal\TransformerAbstract;

class ColumnTransformer extends TransformerAbstract
{
    public function transform(Column $column): array
    {
        return [
            'id' => $column->getId(),
            'name' => $column->getName(),
            'order' => $column->getOrder(),
        ];
    }
}
