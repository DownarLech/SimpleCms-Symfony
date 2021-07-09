<?php declare(strict_types=1);

namespace App\Tests\Unit\Component\Category\Persistence;

use App\Component\Category\Business\CategoryBusinessFacade;
use App\Component\Category\Business\CategoryBusinessFacadeInterface;
use App\Component\Category\Persistence\CategoryReader;
use App\DataFixtures\CategoryFixture;
use App\DataTransferObject\CategoryDataProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CategoryReaderTest extends KernelTestCase
{
    private CategoryBusinessFacadeInterface $categoryFacade;
    private CategoryFixture $categoryFixture;
    private ?EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        parent::setUp();

        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $categoryFacade = static::getContainer()->get(CategoryBusinessFacade::class);
        $this->categoryFacade = $categoryFacade;

        $categoryFixture = static::getContainer()->get(CategoryFixture::class);
        $this->categoryFixture = $categoryFixture;

        $this->categoryFixture->load($this->entityManager);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }

    public function testGetCategoryList(): void
    {
        $dataFromDb = $this->categoryFacade->getCategoryList();
        $dataGiven = $this->categoryFixture->getData();

        self::assertCount(3, $dataFromDb);
        self::assertSame(1, $dataFromDb[1]->getId());
        self::assertSame('smartphone', $dataFromDb[2]->getName());
        self::assertSame(3, $dataFromDb[3]->getId());
        self::assertSame('laptop', $dataFromDb[3]->getName());

        foreach ($dataFromDb as $dataDb) {
            $data = $dataGiven[$dataDb->getId()-1];
            self::assertSame($dataDb->getName(), $data['categoryName']);
        }
    }

    public function testGetCategoryById(): void
    {
        $dataFromDb = $this->categoryFacade->getCategoryById(3);

        self::assertInstanceOf(CategoryDataProvider::class, $dataFromDb);
        self::assertSame(3, $dataFromDb->getId());
        self::assertSame('laptop', $dataFromDb->getName());
    }

    public function testGetCategoryByIdNotFound(): void
    {
        $dataFromDb = $this->categoryFacade->getCategoryById(0);

        self::assertNull($dataFromDb);
    }

    public function testGetCategoryByName(): void
    {
        $dataFromDb = $this->categoryFacade->getCategoryByName('tablet');

        self::assertInstanceOf(CategoryDataProvider::class, $dataFromDb);

        self::assertSame(1, $dataFromDb->getId());
        self::assertSame('tablet', $dataFromDb->getName());
    }

    public function testGetCategoryByNameNotFound(): void
    {
        $dataFromDb = $this->categoryFacade->getCategoryByName('car');

        self::assertNull($dataFromDb);
    }
}
