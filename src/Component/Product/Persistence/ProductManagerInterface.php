<?php declare(strict_types=1);

namespace App\Component\Product\Persistence;

use App\DataTransferObject\CsvProductDataProvider;

interface ProductManagerInterface
{
    public function delete(CsvProductDataProvider $product): void;

    public function save(CsvProductDataProvider $csvProductDataProvider): CsvProductDataProvider;
}