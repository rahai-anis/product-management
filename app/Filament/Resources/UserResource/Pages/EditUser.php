<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Hash;
use Filament\Notifications\Notification;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
{
    if (empty($data['password'])) {
        // Garder l'ancien mot de passe si le champ est vide
        $data['password'] = $this->record->password ?? Hash::make('default_password'); 
    } else {
        // Hasher le nouveau mot de passe
        $data['password'] = Hash::make($data['password']);
    }
    if (empty($data['role_id'])) {
        $data['role_id'] = null; // Permettre un utilisateur sans rôle
    }

    return $data;
}
    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
