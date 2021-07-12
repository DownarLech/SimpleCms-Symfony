<?php declare(strict_types=1);

namespace App\Component\Product\Persistence\Mapper;

use App\Component\Category\Business\CategoryBusinessFacadeInterface;
use App\Component\Category\Persistence\Mapper\CategoryMapperInterface;
use App\DataTransferObject\CategoryDataProvider;
use App\DataTransferObject\CsvProductDataProvider;
use App\DataTransferObject\ProductDataProvider;
use App\Entity\Category;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use RuntimeException;

class ProductMapper implements ProductMapperInterface
{
    private CategoryMapperInterface $categoryMapper;
    private CategoryBusinessFacadeInterface $categoryBusinessFacade;
    private CategoryRepository $categoryRepository;


    /**
     * ProductMapper constructor.
     * @param CategoryMapperInterface $categoryMapper
     * @param CategoryBusinessFacadeInterface $categoryBusinessFacade
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(
        CategoryMapperInterface $categoryMapper,
        CategoryBusinessFacadeInterface $categoryBusinessFacade,
        CategoryRepository $categoryRepository
    )
    {
        $this->categoryMapper = $categoryMapper;
        $this->categoryBusinessFacade = $categoryBusinessFacade;
        $this->categoryRepository = $categoryRepository;
    }

    public function mapEntityToCsvProductDto(Product $product, CsvProductDataProvider $csvProductDto): CsvProductDataProvider
    {
        $csvProductDto->setId($product->getId());
        $csvProductDto->setName($product->getName());
        $csvProductDto->setDescription($product->getDescription());
        $csvProductDto->setProductCsvNumber($product->getProductCsvNumber());
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
        $product->setProductCsvNumber($csvProductDto->getProductCsvNumber());

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