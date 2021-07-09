<?php declare(strict_types=1);


namespace App\Component\User\Business;

use App\Component\User\Persistence\UserManagerInterface;
use App\Component\User\Persistence\UserReaderInterface;
use App\DataTransferObject\UserDataProvider;

class UserBusinessFacade implements UserBusinessFacadeInterface
{
    private UserManagerInterface $userManager;
    private UserReaderInterface $userReader;

    /**
     * UserBusinessFacade constructor.
     * @param UserManagerInterface $userManager
     * @param UserReaderInterface $userReader
     */
    public function __construct(
        UserManagerInterface $userManager,
        UserReaderInterface $userReader
    )
    {
        $this->userManager = $userManager;
        $this->userReader = $userReader;
    }


    public function delete(UserDataProvider $user): void
    {
        $this->userManager->delete($user);
    }

    public function save(UserDataProvider $userDataProvider): UserDataProvider
    {
        return $this->userManager->save($userDataProvider);
    }

    /**
     * @return UserDataProvider[]
     */
    public function getUserList(): array
    {
        return $this->userReader->getUserList();
    }

    public function getUserByNameAndPassword(string $username, string $password): ?UserDataProvider
    {
        return $this->userReader->getUserByNameAndPassword($username, $password);
    }

    public function getUserById(int $id): ?UserDataProvider
    {
        return $this->userReader->getUserById($id);
    }
}