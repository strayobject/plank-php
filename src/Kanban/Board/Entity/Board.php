<?php
declare(strict_types=1);

namespace Plank\Kanban\Board\Entity;

class Board implements \JsonSerializable
{
    private $id;
    private $url;
    private $name;
    private $description;
    private $categories;
    private $tasks;

    public function __construct(string $name, string $description)
    {
        $this->id = Uuid::uuid4()->serialize();
        $this->url = (new Slugify())->slugify($name);
        $this->name = $name;
        $this->description = $description;
        $this->categories = [];
        $this->tasks = [];
    }

    public function jsonSerialize() : array
    {
        return [
            'id' => $this->id,
            'url' => $this->url,
            'name' => $this->name,
            'description' => $this->description,
            'categories' => $this->categories,
            'tasks' => $this->tasks,
        ];
    }

    public function addTask(Task $task) : void
    {
        $this->tasks[$task->getId()] = $task;
    }

    public function removeTask($task) :void
    {
        unset($this->tasks[$task->getId()]);
    }

    public function addCategory($category) : void
    {
        $this->categories[$category] = $category;
    }

    public function removeCategory($category) :void
    {
        unset($this->categories[$category]);
    }

    /**
     * Gets the value of id.
     *
     * @return mixed
     */
    public function getId() : string
    {
        return $this->id;
    }

    /**
     * Gets the value of url.
     *
     * @return mixed
     */
    public function getUrl() : string
    {
        return $this->url;
    }

    /**
     * Gets the value of name.
     *
     * @return mixed
     */
    public function getName() : string
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
    public function setName(string $name) : Board
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the value of description.
     *
     * @return mixed
     */
    public function getDescription() : string
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
    public function setDescription($description) : Board
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Gets the value of categories.
     *
     * @return mixed
     */
    public function getCategories() : array
    {
        return $this->categories;
    }

    /**
     * Gets the value of tasks.
     *
     * @return mixed
     */
    public function getTasks() : array
    {
        return $this->tasks;
    }
}
