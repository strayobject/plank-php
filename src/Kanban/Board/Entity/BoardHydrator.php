<?php
declare(strict_types=1);

namespace Plank\Kanban\Board\Entity;

class BoardHydrator
{
    public function hydrate($data)
    {
        if ($data instanceof \ArrayObject) {
            return $this->singleItemHydrate($data);
        } elseif ($data instanceof \r\Cursor) {
            return $this->multiItemHydrate($data);
        } else {
            throw new \InvalidArgumentException('No handler for: '.(is_object($data) ? get_class($data): gettype($data)));
        }
    }

    private function singleItemHydrate($data): Board
    {
        $columns = (new ColumnHydrator())->hydrate($data['columns']);
        $board = new Board(
            $data['id'],
            $data['ownerId'],
            $data['name'],
            $data['description'],
            $data['participants'],
            $columns
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
