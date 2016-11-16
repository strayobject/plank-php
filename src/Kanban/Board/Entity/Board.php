<?php
declare(strict_types=1);

namespace Plank\Kanban\Board\Entity;

class Board implements \JsonSerializable
{
    private $id = '';
    private $ownerId = '';
    private $participants = [];
    private $name = '';
    private $description = '';
    private $columns = [];

    public function __construct(string $id, string $ownerId, string $name, string $description)
    {
        $this->id = $id;
        $this->ownerId = $ownerId;
        $this->name = $name;
        $this->description = $description;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'ownerId' => $this->ownerId,
            'name' => $this->name,
            'description' => $this->description,
            'columns' => $this->columns,
        ];
    }

    public function addParticipant(string $participant): Board
    {
        $this->participants[$participant] = $participant;

        return $this;
    }

    public function addColumn(Column $column): Board
    {
        $this->categories[$column->getId()] = $column;

        return $this;
    }

    public function removeColumn($column): Board
    {
        unset($this->columns[$column]);

        return $this;
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
    public function setName(string $name): Board
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the value of description.
     *
     * @return mixed
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Sets the value of description.
     *
     * @param mixed $description the description
     *
     * @return self
     */
    public function setDescription($description): Board
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Gets the value of categories.
     *
     * @return mixed
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * Gets the value of participants.
     *
     * @return mixed
     */
    public function getParticipants(): array
    {
        return $this->participants;
    }

    /**
     * Gets the value of ownerId.
     *
     * @return mixed
     */
    public function getOwnerId(): string
    {
        return $this->ownerId;
    }

    /**
     * Sets the value of columns.
     *
     * @param mixed $columns the columns
     *
     * @return self
     */
    public function setColumns($columns): Board
    {
        $this->columns = $columns;

        return $this;
    }
}
