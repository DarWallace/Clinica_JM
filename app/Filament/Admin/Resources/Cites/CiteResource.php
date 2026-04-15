<?php

namespace App\Filament\Admin\Resources\Cites;

use App\Filament\Admin\Resources\Cites\Pages\CreateCite;
use App\Filament\Admin\Resources\Cites\Pages\EditCite;
use App\Filament\Admin\Resources\Cites\Pages\ListCites;
use App\Filament\Admin\Resources\Cites\Schemas\CiteForm;
use App\Filament\Admin\Resources\Cites\Tables\CitesTable;
use App\Models\Cite;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CiteResource extends Resource
{
    protected static ?string $model = Cite::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = 'Citas';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return CiteForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CitesTable::configure($table);
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
            'index' => ListCites::route('/'),
            //'create' => CreateCite::route('/create'),
            'edit' => EditCite::route('/{record}/edit'),
        ];
    }
}
