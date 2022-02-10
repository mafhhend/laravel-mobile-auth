<?php

namespace Mafhhend\LaravelMobileAuth\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Mafhhend\LaravelMobileAuth\Http\Models\Otp;

class AuthController extends Controller
{
    public function login()
    {
        return view("laravel-mobile-auth::login");
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->route("laravel_mobile_auth.login")->with([
            "is_logged_out" => true
        ]);
    }

    public function otpCheck(Request $request)
    {
        session()->reflash();
        $request->validate([
            'phone' => 'required|numeric|digits:11|exists:otps,phone',
            'otp'   => 'required|numeric|digits:4'
        ], [
            'phone.required' => 'شماره موبایل الزامی است',
            'phone.numeric'  => 'شماره موبایل باید عدد باشد.',
            'phone.digits'   => 'شماره موبایل باید 11 رقم باشد.',
            'phone.exists'   => 'شماره وارد شده معتبر نیست!',
            'otp.required'   => 'کدیکبار مصرف الزامی است',
            'otp.numeric'    => 'کدیکبار مصرف باید عدد باشد.',
            'otp.digits'     => 'کدیکبار مصرف باید 4 رقم باشد.',
        ]);

        // Validated
        $phone = $request->input('phone');
        $otp   = $request->input('otp');

        $otpRequest = Otp::where('phone', $phone)->first();
        if ($otpRequest->code != $otp) {
            return redirect()->back()->withInput(['phone' => $phone])->withErrors(['otp' => "رمز یکبار مصرف وارد شده معتبر نیست!"]);
        }
        // 100% Sure, Code is TRUE
        $user = User::where(['phone' => $phone])->first();
        if (!$user)
            $user = User::create([
                "phone" => $phone
            ]);

        Auth::loginUsingId($user->id);

        // Remove All OTP for this phone FROM DB
        Otp::firstWhere("phone", $phone)->delete();

        $user->update([
            "attempts_left" => 3,
            "must_login_with_otp" => false
        ]);

        return redirect()->route("laravel_mobile_auth.dashboard")->with([
            "welcome_message" => true
        ]);
    }

    public function otpLogin()
    {
        $phone = session('phone');
        if (!$phone)
            return redirect()->route("laravel_mobile_auth.login");

        $this->_fireOtpEvent();
        session()->reflash(); // to avoid expire the phone session every refresh
        return view("laravel-mobile-auth::otp", compact("phone"));
    }

    private function _fireOtpEvent()
    {
        $phone = session()->get("phone");
        $otpRequest = Otp::firstWhere(['phone' => $phone]);

        if (!$otpRequest) {
            $this->_generateOtp();
        } else {
            // our OTP request will expire 2m later :
            $expiredAt = $otpRequest->created_at + 120;
            if (time() > $expiredAt) {
                //expired, generate new code :
                $this->_generateOtp();
            }
        }
    }

    public function _generateOtp()
    {
        $phone = session()->get("phone");
        Otp::where('phone', $phone)->delete();
        $code = random_int(1000, 9999);
        $isExists = Otp::firstWhere('code', $code);

        if ($isExists)
            $this->_generateOtp();

        Otp::create([
            'phone' => $phone,
            "code" => $code
        ]);
    }
    public function dashboard()
    {
        return view("laravel-mobile-auth::layouts.dashboard");
    }

    public function passwordLogin()
    {
        session()->reflash();
        return view("laravel-mobile-auth::password");
    }

    public function passwordCheck(Request $request)
    {
        $request->validate(
            [
                "phone" => "required|numeric|digits:11|exists:users,phone",
                "password" => "required"
            ],
            [
                "phone.required" => "شماره موبایل الزامی است.",
                "phone.numeric" => "شماره موبایل باید عددی باشد.",
                "phone.digits" => "شماره موبایل باید ۱۱ رقم باشد",
                "phone.exists" => "شماره وارد شده معتبر نیست",
                "password.required" => "گذرواژه الزامی است"
            ]
        );
        $phone = $request->input("phone");
        $password = $request->input("password");

        $user = User::firstWhere('phone', $phone);


        if ($user->attempts_left <= 0 || $user->must_login_with_otp)
            return redirect()->route("laravel_mobile_auth.otp")->with([
                "phone" => $phone,
                "is_redirected_from_password_login" => true
            ]);


        if ($user->attempts_left <= 0 || $user->must_login_with_otp)
            return redirect()->route("laravel_mobile_auth.otp")->with([
                "phone" => $phone,
                "is_redirected_from_password_login" => true
            ]);


        if (Hash::check($password, $user->password)) {
            Auth::loginUsingId($user->id);
            $user->update([
                "attempts_left" => 3,
                "must_login_with_otp" => false
            ]);
            return redirect()->route("laravel_mobile_auth.dashboard")->with([
                "welcome_message" => true
            ]);
        } else {
            // #tip for using update method `attempts_left` MUST be `fillable` eles won't work!
            $user->decrement('attempts_left', 1);
            return back()->withErrors([
                "password" => 'گذرواژه وارد شده اشتباه می باشد',
            ])->with('phone', $phone);
        }
    }

    public function checkAuth(Request $request)
    {
        $request->validate([
            "phone" => "required|numeric|digits:11"
        ], [
            "phone.required" => "شماره موبایل الزامی است.",
            "phone.numeric" => "شماره موبایل باید عددی باشد.",
            "phone.digits" => "شماره موبایل باید ۱۱ رقم باشد"
        ]);

        $phone = $request->input('phone');
        $user = User::firstWhere("phone", $phone);
        if (!$user)
            return redirect()->route("laravel_mobile_auth.otp")->with(['phone' => $phone, 'can_login_with_password' => false]);

        // User Exists ! :)
        // If user hasn't password | if user should login with OTP | if user attempts is 0 or less
        if (!$user->password || $user->must_login_with_otp || $user->attempts_left <= 0) {
            return redirect()->route("laravel_mobile_auth.otp")->with('phone', $phone);
        }

        if ($user->attempts_left > 0) {
            return redirect()->route("laravel_mobile_auth.password")->with('phone', $phone);
        }
    }
}
