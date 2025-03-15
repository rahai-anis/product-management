<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'reference', 'buying_price', 'selling_price', 'image', 'description'];
  

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'product_attributes')->withTimestamps();
    }
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function generateVariants(array $selectedAttributeValues)
{
   
    if (empty($selectedAttributeValues)) {
        return; // Skip if no attributes selected
    }
    
    // Eager load attributes and order by attribute_id
    $groupedValues = AttributeValue::with('attribute')
        ->whereIn('id', $selectedAttributeValues)
        ->get()
        ->sortBy('attribute_id')
        ->groupBy('attribute_id');

    // Generate all combinations using Cartesian product
    $combinations = collect([[]]);

    foreach ($groupedValues as $attributeId => $values) {
        $combinations = $combinations->flatMap(function ($combination) use ($values) {
            return $values->map(function ($value) use ($combination) {
                return array_merge($combination, [$value]);
            });
        });
    }

    // Create variants for each combination
    foreach ($combinations as $combination) {
        $variantValues = collect($combination)->mapWithKeys(function ($value) {
            return [$value->attribute->name => $value->value];
        })->toArray();

        ProductVariant::create([
            'product_id' => $this->id,
            'reference' => $this->reference . '_' . implode('_', array_values($variantValues)),
            'attributes' => json_encode($variantValues),
        ]);
    }
}
}
