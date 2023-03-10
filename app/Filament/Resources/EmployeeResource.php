<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Filament\Resources\EmployeeResource\Widgets\EmployeeStatsOverview;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;
    // protected static ?string $navigationLabel = 'Custom Navigation Label';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup =
    'System';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Select::make('country_id')->label('Country')
                            ->required()
                            ->options(Country::all()->pluck('name', 'id')->toArray())
                            ->reactive()
                            ->afterStateUpdated(fn (callable $set) => $set('state_id', null)),

                        Select::make('state_id')
                            ->label('State')
                            ->required()
                            ->options(function (callable $get) {
                                $country = Country::find($get('country_id'));
                                if ($country) {
                                    return $country->states->pluck('name', 'id');
                                }
                            })
                            ->reactive()
                            ->afterStateUpdated(fn (callable $set) => $set('city_id', null)),

                        Select::make('city_id')
                            ->label('City')
                            ->required()
                            ->options(function (callable $get) {
                                $state = State::find($get('state_id'));
                                if ($state) {
                                    return $state->cities->pluck('name', 'id');
                                }
                            })
                            ->reactive(),

                        Select::make('department_id')
                            ->relationship('department', 'name')->required(),
                        TextInput::make('first_name')->required()
                            ->maxLength(200),
                        TextInput::make('last_name')->required()
                            ->maxLength(200),
                        TextInput::make('address')->required(),
                        TextInput::make('phone_number')->required(),
                        TextInput::make('zip_code')->required(),
                        DatePicker::make('birth_date')->required(),
                        DatePicker::make('date_hired')->required()->default(today()),
                    ])->columns(3)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('#')->rowIndex(),
                TextColumn::make('first_name')->sortable()->searchable()->toggleable(),
                TextColumn::make('last_name')->sortable()->searchable()->toggleable(),
                TextColumn::make('department.name')->sortable()->toggleable(),
                TextColumn::make('phone_number')->sortable()->searchable()->toggleable(),
                TextColumn::make('date_hired')->date('d M Y')->toggleable(),
                TextColumn::make('birth_date')->date('d M Y')->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('department')
                    ->relationship('department', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getWidgets(): array
    {
        return [
            EmployeeStatsOverview::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
