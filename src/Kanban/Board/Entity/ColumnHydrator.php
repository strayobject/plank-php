<?php
declare(strict_types=1);

namespace Plank\Kanban\Board\Entity;

class ColumnHydrator
{
    public function hydrate($data)
    {
        if ($data instanceof \ArrayObject) {
            return $this->singleItemHydrate($data);
        } elseif (is_array($data) || $data instanceof \r\Cursor) {
            return $this->multiItemHydrate($data);
        } else {
            throw new \InvalidArgumentException('No handler for: '.(is_object($data) ? get_class($data): gettype($data)));
        }
    }
    /**
     * @todo check data type and remove cast
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    private function singleItemHydrate($data): Column
    {
        $item = new Column($data['id'], $data['name'], (int) $data['order']);

        return $item;
    }

    private function multiItemHydrate($data): array
    {
        $ret = [];

        foreach ($data as $item) {
            $ret[] = $this->singleItemHydrate($item);
        }

        return $ret;
    }
}
