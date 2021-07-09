<?php declare(strict_types=1);

namespace App\Component\User\Persistence;

use App\Component\User\Persistence\Mapper\UserMapperInterface;
use App\DataTransferObject\UserDataProvider;
use App\Repository\UserRepository;

class UserReader implements UserReaderInterface
{
    private UserRepository $userRepository;
    private UserMapperInterface $userMapper;

    /**
     * UserReader constructor.
     * @param UserRepository $userRepository
     * @param UserMapperInterface $userMapper
     */
    public function __construct(
        UserRepository $userRepository,
        UserMapperInterface $userMapper
    )
    {
        $this->userRepository = $userRepository;
        $this->userMapper = $userMapper;
    }

    /**
     * @return UserDataProvider[]
     */
    public function getUserList(): array
    {
        $userList = [];
        $arrayData = $this->userRepository->findAll();  // Entity/User

        foreach ($arrayData as $user) {
            $userList[$user->getId()] = $this->userMapper->mapEntityToDataProvider($user, new UserDataProvider());
        }
        return $userList;
    }

    public function getUserByNameAndPassword(string $username, string $password): ?UserDataProvider
    {
        $userFromDb = $this->userRepository->findOneByUserNameAndPassword($username, $password);

        if (!$userFromDb) {
            return null;
            //throw new \OutOfBoundsException('This userFromDb is not in database');
        }
        return $this->userMapper->mapEntityToDataProvider($userFromDb, new UserDataProvider());
    }


    public function getUserById(int $id): ?UserDataProvider
    {
        $userFromDb = $this->userRepository->findOneById($id);

        if (!$userFromDb) {
            return null;
            //throw new \OutOfBoundsException('This user is not in database');
        }
        return $this->userMapper->mapEntityToDataProvider($userFromDb, new UserDataProvider());
    }

}
