<?php

namespace App\Filament\Resources\ProductVariantResource\Pages;

use App\Filament\Resources\ProductVariantResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;

class EditProductVariant extends EditRecord
{
    protected static string $resource = ProductVariantResource::class;
    public bool $confirmed_edit = false;
    
    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (!empty($data['attributes'])) {
            $decodedAttributes = json_decode($data['attributes'], true);
            
            $attributeIds = Attribute::whereIn('name', array_keys($decodedAttributes))->pluck('id', 'name');
            $valueIds = AttributeValue::whereIn('value', array_values($decodedAttributes))->pluck('id', 'value');

            
            $data['attributes'] = $attributeIds->values()->toArray(); 
            $data['attribute_values'] = [];

            foreach ($decodedAttributes as $attributeName => $attributeValue) {
                $attributeId = $attributeIds[$attributeName] ?? null;
                $valueId = $valueIds[$attributeValue] ?? null;

                if ($attributeId && $valueId) {
                    $data['attribute_values']["attr_$attributeId"] = $valueId;
                }
            }
        }

        return $data;
    }
    
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
    
  
    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
