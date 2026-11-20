<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class TransactionChart extends ChartWidget
{
    protected ?string $heading = 'Transaction Chart';

    protected function getData(): array
    {
        $data = Trend::model(Transaction::class)
            ->between(
                start: now()->subDays(30),
                end: now(),
            )
            ->perDay()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Total Transactions',
                    'data' => $data->map(
                        fn (TrendValue $value) => $value->aggregate
                    ),
                ],
            ],
            'labels' => $data->map(
                fn (TrendValue $value) => $value->date
            ),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}