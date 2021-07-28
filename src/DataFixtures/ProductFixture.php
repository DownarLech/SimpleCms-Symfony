<?php declare(strict_types=1);


namespace App\DataFixtures;

use App\Component\Product\Persistence\ProductManagerInterface;
use App\DataTransferObject\CsvProductDataProvider;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixture extends Fixture
{
    private ProductManagerInterface $productManager;
    private CategoryFixture $categoryFixture;

    /**
     * ProductFixture constructor.
     * @param ProductManagerInterface $productManager
     * @param CategoryFixture $categoryFixture
     */
    public function __construct(
        ProductManagerInterface $productManager,
        CategoryFixture $categoryFixture
    )
    {
        $this->productManager = $productManager;
        $this->categoryFixture = $categoryFixture;
    }

    public function load(ObjectManager $manager): void
    {
        $this->truncateTable($manager);
        $this->categoryFixture->load($manager);
        $this->createTemporaryProducts($this->getData());
    }

    public function loadUpdate(ObjectManager $manager): void
    {
        $this->truncateTable($manager);
        $this->categoryFixture->loadUpdate($manager);
        $this->createTemporaryProducts($this->getUpdate());
    }

    /**
     * @param ObjectManager $manager
     */
    public function truncateTable(ObjectManager $manager): void
    {
        $connection = $manager->getConnection();
        $platform = $connection->getDatabasePlatform();

        $connection->executeQuery('DELETE FROM product');
        // AUTO_INCREMENT=0 dont work. Must be = 1
        $connection->executeQuery('ALTER TABLE product AUTO_INCREMENT = 1');

        $manager->clear();
    }

    public function createTemporaryProducts(array $productList): array
    {
        $productDtoList = [];

        foreach ($productList as $product) {
            $productDto = new CsvProductDataProvider();
            $productDto->setName($product['name']);
            $productDto->setDescription($product['description']);
            $productDto->setArticleNumber($product['articleNumber']);
            $productDto->setCategoryName($product['category']);

            $productDtoList[] = $this->productManager->save($productDto);
        }
        return $productDtoList;
    }


    public function getData(): array
    {
        return [
            [
                'name' => 'Asus',
                'description' => 'lorem Asus 102',
                'articleNumber' => '004',
                'category' => 'tablet'
            ],
            [
                'name' => 'Samsung',
                'description' => 'lorem Samsung A1',
                'articleNumber' => '008',
                'category' => 'smartphone'
            ],
            [
                'name' => 'Dell',
                'description' => 'lorem Dell X4',
                'articleNumber' => '034',
                'category' => 'laptop'
            ],
            [
                'name' => 'Lenovo',
                'description' => 'lorem Lenovo',
                'articleNumber' => '077',
                'category' => 'tablet'
            ]
        ];
    }

    public function getUpdate(): array
    {
        return [
            [
                'name' => 'Apple',
                'description' => 'lorem Apple Mac',
                'articleNumber' => '004',
                'category' => 'tablet'
            ],
            [
                'name' => 'Samsung',
                'description' => 'lorem Samsung A1',
                'articleNumber' => '008',
                'category' => 'tablet'
            ],
            [
                'name' => 'Dell',
                'description' => 'lorem Dell X4',
                'articleNumber' => '034',
                'category' => 'laptop'
            ],
            [
                'name' => 'Lenovo',
                'description' => 'lorem Lenovo',
                'articleNumber' => '077',
                'category' => 'smartphone'
            ],
            [
                'name' => 'Nokia',
                'description' => 'lorem Nokia',
                'articleNumber' => '105',
                'category' => 'camera'
            ]
        ];

    }


}