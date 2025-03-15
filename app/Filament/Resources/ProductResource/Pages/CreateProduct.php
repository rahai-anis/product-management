<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;
    public bool $confirmedSave = false;
    protected function beforeCreate(): void
    {
        // Vérifier si l'utilisateur a déjà confirmé
        if (session()->pull('confirmed_creation', false)) {
            return; // Continuer sans réafficher la notification
        }
    
        // Récupérer les valeurs d'attributs sélectionnées
        $attributeValues = $this->form->getState()['attribute_values'] ?? [];
    
        if (empty($attributeValues)) {
            Notification::make()
                ->title('Aucun attribut sélectionné')
                ->body('Vous n\'avez pas sélectionné d\'attributs. Voulez-vous continuer ?')
                ->actions([
                    Action::make('continuer')
                        ->label('Continuer')
                        ->button()
                        ->color('primary')
                        ->emit('continueCreation'),
                    Action::make('annuler')
                        ->label('Annuler')
                        ->button()
                        ->color('danger')
                        ->close(),
                ])
                ->send();
    
            $this->halt(); // Stopper temporairement la création
        } else {
            session()->put('attribute_values', $attributeValues);
        }
    }
    
    protected function afterCreate(): void
    {
        // Récupérer les valeurs d'attributs stockées en session
        $attributeValues = session()->pull('attribute_values', []);
    
        // Générer les variantes si des valeurs d'attributs existent
        if (!empty($attributeValues)) {
            $this->record->generateVariants($attributeValues);
        }
    }
    protected function getListeners(): array
    {
        return [
            'continueCreation' => 'continueCreation',
        ];
    }
    
    public function continueCreation()
    {
        // Marquer la création comme confirmée pour éviter la boucle
        session()->put('confirmed_creation', true);
    
        // Relancer la création
        $this->create();
    }

 

   /* protected function beforeCreate(): void
    {
        $state = $this->form->getState();

        if (empty($state['attributes']) || empty($state['attribute_values'])) {
            if (!$this->confirmingSave) {
                $this->showAttributeWarning();
                $this->halt();
            }
        }
    }

  

    private function showAttributeWarning(): void
    {
        Notification::make()
            ->title('No Attributes or Values Selected')
            ->body('You have not selected any attributes or values. Are you sure you want to proceed?')
            ->warning()
            ->actions([
                Action::make('Proceed')
                    ->button()
                    ->color('primary')
                    ->close()
                    ->extraAttributes(['wire:click' => 'confirmSave']), // Call Livewire method
                Action::make('Cancel')
                    ->button()
                    ->color('danger')
                    ->close(),
            ])
            ->persistent()
            ->send();
    }*/
 /*   protected function beforeCreate(): void
    {
        if ($this->confirmedSave) {
            return; // Proceed with saving
        }
    
        if (empty($this->form->getState()['attributes']) || empty($this->form->getState()['attribute_values'])) {
            $this->showAttributeWarning();
            $this->halt(); // Stop the save process
        }
    }
    private function showAttributeWarning(): void
    {
        Notification::make()
            ->title('No Attributes or Values Selected')
            ->body('You have not selected any attributes or values. Are you sure you want to proceed?')
            ->warning()
            ->actions([
                Action::make('Proceed')
                    ->button()
                    ->color('primary')
                    ->close(),
                 
                Action::make('Cancel')
                    ->button()
                    ->color('danger')
                    ->close(),
            ])
            ->persistent()
            ->send();
    }*/

  
}
