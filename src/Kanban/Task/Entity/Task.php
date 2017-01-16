<?php
declare(strict_types=1);

namespace Plank\Kanban\Task\Entity;

class Task
{
    /**
     * @var string
     */
    private $id = '';
    /**
     * @var string
     */
    private $title = '';
    /**
     * @var string
     */
    private $description = '';
    /**
     * @var int
     */
    private $order = 0;
    /**
     * @var string
     */
    private $boardId = '';
    /**
     * @var string
     */
    private $columnId = '';
    /**
     * @var array
     */
    private $tags = [];

    public function __construct(
        string $id,
        string $title,
        string $description,
        int $order,
        string $boardId,
        string $columnId,
        array $tags
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->order = $order;
        $this->boardId = $boardId;
        $this->columnId = $columnId;
        $this->setTags($tags);
    }

    /**
     * Gets the value of id.
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Gets the value of title.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Sets the value of title.
     *
     * @param string $title the title
     *
     * @return self
     */
    public function setTitle($title): Task
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Gets the value of description.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Sets the value of description.
     *
     * @param string $description the description
     *
     * @return self
     */
    public function setDescription($description): Task
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Gets the value of order.
     *
     * @return int
     */
    public function getOrder(): int
    {
        return $this->order;
    }

    /**
     * Sets the value of order.
     *
     * @param int $order the order
     *
     * @return self
     */
    public function setOrder($order): Task
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Gets the value of boardId.
     *
     * @return string
     */
    public function getBoardId(): string
    {
        return $this->boardId;
    }

    /**
     * Sets the value of boardId.
     *
     * @param string $boardId the board id
     *
     * @return self
     */
    public function setBoardId($boardId): Task
    {
        $this->boardId = $boardId;

        return $this;
    }

    /**
     * Gets the value of columnId.
     *
     * @return string
     */
    public function getColumnId(): string
    {
        return $this->columnId;
    }

    /**
     * Sets the value of columnId.
     *
     * @param string $columnId the column id
     *
     * @return self
     */
    public function setColumnId($columnId): Task
    {
        $this->columnId = $columnId;

        return $this;
    }

    /**
     * Gets the value of tags.
     *
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * Sets the value of tags.
     *
     * @param array $tags the tags
     *
     * @return self
     */
    private function setTags(array $tags): void
    {
        foreach ($tags as $tag) {
            if (!($tag instanceof Tag)) {
                throw new \InvalidArgumentException('Only objects of type "Tag" are allowed');
            }
        }

        $this->tags = $tags;
    }
}
