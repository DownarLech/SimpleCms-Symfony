<?php declare(strict_types=1);

namespace App\Component\User\Persistence\Mapper;

use App\DataTransferObject\UserDataProvider;
use App\Entity\User;

interface UserMapperInterface
{
    public function mapEntityToDataProvider(User $user, UserDataProvider $userDataProvider): UserDataProvider;

    public function mapDataProviderToEntity(UserDataProvider $userDataProvider, User $user): User;
}