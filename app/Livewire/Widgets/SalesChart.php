<?php

namespace App\Livewire\Widgets;

use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

use function Termwind\parse;

class SalesChart extends ChartWidget
{
    protected static ?string $heading = 'Transaksi';

    public ?string $filter = 'month';

    protected function getData(): array
    {
        $activeFilter = $this->filter;

        if ($activeFilter === 'month') {
            $data = Trend::model(Transaction::class)
                ->between(
                    start: now()->startOfMonth(),
                    end: now()->endOfMonth(),
                )
                ->perDay()
                ->count();
        } else if ($activeFilter === 'year') {
            $data = Trend::model(Transaction::class)
                ->between(
                    start: now()->startOfYear(),
                    end: now()->endOfYear(),
                )
                ->perMonth()
                ->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Transaksi',
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(function (TrendValue $value) use ($activeFilter) {
                if ($activeFilter === 'month') {
                    $date = Carbon::parse($value->date)->translatedFormat('d');
                } else if ($activeFilter === 'year') {
                    $date = Carbon::parse($value->date)->translatedFormat('F');
                }
                return $date;
            }),
        ];
    }


    protected function getType(): string
    {
        return 'line';
    }

    protected function getFilters(): ?array
    {
        return [
            'month' => 'Last month',
            'year' => 'This year',
        ];
    }

    protected function getOptions(): RawJs
    {
        return RawJs::make(<<<JS
        {
            scales: {
                y: {
                    ticks: {
                        callback: (value) => Math.floor(value),
                    },
                },
            },
        }
    JS);
    }
}
