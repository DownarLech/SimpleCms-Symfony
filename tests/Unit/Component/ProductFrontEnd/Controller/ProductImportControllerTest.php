<?php declare(strict_types=1);

namespace App\Tests\Unit\Component\ProductFrontEnd\Controller;

use App\Component\Product\Business\ProductBusinessFacade;
use App\Component\Product\Business\ProductBusinessFacadeInterface;
use App\DataFixtures\CategoryFixture;
use App\DataFixtures\ProductFixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class ProductImportControllerTest extends WebTestCase
{
    private ProductFixture $productFixture;
    private CategoryFixture $categoryFixture;
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

        $categoryFixture = static::getContainer()->get(CategoryFixture::class);
        $this->categoryFixture = $categoryFixture;

        $productBusinessFacade = static::getContainer()->get(ProductBusinessFacade::class);
        $this->productBusinessFacade = $productBusinessFacade;

        $this->productFixture->truncateTable($this->entityManager);
        $this->categoryFixture->truncateTable($this->entityManager);

        copy(__DIR__ . '/../../../CsvFile/dataInsert.csv', __DIR__ . '/../import/dataInsert.csv');
    }

    protected function tearDown(): void
    {
        shell_exec('cd ' . '%kernel.project_dir%/import' . '; rm -rf *.csv');

        $this->entityManager->close();
        $this->entityManager = null;
        parent::tearDown();
    }


    public function testImportMake(): void
    {
        $csrfToken = static::getContainer()->get(CsrfTokenManagerInterface::class)->getToken('product_import')->getValue();
        //$uploadedFile = __DIR__ . '/../../../CsvFile/dataInsert.csv';
        $uploadedFile = new UploadedFile(__DIR__ . '/../import/dataInsert.csv', 'dataInsert.csv');

        $crawler = $this->client->request(
            'POST',
            '/product/import/make',
            [
                'product_import' => [
                    '_token' => $csrfToken,
                ],
            ],
            [
                'product_import' => [
                    'upload_file' => $uploadedFile,
                ],
            ],
            [
                'product_import' => [
                    'send' => true,
                ],
            ]
        );

        self::assertResponseStatusCodeSame(302);
        static::assertResponseRedirects('/product/list');
        $dataFromDB = $this->productBusinessFacade->getProductList();
        self::assertCount(6, $dataFromDB);
    }

    public function testImportMakeFileNotExist(): void
    {
        $this->expectException(FileException::class);
        $uploadedFile = new UploadedFile(__DIR__ . '/../import/data.csv', 'data.csv');
    }

    public function testAsusProductPage(): void
    {
        $this->client->request(
            'GET',
            '/product/import/make'
        );
        self::assertResponseStatusCodeSame(200);
        self::assertSelectorTextContains('h1', 'Import');
    }


}
