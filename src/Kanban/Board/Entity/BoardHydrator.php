<?php
declare(strict_types=1);

namespace Plank\Kanban\Board\Entity;

use Ramsey\Uuid\UuidFactoryInterface;

class BoardHydrator
{
    private $idGen;
    private $columnHydrator;

    public function __construct(
        UuidFactoryInterface $idGen,
        ColumnHydrator $columnHydrator
    ) {
        $this->idGen = $idGen;
        $this->columnHydrator = $columnHydrator;
    }

    public function hydrate($data)
    {
        if (is_array($data) || $data instanceof \ArrayObject) {
            return $this->singleItemHydrate($data);
        } elseif ($data instanceof \r\Cursor) {
            return $this->multiItemHydrate($data);
        } else {
            throw new \InvalidArgumentException('No handler for: '.(is_object($data) ? get_class($data): gettype($data)));
        }
    }

    private function singleItemHydrate($data): Board
    {
        $columns = $this->columnHydrator->hydrate($data['columns']);
        $board = new Board(
            empty($data['id']) ? $this->idGen->uuid4()->toString() : $data['id'],
            $data['ownerId'],
            $data['name'],
            $data['description'],
            empty($data['participants']) ? [$data['ownerId']] : $data['participants'],
            is_array($columns) ? $columns : [$columns]
        );

        return $board;
    }

    private function multiItemHydrate(\r\Cursor $data): array
    {
        $ret = [];

        foreach ($data as $item) {
            $ret[] = $this->singleItemHydrate($item);
        }

        return $ret;
    }
}
