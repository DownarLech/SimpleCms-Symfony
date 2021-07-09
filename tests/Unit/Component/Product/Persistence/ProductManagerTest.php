<?php declare(strict_types=1);

namespace App\Tests\Unit\Component\Product\Persistence;

use App\Component\Category\Business\CategoryBusinessFacade;
use App\Component\Category\Business\CategoryBusinessFacadeInterface;
use App\Component\Product\Business\ProductBusinessFacade;
use App\Component\Product\Business\ProductBusinessFacadeInterface;
use App\DataFixtures\ProductFixture;
use App\DataTransferObject\CategoryDataProvider;
use App\DataTransferObject\CsvProductDataProvider;
use App\DataTransferObject\ProductDataProvider;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProductManagerTest extends KernelTestCase
{
    private ProductBusinessFacadeInterface $productBusinessFacade;
    private CategoryBusinessFacadeInterface $categoryBusinessFacade;
    private ProductFixture $productFixture;
    private ?EntityManagerInterface $entityManager;
    private ProductRepository $productRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $productBusinessFacade = static::getContainer()->get(ProductBusinessFacade::class);
        $this->productBusinessFacade = $productBusinessFacade;

        $categoryBusinessFacade = static::getContainer()->get(CategoryBusinessFacade::class);
        $this->categoryBusinessFacade = $categoryBusinessFacade;

        $productFixture = static::getContainer()->get(ProductFixture::class);
        $this->productFixture = $productFixture;

        $productRepository = static::getContainer()->get(ProductRepository::class);
        $this->productRepository = $productRepository;

        $this->productFixture->load($this->entityManager);

    }

    protected function tearDown(): void
    {

        $this->entityManager->close();
        $this->entityManager = null;

        parent::tearDown();

    }

    public function testDelete(): void
    {
        $dataFromDb = $this->productBusinessFacade->getProductById(3);
        self::assertSame('laptop', $dataFromDb->getCategoryName());

        self::assertInstanceOf(CsvProductDataProvider::class, $dataFromDb);

        $this->productBusinessFacade->delete($dataFromDb);
        self::assertNull($this->productBusinessFacade->getProductById(3));

        $category = $this->categoryBusinessFacade->getCategoryByName('laptop');
        self::assertSame(3, $category->getId());
    }

    public function testSave(): void
    {
        $csvProductDataProvider = new CsvProductDataProvider();
        $csvProductDataProvider->setName('Apple');
        $csvProductDataProvider->setDescription('lorem Apple Iphone');
        $csvProductDataProvider->setCategoryName('smartphone');

        $actualData = $this->productBusinessFacade->save($csvProductDataProvider);
        $dataFromDb = $this->productBusinessFacade->getProductById($actualData->getId());

        self::assertSame($actualData->getId(), $dataFromDb->getId());

        self::assertSame(5, $dataFromDb->getId());
        self::assertSame('Apple', $dataFromDb->getName());
        self::assertSame('lorem Apple Iphone', $dataFromDb->getDescription());
        self::assertSame('smartphone', $dataFromDb->getCategoryName());
    }

    public function testSaveUpdate(): void
    {
        $csvProductDataProvider = new CsvProductDataProvider();
        $csvProductDataProvider->setId(1);
        $csvProductDataProvider->setName('Apple');
        $csvProductDataProvider->setDescription('lorem Apple Iphone');
        $csvProductDataProvider->setCategoryName('tablet');
        //$productDataProvider->setCategory_id($this->productBusinessFacade->addCategoryByName('camera'));

        $this->productBusinessFacade->save($csvProductDataProvider);
        $dataFromDb = $this->productBusinessFacade->getProductById(1);

        self::assertSame(1, $dataFromDb->getId());
        self::assertSame('Apple', $dataFromDb->getName());
        self::assertSame('lorem Apple Iphone', $dataFromDb->getDescription());
        self::assertSame('tablet', $dataFromDb->getCategoryName());
    }

    public function testDeleteOnlyCategoryFromProduct(): void
    {
        $categoryFromDb = $this->categoryBusinessFacade->getCategoryById(3); //laptop
        self::assertInstanceOf(CategoryDataProvider::class, $categoryFromDb);

        $this->categoryBusinessFacade->delete($categoryFromDb);
        self::assertNull($this->categoryBusinessFacade->getCategoryById(3));

        // w BD tabela Category nie mam laptop i w tabel Product dl id 3 category=null
        // ale pobiera z category=laptop. jest jeszcze pamiec podreczna?
        $productFromDb = $this->productBusinessFacade->getProductById(3);

        self::assertSame('Dell', $productFromDb->getName());
        self::assertSame('', $productFromDb->getCategoryName());
        //self::assertNull($productFromDb->getCategoryName());
    }
}
