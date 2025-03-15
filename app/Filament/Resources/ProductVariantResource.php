<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductVariantResource\Pages;

use App\Models\ProductVariant;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

use App\Models\Product;
use App\Models\Attribute;
use App\Models\AttributeValue;


use Illuminate\Database\Eloquent\Model;

class ProductVariantResource extends Resource
{
    protected static ?string $model = ProductVariant::class;

   
    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationLabel = 'Variantes de produit';

    protected static ?string $pluralLabel = 'Variantes de produit';

    protected static ?string $modelLabel = 'Variante de produit';
    public static function form(Form $form): Form
    {
        return $form
    ->schema([
        Forms\Components\Select::make('product_id')
            ->label('Produit parent')
            ->options(Product::pluck('reference', 'id'))
            ->searchable()
            ->required()
            ->reactive()
            ->afterStateUpdated(fn (callable $set) => $set('reference', '')),

        Forms\Components\Select::make('attributes')
            ->label('Sélectionner les attributs')
            ->multiple()
            ->options(Attribute::pluck('name', 'id'))
            ->preload()
            ->searchable()
            ->required()
            ->reactive()
            ->afterStateUpdated(function (callable $set, $state) {
               
                $set('attribute_values', []);
                
               
                $values = [];
                foreach ($state as $attributeId) {
                    $values["attr_$attributeId"] = null;
                }
                $set('attribute_values', $values);
            }),

     
        Forms\Components\Hidden::make('attribute_values')
            ->reactive(),

        
        Forms\Components\Group::make()
            ->visible(fn ($get) => !empty($get('attributes')))
            ->schema(function ($get) {
                $schema = [];
                $attributes = $get('attributes') ?? [];

                foreach ($attributes as $attributeId) {
                    $schema[] = Forms\Components\Select::make("attribute_values.attr_$attributeId")
                        ->label(Attribute::find($attributeId)?->name ?? 'Attribut')
                        ->options(AttributeValue::where('attribute_id', $attributeId)->pluck('value', 'id'))
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function (callable $set, callable $get) {
                                $product = Product::find($get('product_id'));
                                $attributeValues = $get('attribute_values') ?? [];
                                $values = [];

                                if ($product) {
                                    foreach ($get('attributes') ?? [] as $attributeId) {
                                        $valueId = $attributeValues["attr_$attributeId"] ?? null;
                                        if ($valueId) {
                                            $values[] = AttributeValue::find($valueId)?->value;
                                        }
                                    }
                                    $set('reference', $product->reference . '_' . implode('_', $values));
                                }
                               
                        });
                }

                return $schema;
            }),

            Forms\Components\TextInput::make('reference')
            ->label('Référence de la variante')
            ->required()
            ->unique(
                table: 'product_variants', 
                column: 'reference',
                ignoreRecord: true
            )
            ->disabled()
            ->reactive(),
            
    ]);
    }
   
  
    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('product.name')
                ->label('Produit parent')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('reference')
                ->label('Référence')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('attributes')
                ->label('Attributs')
                ->formatStateUsing(fn ($state) => collect(json_decode($state, true))->map(fn ($v, $k) => "$k: $v")->implode(', '))
                ->sortable(),
        ])
        ->filters([
            //
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ]);
    }
    
    public static function canViewAny(): bool
    {
        return auth()->check(); 
    }
    
    public static function canCreate(): bool
    {
        return auth()->check() && optional(auth()->user()->role)->name === 'Administrator';
    }
    public static function canEdit(Model $record): bool
    {
        return auth()->check() && optional(auth()->user()->role)->name === 'Administrator';
    }
    public static function canDelete(Model $record): bool
    {
        return auth()->check() && optional(auth()->user()->role)->name === 'Administrator';
    }
    public static function canDeleteAny(): bool
    {
        return auth()->check() && optional(auth()->user()->role)->name === 'Administrator';
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductVariants::route('/'),
            'create' => Pages\CreateProductVariant::route('/create'),
            'edit' => Pages\EditProductVariant::route('/{record}/edit'),
        ];
    }    
}
