<?php

namespace App\Http\Controllers\Frontend;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Traits\FileManageTrait;
use Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use PragmaRX\Google2FALaravel\Support\Authenticator;

class ProfileController extends Controller
{
    use FileManageTrait;

    public function index()
    {
        $user = auth()->user();

        return view('frontend.user.setting.profile', compact('user'));
    }

    /**
     * Handle the user profile update request.
     *
     * @return RedirectResponse
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        // validation rules
        $validate = $request->validate([
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'phone' => 'nullable',
            'address' => 'nullable',
            'city' => 'nullable',
            'state' => 'nullable',
            'country' => 'nullable',
            'zip' => 'nullable',
        ]);

        if (config('app.demo') && $user->email === config('app.demo_user_email')) {
            notifyEvs('warning', __('Profile update is disabled for demo users'));
            return redirect()->back();
        }

        // if user uploaded a new avatar, update the avatar
        if ($request->hasFile('avatar')) {
            $validate['avatar'] = $this->uploadImage($request->file('avatar'), $user->avatar);
        }

        if ($user->email !== $validate['email']) {
            $validate['email_verified_at'] = null;
        }

        // update the user
        $user->update($validate);

        notifyEvs('success', 'Profile updated successfully');

        // return the user back to the form with a success message
        return redirect()->back();
    }

    public function changePassword()
    {
        return view('frontend.user.setting.change_password');
    }

    public function updatePassword(Request $request)
    {
        $user = auth()->user();
        $validate = $request->validate([
            'old_password' => 'required',
            'password' => 'required|confirmed',
        ]);

        if (config('app.demo') && $user->email === config('app.demo_user_email')) {
            notifyEvs('warning', __('Password change is disabled for demo users'));
            return redirect()->back();
        }

        if (! password_verify($validate['old_password'], $user->password)) {
            notifyEvs('warning', 'Wrong current password');

            return redirect()->back();
        }
        $user->password = bcrypt($validate['password']);
        $user->save();
        notifyEvs('success', 'Password updated successfully');

        return redirect()->back();
    }

    /**
     * Verify the user's email address.
     *
     * @return RedirectResponse
     */
    public function verifyEmail()
    {

        if (auth()->user()->hasVerifiedEmail()) {
            notifyEvs('warning', 'Your email address is already verified');

            return redirect()->intended(route('user.dashboard'));
        }

        auth()->user()->sendEmailVerificationNotification();
        notifyEvs('success', 'A fresh verification link has been sent to your email addres');

        // return the user back to the form with an error message
        return redirect()->back();
    }

    public function security()
    {
        $user = auth()->user();

        return view('frontend.user.setting.security', compact('user'));
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
        return redirect(route('user.dashboard'));
    }
}
