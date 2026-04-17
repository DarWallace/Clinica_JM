<?php

namespace App\Filament\Admin\Resources\Exceptions;

use App\Filament\Admin\Resources\Exceptions\Pages\CreateException;
use App\Filament\Admin\Resources\Exceptions\Pages\EditException;
use App\Filament\Admin\Resources\Exceptions\Pages\ListExceptions;
use App\Filament\Admin\Resources\Exceptions\Schemas\ExceptionForm;
use App\Filament\Admin\Resources\Exceptions\Tables\ExceptionsTable;
use App\Models\Exception;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ExceptionResource extends Resource
{
    protected static ?string $model = Exception::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-no-symbol';
     protected static ?string $navigationLabel = 'Excepciones';

    protected static ?string $modelLabel = 'Excepción';

    protected static ?string $pluralModelLabel = 'Excepciones';


    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return ExceptionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ExceptionsTable::configure($table);
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
            'index' => ListExceptions::route('/'),
            'create' => CreateException::route('/create'),
            'edit' => EditException::route('/{record}/edit'),
        ];
    }
}
