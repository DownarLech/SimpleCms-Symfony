<?php declare(strict_types=1);

namespace App\DataFixtures;

use App\Component\Category\Persistence\CategoryManagerInterface;
use App\DataTransferObject\CategoryDataProvider;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixture extends Fixture
{
    private CategoryManagerInterface $categoryManager;

    /**
     * CategoryFixture constructor.
     * @param CategoryManagerInterface $categoryManager
     */
    public function __construct(CategoryManagerInterface $categoryManager)
    {
        $this->categoryManager = $categoryManager;
    }


    public function load(ObjectManager $manager): void
    {
        $this->truncateTable($manager);
        $this->createTemporaryCategories($this->getData());

    }

    public function loadUpdate(ObjectManager $manager): void
    {
        $this->truncateTable($manager);
        $this->createTemporaryCategories($this->getUpdate());
    }

    /**
     * @param ObjectManager $manager
     */
    public function truncateTable(ObjectManager $manager): void
    {
        $connection = $manager->getConnection();
        $platform = $connection->getDatabasePlatform();

        //$connection->executeQuery('ALTER TABLE product DROP COLUMN category_id');
        $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 0;');
        $connection->executeQuery('DELETE FROM category');
        $connection->executeQuery('ALTER TABLE category AUTO_INCREMENT = 1');
        $connection->executeQuery('UPDATE product SET category_id = null');
        $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 1;');
        $manager->clear();
    }

    public function createTemporaryCategories(array $categoryList): array
    {
        $categoryDtoList = [];

        foreach ($categoryList as $category) {
            $categoryDto = new CategoryDataProvider();
            $categoryDto->setName($category['categoryName']);

            $categoryDtoList[] = $this->categoryManager->save($categoryDto);
        }
        return $categoryDtoList;
    }


    public function getData(): array
    {
        return [
            [
                'categoryName' => 'tablet'
            ],
            [
                'categoryName' => 'smartphone'
            ],
            [
                'categoryName' => 'laptop'
            ]
        ];
    }

    public function getUpdate(): array
    {
        return [
            [
                'categoryName' => 'tablet'
            ],
            [
                'categoryName' => 'smartphone'
            ],
            [
                'categoryName' => 'laptop'
            ],
            [
                'categoryName' => 'camera'
            ]
        ];
    }
}