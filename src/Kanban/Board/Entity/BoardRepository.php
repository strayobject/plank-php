<?php
declare(strict_types=1);

namespace Plank\Kanban\Board\Entity;

use r\{function table};

class BoardRepository
{
    const TABLE_NAME = 'boards';
    private $conn;

    public function __construct($dbConn)
    {
        $this->conn = $dbConn;
        $this->hydrator = new BoardHydrator();
    }

    public function getBoards()
    {
        $data = table(self::TABLE_NAME)->run($this->conn);

        return $this->hydrator->convert($data);
    }

    public function save(Board $board): Board
    {

        return $board;
    }

    public function getBoard(string $id)//: Board
    {
        $data = table(self::TABLE_NAME)->get($id)->run($this->conn);

        if (is_null($data)) {
            /**
             * @todo ItemNotFoundException
             */
            throw new \Exception('Board not found.');
        }

        return $this->hydrator->convert($data);
    }
}
