<?php declare(strict_types=1);

namespace App\Component\Product\Persistence;

use App\Component\Product\Persistence\Mapper\ProductMapperInterface;
use App\DataTransferObject\CsvProductDataProvider;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

class ProductManager implements ProductManagerInterface
{
    private ProductRepository $productRepository;
    private ProductMapperInterface $productMapper;
    private EntityManagerInterface $entityManager;

    /**
     * ProductManager constructor.
     * @param ProductRepository $productRepository
     * @param ProductMapperInterface $productMapper
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        ProductRepository $productRepository,
        ProductMapperInterface $productMapper,
        EntityManagerInterface $entityManager
    )
    {
        $this->productRepository = $productRepository;
        $this->productMapper = $productMapper;
        $this->entityManager = $entityManager;
    }

    public function delete(CsvProductDataProvider $product): void
    {
        $productEntity = $this->productRepository->find($product->getId());
        $this->entityManager->remove($productEntity);
        $this->entityManager->flush();
    }

    public function save(CsvProductDataProvider $csvProductDataProvider): CsvProductDataProvider
    {
        if($csvProductDataProvider->hasProductCsvNumber()) {
            $productCsvNumber = $csvProductDataProvider->getProductCsvNumber();
            $product = $this->productRepository->findOneByCsvNumber($productCsvNumber);
        }
        if($csvProductDataProvider->hasId()) {
            $id = $csvProductDataProvider->getId();
            $product = $this->productRepository->findOneById($id);
        }

        if(!isset($product) || !$product instanceof Product) {
            $product = new Product();
        }

        $this->productMapper->mapCsvProductDtoToEntity($csvProductDataProvider, $product);

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $this->productMapper->mapEntityToCsvProductDto($product, new CsvProductDataProvider());
    }
}