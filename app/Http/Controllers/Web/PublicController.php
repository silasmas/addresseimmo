<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\ApiClientManager;
use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerOrder as ResourcesCustomerOrder;
use App\Http\Resources\Payment as ResourcesPayment;
use App\Http\Resources\Product as ResourcesProduct;
use App\Http\Resources\User as ResourcesUser;
use App\Models\Cart;
use App\Models\Category;
use App\Models\CustomerOrder;
use App\Models\File;
use App\Models\Payment;
use App\Models\Post;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

/**
 * @author Xanders
 * @see https://team.xsamtech.com/xanderssamoth
 */
class PublicController extends Controller
{
    public static $api_client_manager;

    public function __construct()
    {
        $this::$api_client_manager = new ApiClientManager();
    }

    // ==================================== HTTP GET METHODS ====================================
    /**
     * GET: Change language
     *
     * @param  string  $locale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeLanguage($locale)
    {
        app()->setLocale($locale);
        session()->put('locale', $locale);

        return redirect()->back();
    }

    /**
     * GET: Change language
     *
     * @param  string  $currency
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeCurrency($currency)
    {
        $user = User::find(Auth::id());

        if (!$user) {
            return redirect()->back();
        }

        $user->update(['currency' => $currency]);

        return redirect()->back();
    }

    /**
     * GET: Home page
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('welcome');
    }

    /**
     * GET: Create symbolic link
     *
     * @return \Illuminate\View\View
     */
    public function symlink()
    {
        return view('symlink');
    }

    /**
     * GET: About page
     *
     * @return \Illuminate\View\View
     */
    public function about()
    {
        return view('about');
    }

    /**
     * GET: Contact page
     *
     * @return \Illuminate\View\View
     */
    public function contact()
    {
        return view('contact');
    }

    /**
     * GET: Search something
     * 
     * @return \Illuminate\View\View
     */
    public function search(Request $request)
    {
        // Retrieve query parameters
        $query = $request->get('query');
        $action = $request->get('action');

        // Perform the Eloquent query
        $products = Product::where(function($q) use ($query) {
                                $q->where('product_name', 'LIKE', '%' . $query . '%')
                                ->orWhere('product_description', 'LIKE', '%' . $query . '%')
                                ->orWhere('municipality', 'LIKE', '%' . $query . '%')
                                ->orWhere('neighborhood', 'LIKE', '%' . $query . '%');
                            })
                            ->where('action', $action) // Filter by action
                            ->whereNotIn('id', function($query) {
                                $query->select('product_id')
                                    ->from('customer_orders');
                            })
                            ->paginate(10);

        // Return paginated results
        return ResourcesProduct::collection($products);
    }

    /**
     * GET: Create symbolic link
     *
     * @return \Illuminate\View\View
     */
    public function cart()
    {
        $cartItems = session()->get('cart', []);

        return view('cart', ['items' => $cartItems]);
    }

    /**
     * GET: Account page
     *
     * @return \Illuminate\View\View
     */
    public function account()
    {
        return view('account');
    }

    /**
     * GET: User profile page
     *
     * @return \Illuminate\View\View
     */
    public function profile($id)
    {
        $user = User::find($id);

        if (is_null($user)) {
            return redirect('/')->with('error_message', __('notifications.find_user_404'));
        }

        return view('profile', ['user' => $user]);
    }

