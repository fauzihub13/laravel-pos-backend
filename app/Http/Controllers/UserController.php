<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        // get all users with pagination
        $users = DB::table('users')
        ->when($request->input('name'), function($query, $name) {
            $query->where('name', 'like', '%'.$name.'%')
            ->orWhere('email', 'like', '%'.$name.'%');
        })
        ->paginate(10);
        return view('pages.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('pages.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:8',
            'role'=>'required|in:admin,staff,user',
        ]);

        // add the request
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->role = $request->role;
        $user->save();

        return redirect()->route('users.index')->with('success', 'User added succesfully');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        return view('pages.users.show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $user = User::findOrFail($id);
        return view('pages.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'name'=>'required',
            'email'=>'required',
            'role'=>'required|in:admin,staff,user',
        ]);

        // update the request
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->save();

        // if password not empty
        if ($request->password){
            $user->password = Hash::make($request->password);
            $user->save();
        }

        return redirect()->route('users.index')->with('success', 'User updated succesfully');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $user = User::find($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted succesfully');

    }
}
