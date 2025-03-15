<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;
    protected function afterSave(): void
    {
      /*  $product = $this->record;
        
        // Check if attributes/values changed
        $originalAttributes = $product->original['attributes'] ?? [];
        $newAttributes = $product->attributes;
        
        if ($originalAttributes != $newAttributes) {
            // Delete old variants and regenerate
            $product->variants()->delete();
            $product->generateVariants($product->attribute_values);
        }*/
    }
    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
