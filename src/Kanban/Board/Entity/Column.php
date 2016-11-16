<?php
declare(strict_types=1);

namespace Plank\Kanban\Board\Entity;

class Column implements \JsonSerializable
{
    private $id;
    private $name;
    private $order;

    public function __construct(string $id, string $name, int $order)
    {
        $this->id = $id;
        $this->name = $name;
        $this->order = $order;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'order' => $this->order,
        ];
    }

    /**
     * Gets the value of id.
     *
     * @return mixed
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Gets the value of name.
     *
     * @return mixed
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the value of name.
     *
     * @param mixed $name the name
     *
     * @return self
     */
    public function setName(string $name): Column
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the value of order.
     *
     * @return mixed
     */
    public function getOrder(): int
    {
        return $this->order;
    }

    /**
     * Sets the value of order.
     *
     * @param mixed $order the order
     *
     * @return self
     */
    public function setOrder($order): Column
    {
        $this->order = $order;

        return $this;
    }
}
