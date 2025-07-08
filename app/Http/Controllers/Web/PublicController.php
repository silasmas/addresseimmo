<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Category;
use App\Models\File;
use App\Models\Product;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

/**
 * @author Xanders
 * @see https://team.xsamtech.com/xanderssamoth
 */
class PublicController extends Controller
{
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
     * GET: Create symbolic link
     *
     * @return \Illuminate\View\View
     */
    public function symlink()
    {
        return view('symlink');
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
     * GET: Create symbolic link
     *
     * @return \Illuminate\View\View
     */
    public function search(Request $request)
    {
        $query = $request->get('query');
        $per_page = $request->get('per_page', 15);
        $filters = $request->only(['category_id', 'user_id', 'type', 'action']);
        // Request
        $products = Product::searchWithFilters($query, $filters, $per_page);

        return view('search', ['products' => $products]);
    }

    /**
     * GET: Account page
     *
     * @return \Illuminate\View\View
     */
    public function account()
    {
        return view('account', ['countries' => showCountries()]);
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
        $category = null;
        $categories = null;
        $items = null;

        if ($entity == 'cart') {
            $entity_title = __('miscellaneous.menu.account.cart');
            // Get user unpaid orders
            $items = $current_user->unpaidOrders();
        }

        return view('account', [
            'entity' => $entity,
            'entity_title' => $entity_title,
            'category' => $category,
            'categories' => $categories,
            'items' => $items,
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
        $categories = null;
        $items = null;

        if ($entity == 'buy') {
            $entity_title = 'Acheter une maison';
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
     * GET: Home page
     *
     * @param  string  $entity
     * @param  string  $id
     * @return \Illuminate\View\View
     */
    public function productDatas($entity, $id)
    {
        $entity_title = null;
        $selected_product = 'null';

        if ($entity == 'buy') {
            $entity_title = 'Détails sur la propriété';
            // $selected_product = Product::find($id);
        }

        return view('products', [
            'entity_title' => $entity_title,
            'entity' => $entity,
            'selected_product' => $selected_product,
        ]);
    }

    // ==================================== HTTP DELETE METHODS ====================================
    /**
     * GET: Delete product
     *
     * @param  string $entity
     * @param  int $id
     * @throws \Illuminate\Http\RedirectResponse
     */
    public function removeData($entity, $id)
    {
        if ($entity == 'product') {
            $product = Product::find($id);

            if (!$product) {
                return redirect(RouteServiceProvider::HOME)->with('error_message', __('notifications.find_error'));
            }

            $filesToDelete = File::where('product_id', $product->id)->get();

            foreach ($filesToDelete as $file) {
                // Delete the file from the file system
                $relativeStoragePath = str_replace(getWebURL() . '/storage/', '', $file->file_url);

                Storage::disk('public')->delete($relativeStoragePath);

                // Deletes the row at the database
                $file->delete();
            }

            $product->delete();

            return redirect('/products/' . $product->type)->with('success_message', __('notifications.deleted_data'));
        }

        if ($entity == 'cart') {
            $cart = Cart::find($id);

            if (!$cart) {
                return redirect(RouteServiceProvider::HOME)->with('error_message', __('notifications.find_error'));
            }

            $cart->delete();

            return redirect('/account')->with('success_message', __('notifications.deleted_data'));
        }
    }

    // ==================================== HTTP POST METHODS ====================================
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

        if ($request->has('p_o_box')) {
            $rules['p_o_box'] = ['nullable', 'string', 'max:255'];
        }

        if ($request->has('address_1')) {
            $rules['address_1'] = ['nullable', 'string'];
        }

        if ($request->has('address_2')) {
            $rules['address_2'] = ['nullable', 'string'];
        }

        if ($request->has('phone')) {
            $rules['phone'] = ['nullable', 'string', 'max:20'];
        }

        if ($request->has('email') && $request->input('email') !== $user->email) {
            $rules['email'] = ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)];
        }

        if ($request->has('username') && $request->input('username') !== $user->username) {
            $rules['username'] = ['required', 'string', 'max:45', Rule::unique('users')->ignore($user->id)];
        }

        if ($request->filled('password')) {
            $rules['password'] = ['required', 'confirmed', Rules\Password::defaults()];
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
     * POST: Add a product entity
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $entity
     * @throws \Illuminate\Http\RedirectResponse
     */
    public function addProductEntity(Request $request, $entity)
    {
        if ($entity == 'category') {
            // $request->validate([
            //     'category_name.en' => 'required|string|max:255',
            //     'category_name.fr' => 'required|string|max:255',
            //     'category_description.en' => 'nullable|string',
            //     'category_description.fr' => 'nullable|string',
            //     'alias' => 'required|string|unique:categories,alias',
            //     'for_service' => 'nullable|boolean',
            // ]);

            $category = Category::create([
                'category_name' => [
                    'en' => $request->category_name_en,
                    'fr' => $request->category_name_fr
                ],
                'category_description' => [
                    'en' => $request->category_description_en,
                    'fr' => $request->category_description_fr
                ],
                'for_service' => $request->filled('for_service') ? $request->for_service : 0,
                'alias' => $request->alias,
                'created_by' => Auth::check() ? Auth::id() : null,
            ]);

            if (isset($request->image_64)) {
                // $extension = explode('/', explode(':', substr($request->image_64, 0, strpos($request->image_64, ';')))[1])[1];
                $replace = substr($request->image_64, 0, strpos($request->image_64, ',') + 1);
                // Find substring from replace here eg: data:image/png;base64,
                $image = str_replace($replace, '', $request->image_64);
                $image = str_replace(' ', '+', $image);
                // Create image URL
                $image_path = 'images/categories/' . $category->id . '/' . Str::random(50) . '.png';

                // Upload image
                Storage::disk('public')->put($image_path, base64_decode($image));

                $category->update([
                    'image_url' => Storage::url($image_path),
                    'updated_at' => now()
                ]);
            }

            return response()->json(['status' => 'success', 'message' => __('notifications.registered_data')]);
        }

        if ($entity == 'product') {
            $request->validate([
                'product_name' => ['required', 'string', 'max:255'],
                'price' => ['required', 'float'],
                'quantity' => ['required', 'integer', 'min:1'],
            ], [
                'product_name.required' => __('validation.required'),
                'price' => __('validation.required'),
                'quantity' => __('validation.required'),
            ]);

            $product = Product::create([
                'product_name' => $request->product_name,
                'product_description' => $request->product_description,
                'quantity' => $request->quantity,
                'price' => $request->price,
                'currency' => $request->currency,
                'type' => $request->filled('type') ? $request->type : 'product',
                'action' => $request->filled('action') ? $request->action : 'sell',
                'is_shared' => $request->filled('is_shared') ? $request->is_shared : 0,
                'category_id' => $request->category_id,
                'user_id' => Auth::id(),
                'created_by' => Auth::check() ? Auth::id() : null,
            ]);

            // If image files exist
            if ($request->hasFile('images_urls')) {
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
                        return $this->handleError(__('notifications.type_is_not_file'));
                    }

                    // Generate a unique path for the file
                    $filename = $singleFile->getClientOriginalName();
                    $file_url =  $custom_uri . '/' . $product->id . '/' . $filename;

                    // Upload file
                    try {
                        $singleFile->storeAs($custom_uri . '/' . $product->id, $filename, 'public');

                    } catch (\Throwable $th) {
                        return $this->handleError($th, __('notifications.create_work_file_500'), 500);
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

            return response()->json(['status' => 'success', 'message' => __('notifications.registered_data')]);
        }
    }

    /**
     * POST: Add a product entity
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $entity
     * @param  int  $id
     * @throws \Illuminate\Http\RedirectResponse
     */
    public function updateProductEntity(Request $request, $entity, $id)
    {
        if ($entity == 'add-to-cart') {
            $request->validate([
                'quantity'   => 'required|integer|min:1',
            ]);

            $user = User::find(Auth::id());

            try {
                $user->addProductToCart($id, $request->quantity);

                return response()->json(['message' => __('notifications.added_data')]);
            } catch (\Exception $e) {
                return response()->json(['message' => $e->getMessage()], 422);
            }
        }
    }
}
