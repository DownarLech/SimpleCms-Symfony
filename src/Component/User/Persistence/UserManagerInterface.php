<?php declare(strict_types=1);


namespace App\Component\User\Persistence;


use App\DataTransferObject\UserDataProvider;

interface UserManagerInterface
{
    public function delete(UserDataProvider $userDataProvider): void;

    public function save(UserDataProvider $userDataProvider): UserDataProvider;

}