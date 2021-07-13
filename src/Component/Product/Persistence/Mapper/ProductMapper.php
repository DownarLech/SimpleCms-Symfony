<?php declare(strict_types=1);

namespace App\Component\Product\Persistence\Mapper;

use App\DataTransferObject\CsvProductDataProvider;
use App\Entity\Category;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use RuntimeException;

class ProductMapper implements ProductMapperInterface
{
    private CategoryRepository $categoryRepository;


    /**
     * ProductMapper constructor.
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function mapEntityToCsvProductDto(Product $product, CsvProductDataProvider $csvProductDto): CsvProductDataProvider
    {
        $csvProductDto->setId($product->getId());
        $csvProductDto->setName($product->getName());
        $csvProductDto->setDescription($product->getDescription());
        $csvProductDto->setArticleNumber($product->getArticleNumber());
        //What with null here?? When Category is null, in csvProductDP categoryName is "" empty string
        $category = $product->getCategory();
        if ($category instanceof Category) {
            $csvProductDto->setCategoryName($product->getCategory()->getName());
        }

        return $csvProductDto;
    }

    public function mapCsvProductDtoToEntity(CsvProductDataProvider $csvProductDto, Product $product): Product
    {
        $product->setName($csvProductDto->getName());
        $product->setDescription($csvProductDto->getDescription());
        $product->setArticleNumber($csvProductDto->getArticleNumber());

        $categoryName = $csvProductDto->getCategoryName();
        $category = $this->categoryRepository->findOneByName($categoryName);
        //What if Category id=null and name=something
        if (!$category instanceof Category) {
            throw new RuntimeException('Category not found:'.$categoryName);
        }
        $product->setCategory($category);

        return $product;
    }

}