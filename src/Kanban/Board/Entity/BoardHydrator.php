<?php
declare(strict_types=1);

namespace Plank\Kanban\Board\Entity;

class BoardHydrator
{
    public function convert($data)
    {
        if ($data instanceof \ArrayObject) {
            return $this->singleBoardConverter($data);
        } elseif ($data instanceof \r\Cursor) {
            return $this->multiBoardConverter($data);
        } else {
            throw new \InvalidArgumentException('No handler for: '.(is_object($data) ? get_class($data): gettype($data)));
        }
    }

    private function singleBoardConverter($data): Board
    {
        $board = new Board($data['id'], $data['ownerId'], $data['name'], $data['description']);
        $board->setColumns((new ColumnHydrator())->convert($data['columns']));

        return $board;
    }

    private function multiBoardConverter(\r\Cursor $data): array
    {
        $ret = [];

        foreach ($data as $item) {
            $ret[] = $this->singleBoardConverter($item);
        }

        return $ret;
    }
}
