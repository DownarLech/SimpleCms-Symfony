<?php declare(strict_types=1);


namespace App\DataFixtures;


use App\Component\User\Persistence\UserManagerInterface;
use App\DataTransferObject\UserDataProvider;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends Fixture
{

    private UserManagerInterface $userManager;

    /**
     * UserFixture constructor.
     * @param UserManagerInterface $userManager
     */
    public function __construct(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }


    public function load(ObjectManager $manager): void
    {
        $this->truncateTable($manager);
        $this->createTemporaryUsers($this->getData());
    }

    public function loadUpdate(ObjectManager $manager): void
    {
        $this->truncateTable($manager);
        $this->createTemporaryUsers($this->getUpdate());
    }

    /**
     * @param ObjectManager $manager
     */
    public function truncateTable(ObjectManager $manager): void
    {
        $connection = $manager->getConnection();
        $platform = $connection->getDatabasePlatform();

        $connection->executeUpdate($platform->getTruncateTableSQL('user'));
        $manager->clear();
    }


    public function createTemporaryUsers(array $userList): array
    {
        $userDtoList = [];

        foreach ($userList as $user) {
            $userDataTransferObject = new UserDataProvider();
            $userDataTransferObject->setUserName($user['username']);
            $userDataTransferObject->setPassword($user['password']);
            $userDataTransferObject->setUserRole($user['userrole']);

            $userDtoList[] = $this->userManager->save($userDataTransferObject);
        }
        return $userDtoList;
    }

    public function getData(): array
    {
        return [
            [
                'username' => 'John',
                'password' => 'a',
                'userrole' => 'Admin'
            ],
            [
                'username' => 'Mark',
                'password' => 'b',
                'userrole' => 'Customer'
            ],
            [
                'username' => 'Tom',
                'password' => 'c',
                'userrole' => 'Customer'
            ]
        ];
    }


    public function getUpdate(): array
    {
        return [
            [
                'username' => 'John',
                'password' => 'a',
                'userrole' => 'Admin'
            ],
            [
                'username' => 'Mark',
                'password' => 'b',
                'userrole' => 'Customer'
            ],
            [
                'username' => 'Tom',
                'password' => 'c',
                'userrole' => 'Customer'
            ],
            [
                'username' => 'William',
                'password' => 'd',
                'userrole' => 'Customer'
            ]
        ];
    }
}