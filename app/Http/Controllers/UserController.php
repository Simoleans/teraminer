<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Users/Index', [
            'users' => User::all(),
            'flash' => [
                'alert' => session('alert'),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Users/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);

        User::create($request->all());

        return redirect()->route('users.index')->with('alert', 'Usuario Creado Correctamente.');


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::find($id);

        return Inertia::render('Users/Edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        //validate rule, email unique and passqword required if password is different null
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'rol' => 'required',
        ]);

        $user = User::find($id);

        if ($request->password) {
            $user->password = bcrypt($request->password);
        }
        $user->name = $request->name;
        $user->email = $request->email;
        $user->rol = $request->rol;
        $user->save();

        return redirect()->route('users.index')->with('alert', 'Usuario Actualizado Correctamente.');
    }

    public function updateStatus(Request $request, string $id)
    {
        $user = User::find($id);
        $user->status = 0;
        $user->save();

        return redirect()->route('users.index')->with('alert', $user->name.' ha sido suspendido Correctamente.');
    }

    public function restoreStatus(Request $request, string $id)
    {
        $user = User::find($id);
        $user->status = 1;
        $user->save();

        return redirect()->route('users.index')->with('alert', $user->name.' ha sido restaurado Correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

    }
}
