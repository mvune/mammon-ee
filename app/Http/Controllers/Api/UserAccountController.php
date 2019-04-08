<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use App\Http\Controllers\Controller;
use App\Http\Resources\User as UserResource;

class UserAccountController extends Controller
{
    /**
     * Get the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return new UserResource(Auth::user());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string',
            'passwordNew' => 'nullable|min:8',
            'passwordRepeat' => 'same:passwordNew',
        ]);

        if (!password_verify($request->password, Auth::user()->password)) {
            return response()->json(['errors' => ['password' => [__('auth.wrong_password')]]], 422);
        }

        $data = $request->only(['name', 'email']);

        if ($request->filled('passwordNew')) {
            $data['password'] = Hash::make($request->passwordNew);
        }

        Auth::user()->update($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        Auth::user()->delete();
        session()->flash('flash_message', __('auth.account_deleted'));

        return redirect()->route('home');
    }
}
