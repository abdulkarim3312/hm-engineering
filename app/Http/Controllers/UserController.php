<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class UserController extends Controller
{
    public function index() {
        $users = User::orderBy('name')->get();
//        DB::table('model_has_permissions')->update([
//            'model_type'=>'App\Models\User'
//        ]);
        return view('user_management.all', compact('users'));
    }

    public function add() {
        return view('user_management.add');
    }

    public function addPost(Request $request) {

//                foreach ($request->permission as $per){
//                    DB::table('permissions')->insert(
//                        [
//                            'name'=> $per,
//                            'guard_name'=>'web',
//                            'created_at'=>Carbon::now(),
//                            'updated_at'=>Carbon::now(),
//                        ]
//                    );
//                }

            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
//                'status' => 'required'
            ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->role = 1;
        $user->save();

        $user->syncPermissions($request->permission);

        return redirect()->route('user.all')->with('message', 'User add successfully.');
    }

    public function edit(User $user) {
        return view('user_management.edit', compact('user'));
    }

    public function editPost(User $user, Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:8|',
//            'status' => 'required',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
//        $user->status = $request->status;

        if ($request->password) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        $user->syncPermissions($request->permission);

        return redirect()->route('user.all')->with('message', 'User edit successfully.');
    }
    public function delete(Request $request) {
        $user = User::find($request->id);
        $user->delete();
    }
}
