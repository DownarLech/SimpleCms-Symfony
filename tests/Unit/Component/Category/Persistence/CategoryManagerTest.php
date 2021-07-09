<?php declare(strict_types=1);

namespace App\Tests\Unit\Component\Category\Persistence;

use App\Component\Category\Business\CategoryBusinessFacade;
use App\Component\Category\Business\CategoryBusinessFacadeInterface;
use App\DataFixtures\CategoryFixture;
use App\DataTransferObject\CategoryDataProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CategoryManagerTest extends KernelTestCase
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

        $this->entityManager->close();
        $this->entityManager = null;
        parent::tearDown();

    }

    public function testDelete(): void
    {
        $dataFromDb = $this->categoryFacade->getCategoryById(2);
        self::assertInstanceOf(CategoryDataProvider::class, $dataFromDb);

        $this->categoryFacade->delete($dataFromDb);
        self::assertNull($this->categoryFacade->getCategoryById(2));
    }

    public function testSave(): void
    {
        $categoryDataProvider = new CategoryDataProvider();
        $categoryDataProvider->setName('camera');

        $actualData = $this->categoryFacade->save($categoryDataProvider);
        $dataFromDb = $this->categoryFacade->getCategoryById($actualData->getId());

        self::assertSame($actualData->getId(), $dataFromDb->getId());
        self::assertSame('camera', $dataFromDb->getName());
    }


    public function testSaveUpdate(): void
    {
        $categoryDataProvider = new CategoryDataProvider();
        $categoryDataProvider->setId(1);
        $categoryDataProvider->setName('fridge');

        $this->categoryFacade->save($categoryDataProvider);
        $dataFromDb = $this->categoryFacade->getCategoryById(1);

        self::assertSame(1, $dataFromDb->getId());
        self::assertSame('fridge', $dataFromDb->getName());

        $categoryDataProvider = new CategoryDataProvider();
        $categoryDataProvider->setName('fridge');

        $this->categoryFacade->save($categoryDataProvider);
        $dataFromDb = $this->categoryFacade->getCategoryById(1);

        self::assertSame(1, $dataFromDb->getId());
        self::assertSame('fridge', $dataFromDb->getName());
    }

}
