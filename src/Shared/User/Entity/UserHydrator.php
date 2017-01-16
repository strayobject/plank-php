<?php
declare(strict_types=1);

namespace Plank\Shared\User\Entity;

class UserHydrator
{
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

    private function singleItemHydrate($data): User
    {
        $item = new User(
            $data['id'],
            $data['firstName'],
            $data['lastName'],
            $data['alias'],
            $data['email'],
            $data['password']
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
