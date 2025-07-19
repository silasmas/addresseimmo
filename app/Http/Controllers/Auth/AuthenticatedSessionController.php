<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Product;
use App\Models\User;
// use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * @author Xanders
 * @see https://team.xsamtech.com/xanderssamoth
 */
class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        if (session()->has('email')) session()->forget('email');
        if (session()->has('phone')) session()->forget('phone');

        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $login_type = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        if (!Auth::attempt([$login_type => $request->login, 'password' => $request->password], $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'login' => __('notifications.find_user_password_404'),
            ]);
        }

        $request->session()->regenerate();

        // Une fois l'utilisateur connecté, on récupère ses informations
        if (Auth::check()) {
            $user = User::find(Auth::id());

            // Vérifier s'il y a des produits en session (panier)
            if (session()->has('cart')) {
                // Récupérer les produits en session
                $cartItems = session()->get('cart', []);

                // Ajouter les produits au panier de l'utilisateur si ils sont valides
                foreach ($cartItems as $productId => $item) {
                    // Vérifier si le produit existe toujours dans la base de données
                    $product = Product::find($productId);

                    if ($product) {
                        // Si le produit existe, on l'ajoute au panier de l'utilisateur
                        $user->addProductToCart($productId, $item['quantity']);
                    }
                }

                // Vider le panier en session après l'ajout
                session()->forget('cart');
            }
        }

        if ($request->has('product_id')) {
            $product = Product::find($request->get('product_id'));

            if (!$product) {
                return redirect()->intended(RouteServiceProvider::HOME);
            }

            return redirect()->route('product.entity.datas', ['entity' => $product->type, 'id' => $product->id]);
        }

        if ($request->has('cart')) {
            return redirect()->route('account.entity', ['entity' => 'cart']);
        }

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
