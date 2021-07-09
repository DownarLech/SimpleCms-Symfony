<?php declare(strict_types=1);

namespace App\Component\Category\Persistence;

use App\Component\Category\Persistence\Mapper\CategoryMapperInterface;
use App\DataTransferObject\CategoryDataProvider;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;

class CategoryManager implements CategoryManagerInterface
{
    private CategoryRepository $categoryRepository;
    private EntityManagerInterface $entityManager;
    private CategoryMapperInterface $categoryMapper;

    /**
     * CategoryManager constructor.
     * @param CategoryRepository $categoryRepository
     * @param EntityManagerInterface $entityManager
     * @param CategoryMapperInterface $categoryMapper
     */
    public function __construct(
        CategoryRepository $categoryRepository,
        EntityManagerInterface $entityManager,
        CategoryMapperInterface $categoryMapper
    )
    {
        $this->categoryRepository = $categoryRepository;
        $this->entityManager = $entityManager;
        $this->categoryMapper = $categoryMapper;
    }


    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function delete(CategoryDataProvider $categoryDataProvider): void
    {
        $connection = $this->entityManager->getConnection();

        $id = $categoryDataProvider->getId();
        $categoryEntity = $this->categoryRepository->find($id);
        $this->entityManager->remove($categoryEntity);

        $connection->executeQuery('UPDATE product SET category_id=null WHERE category_id =' . $id);

        $this->entityManager->flush();
        //This really made my day. Without it, categoryName stayed in DB. Doctrine cache :(
        $this->entityManager->clear();
    }

    public function save(CategoryDataProvider $categoryDataProvider): CategoryDataProvider
    {
        if ($categoryDataProvider->hasName()) {
            $category = $this->categoryRepository->findOneByName($categoryDataProvider->getName());
        }

        if ($categoryDataProvider->hasId()) {
            $id = $categoryDataProvider->getId();
            $category = $this->categoryRepository->findOneById($id);
        }

        if (!isset($category) || !$category instanceof Category) {
            $category = new Category();
        }

        $this->categoryMapper->mapDataProviderToEntity($categoryDataProvider, $category);

        $this->entityManager->persist($category);
        $this->entityManager->flush();

        return $this->categoryMapper->mapEntityToDataProvider($category, new CategoryDataProvider());
    }
}