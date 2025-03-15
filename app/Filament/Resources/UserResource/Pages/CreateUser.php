<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;
use Filament\Notifications\Notification;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
    protected function mutateFormDataBeforeSave(array $data): array
    {
       
        // Vérifier si le rôle est défini
        if (empty($data['role_id'])) {
            $data['role_id'] = null; // Permettre un utilisateur sans rôle
        }
    
        return $data;
    }
}
