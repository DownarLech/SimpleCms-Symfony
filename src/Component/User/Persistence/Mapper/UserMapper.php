<?php declare(strict_types=1);

namespace App\Component\User\Persistence\Mapper;

use App\DataTransferObject\UserDataProvider;
use App\Entity\User;

class UserMapper implements UserMapperInterface
{
    public function mapEntityToDataProvider(User $user, UserDataProvider $userDataProvider): UserDataProvider
    {
        $userDataProvider->setId($user->getId());
        $userDataProvider->setUsername($user->getUserName());
        $userDataProvider->setPassword($user->getPassword());
        $userDataProvider->setUserrole($user->getUserRole());

        return $userDataProvider;
    }

    public function mapDataProviderToEntity(UserDataProvider $userDataProvider, User $user): User
    {
        $user->setUserName($userDataProvider->getUsername());
        $user->setPassword($userDataProvider->getPassword());
        $user->setUserRole($userDataProvider->getUserrole());

        return $user;
    }

}