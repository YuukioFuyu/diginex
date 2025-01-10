<?php

namespace App\Http\Controllers\Backend;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Traits\FileManageTrait;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PragmaRX\Google2FALaravel\Support\Authenticator;

class ProfileController extends Controller
{
    use FileManageTrait;

    public function __construct()
    {
        $this->middleware('auth');
    }

    //admin profile view for edit
    public function profile()
    {
        $user = auth()->user();
        $activeSection = 'profile';
        return view('backend.profile.edit', compact('user', 'activeSection'));
    }

    //admin profile update
    public function update(Request $request)
    {
        $input = $request->validate(['first_name' => 'required', 'last_name' => 'required', 'email' => 'required']);

        $user = auth()->user();
        if ($request->hasFile('avatar')) {

            $oldAvatar = $user->avatar == 'general/static/default/avatar.png' ? null : $user->avatar;
            $input['avatar'] = self::uploadImage($request->file('avatar'), $oldAvatar);
        }
        $user->update($input);
        notifyEvs('success', __('Profile Updated Successfully'));

        return redirect()->back();
    }

    public function twoFaAuthGenerate()
    {
        $user = auth()->user();
        $google2fa = app('pragmarx.google2fa');
        $user->google2fa_secret = $google2fa->generateSecretKey();
        $user->save();
        notifyEvs('success', __('Two Factor Authentication Secret Key Generated Successfully'));

        return redirect()->back();
    }

    public function twoFaAuth(Request $request)
    {

        $user = auth()->user();

        if ($request->status == Status::DISABLE) {

            if (Hash::check(request('one_time_password'), $user->password)) {
                $user->update(['google2fa_enabled' => Status::INACTIVE]);

                notifyEvs('success', __('2Fa Authentication Disable successfully'));

                return redirect()->back();
            }

            notifyEvs('error', __('Wrong Login Password'));

            return redirect()->back();

        } elseif ($request->status == Status::ENABLE) {

            session([config('google2fa.session_var') => ['auth_passed' => false]]);
            $authenticator = app(Authenticator::class)->boot($request);
            if ($authenticator->isAuthenticated()) {

                $user->update(['google2fa_enabled' => Status::ACTIVE]);
                notifyEvs('success', __('2Fa Authentication Enable successfully'));

                return redirect()->back();

            }
            notifyEvs('error', __('2Fa Authentication Wrong One Time Key'));

            return redirect()->back();
        }

    }

    public function twoFaAuthVerify(Request $request)
    {
        return redirect(route('admin.dashboard'));
    }

    //admin profile password update
    public function passwordUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), (['old_password' => 'required', 'new_password' => 'required|min:6', 'confirm_password' => 'required|same:new_password']));

        if ($validator->fails()) {
            notifyEvs('error', $validator->errors()->first('confirm_password'));

            return redirect()->back();
        }

        $input = $validator->validated();
        $user = auth()->user();

        if (Hash::check($input['old_password'], $user->password)) {
            $user->update(['password' => Hash::make($input['new_password'])]);

            notifyEvs('success', __('Password Updated Successfully'));

            return redirect()->back();
        }

        notifyEvs('error', __('Old Password Does Not Match'));

        return redirect()->back();

    }
}
