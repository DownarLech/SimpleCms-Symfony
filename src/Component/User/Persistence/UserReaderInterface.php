<?php declare(strict_types=1);


namespace App\Component\User\Persistence;


use App\DataTransferObject\UserDataProvider;

interface UserReaderInterface
{
    public function getUserList(): array;

    public function getUserByNameAndPassword(string $username, string $password): ?UserDataProvider;

    public function getUserById(int $id): ?UserDataProvider;

}