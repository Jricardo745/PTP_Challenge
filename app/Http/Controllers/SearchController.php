<?php

namespace App\Http\Controllers;

use App\Entities\Client;
use App\Entities\User;
use App\Entities\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Access\AuthorizationException;

class SearchController extends Controller
{
    /**
     * Display the specified resource filtering by name.
     * @param Request $request
     * @return
     * @throws AuthorizationException
     */
    public function clients(Request $request)
    {
        $this->authorize('viewAny', Client::class);
        return DB::table('users as u')
            ->selectRaw('u.id, concat(u.name, " ", u.surname) as fullname')
            ->join('clients as c', 'c.id', '=', 'u.id')
            ->whereRaw('concat(u.name, " ", u.surname) like "%' . $request->name . '%"')
            ->orderBy('fullname')
            ->limit('100')
            ->get();
    }

    /**
     * Display the specified resource filtering by name.
     * @param Request $request
     * @return
     * @throws AuthorizationException
     */
    public function users(Request $request)
    {
        $this->authorize('viewAny', User::class);
        return User::selectRaw('id, concat(name, " ", surname) as fullname')
            ->whereDoesntHave('client', function ($query) {
                $query->where('id', '!=', 'id');
            })
            ->whereRaw('concat(name, " ", surname) like "%' . $request->name . '%"')
            ->orderBy('fullname')
            ->limit('100')
            ->get();
    }

    /**
     * Display the specified resource filtering by name.
     * @param Request $request
     * @return
     * @throws AuthorizationException
     */
    public function products(Request $request)
    {
        $this->authorize('viewAny', Product::class);
        return Product::where('name', 'like', '%'. $request->name .'%')
            ->orderBy('name')
            ->limit('100')
            ->get();
    }

    /**
     * Display the specified resource filtering by rol.
     * @param Request $request
     * @return
     */
    public function permissions(Request $request)
    {
        $permissions = DB::table('role_has_permissions as rp')
            ->select('rp.permission_id as id')
            ->where('role_id', $request->role_id)
            ->get();

        return response()->json([
            'permissions' => $permissions,
        ]);
    }
}
