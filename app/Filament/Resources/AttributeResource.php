<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttributeResource\Pages;

use App\Models\Attribute;
use App\Models\AttributeValue;

use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;


class AttributeResource extends Resource
{
    protected static ?string $model = Attribute::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            TextInput::make('name')
                ->label('Attribute Name')
                ->unique(table: Attribute::class, column: 'name',  ignoreRecord: true)
                
            
                ->required(),

            Repeater::make('values')
                ->label('Attribute Values')
                ->relationship('values') // Relationship with AttributeValue
                ->schema([
                    TextInput::make('value')->required()->label('Value')
                    ->unique(table: AttributeValue::class, column: 'value', ignoreRecord: true),
                    
                ])
                
            
            
                ->collapsible(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('name')->label('Attribute'),
          //  TextColumn::make('values.value')->label('Values')->separator(', '),
        ]);
    }
    
    public static function canViewAny(): bool
    {
        return auth()->check() && optional(auth()->user()->role)->name === 'Administrator';
    }
    
    // Optional: Hide from navigation for non-admins
    public static function shouldRegisterNavigation(): bool
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
            'index' => Pages\ListAttributes::route('/'),
            'create' => Pages\CreateAttribute::route('/create'),
            'edit' => Pages\EditAttribute::route('/{record}/edit'),
        ];
    }    
}
