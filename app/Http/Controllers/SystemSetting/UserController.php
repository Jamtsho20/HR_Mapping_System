<?php

namespace App\Http\Controllers\SystemSetting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use DB;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:system-setting/users,view', ['only' => ['index', 'show']]);
        $this->middleware('permission:system-setting/users,create', ['only' => ['create', 'store']]);
        $this->middleware('permission:system-setting/users,edit', ['only' => ['edit', 'update', 'postDisableToggle']]);
        $this->middleware('permission:system-setting/users,delete', ['only' => 'destroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $privileges = $request->instance();
        
        $users = User::filter($request)->orderBy('name')->paginate(config('global.pagination'))->withQueryString();

        return view('system-settings.users.index', compact('users', 'privileges'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::orderBy('id')->get();

        return view('system-settings.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'username' => 'required|unique:mas_employees,username',
            'email' => 'required|email|unique:mas_employees,email',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
        ];

        if (!$request->roles) {
            return redirect()->back()->with('msg_error', 'You need to select at least one role');
        }

        $this->validate($request, $rules);

        \DB::transaction(function () use ($request) {
            //refer uploadImageToDirectory in helpers.php
            // if ($request->hasFile('profile_pic')) {
            //     $imageSource = uploadImageToDirectory($request->file('profile_pic'), 'uploads/user-avatars/');
            // }

            $user = new User;
            $user->name = $request->name;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->is_active = 1;
            // $user->profile_pic = isset($imageSource) ? $imageSource : null;
            $user->created_by = auth()->user()->id;
            $user->save();

            $rolesAssigned = [];
            foreach($request->roles as $key => $value) {
                $rolesAssigned[$value] = [
                    'created_by' => $request->user()->id,
                    'updated_by' => $request->user()->id,
                ];
            }

            $user->roles()->sync($rolesAssigned);

        });

        return redirect('system-setting/users')->with('msg_success', 'User created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $instance = $request->instance(); //we need to pull the privileges from the instance
        $canUpdate = (integer) $instance->edit;
        $user = User::with('roles')->findOrFail($id);
        return view('system-settings.users.show', compact('user', 'canUpdate'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $roles = Role::orderBy('id')->get();
        $user = User::with('roles')->findOrFail($id);
        $rolesAssigned = $user->roles->pluck('id')->toArray();

        return view('system-settings.users.edit', compact('roles', 'user', 'rolesAssigned'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!$request->roles) {
            return redirect()->back()->with('msg_error', 'You need to at least select one role');
        }

        $rules = [
            'name' => 'required',
        ];

        $rules['email'] = 'required|email|unique:mas_employees,email,' . $id;
        $rules['username'] = 'required|unique:mas_employees,username,' . $id;

        $request->validate($rules);

        DB::transaction(function () use ($request, $id) {
            //refer uploadImageToDirectory in helpers.js
            // if ($request->hasFile('profile_pic')) {
            //     $imageSource = uploadImageToDirectory($request->file('profile_pic'), 'uploads/user-avatars/');
            // }

            $user = User::findOrFail($id);

            $user->name = $request->name;
            $user->username = $request->username;
            $user->email = $request->email;
            // $user->profile_pic = isset($imageSource) ? $imageSource : null;
            $user->updated_by = auth()->user()->id;
            $user->save();

            $rolesAssigned = [];
            foreach($request->roles as $key => $value) {
                $rolesAssigned[$value] = [
                    'created_by' => $request->user()->id,
                    'updated_by' => $request->user()->id,
                ];
            }

            $user->roles()->sync($rolesAssigned);

        });

        return redirect('system-setting/users')->with('msg_success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function changeUserStatus(Request $request)
    {
        $user = User::findOrFail($request->id);

        $disable = false;

        if ($user->is_active == 1) {
            $disable = true;
            $user->is_active = 0;
        } else {
            $user->is_active = 1;
        }

        $user->save();

        if ($disable) {
            return redirect('system-setting/users')->with('msg_success', 'The user account has been suspended.');
        } else {
            return redirect('system-setting/users')->with('msg_success', 'The user account is activated.');
        }
    }

    public function getResetPassword(Request $request, $id)
    {
        $instance = $request->instance(); //we need to pull the privileges from the instance
        $canUpdate = (integer) $instance->edit;
        $user = User::with('roles')->findOrFail($id);

        return view('system-settings.users.reset-password', compact('user', 'canUpdate'));
    }

    public function postResetPassword(Request $request, $id)
    {
        $this->validate($request, [
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'password' => bcrypt($request->confirm_password),
            'updated_by' => auth()->user()->id
        ]);

        return redirect('system-setting/users')->with('msg_success', 'Password reset successfully done');
    }
}
