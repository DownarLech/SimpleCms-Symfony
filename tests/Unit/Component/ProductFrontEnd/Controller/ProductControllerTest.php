<?php declare(strict_types=1);

namespace App\Tests\Unit\Component\ProductFrontEnd\Controller;


use App\Component\Product\Business\ProductBusinessFacade;
use App\Component\Product\Business\ProductBusinessFacadeInterface;
use App\DataFixtures\ProductFixture;
use App\DataTransferObject\CsvProductDataProvider;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class ProductControllerTest extends WebTestCase
{
    private ProductFixture $productFixture;
    private ProductBusinessFacadeInterface $productBusinessFacade;
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

        $productFixture = static::getContainer()->get(ProductFixture::class);
        $this->productFixture = $productFixture;

        $productBusinessFacade = static::getContainer()->get(ProductBusinessFacade::class);
        $this->productBusinessFacade = $productBusinessFacade;

        $this->productFixture->truncateTable($this->entityManager);
        $this->productFixture->load($this->entityManager);
    }

    protected function tearDown(): void
    {
        $this->entityManager->close();
        $this->entityManager = null;
        parent::tearDown();
    }

    public function testAsusProductPage(): void
    {
        $this->client->request(
            'GET',
            '/product/detail/1'
        );
        self::assertResponseStatusCodeSame(200);
        self::assertSelectorTextContains('h1', '1');
        self::assertSelectorTextContains('h2', 'Asus');
        self::assertSelectorTextContains('h3', 'lorem Asus 102');
    }

    public function testListPage(): void
    {
        $clawrel = $this->client->request(
            'GET',
            '/product/list'
        );

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(200);
        self::assertSelectorTextContains('h1', 'Products');

        $dataFromDB = $this->productBusinessFacade->getProductList();

        $productsList = $clawrel->filter('a.product_detail_url');
        self::assertCount(4, $productsList);

        foreach ($dataFromDB as $productDB) {
            $node = $productsList->getNode($productDB->getId() - 1);
            self::assertSame($node->nodeValue, $productDB->getName());
        }

        $productsList->each(function (Crawler $node, $i) {
            return $node->text();
        });
    }

    public function testProductNotFound(): void
    {
        $this->client->request(
            'GET',
            '/product/detail/99999'
        );
        self::assertResponseStatusCodeSame(404);
    }

    public function testProductIndexPage(): void
    {
        $crowler = $this->client->request(
            'GET',
            '/product'
        );
        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Hello ProductController!');
        self::assertSelectorTextContains('.example-wrapper h1', 'Hello');
    }

    public function testDelete(): void
    {
        self::assertInstanceOf(CsvProductDataProvider::class,
            $this->productBusinessFacade->getProductById(1));

        $this->client->request(
            'GET',
            '/product/delete/1'
        );

        self::assertNull($this->productBusinessFacade->getProductById(1));
    }

    public function testDeleteProductNotFound(): void
    {
        $this->client->request(
            'GET',
            '/product/delete/999999999'
        );
        self::assertResponseStatusCodeSame(404);
    }

    public function testUpdate(): void
    {
        $csrfToken = static::getContainer()->get(CsrfTokenManagerInterface::class)->getToken('product_update')->getValue();

        $crawler = $this->client->request(
            'POST',
            '/product/update/1',
            [
                'product_update' => [
                    '_token' => $csrfToken,
                    'name' => 'newName',
                    'description' => 'abc',
                    'articleNumber' => '777',
                    'categoryName' => '3', //her I need category Id. "categories" => array:1 [0 => "1", 1 => "2"]
                    'update' => true,
                ],
            ]
        );
        $product = $this->productBusinessFacade->getProductById(1);
        self::assertSame('newName', $product->getName());
        self::assertSame('abc', $product->getDescription());
        self::assertSame('777', $product->getArticleNumber());
        self::assertSame('laptop', $product->getCategoryName());
    }

    public function testProductProductPage(): void
    {
        $this->client->request(
            'GET',
            '/product/update/1'
        );
        self::assertResponseStatusCodeSame(200);
        self::assertSelectorTextContains('h1', '1');
        self::assertSelectorTextContains('h2', 'Asus');
    }

    public function testUpdateProductNotFound(): void
    {
        $this->client->request(
            'GET',
            '/product/update/999999999'
        );
        self::assertResponseStatusCodeSame(404);
    }

    public function testNew(): void
    {
        $csrfToken = static::getContainer()->get(CsrfTokenManagerInterface::class)->getToken('product_add_new')->getValue();

        $crawler = $this->client->request(
            'POST',
            '/product/new',
            [
                'product_add_new' => [
                    '_token' => $csrfToken,
                    'name' => 'newName',
                    'description' => 'abc',
                    'articleNumber' => '777',
                    'categoryName' => 'tablet',
                    'save' => true,
                ],
            ]
        );
        $product = $this->productBusinessFacade->getProductById(5); //How get the productId automatically

        self::assertInstanceOf(CsvProductDataProvider::class, $product);
        self::assertSame('newName', $product->getName());
        self::assertSame('abc', $product->getDescription());
        self::assertSame('777', $product->getArticleNumber());
        self::assertSame('tablet', $product->getCategoryName());
    }

    public function testNewProductPage(): void
    {
        $this->client->request(
            'GET',
            '/product/new'
        );
        self::assertResponseStatusCodeSame(200);
    }

    /**
     * @codeCoverageIgnore
     */
    public function testNew2X(): void
    {
        $csrfToken = static::getContainer()->get(CsrfTokenManagerInterface::class)->getToken('product_add_new')->getValue();

        $crawler = $this->client->request(
            'POST',
            '/product/new',
        );

        $crawler2 = $this->client->submitForm('Save', [
            'product_add_new[_token]' => $csrfToken,
            'product_add_new[name]' => 'newName',
            'product_add_new[description]' => 'abc',
            'product_add_new[articleNumber]' => '777',
            'product_add_new[categoryName]' => 'tablet'
        ]);

        //I must submit form somehow :(

        $crawler2->selectButton('Save')->form();

        self::assertInstanceOf(CsvProductDataProvider::class,
            $this->productBusinessFacade->getProductById(5));
    }
}
