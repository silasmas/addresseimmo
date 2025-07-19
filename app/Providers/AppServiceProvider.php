<?php

namespace App\Providers;

use App\Http\Resources\Product as ResourcesProduct;
use App\Http\Resources\User as ResourcesUser;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

/**
 * @author Xanders
 * @see https://team.xsamtech.com/xanderssamoth
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(CartService $cartService): void
    {
        Paginator::useBootstrap();

        view()->composer('*', function ($view) use ($cartService) {
            $sessionCartTotal = session()->has('cart') ? $cartService->getCartTotalFromSession() : 0;
            $current_user = null;

            if (Auth::check()) {
                $current_user = new ResourcesUser(Auth::user());
                $user_orders = $current_user->unpaidOrders();

                $view->with('user_orders', $user_orders);
            }

            $popular_projects = Product::mostOrdered(6, 'project', 'monthly');
            $popular_products = Product::mostOrdered(6, 'product', 'monthly');
            $popular_services = Product::mostOrdered(6, 'service', 'monthly');

            $view->with('cartService', $cartService);
            $view->with('session_cart_total', $sessionCartTotal);
            $view->with('current_user', $current_user);
            $view->with('popular_projects', ResourcesProduct::collection($popular_projects)->resolve());
            $view->with('popular_products', ResourcesProduct::collection($popular_products)->resolve());
            $view->with('popular_services', ResourcesProduct::collection($popular_services)->resolve());
            $view->with('current_locale', app()->getLocale());
            $view->with('available_locales', config('app.available_locales'));
        });
    }
}
