<?php declare(strict_types=1);


namespace App\Component\Product\Business;


use App\Component\Product\Persistence\ProductManagerInterface;
use App\Component\Product\Persistence\ProductReaderInterface;
use App\DataTransferObject\CategoryDataProvider;
use App\DataTransferObject\CsvProductDataProvider;
use App\DataTransferObject\ProductDataProvider;

class ProductBusinessFacade implements ProductBusinessFacadeInterface
{
    private ProductManagerInterface $productManager;
    private ProductReaderInterface $productReader;

    /**
     * ProductBusinessFacade constructor.
     * @param ProductManagerInterface $productManager
     * @param ProductReaderInterface $productReader
     */
    public function __construct(
        ProductManagerInterface $productManager,
        ProductReaderInterface $productReader
    )
    {
        $this->productManager = $productManager;
        $this->productReader = $productReader;
    }


    public function delete(CsvProductDataProvider $product): void
    {
        $this->productManager->delete($product);
    }

    public function save(CsvProductDataProvider $csvProductDataProvider): CsvProductDataProvider
    {
        return $this->productManager->save($csvProductDataProvider);
    }

    /**
     * @return CsvProductDataProvider[]
     */
    public function getProductList(): array
    {
        return $this->productReader->getProductList();
    }

    public function getProductById(int $id): ?CsvProductDataProvider
    {
        return $this->productReader->getProductById($id);
    }

}