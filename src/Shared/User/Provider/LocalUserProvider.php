<?php
declare(strict_types=1);

namespace Plank\Shared\User\Provider;

use Plank\Shared\User\Entity\{User, UserRepository};

class LocalUserProvider implements UserProviderInterface
{
    private $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function fetchUserById(string $id): User
    {
        return $this->userRepo->getUser($id);
    }

    public function fetchUsersBy(array $data): array
    {
        return $this->userRepo->getUsersBy($data);
    }

    public function fetchUserForAuth(string $email): User
    {
        return $this->userRepo->getUserForAuth($email);
    }

    public function fetchUserByToken(string $type, string $value): User
    {
        throw new \LogicException('Local user provider is unable to fetch user by token.');
    }
}
