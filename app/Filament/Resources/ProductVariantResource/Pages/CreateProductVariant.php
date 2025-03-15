<?php

namespace App\Filament\Resources\ProductVariantResource\Pages;

use App\Filament\Resources\ProductVariantResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Attribute;
use App\Models\AttributeValue;

class CreateProductVariant extends CreateRecord
{
    protected static string $resource = ProductVariantResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
{
    $data['attributes'] = $this->formatAttributes($data);
    return $data;
}

protected function mutateFormDataBeforeSave(array $data): array
{
    $data['attributes'] = $this->formatAttributes($data);
    return $data;
}

/**
 * Format attributes into JSON {"Color": "green", "Taille": "M"}
 */
private function formatAttributes(array $data): string
{
    if (!isset($data['attributes']) || !isset($data['attribute_values'])) {
        return json_encode([]); // Retourne un JSON vide si pas d’attributs
    }

    $formattedAttributes = [];
    foreach ($data['attributes'] as $attributeId) {
        $attribute = Attribute::find($attributeId);
        $valueId = $data['attribute_values']["attr_$attributeId"] ?? null;
        $value = AttributeValue::find($valueId)?->value;

        if ($attribute && $value) {
            $formattedAttributes[$attribute->name] = $value;
        }
    }

    return json_encode($formattedAttributes, JSON_UNESCAPED_UNICODE);
}
}
