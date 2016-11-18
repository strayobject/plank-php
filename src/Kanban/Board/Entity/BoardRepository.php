<?php
declare(strict_types=1);

namespace Plank\Kanban\Board\Entity;

use r\{function table};
use Plank\Kanban\App\Exception\ItemNotFoundException;

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
        /**
         * @todo move rethink calls to a provider or catch driver exceptions
         */
        $data = table(self::TABLE_NAME)->run($this->conn);

        return $this->hydrator->hydrate($data);
    }

    public function save(Board $board): Board
    {

        return $board;
    }
    /**
     * Fetches a single board
     *
     * @param  string $id
     * @throws ItemNotFoundException
     * @return Board
     */
    public function getBoard(string $id): Board
    {
        /**
         * @todo move rethink calls to a provider or catch driver exceptions
         */
        $data = table(self::TABLE_NAME)->get($id)->run($this->conn);

        if (is_null($data)) {
            throw new ItemNotFoundException('Board not found.');
        }

        return $this->hydrator->hydrate($data);
    }
}
