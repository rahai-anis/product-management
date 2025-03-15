<?php

namespace App\Filament\Resources;


use App\Filament\Resources\ProductResource\Pages;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Product;

use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;


use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;

use Illuminate\Database\Eloquent\Model;


class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        
        return $form->schema([
            TextInput::make('name')->required(),
            TextInput::make('reference')->required()
           ,
            TextInput::make('buying_price')->numeric()->required(),
            TextInput::make('selling_price')->numeric()->required(),
            FileUpload::make('image'),
            Textarea::make('description'),
        
            // Creation-only fields
            Select::make('attributes')
                ->label('Select Attributes')
                ->multiple()
                ->options(Attribute::pluck('name', 'id'))
                ->preload()
                ->reactive()
                ->afterStateUpdated(fn (callable $set) => $set('attribute_values', []))
                ->visible(fn ($record) => $record === null)
                ->dehydrated(),
        
            Select::make('attribute_values')
                ->label('Select Attribute Values')
                ->multiple()
                ->options(fn (callable $get) => AttributeValue::whereIn('attribute_id', $get('attributes') ?? [])->pluck('value', 'id'))
                ->preload()
                ->reactive()
                ->visible(fn ($record) => $record === null)
                ->dehydrated(),
        
            
        ]);
        
        
        
       
    }
   
   
    
   
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Product'),
                TextColumn::make('reference')->label('Reference'),
                TextColumn::make('buying_price')->label('Buying Price'),
                TextColumn::make('selling_price')->label('Selling Price'),
               
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }    
}
