<?php
declare(strict_types=1);

namespace Plank\Kanban\Board\Entity;

class ColumnHydrator
{
    public function convert($data)
    {
        if ($data instanceof \ArrayObject) {
            return $this->singleItemConverter($data);
        } elseif (is_array($data) || $data instanceof \r\Cursor) {
            return $this->multiItemConverter($data);
        } else {
            throw new \InvalidArgumentException('No handler for: '.get_class($data));
        }
    }
    /**
     * @todo check data type and remove cast
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    private function singleItemConverter($data): Column
    {
        $item = new Column($data['id'], $data['name'], (int) $data['order']);

        return $item;
    }

    private function multiItemConverter($data): array
    {
        $ret = [];

        foreach ($data as $item) {
            $ret[] = $this->singleItemConverter($item);
        }

        return $ret;
    }
}