    /**
     * GET: Account page
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $entity
     * @return \Illuminate\View\View
     */
    public function accountEntity(Request $request, $entity)
    {
        $current_user = User::find(Auth::id());
        $entity_title = null;
        $cart = null;
        $category = null;
        $categories = [];
        $items_req = null;
        $items = [];
        $countries = [];

        if ($entity == 'settings') {
            $entity_title = 'Paramètres du compte';
            $countries = showCountries();
        }

        if ($entity == 'cart') {
            $entity_title = 'Mon panier';
            $countries = showCountries();
            // Get user unpaid cart
            $cart = $current_user->unpaidCart()->first();
            // Get user unpaid orders
            $orders = $current_user->unpaidOrders();
            $items = ResourcesCustomerOrder::collection($orders)->resolve();
        }

        if ($entity == 'offers') {
            $entity_title = 'Mes offres';
            $countries = showCountries();
            $categories = Category::orderByDesc('category_name')->get();
            $items_req = Product::where('user_id', $current_user->id)->orderByDesc('created_at')->paginate(7)->appends(request()->query());
            $items = ResourcesProduct::collection($items_req)->resolve();
        }

        if ($entity == 'payments') {
            $entity_title = 'Mes paiements';
            $items_req = Payment::whereHas('cart', function ($query) use ($current_user) { $query->where('user_id', $current_user->id); })->orderByDesc('created_at')->paginate(7)->appends(request()->query());
            $items = ResourcesPayment::collection($items_req)->resolve();
        }

        return view('account', [
            'logged_in_user' => (new ResourcesUser($current_user))->resolve(),
            'entity' => $entity,
            'entity_title' => $entity_title,
            'cart' => $cart,
            'category' => $category,
            'categories' => $categories,
            'items' => $items,
            'items_req' => $items_req,
            'countries' => $countries
        ]);
    }

    /**
     * GET: Products page
     *
     * @return \Illuminate\View\View
     */
    public function products()
    {
        return view('products');
    }

    /**
     * GET: Product entity page
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $entity
     * @return \Illuminate\View\View
     */
    public function productEntity(Request $request, $entity)
    {
        $entity_title = null;
        $category = null;
        $categories = [];
        $items = [];

        if ($entity == 'sell') {
            $entity_title = 'Propriétés / équipements à vendre';
        }

        if ($entity == 'rent') {
            $entity_title = 'Maisons / appartements à louer';
        }

        if ($entity == 'build') {
            $entity_title = 'Service de construction';
        }

        if ($entity == 'desing') {
            $entity_title = 'Décoration intérieure';
        }

        if ($entity == 'moving') {
            $entity_title = 'Service de déménagement';
        }

        return view('products', [
            'entity' => $entity,
            'entity_title' => $entity_title,
            'category' => $category,
            'categories' => $categories,
            'items' => $items,
        ]);
    }

