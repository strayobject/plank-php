<?php
declare(strict_types=1);

namespace Plank\Kanban\Board\Entity;

use r\{Connection, function table};
use r\Exceptions\RqlDriverError;
use Plank\Kanban\App\Exception\ItemNotFoundException;

class BoardRepository
{
    const TABLE_NAME = 'boards';
    private $conn;

    public function __construct(Connection $dbConn, BoardHydrator $hydrator)
    {
        $this->conn = $dbConn;
        $this->hydrator = $hydrator;
    }

    public function getBoards(string $userId): array
    {
        /**
         * @todo move rethink calls to a provider or catch driver exceptions
         */
        $data = table(self::TABLE_NAME)->filter(['ownerId' => $userId])->run($this->conn);

        return $this->hydrator->hydrate($data);
    }

    /**
     * @todo check if returned data [errors => 1] and log response if so
     * @param  array  $data
     * @return \ArrayObject
     */
    public function persist(array $data): \ArrayObject
    {
        return table(self::TABLE_NAME)->insert($data)->run($this->conn);
    }

    /**
     * Fetches a single board
     *
     * @param  string $id
     * @throws ItemNotFoundException
     * @return Board
     */
    public function getBoard(string $id, string $userId): Board
    {
        /**
         * @todo move rethink calls to a provider or catch driver exceptions
         */
        try {
            $data = table(self::TABLE_NAME)->getAll($id)->filter(['ownerId' => $userId])->run($this->conn);

            if (is_null($data) || is_null($data->current())) {
                throw new ItemNotFoundException('Board not found.');
            }
        } catch (RqlDriverError $e) {
            if (strpos($e->getMessage(), 'No more data available') === 0) {
                throw new ItemNotFoundException('Board not found.');
            }

            throw $e;
        }

        return $this->hydrator->hydrate($data)[0];
    }
}
