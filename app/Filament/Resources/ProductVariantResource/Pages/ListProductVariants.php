<?php

namespace App\Filament\Resources\ProductVariantResource\Pages;

use App\Filament\Resources\ProductVariantResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Models\Attribute;
use App\Models\AttributeValue;

class ListProductVariants extends ListRecords
{
    protected static string $resource = ProductVariantResource::class;

   
    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
