<?php
declare(strict_types=1);

namespace Plank\Kanban\Board\Transformer;

use Plank\Kanban\Board\Entity\Board;
use League\Fractal\{TransformerAbstract, Resource\Collection};

class BoardTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'columns',
    ];

    public function transform(Board $board): array
    {
        return [
            'id' => $board->getId(),
            'ownerId' => $board->getOwnerId(),
            'name' => $board->getName(),
            'description' => $board->getDescription(),
        ];
    }

    public function includeColumns(Board $board): Collection
    {
        $columns = $board->getColumns();

        return $this->collection($columns, new ColumnTransformer, 'columns');
    }
}
