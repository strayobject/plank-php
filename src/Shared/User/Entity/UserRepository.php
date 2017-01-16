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

    public function getUsers(): array
    {
        /**
         * @todo move rethink calls to a provider or catch driver exceptions
         */
        $data = table(self::TABLE_NAME)->run($this->conn);

        if (is_null($data) || iterator_count($data) < 1) {
            throw new ItemNotFoundException('User not found.');
        }

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

        if (is_null($data) || iterator_count($data) < 1) {
            throw new ItemNotFoundException('User not found.');
        }

        return $this->hydrator->hydrate($data);
    }

    public function getUsersBy(array $criteria): array
    {
        /**
         * @todo move rethink calls to a provider or catch driver exceptions
         */

        if (isset($criteria['email'])) {
            $email = $criteria['email'];
            unset($criteria['email']);
            $data = table(self::TABLE_NAME)
                ->getAll($email, ['index' => 'email'])
                ->filter($criteria)
                ->run($this->conn)
            ;
        } else {
            $data = table(self::TABLE_NAME)
                ->filter($criteria)
                ->run($this->conn)
            ;
        }

        if (is_null($data) || iterator_count($data) < 1) {
            throw new ItemNotFoundException('User not found.');
        }

        $users = $this->hydrator->hydrate($data);

        return is_array($users) ? $users : [$users];
    }

    public function getUserForAuth(string $email): User
    {
        $data = table(self::TABLE_NAME)
            ->getAll($email, ['index' => 'email'])
            ->run($this->conn)
        ;

        if (is_null($data) || is_null($data->current())) {
            throw new ItemNotFoundException('User not found.');
        }

        return $this->hydrator->hydrate($data)[0];
    }
}
