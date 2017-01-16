<?php
declare(strict_types=1);

namespace Plank\Kanban\Board\Entity;

use Ramsey\Uuid\UuidFactoryInterface;

class ColumnHydrator
{
    private $idGen;

    public function __construct(UuidFactoryInterface $idGen)
    {
        $this->idGen = $idGen;
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
    /**
     * @todo check data type and remove cast
     * @param mixed $data
     * @return Column
     */
    private function singleItemHydrate($data): Column
    {
        $item = new Column(
            empty($data['id']) ? $this->idGen->uuid4()->toString() : $data['id'],
            empty($data['name']) ? 'default' : $data['name'],
            (int) $data['order']
        );

        return $item;
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
