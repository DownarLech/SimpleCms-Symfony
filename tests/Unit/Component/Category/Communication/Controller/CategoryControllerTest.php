<?php declare(strict_types=1);

namespace App\Tests\Unit\Component\Category\Communication\Controller;

use App\Component\Category\Business\CategoryBusinessFacade;
use App\Component\Category\Business\CategoryBusinessFacadeInterface;
use App\Component\Category\Communication\Controller\CategoryController;
use App\DataFixtures\CategoryFixture;
use App\DataTransferObject\CategoryDataProvider;
use Doctrine\Persistence\ObjectManager;
use ErrorException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class CategoryControllerTest extends WebTestCase
{
    private CategoryFixture $categoryFixture;
    private CategoryBusinessFacadeInterface $categoryBusinessFacade;
    private ?ObjectManager $entityManager;
    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();

        //$kernel = self::bootKernel();

        $this->client = self::createClient();

        $this->entityManager = $this->client->getContainer()
            ->get('doctrine')
            ->getManager();

        $categoryFixture = static::getContainer()->get(CategoryFixture::class);
        $this->categoryFixture = $categoryFixture;

        $categoryBusinessFacade = static::getContainer()->get(CategoryBusinessFacade::class);
        $this->categoryBusinessFacade = $categoryBusinessFacade;

        $this->categoryFixture->truncateTable($this->entityManager);
        $this->categoryFixture->load($this->entityManager);
    }

    protected function tearDown(): void
    {
        $this->entityManager->close();
        $this->entityManager = null;
        parent::tearDown();
    }

    public function testTabletCategoryPage(): void
    {
        $this->client->request(
            'GET',
            '/category/detail/1'
        );
        self::assertResponseStatusCodeSame(200);
        self::assertSelectorTextContains('h1', '1');
        self::assertSelectorTextContains('h2', 'tablet');
    }

    public function testListPage(): void
    {

        $clawrel = $this->client->request(
            'GET',
            '/category/list'
        );

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(200);
        self::assertSelectorTextContains('h1', 'Categories');

        $categoryNames = [];
        $dataFromDB = $this->categoryBusinessFacade->getCategoryList();

        foreach ($dataFromDB as $category) {
            $categoryNames[] = $category->getName();
        }

        $categoryList = $clawrel->filter('a.category_detail_url');
        self::assertCount(3, $categoryList);

        $nodeValue = $categoryList->getNode(1)->nodeValue;
        self::assertContains($nodeValue, $categoryNames);
    }

    public function testCategoryNotFound(): void
    {
        $this->client->request(
            'GET',
            '/category/detail/99999'
        );
        self::assertResponseStatusCodeSame(404);
    }

    public function testDelete(): void
    {
        self::assertInstanceOf(CategoryDataProvider::class,
            $this->categoryBusinessFacade->getCategoryById(1));

        $this->client->request(
            'GET',
            '/category/delete/1'
        );
        self::assertNull($this->categoryBusinessFacade->getCategoryById(1));
    }

    public function testDeleteCategoryNotFound(): void
    {
        $this->client->request(
            'GET',
            '/category/delete/9999'
        );
        self::assertResponseStatusCodeSame(404);
    }


    public function testUpdate(): void
    {
        $csrfToken = static::getContainer()->get(CsrfTokenManagerInterface::class)->getToken('category_update')->getValue();

        $this->client->request(
            'POST',
            '/category/update/1',
            [
                'category_update' => [
                    '_token' => $csrfToken,
                    'name' => 'fridge',
                    'update' => true,
                ],
            ]
        );

        $category = $this->categoryBusinessFacade->getCategoryById(1);
        self::assertSame('fridge', $category->getName());
    }

    public function testUpdateCategoryPage(): void
    {
        $this->client->request(
            'GET',
            '/category/update/1'
        );
        self::assertResponseStatusCodeSame(200);
        self::assertSelectorTextContains('h1', '1');
        self::assertSelectorTextContains('h2', 'tablet');
    }

    public function testUpdateCategoryNotFound(): void
    {
        $this->client->request(
            'POST',
            '/category/update/999999999'
        );
        self::assertResponseStatusCodeSame(404);
    }

    public function testNew(): void
    {
        $csrfToken = static::getContainer()->get(CsrfTokenManagerInterface::class)->getToken('category_add_new')->getValue();

        $this->client->request(
            'POST',
            '/category/new',
            [
                'category_add_new' => [
                    '_token' => $csrfToken,
                    'name' => 'fridge',
                    'save' => true,
                ],
            ]
        );
        self::assertInstanceOf(CategoryDataProvider::class,
            $this->categoryBusinessFacade->getCategoryByName('fridge'));
    }

    public function testNewCategoryPage(): void
    {
        $this->client->request(
            'GET',
            '/category/new'
        );
        self::assertResponseStatusCodeSame(200);
    }

}
