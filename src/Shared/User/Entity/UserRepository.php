<?php
declare(strict_types=1);

namespace Plank\Shared\User\Entity;

use r\{function table};
use Plank\Kanban\App\Exception\ItemNotFoundException;

class UserRepository
{
    const TABLE_NAME = 'users';
    private $conn;

    public function __construct($dbConn)
    {
        $this->conn = $dbConn;
        $this->hydrator = new UserHydrator();
    }

    public function getUsers()
    {
        /**
         * @todo move rethink calls to a provider or catch driver exceptions
         */
        $data = table(self::TABLE_NAME)->run($this->conn);

        return $this->hydrator->convert($data);
    }

    public function save(User $user): User
    {

        return $user;
    }
    /**
     * Fetches a single user
     *
     * @param  string $id
     * @throws ItemNotFoundException
     * @return User
     */
    public function getUser(string $id): User
    {
        /**
         * @todo move rethink calls to a provider or catch driver exceptions
         */
        $data = table(self::TABLE_NAME)->get($id)->run($this->conn);

        if (is_null($data)) {
            throw new ItemNotFoundException('User not found.');
        }

        return $this->hydrator->convert($data);
    }
}
