<?php declare(strict_types=1);

namespace App\Tests\Unit\Component\Import\Category;

use App\Component\Import\Category\Business\CsvCategoryImporter;
use App\DataFixtures\CategoryFixture;
use App\DataFixtures\ProductFixture;
use App\DataTransferObject\CategoryDataProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


class CsvCategoryImporterTest extends KernelTestCase
{
    private CsvCategoryImporter $csvCategoryImporter;
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

        $csvCategoryImporter = static::getContainer()->get(CsvCategoryImporter::class);
        $this->csvCategoryImporter = $csvCategoryImporter;

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
    public function testLoadDataAsCsvDto(): void
    {
        $path = __DIR__ . '/../../../CsvFile/dataInsert.csv';
        $csvDtoList = $this->csvCategoryImporter->loadDataAsCsvDto($path);

        self::assertCount(6, $csvDtoList);

        foreach ($csvDtoList as $categoryCsvDto) {
            self::assertInstanceOf(CategoryDataProvider::class, $categoryCsvDto);
        }
    }

}
