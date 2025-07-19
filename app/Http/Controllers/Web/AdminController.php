<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

/**
 * @author Xanders
 * @see https://team.xsamtech.com/xanderssamoth
 */
class AdminController extends Controller
{
    // ==================================== HTTP GET METHODS ====================================
    /**
     * GET: Home page
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('dashboard');
    }

    // ==================================== HTTP POST METHODS ====================================
    /**
     * POST: Update a role entity
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $entity
     * @param  int  $id
     * @throws \Illuminate\Http\RedirectResponse
     */
    public function updateRoleEntity(Request $request, $entity, $id)
    {
        if ($entity == 'users') {
            $user = User::find($id);

            DB::beginTransaction();

            try {
                // 1. Update all other roles for this user and set is_selected to 0
                $user->roles()->updateExistingPivot($user->roles->pluck('id')->toArray(), ['is_selected' => 0]);
                // 2. Add the new role and set is_selected to 1
                $user->roles()->attach($request->role_id, ['is_selected' => 1]);

                DB::commit();

                return response()->json(['message' => 'Rôle ajouté avec succès.']);
            } catch (\Exception $e) {
                DB::rollBack();

                return response()->json(['message' => 'Une erreur est survenue.', 'error' => $e->getMessage()], 500);
            }
        }
    }
}