    /**
     * GET: Product details
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function productDatas($id)
    {
        $entity_title = 'A propos de l’offre';
        $selected_product = Product::find($id);

        if (is_null($selected_product)) {
            return redirect('/')->with('error_message', 'Offre non trouvée');
        }

        return view('products', [
            'entity_title' => $entity_title,
            'selected_product' => (new ResourcesProduct($selected_product))->resolve(),
        ]);
    }

    // ==================================== HTTP DELETE METHODS ====================================
    /**
     * GET: Delete something
     *
     * @param  string $entity
     * @param  int $id
     * @throws \Illuminate\Http\RedirectResponse
     */
    public function removeData($entity, $id)
    {
        if ($entity == 'order') {
            try {
                // We start by retrieving the order associated with this ID (for connected users)
                if (Auth::check()) {
                    // If the user is logged in
                    $user = User::find(Auth::id());

                    // Get user's unpaid cart
                    $cart = $user->unpaidCart()->first();

                    if (!$cart) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Panier non trouvé'
                        ], 404);
                    }

                    // Check if the product is still in the user's cart
                    $inCart = !$cart->customer_orders()->find($id);

                    // Get user's unpaid cart
                    $user->removeProductFromCart($id);

                    $isLoggedIn = true;

                } else {
                    // If the user is not logged in, we work with the cart in the session
                    $cart = session()->get('cart', []);

                    // Check if order exists in session (by order ID)
                    if (isset($cart[$id])) {
                        // Remove product from session
                        unset($cart[$id]);
                        session()->put('cart', $cart);

                        // Check if the cart is empty
                        if (empty($cart)) {
                            // If the cart is empty, delete the session
                            session()->forget('cart');
                        }

                        $inCart = false;  // The product has been removed from the cart

                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => 'Offre non trouvée'
                        ], 404);
                    }

                    $isLoggedIn = false;
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Commande supprimée du panier',
                    'inCart' => $inCart,
                    'isLoggedIn' => $isLoggedIn,
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 422);
            }
        }
    }

    /**
     * Display the message about transaction in waiting.
     *
     * @return \Illuminate\View\View
     */
    public function transactionWaiting()
    {
        return view('transaction_message');
    }

    /**
     * Display the message about transaction done.
     *
     * @return \Illuminate\View\View
     */
    public function transactionMessage($order_number)
    {
        // Find payment by order number API
        $payment1 = $this::$api_client_manager::call('GET', getApiURL() . '/payment/find_by_order_number/' . $order_number);

        return view('transaction_message', [
            'message_content' => __('notifications.transaction_done'),
            'status_code' => (string) $payment1->data->status,
            'payment' => $payment1->data,
        ]);
    }

    /**
     * GET: Current user account
     *
     * @param $amount
     * @param $currency
     * @param $code
     * @param $cart_id
     * @return \Illuminate\View\View
     */
    public function paid($amount = null, $currency = null, $code, $cart_id)
    {
        $cart = Cart::find($cart_id);

        if ($code == '0') {
            return view('transaction_message', [
                'amount' => $amount,
                'currency' => $currency,
                'status_code' => $code,
                'cart' => $cart,
                'message_content' => __('notifications.processing_succeed')
            ]);
        }

        if ($code == '1') {
            // Find payment by order number API
            $payment = $this::$api_client_manager::call('GET', getApiURL() . '/payment/find_by_order_number/' . Session::get('order_number'));

            if ($payment->success) {
                // Update payment status API
                $this::$api_client_manager::call('PUT', getApiURL() . '/payment/switch_status/' . $payment->data->id . '/2');
            }

            return view('transaction_message', [
                'amount' => $amount,
                'currency' => $currency,
                'status_code' => $code,
                'cart' => $cart,
                'status_code' => $code,
                'message_content' => __('notifications.process_canceled')
            ]);
        }

        if ($code == '2') {
            // Find payment by order number API
            $payment = $this::$api_client_manager::call('GET', getApiURL() . '/payment/find_by_order_number/' . Session::get('order_number'));

            if ($payment->success) {
                // Update payment status API
                $this::$api_client_manager::call('PUT', getApiURL() . '/payment/switch_status/' . $payment->data->id . '/2');
            }

            return view('transaction_message', [
                'amount' => $amount,
                'currency' => $currency,
                'status_code' => $code,
                'cart' => $cart,
                'status_code' => $code,
                'message_content' => __('notifications.process_failed')
            ]);
        }
    }

    // ==================================== HTTP POST METHODS ====================================
    /**
     * POST: Run cart payment
     *
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Support\Facades\Redirect
     */
    public function runPay(Request $request)
    {
        $inputs = [
            'transaction_type_id' => $request->transaction_type_id,
            'other_phone' => $request->other_phone_code . $request->other_phone_number,
            'amount' => $request->amount,
            'currency' => $request->currency,
            'user_id' => $request->user_id,
            'cart_id' => $request->cart_id,
            'app_url' => $request->app_url
        ];

        if ($inputs['transaction_type_id'] == null) {
            return redirect()->back()->with('error_message', __('notifications.transaction_type_error'));
        }

        if ($inputs['transaction_type_id'] == 1) {
            if (trim($request->other_phone_code) == null OR trim($request->other_phone_number) == null) {
                return redirect()->back()->with('error_message', __('validation.custom.phone.incorrect'));
            }
        }

        if ($inputs['transaction_type_id'] != null) {
            if ($inputs['transaction_type_id'] == 1) {
                if ($request->other_phone_code == null or $request->other_phone_number == null) {
                    return redirect()->back()->with('error_message', __('validation.custom.phone.incorrect'));
                }

                $cart = $this::$api_client_manager::call('POST', getApiURL() . '/product/purchase/' . $inputs['cart_id'] . '/' . $inputs['user_id'], null, $inputs);

                if ($cart->success) {
                    return redirect()->route('transaction.waiting', [
                        'app_id' => '-',
                        'success_message' => $cart->data->result_response->order_number . '-' . $inputs['user_id'],
                    ]);

                } else {
                    return redirect()->back()->with('error_message', $cart->message);
                }
            }

            if ($inputs['transaction_type_id'] == 2) {
                $cart = $this::$api_client_manager::call('POST', getApiURL() . '/product/purchase/' . $inputs['cart_id'] . '/' . $inputs['user_id'], null, $inputs);

                if ($cart->success) {
                    return redirect($cart->data->result_response->url)->with('order_number', $cart->data->result_response->order_number);

                } else {
                    return redirect()->back()->with('error_message', $cart->message);
                }
            }
        }
    }

    /**
     * POST: Update account
     *
     * @param  \Illuminate\Http\Request  $request
     * @throws \Illuminate\Http\RedirectResponse
     */
    public function updateAccount(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        // Preparing dynamic rules
        $rules = [];

        if ($request->has('firstname')) {
            $rules['firstname'] = ['required', 'string', 'max:255'];
        }

        if ($request->has('lastname')) {
            $rules['lastname'] = ['nullable', 'string', 'max:255'];
        }

        if ($request->has('surname')) {
            $rules['surname'] = ['nullable', 'string', 'max:255'];
        }

        if ($request->has('about_me')) {
            $rules['about_me'] = ['nullable', 'string', 'max:255'];
        }

        if ($request->has('gender')) {
            $rules['gender'] = ['nullable', Rule::in(['M', 'F'])];
        }

        if ($request->has('birthdate')) {
            $rules['birthdate'] = ['nullable', 'date_format:d/m/Y'];
        }

        if ($request->has('country')) {
            $rules['country'] = ['nullable', 'string', 'max:255'];
        }

        if ($request->has('city')) {
            $rules['city'] = ['nullable', 'string', 'max:255'];
        }

        if ($request->has('address_1')) {
            $rules['address_1'] = ['nullable', 'string'];
        }

        if ($request->has('address_2')) {
            $rules['address_2'] = ['nullable', 'string'];
        }

        if ($request->has('p_o_box')) {
            $rules['p_o_box'] = ['nullable', 'string', 'max:45'];
        }

        if ($request->has('currency')) {
            $rules['currency'] = ['nullable', 'string', 'max:45'];
        }

        if ($request->has('email') && $request->input('email') !== $user->email) {
            $rules['email'] = ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)];
        }

        if ($request->has('phone')) {
            $rules['phone'] = ['nullable', 'string', 'max:20'];
        }

        if ($request->has('email_verified_at')) {
            $rules['email_verified_at'] = ['nullable', 'date_format:d/m/Y H:i:s'];
        }

        if ($request->has('phone_verfied_at')) {
            $rules['phone_verfied_at'] = ['nullable', 'date_format:d/m/Y H:i:s'];
        }

        if ($request->has('username') && $request->input('username') !== $user->username) {
            $rules['username'] = ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)];
        }

        if ($request->filled('password')) {
            $rules['password'] = ['required', 'confirmed', Rules\Password::defaults()];
        }

        if ($request->has('status')) {
            $rules['status'] = ['nullable', Rule::in(['created', 'activated', 'disabled'])];
        }

        if ($request->has('image_64')) {
            $rules['image_64'] = ['required', 'string', 'starts_with:data:image/'];
        }

        // Validation of present fields only
        $validated = $request->validate($rules);

        // Date formatting
        if (isset($validated['birthdate'])) {
            $validated['birthdate'] = \Carbon\Carbon::createFromFormat('d/m/Y', $validated['birthdate'])->format('Y-m-d');
        }

        // Password hash if present
        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        // Processing of the base64 image if present
        if (isset($validated['image_64'])) {
            $replace = substr($validated['image_64'], 0, strpos($validated['image_64'], ',') + 1);
            $image = str_replace($replace, '', $validated['image_64']);
            $image = str_replace(' ', '+', $image);

            $image_path = 'images/users/' . $user->id . '/avatar/' . Str::random(50) . '.png';

            Storage::disk('public')->put($image_path, base64_decode($image));

            $validated['avatar_url'] = Storage::url($image_path);

            unset($validated['image_64']);
        }

        // Update user with valid fields
        $user->update($validated);

        // Update PasswordReset only if necessary
        $password_reset = !empty($user->email)
            ? \App\Models\PasswordReset::where('email', $user->email)->first()
            : \App\Models\PasswordReset::where('phone', $user->phone)->first();

        if ($password_reset) {
            $updateData = [];

            if ($request->filled('email')) {
                $updateData['email'] = $request->email;
            }

            if ($request->filled('phone')) {
                $updateData['phone'] = $request->phone;
            }

            $updateData['token'] = (string) random_int(1000000, 9999999);

            $password_reset->update($updateData);
        }

        // Conditional return: AJAX or HTML POST
        return $request->expectsJson()
            ? response()->json(['success_message' => true, 'avatar_url' => $user->avatar_url ?? null])
            : back()->with('success_message', 'Vos informations ont bien été mises à jour.');
    }

    /**
     * POST: Add a product
     *
     * @param  \Illuminate\Http\Request  $request
     * @throws \Illuminate\Http\RedirectResponse
     */
    public function addProduct(Request $request)
    {
        $current_user = User::find(Auth::user()->id);
        $role_seller = Role::where('role_name', 'Vendeur')->first();
        $product = Product::create([
            'product_name' => $request->product_name,
            'product_description' => $request->product_description,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'is_service' => $request->is_service,
            'action' => $request->filled('action') ? $request->action : 'sell',
            'country' => $request->country,
            'city' => $request->city,
            'address' => $request->address,
            'municipality' => $request->municipality,
            'neighborhood' => $request->neighborhood,
            'street' => $request->street,
            'is_shared' => $request->filled('is_shared') ? $request->is_shared : 0,
            'created_by' => Auth::id(),
            'type' => $request->filled('type') ? $request->type : 'house',
            'category_id' => $request->category_id,
            'user_id' => Auth::check() ? Auth::id() : null,
        ]);

        // If image files exist
        if ($request->hasFile('files_urls')) {
            $files = $request->file('files_urls', []);
            $fileNames = $request->input('files_names', []);

            // Types of extensions for different file types
            $video_extensions = ['mp4', 'avi', 'mov', 'mkv', 'webm'];
            $photo_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $document_extensions = ['pdf', 'doc', 'docx', 'txt'];
            $audio_extensions = ['mp3', 'wav', 'flac'];

            foreach ($files as $key => $singleFile) {
                // Checking the file extension
                $file_extension = $singleFile->getClientOriginalExtension();

                // File type check
                $custom_uri = '';
                $is_valid_type = false;
                $file_type = null;

                if (in_array($file_extension, $video_extensions)) { // File is a video
                    $custom_uri = 'videos/products';
                    $file_type = 'video';
                    $is_valid_type = true;

                } elseif (in_array($file_extension, $photo_extensions)) { // File is a photo
                    $custom_uri = 'photos/products';
                    $file_type = 'photo';
                    $is_valid_type = true;

                } elseif (in_array($file_extension, $audio_extensions)) { // File is an audio
                    $custom_uri = 'audios/products';
                    $file_type = 'audio';
                    $is_valid_type = true;

                } elseif (in_array($file_extension, $document_extensions)) { // File is a document
                    $custom_uri = 'documents/products';
                    $file_type = 'video';
                    $is_valid_type = true;
                }

                // If the extension does not match any valid type
                if (!$is_valid_type) {
                    return response()->json(['status' => 'error', 'message' => 'Le type que vous avez choisi n’est pas un fichier']);
                }

                // Generate a unique path for the file
                $filename = $singleFile->getClientOriginalName();
                $file_url =  $custom_uri . '/' . $product->id . '/' . $filename;

                // Upload file
                try {
                    $singleFile->storeAs($custom_uri . '/' . $product->id, $filename, 'public');

                } catch (\Throwable $th) {
                    return response()->json(['status' => 'error', 'message' => 'Le fichier n’a pas pu être créé']);
                }

                // Creating the database record for the file
                File::create([
                    'file_name' => trim($fileNames[$key] ?? $filename),
                    'file_url' => getWebURL() . '/storage/' . $file_url,
                    'file_type' => $file_type,
                    'product_id' => $product->id
                ]);
            }
        }

        // If user is not yet seller, update its role
        if ($current_user->selected_role->id != $role_seller->id) {
            $current_user->roles()->updateExistingPivot($current_user->roles->pluck('id')->toArray(), ['is_selected' => 0]);
            $current_user->roles()->attach($role_seller->id, ['is_selected' => 1]);
        }

        return response()->json(['status' => 'success', 'message' => 'Offre enregistrée']);
    }

    /**
     * POST: Update a product entity
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $entity
     * @param  int  $id
     * @throws \Illuminate\Http\RedirectResponse
     */
    public function updateProductEntity(Request $request, $entity, $id)
    {
        $product = Product::find($id);

        if (is_null($product)) {
            return redirect('/')->with('error_message', 'Offre non trouvée');
        }

        if ($entity == 'product') {
            $inputs = [
                'product_name' => $request->product_name,
                'product_description' => $request->product_description,
                'quantity' => $request->quantity,
                'price' => $request->price,
                'currency' => $request->currency,
                'is_service' => $request->is_service,
                'action' => $request->filled('action') ? $request->action : 'sell',
                'country' => $request->country,
                'city' => $request->city,
                'address' => $request->address,
                'municipality' => $request->municipality,
                'neighborhood' => $request->neighborhood,
                'street' => $request->street,
                'type' => $request->type,
                'is_shared' => 0,
                'category_id' => $request->category_id,
            ];

            $current_product = Product::find($id);

            if ($inputs['product_name'] != null) {
                $current_product->update([
                    'product_name' => $inputs['product_name'],
                    'updated_by' => Auth::check() ? Auth::id() : null,
                ]);
            }

            if ($inputs['product_description'] != null) {
                $current_product->update([
                    'product_description' => $inputs['product_description'],
                    'updated_by' => Auth::check() ? Auth::id() : null,
                ]);
            }

            if ($inputs['quantity'] != null) {
                $current_product->update([
                    'quantity' => $inputs['quantity'],
                    'updated_by' => Auth::check() ? Auth::id() : null,
                ]);
            }

            if ($inputs['price'] != null) {
                $current_product->update([
                    'price' => $inputs['price'],
                    'updated_by' => Auth::check() ? Auth::id() : null,
                ]);
            }

            if ($inputs['currency'] != null) {
                $current_product->update([
                    'currency' => $inputs['currency'],
                    'updated_by' => Auth::check() ? Auth::id() : null,
                ]);
            }

            if ($inputs['is_service'] != $current_product->is_service) {
                $current_product->update([
                    'is_service' => $inputs['is_service'],
                    'updated_by' => Auth::check() ? Auth::id() : null,
                ]);
            }

            if ($inputs['action'] != $current_product->action) {
                $current_product->update([
                    'action' => $inputs['action'],
                    'updated_by' => Auth::check() ? Auth::id() : null,
                ]);
            }

            if ($inputs['country'] != null) {
                $current_product->update([
                    'country' => $inputs['country'],
                    'updated_by' => Auth::check() ? Auth::id() : null,
                ]);
            }

            if ($inputs['city'] != null) {
                $current_product->update([
                    'city' => $inputs['city'],
                    'updated_by' => Auth::check() ? Auth::id() : null,
                ]);
            }

            if ($inputs['address'] != null) {
                $current_product->update([
                    'address' => $inputs['address'],
                    'updated_by' => Auth::check() ? Auth::id() : null,
                ]);
            }

            if ($inputs['municipality'] != null) {
                $current_product->update([
                    'municipality' => $inputs['municipality'],
                    'updated_by' => Auth::check() ? Auth::id() : null,
                ]);
            }

            if ($inputs['neighborhood'] != null) {
                $current_product->update([
                    'neighborhood' => $inputs['neighborhood'],
                    'updated_by' => Auth::check() ? Auth::id() : null,
                ]);
            }

            if ($inputs['street'] != null) {
                $current_product->update([
                    'street' => $inputs['street'],
                    'updated_by' => Auth::check() ? Auth::id() : null,
                ]);
            }

            if ($inputs['is_shared'] != 0) {
                $current_product->update([
                    'is_shared' => $inputs['is_shared'],
                    'updated_by' => Auth::check() ? Auth::id() : null,
                ]);
            }

            if ($inputs['type'] != null) {
                $current_product->update([
                    'type' => $inputs['type'],
                    'updated_by' => Auth::check() ? Auth::id() : null,
                ]);
            }

            if ($inputs['category_id'] != null) {
                $current_product->update([
                    'category_id' => $inputs['category_id'],
                    'updated_by' => Auth::check() ? Auth::id() : null,
                ]);
            }

            // If image files exist
            if ($request->hasFile('files_urls')) {
                $files = $request->file('files_urls', []);
                $fileNames = $request->input('files_names', []);

                // Types of extensions for different file types
                $video_extensions = ['mp4', 'avi', 'mov', 'mkv', 'webm'];
                $photo_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                $document_extensions = ['pdf', 'doc', 'docx', 'txt'];
                $audio_extensions = ['mp3', 'wav', 'flac'];

                foreach ($files as $key => $singleFile) {
                    // Checking the file extension
                    $file_extension = $singleFile->getClientOriginalExtension();

                    // File type check
                    $custom_uri = '';
                    $is_valid_type = false;
                    $file_type = null;

                    if (in_array($file_extension, $video_extensions)) { // File is a video
                        $custom_uri = 'videos/products';
                        $file_type = 'video';
                        $is_valid_type = true;

                    } elseif (in_array($file_extension, $photo_extensions)) { // File is a photo
                        $custom_uri = 'photos/products';
                        $file_type = 'photo';
                        $is_valid_type = true;

                    } elseif (in_array($file_extension, $audio_extensions)) { // File is an audio
                        $custom_uri = 'audios/products';
                        $file_type = 'audio';
                        $is_valid_type = true;

                    } elseif (in_array($file_extension, $document_extensions)) { // File is a document
                        $custom_uri = 'documents/products';
                        $file_type = 'video';
                        $is_valid_type = true;
                    }

                    // If the extension does not match any valid type
                    if (!$is_valid_type) {
                        return response()->json(['status' => 'error', 'message' => 'Le type que vous avez choisi n’est pas un fichier']);
                    }

                    // Generate a unique path for the file
                    $filename = $singleFile->getClientOriginalName();
                    $file_url =  $custom_uri . '/' . $current_product->id . '/' . $filename;

                    // Upload file
                    try {
                        $singleFile->storeAs($custom_uri . '/' . $current_product->id, $filename, 'public');

                    } catch (\Throwable $th) {
                        return response()->json(['status' => 'error', 'message' => 'Le fichier n’a pas pu être créé']);
                    }

                    // Creating the database record for the file
                    File::create([
                        'file_name' => trim($fileNames[$key] ?? $filename),
                        'file_url' => getWebURL() . '/storage/' . $file_url,
                        'file_type' => $file_type,
                        'product_id' => $current_product->id
                    ]);
                }
            }

            return response()->json(['status' => 'success', 'message' => 'Données mises à jour']);
        }

        if ($entity == 'add-to-cart') {
            $request->validate([
                'quantity' => 'required|integer|min:1',
            ]);

            try {
                if (Auth::check()) {
                    // If user is connected, we add to its normal cart
                    $user = User::find(Auth::id());

                    $user->addProductToCart($id, $request->quantity);

                    $inCart = $user->hasProductInUnpaidCart($id);  // Check if product is in the cart
                    $inStock = $product->quantity > 0;  // Check if prouct is in stock
                    $isLoggedIn = true;

                } else {
                    // If user is connected, we store product in the session
                    $cart = session()->get('cart', []);
                    // Product photos
                    $photos = $product->photos()->pluck('file_url');
                    // Add product in the session cart
                    $cart[$id] = [
                        'id' => $product->id,
                        'product_name' => $product->product_name,
                        'product_description' => $product->product_description,
                        'quantity' => $request->quantity,
                        'price' => $product->price,
                        'currency' => $product->currency,
                        'type' => $product->type,
                        'action' => $product->action,
                        'photos' => $photos,
                    ];

                    session()->put('cart', $cart);

                    $inCart = true;  // Le produit est dans la session "panier"
                    $inStock = $product->quantity > 0;
                    $isLoggedIn = false;  // L'utilisateur n'est pas connecté
                }

                return response()->json([
                    'message' => 'Ajout réussi',
                    'inCart' => $inCart,
                    'inStock' => $inStock,
                    'isLoggedIn' => $isLoggedIn,
                ]);
            } catch (\Exception $e) {
                return response()->json(['message' => $e->getMessage()], 422);
            }
        }

        if ($entity == 'update-order-quantity') {
            try {
                // Checking if the user is authenticated
                if (Auth::check()) {
                    $order = CustomerOrder::find($id); // Get the order with the ID passed in the URL

                    if (!$order) {
                        return response()->json([
                            'message' => 'Commande non trouvée'
                        ], 404);
                    }

                    // Get the product associated with the order
                    $product = $order->product;

                    if (!$product) {
                        return response()->json(['message' => 'Offre non trouvée'], 404);
                    }

                    $user = User::find(Auth::id());

                    switch ($request->action) {
                        case 'increment':
                            $user->updateProductQuantityInCart($order->id, 1, 'increment');
                            break;

                        case 'decrement':
                            $user->updateProductQuantityInCart($order->id, 1, 'decrement');
                            break;

                        case 'update':
                            if ($request->quantity < 1) {
                                return response()->json([
                                    'message' => 'La quantité doit être au moins 1 offre commandée',
                                    'newQuantity' => $order->quantity,
                                    'inStock' => false,
                                ], 400);

                            } else {
                                $user->updateProductQuantityInCart($order->id, $request->quantity, 'update');
                            }
                            break;

                        default:
                            return response()->json([
                                'message' => 'Quelle action voulez-vous faire ?',
                                'newQuantity' => $order->quantity,
                                'inStock' => false,
                            ], 400);
                    }

                    return response()->json([
                        'message' => 'Commande mise à jour',
                        'newQuantity' => $order->quantity,
                        'inCart' => true,
                        'inStock' => $product->quantity > 0,
                    ]);

                } else {
                    $product = Product::find($id);

                    if (!$product) {
                        return response()->json(['message' => 'Offre non trouvée'], 404);
                    }

                    // If user is not connected, operation is done in the session
                    $cart = session()->get('cart', []);

                    if (!isset($cart[$id])) {
                        return response()->json(['message' => 'Panier non trouvé'], 404);
                    }

                    // Vérification de la quantité
                    switch ($request->action) {
                        case 'increment':
                            // Check if stock is sufficient for increment
                            if ($product->quantity <= 0) {
                                return response()->json([
                                    'message' => "Stock insuffisant pour « {$product->product_name} ». (Disponible : {$product->quantity})",
                                    'newQuantity' => $cart[$id]['quantity'],
                                    'inStock' => false,
                                ], 422);

                            } else {
                                // Increment quantity in cart
                                $cart[$id]['quantity']++;
                            }
                            break;

                        case 'decrement':
                            // Check that the quantity in the cart is > 500
                            if ($cart[$id]['quantity'] <= 1) {
                                return response()->json([
                                    'message' => 'La quantité doit être au moins 1 offre commandée',
                                    'newQuantity' => $cart[$id]['quantity'],
                                    'inStock' => false,
                                ], 422);

                            } else {
                                // Decrease quantity in cart
                                $cart[$id]['quantity']--;
                            }
                            break;

                        case 'update':
                            // Mise à jour de la quantité
                            if ($request->quantity < 1) {
                                return response()->json([
                                    'message' => 'La quantité doit être au moins 1 offre commandée',
                                    'newQuantity' => $cart[$id]['quantity'],
                                    'inStock' => false,
                                ], 400);

                            } else {
                                $cart[$id]['quantity'] = $request->quantity;
                            }
                            break;

                        default:
                            return response()->json([
                                'message' => 'Quelle action voulez-vous faire ?',
                                'newQuantity' => $cart[$id]['quantity'],
                                'inStock' => false,
                            ], 400);
                    }

                    // Save session with new quantity
                    session()->put('cart', $cart);

                    return response()->json([
                        'message' => 'Commande mise à jour',
                        'newQuantity' => $cart[$id]['quantity'],
                        'inCart' => true,
                        'inStock' => true,
                    ]);
                }

            } catch (\Exception $e) {
                return response()->json(['message' => $e->getMessage()], 422);
            }
        }
    }
}
