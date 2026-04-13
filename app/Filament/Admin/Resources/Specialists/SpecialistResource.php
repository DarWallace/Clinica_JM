<?php

namespace App\Filament\Admin\Resources\Specialists;

use App\Filament\Admin\Resources\Specialists\Pages\CreateSpecialist;
use App\Filament\Admin\Resources\Specialists\Pages\EditSpecialist;
use App\Filament\Admin\Resources\Specialists\Pages\ListSpecialists;
use App\Filament\Admin\Resources\Specialists\Schemas\SpecialistForm;
use App\Filament\Admin\Resources\Specialists\Tables\SpecialistsTable;
use App\Models\Specialist;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SpecialistResource extends Resource
{
    protected static ?string $model = Specialist::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    // Cambiado para que apunte a la relación
    protected static ?string $recordTitleAttribute = 'name';

    // AÑADE ESTO: Es la pieza que falta para que se vean en la lista
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('user');
    }

    public static function form(Schema $schema): Schema
    {
        return SpecialistForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SpecialistsTable::configure($table);
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
            'index' => ListSpecialists::route('/'),
            'create' => CreateSpecialist::route('/create'),
            'edit' => EditSpecialist::route('/{record}/edit'),
        ];
    }

}
