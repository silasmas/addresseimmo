<?php

namespace App\Providers;

use App\Http\Resources\Category as ResourcesCategory;
use App\Http\Resources\CustomerFeedback as ResourcesCustomerFeedback;
use App\Http\Resources\Product as ResourcesProduct;
use App\Http\Resources\User as ResourcesUser;
use App\Models\Category;
use App\Models\CustomerFeedback;
use App\Models\Product;
use App\Models\User;
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

            $product_categories = Category::where('for_service', 0)->get();
            $service_categories = Category::where('for_service', 1)->get();
            $recent_properties = Product::mostRecent(7);
            $popular_services = Product::popularServices(7, 'monthly');
            $buy_products = Product::whereHas('customer_orders')->where('action', 'sell')->orderByDesc('created_at')->paginate(7)->appends(request()->query());
            $sell_products = Product::where(function ($query) {
                                            // Condition 1 : Le produit a été commandé mais la quantité est > 0
                                            $query->whereHas('customer_orders')->where('quantity', '>', 0)->where('action', 'sell');
                                        })
                                        ->orWhere(function ($query) {
                                            // Condition 2 : Le produit n'a jamais été commandé
                                            $query->doesntHave('customer_orders')->where('action', 'sell');
                                        })->orderByDesc('created_at')->paginate(7)->appends(request()->query());
            $rent_products = Product::where(function ($query) {
                                            // Condition 1 : Le produit a été commandé mais la quantité est > 0
                                            $query->whereHas('customer_orders')->where('quantity', '>', 0)->where('action', 'rent');
                                        })
                                        ->orWhere(function ($query) {
                                            // Condition 2 : Le produit n'a jamais été commandé
                                            $query->doesntHave('customer_orders')->where('action', 'rent');
                                        })->orderByDesc('created_at')->paginate(7)->appends(request()->query());
            $build_products = Product::where(function ($query) {
                                            // Condition 1 : Le produit a été commandé mais la quantité est > 0
                                            $query->whereHas('customer_orders')->where('quantity', '>', 0)->where('action', 'build');
                                        })
                                        ->orWhere(function ($query) {
                                            // Condition 2 : Le produit n'a jamais été commandé
                                            $query->doesntHave('customer_orders')->where('action', 'build');
                                        })->orderByDesc('created_at')->paginate(7)->appends(request()->query());
            $design_products = Product::where(function ($query) {
                                            // Condition 1 : Le produit a été commandé mais la quantité est > 0
                                            $query->whereHas('customer_orders')->where('quantity', '>', 0)->where('action', 'design');
                                        })
                                        ->orWhere(function ($query) {
                                            // Condition 2 : Le produit n'a jamais été commandé
                                            $query->doesntHave('customer_orders')->where('action', 'design');
                                        })->orderByDesc('created_at')->paginate(7)->appends(request()->query());
            $moving_products = Product::where(function ($query) {
                                            // Condition 1 : Le produit a été commandé mais la quantité est > 0
                                            $query->whereHas('customer_orders')->where('quantity', '>', 0)->where('action', 'moving');
                                        })
                                        ->orWhere(function ($query) {
                                            // Condition 2 : Le produit n'a jamais été commandé
                                            $query->doesntHave('customer_orders')->where('action', 'moving');
                                        })->orderByDesc('created_at')->paginate(7)->appends(request()->query());
            $agents = User::whereHas('roles', function ($query) { $query->where('role_name', 'Agent'); })->orderByDesc('created_at')->paginate(7)->appends(request()->query());
            $customer_feedbacks = CustomerFeedback::orderByDesc('created_at')->paginate(7)->appends(request()->query());

            $view->with('cartService', $cartService);
            $view->with('session_cart_total', $sessionCartTotal);
            $view->with('current_user', $current_user);
            // Products
            $view->with('product_categories', ResourcesCategory::collection($product_categories)->resolve());
            $view->with('service_categories', ResourcesCategory::collection($service_categories)->resolve());
            $view->with('recent_properties', ResourcesProduct::collection($recent_properties)->resolve());
            $view->with('popular_services', ResourcesProduct::collection($popular_services)->resolve());
            // Statistics
            $view->with('buy_products', ResourcesProduct::collection($buy_products)->resolve());
            $view->with('buy_products_req', $buy_products);
            $view->with('sell_products', ResourcesProduct::collection($sell_products)->resolve());
            $view->with('sell_products_req', $sell_products);
            $view->with('rent_products', ResourcesProduct::collection($rent_products)->resolve());
            $view->with('rent_products_req', $rent_products);
            $view->with('build_products', ResourcesProduct::collection($build_products)->resolve());
            $view->with('build_products_req', $build_products);
            $view->with('design_products', ResourcesProduct::collection($design_products)->resolve());
            $view->with('design_products_req', $design_products);
            $view->with('moving_products', ResourcesProduct::collection($moving_products)->resolve());
            $view->with('moving_products_req', $moving_products);
            $view->with('agents', ResourcesProduct::collection($agents)->resolve());
            $view->with('agents_req', $agents);
            // Other
            $view->with('customer_feedbacks', ResourcesCustomerFeedback::collection($customer_feedbacks)->resolve());
            $view->with('customer_feedbacks_req', $customer_feedbacks);
            $view->with('current_locale', app()->getLocale());
            $view->with('available_locales', config('app.available_locales'));
        });
    }
}
