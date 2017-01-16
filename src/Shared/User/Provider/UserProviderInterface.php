<?php
declare(strict_types=1);

namespace Plank\Shared\User\Provider;

Interface UserProviderInterface
{
    public function fetchUserById(string $id);

    public function fetchUsersBy(array $data);

    public function fetchUserForAuth(string $email);

    public function fetchUserByToken(string $type, string $value);
}
