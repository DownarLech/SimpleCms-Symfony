<?php declare(strict_types=1);

namespace App\Component\Category\Business;

use App\DataTransferObject\CategoryDataProvider;

interface CategoryBusinessFacadeInterface
{

    public function delete(CategoryDataProvider $categoryDataProvider): void;

    public function save(CategoryDataProvider $categoryDataProvider): CategoryDataProvider;

    public function getCategoryList(): array;

    public function getCategoryById(int $id): ?CategoryDataProvider;

    public function getCategoryByName(string $name): ?CategoryDataProvider;
}