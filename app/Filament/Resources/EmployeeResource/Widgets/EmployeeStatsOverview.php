<?php

namespace App\Filament\Resources\EmployeeResource\Widgets;

use App\Models\Country;
use App\Models\Employee;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class EmployeeStatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        $mx = Country::where('country_code', 'mx')->withCount('employees')->first();
        $ve = Country::where('country_code', 've')->withCount('employees')->first();
        $co = Country::where('country_code', 'co')->withCount('employees')->first();
        return [
            Card::make('Total Employees', Employee::all()->count()),
            Card::make($mx->name . ' Employees', $mx->employees_count),
            Card::make($ve->name . ' Employees', $ve->employees_count),
            Card::make($co->name . ' Employees', $co->employees_count),
        ];
    }
}
