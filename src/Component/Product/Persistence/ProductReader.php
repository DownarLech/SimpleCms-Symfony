<?php declare(strict_types=1);

namespace App\Component\Product\Persistence;

use App\Component\Product\Persistence\Mapper\ProductMapperInterface;
use App\DataTransferObject\CsvProductDataProvider;
use App\Repository\ProductRepository;

class ProductReader implements ProductReaderInterface
{
    private ProductRepository $productRepository;
    private ProductMapperInterface $productMapper;

    /**
     * ProductReader constructor.
     * @param ProductRepository $productRepository
     * @param ProductMapperInterface $productMapper
     */
    public function __construct(
        ProductRepository $productRepository,
        ProductMapperInterface $productMapper
    )
    {
        $this->productRepository = $productRepository;
        $this->productMapper = $productMapper;
    }


    /**
     * @return CsvProductDataProvider[]
     */
    public function getProductList(): array
    {
        $productList = [];

        $arrayData = $this->productRepository->findAll();

        foreach ($arrayData as $product) {
            $productList[$product->getId()] =
                $this->productMapper->mapEntityToCsvProductDto($product, new CsvProductDataProvider());
        }
        return $productList;
    }

    public function getProductById(int $id): ?CsvProductDataProvider
    {
        $dataFromDb = $this->productRepository->findOneById($id);
        if(!$dataFromDb) {
            return null;
        }
        return $this->productMapper->mapEntityToCsvProductDto($dataFromDb, new CsvProductDataProvider());
    }

    public function getProductByArticleNumber(string $articleNumber): ?CsvProductDataProvider
    {
        $dataFromDb = $this->productRepository->findOneByArticleNumber($articleNumber);
        if(!$dataFromDb) {
            return null;
        }
        return $this->productMapper->mapEntityToCsvProductDto($dataFromDb, new CsvProductDataProvider());
    }
}