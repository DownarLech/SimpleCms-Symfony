<?php declare(strict_types=1);

namespace App\Tests\Unit\Component\Import\Product\Model;

use App\Component\Import\Business\Category\Model\MakeImportCategory;
use App\Component\Import\Business\Category\Model\MakeImportCategoryInterface;
use App\Component\Import\Business\ImportBusinessFacade;
use App\Component\Import\Business\Product\Model\MakeImportProduct;
use App\Component\Import\Business\Product\Model\MakeImportProductInterface;
use App\Component\Product\Business\ProductBusinessFacade;
use App\Component\Product\Business\ProductBusinessFacadeInterface;
use App\DataFixtures\CategoryFixture;
use App\DataFixtures\ProductFixture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MakeImportProductTest extends KernelTestCase
{
    private MakeImportProductInterface $makeImportProduct;
    private MakeImportCategoryInterface $makeImportCategory;

    private ImportBusinessFacade $importBusinessFacade;
    private ProductBusinessFacadeInterface $productBusinessFacade;
    private CategoryFixture $categoryFixture;
    private ProductFixture $productFixture;
    private ?EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        parent::setUp();

        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $makeImportProduct = static::getContainer()->get(MakeImportProduct::class);
        $this->makeImportProduct = $makeImportProduct;

        $makeImportCategory = static::getContainer()->get(MakeImportCategory::class);
        $this->makeImportCategory = $makeImportCategory;

        $importBusinessFacade = static::getContainer()->get(ImportBusinessFacade::class);
        $this->importBusinessFacade = $importBusinessFacade;

        $productBusinessFacade = static::getContainer()->get(ProductBusinessFacade::class);
        $this->productBusinessFacade = $productBusinessFacade;

        $productFixture = static::getContainer()->get(ProductFixture::class);
        $this->productFixture = $productFixture;

        $categoryFixture = static::getContainer()->get(CategoryFixture::class);
        $this->categoryFixture = $categoryFixture;

        $this->productFixture->truncateTable($this->entityManager);
        $this->categoryFixture->truncateTable($this->entityManager);

    }

    protected function tearDown(): void
    {
        $this->entityManager->close();
        $this->entityManager = null;

        parent::tearDown();
    }

    /**
     * @throws \League\Csv\Exception
     */
    public function testSaveProductsFromCsvFile(): void
    {
        $path = __DIR__ . '/../../../../CsvFile/dataInsert.csv';

        $categoryDtoListFromCsv = $this->importBusinessFacade->saveCategoriesFromCsvFile($path);

        $arraySavedProducts = $this->importBusinessFacade->saveProductsFromCsvFile($path);

        $productListFromDb = $this->productBusinessFacade->getProductList();

        self::assertCount(6, $productListFromDb);

        foreach ($arraySavedProducts as $csvDto) {
            $productFromDb = $this->productBusinessFacade->getProductById($csvDto->getId());
            self::assertSame($csvDto->getId(), $productFromDb->getId());
            self::assertSame($csvDto->getName(), $productFromDb->getName());
            self::assertSame($csvDto->getDescription(), $productFromDb->getDescription());
            self::assertSame($csvDto->getArticleNumber(), $productFromDb->getArticleNumber());
            self::assertSame($csvDto->getCategoryName(), $productFromDb->getCategoryName());
        }
    }

    /**
     * @throws \League\Csv\Exception
     */
    public function testUpdateSaveProductsFromCsvFile(): void
    {
        $path = __DIR__ . '/../../../../CsvFile/dataInsert.csv';

        $categoryDtoListFromCsv = $this->importBusinessFacade->saveCategoriesFromCsvFile($path);

        $arraySavedProducts = $this->importBusinessFacade->saveProductsFromCsvFile($path);

        $path = __DIR__ . '/../../../../CsvFile/dataUpdate.csv';

        $categoryDtoListFromCsv = $this->importBusinessFacade->saveCategoriesFromCsvFile($path);

        $arraySavedProducts = $this->importBusinessFacade->saveProductsFromCsvFile($path);

        $productListFromDb = $this->productBusinessFacade->getProductList();

        self::assertCount(7, $productListFromDb);

        foreach ($arraySavedProducts as $csvDto) {
            $productFromDb = $this->productBusinessFacade->getProductById($csvDto->getId());
            self::assertSame($csvDto->getId(), $productFromDb->getId());
            self::assertSame($csvDto->getName(), $productFromDb->getName());
            self::assertSame($csvDto->getDescription(), $productFromDb->getDescription());
            self::assertSame($csvDto->getArticleNumber(), $productFromDb->getArticleNumber());
            self::assertSame($csvDto->getCategoryName(), $productFromDb->getCategoryName());
        }
    }
}
