<?php declare(strict_types=1);

namespace App\Component\User\Business;


use App\DataTransferObject\UserDataProvider;

interface UserBusinessFacadeInterface
{

    public function delete(UserDataProvider $user): void;

    public function save(UserDataProvider $userDataProvider): UserDataProvider;

    public function getUserList(): array;

    public function getUserByNameAndPassword(string $username, string $password): ?UserDataProvider;

    public function getUserById(int $id): ?UserDataProvider;
}