<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;


class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Settings';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nom')
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->unique(ignoreRecord: true)
                    ->required(),

                TextInput::make('password')
                    ->label('Mot de passe')
                    ->password()
                    ->nullable() // Permettre un champ vide en édition
                    ->required(fn($livewire) => $livewire instanceof \App\Filament\Resources\UserResource\Pages\CreateUser)

                    ->dehydrateStateUsing(fn($state) => !empty($state) ? Hash::make($state) : null),

                Select::make('role_id')
                    ->label('Rôle')
                    ->nullable()
                    ->searchable()
                    ->options(Role::pluck('name', 'id')),

                Toggle::make('active')
                    ->label('Actif')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('email')->sortable()->searchable(),
                TextColumn::make('role.name')->label('Rôle')->sortable(),
                IconColumn::make('active')->label('Actif')->boolean(),
                TextColumn::make('created_at')->label('Créé le')->date(),
            ]);
    }
    public static function canViewAny(): bool
    {
        return auth()->check() && optional(auth()->user()->role)->name === 'Administrator';
    }

   
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
