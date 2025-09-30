<?php

namespace App\Filament\Resources\Brands\Widgets;

use Filament\Widgets\ChartWidget;

class ProductChart extends ChartWidget
{
    protected ?string $heading = 'Product Chart';

    protected function getData(): array
    {
        return [
            //
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
