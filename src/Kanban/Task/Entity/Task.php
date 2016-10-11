<?php
declare(strict_types=1);

namespace Phpkanban\Task\Entity;

use Ramsey\Uuid\Uuid;

class Task implements \JsonSerializable
{
    private $id;
    private $title;
    private $description;

    public function __construct(string $title, string $description)
    {
        $this->id = Uuid::uuid4()->serialize();
        $this->title = $title;
        $this->description = $description;
    }

    public function jsonSerialize() : array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
        ];
    }

    /**
     * Gets the value of id.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gets the value of title.
     *
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the value of title.
     *
     * @param mixed $title the title
     *
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Gets the value of description.
     *
     * @return mixed
     */
    public function getDescription()
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
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }
}
