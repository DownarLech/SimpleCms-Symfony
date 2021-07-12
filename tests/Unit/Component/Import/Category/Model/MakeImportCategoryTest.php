<?php declare(strict_types=1);

namespace App\Tests\Unit\Component\Import\Category\Model;

use App\Component\Category\Business\CategoryBusinessFacade;
use App\Component\Category\Business\CategoryBusinessFacadeInterface;
use App\Component\Import\Category\Business\Model\MakeImportCategory;
use App\DataFixtures\CategoryFixture;
use App\DataFixtures\ProductFixture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MakeImportCategoryTest extends KernelTestCase
{
    private MakeImportCategory $makeImportCategory;
    private CategoryBusinessFacadeInterface $categoryBusinessFacade;
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

        $makeImportCategory = static::getContainer()->get(MakeImportCategory::class);
        $this->makeImportCategory = $makeImportCategory;

        $categoryBusinessFacade = static::getContainer()->get(CategoryBusinessFacade::class);
        $this->categoryBusinessFacade = $categoryBusinessFacade;

        $categoryFixture = static::getContainer()->get(CategoryFixture::class);
        $this->categoryFixture = $categoryFixture;

        $productFixture = static::getContainer()->get(ProductFixture::class);
        $this->productFixture = $productFixture;

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
    public function testSaveCategoriesFromCsvFile(): void
    {
        $path = __DIR__ . '/../../../../CsvFile/dataInsert.csv';

        $arraySavedCategories = $this->makeImportCategory->saveCategoriesFromCsvFile($path);

        $categoriesFromDB = $this->categoryBusinessFacade->getCategoryList();

        self::assertCount(4, $categoriesFromDB);

        foreach ($arraySavedCategories as $categoryCsvDto) {
            $categoryFromDb = $this->categoryBusinessFacade->getCategoryById($categoryCsvDto->getId());
            self::assertSame($categoryCsvDto->getId(), $categoryFromDb->getId());
            self::assertSame($categoryCsvDto->getName(), $categoryFromDb->getName());
        }
    }

    /**
     * @throws \League\Csv\Exception
     */
    public function testUpdateSaveCategoriesFromCsvFile(): void
    {
        $path = __DIR__ . '/../../../../CsvFile/dataInsert.csv';
        $arraySavedCategories = $this->makeImportCategory->saveCategoriesFromCsvFile($path);

        $path = __DIR__ . '/../../../../CsvFile/dataUpdate.csv';
        $arrayUpdatedCategories  = $this->makeImportCategory->saveCategoriesFromCsvFile($path);

        $categoriesFromDB = $this->categoryBusinessFacade->getCategoryList();

        self::assertCount(5, $categoriesFromDB);

        foreach ($arrayUpdatedCategories as $categoryCsvDto) {
            $categoryFromDb = $this->categoryBusinessFacade->getCategoryById($categoryCsvDto->getId());
            self::assertSame($categoryCsvDto->getId(), $categoryFromDb->getId());
            self::assertSame($categoryCsvDto->getName(), $categoryFromDb->getName());
        }
    }
}
