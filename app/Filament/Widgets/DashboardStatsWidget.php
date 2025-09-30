<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\Category;
use App\Models\Brand;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalUsers = User::count();
        $totalCategories = Category::count();
        $totalBrands = Brand::count();
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_price');
        $lowStockProducts = Product::where('stock', '<=', 10)->count();
        $publishedProducts = Product::where('status', 'published')->count();

        return [
            Stat::make('Total Products', $totalProducts)
                ->description('Total products in catalog')
                ->descriptionIcon('heroicon-m-cube')
                ->color('success'),

            Stat::make('Total Orders', $totalOrders)
                ->description('All time orders')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('info'),

            Stat::make('Total Revenue', '$' . number_format($totalRevenue, 2))
                ->description('Total earnings')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),

            Stat::make('Total Users', $totalUsers)
                ->description('Registered customers')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Categories', $totalCategories)
                ->description('Product categories')
                ->descriptionIcon('heroicon-m-tag')
                ->color('warning'),

            Stat::make('Brands', $totalBrands)
                ->description('Product brands')
                ->descriptionIcon('heroicon-m-building-storefront')
                ->color('info'),

            Stat::make('Low Stock', $lowStockProducts)
                ->description('Products with <= 10 items')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),

            Stat::make('Published Products', $publishedProducts)
                ->description('Available for sale')
                ->descriptionIcon('heroicon-m-eye')
                ->color('success'),
        ];
    }
}