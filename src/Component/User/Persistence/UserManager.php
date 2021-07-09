<?php declare(strict_types=1);

namespace App\Component\User\Persistence;

use App\Component\User\Persistence\Mapper\UserMapperInterface;
use App\DataTransferObject\UserDataProvider;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserManager implements UserManagerInterface
{
    private UserRepository $userRepository;
    private UserMapperInterface $userMapper;
    private EntityManagerInterface $entityManager;

    /**
     * UserManager constructor.
     * @param UserRepository $userRepository
     * @param UserMapperInterface $userMapper
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        UserRepository $userRepository,
        UserMapperInterface $userMapper,
        EntityManagerInterface $entityManager
    )
    {
        $this->userRepository = $userRepository;
        $this->userMapper = $userMapper;
        $this->entityManager = $entityManager;
    }


    public function delete(UserDataProvider $user): void
    {
        $userEntity = $this->userRepository->find($user->getId());
        $this->entityManager->remove($userEntity);
        $this->entityManager->flush();
    }

    public function save(UserDataProvider $userDataProvider): UserDataProvider
    {
        // $user = null;
        if($userDataProvider->hasId()) {
            $id = $userDataProvider->getId();
            $user = $this->userRepository->findOneById($id); //better use UserReader???
        }

        if(!isset($user) || !$user instanceof User) {
            $user = new User();
        }

        $this->userMapper->mapDataProviderToEntity($userDataProvider, $user);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->userMapper->mapEntityToDataProvider($user, new UserDataProvider());
    }

}