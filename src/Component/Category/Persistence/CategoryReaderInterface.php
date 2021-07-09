<?php declare(strict_types=1);


namespace App\Component\Category\Persistence;


use App\DataTransferObject\CategoryDataProvider;

interface CategoryReaderInterface
{
    public function getCategoryList(): array;

    public function getCategoryById(int $id): ?CategoryDataProvider;

    public function getCategoryByName(string $name): ?CategoryDataProvider;

}