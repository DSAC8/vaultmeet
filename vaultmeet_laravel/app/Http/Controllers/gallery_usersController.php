<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use App\Models\gallery_users;
use App\Models\user;
use Illuminate\Support\Str;

class gallery_usersController extends Controller
{

    




    public function forgot_password(Request $request)
    {
        $user = gallery_users::where('email', $request->input('email'))->first();
        

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $token = Str::random(16);
        $user->update(['pass_token' => $token]);

        $frontendUrl = 'https://nagypeti.moriczcloud.hu/projekt/#/new-pass';
        $resetLink = "$frontendUrl?token=$token";

        $message = "
            <html>
                <body>
                    <div style='background: linear-gradient(to bottom, rgba(28, 143, 151, 0.31), rgba(165, 8, 165, 0.31)), url(\"https://fiverr-res.cloudinary.com/images/t_main1,q_auto,f_auto,q_auto,f_auto/gigs/152164099/original/d793cc44e3997aa1d090c5d4d8041d7feb28e235/create-pixel-art-backgrounds.png\"); background-size: cover; border-radius: 10px 70% 37%  10px;  color:#c9f7cd; padding: 20px;'>
                        <h1 style='color:#c9f7cd;'>A password reset request was made for your account, $user->name!</h1>
                        <h2 style='color:#c9f7cd;'>
                            To reset your password:
                            <a href='$resetLink' style='color:#FFD700 ;text-decoration: underline;'>Reset Password</a>
                        </h2>
                        <h3 style='color:#c9f7cd;'>Request time: " . now() . "</h3>
                        <img style='border-radius: 10px;' src='$this->image_url' alt='Map' />
                    </div>
                </body>
            </html>
        ";

        $headers = "Content-type: text/html; charset=UTF-8";
        mail($request->input('email'), "Forgot Password!", $message, $headers);

        return response()->json(['message' => 'Password reset link sent!']);
    }

 public function register(Request $request)
{
    $user = gallery_users::create([
        'name' => $request->input('name'),
        'email' => $request->input('email'),
        'password' => Hash::make($request->input('password'))
    ]);

    if (!$user) {
        return response()->json(['status' => 'error', 'message' => 'Registration failed'], 500);
    }

    // Automatikus bejelentkeztetés
    $token = $user->createToken('token')->plainTextToken;

    return response()->json([
        'status' => 'success',
        'message' => 'Registration is successful',
        'token' => $token,
        'user' => $user,
    ], 201);
}


    public function login(Request $request)
    {
        if (!Auth::attempt($request->only("email", "password"))) {
            return response([
                'message' => 'Invalid credentials!'
            ], 401);
        }

        $user = Auth::user();

        $existingToken = $user->tokens()->where('name', 'token')->first();

        if ($existingToken) {
            $existingToken->delete();
        }

        $token = $user->createToken('token')->plainTextToken;

        $cookie = cookie('jwt', $token, 60 * 24);

        return response([
            'message' => $token
        ])->withCookie($cookie);
    }


    public function logout(Request $request)
    {
        $cookie = Cookie::forget('jwt');
        auth()->guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $request->user()->tokens()->delete();
        return response([
            "message" => "Success"
        ])->withCookie($cookie);
    }

    public function user()
    {
        return Auth::user();
    }


    public function pass_update(Request $request)
    {
        $token = gallery_users::where('pass_token', $request->input('pass_token'))->first();

        if ($token) {
            $updated = $token->update([
                'password' => Hash::make($request->input('password')),
            ]);
            return response()->json([
                'status' => 'success',
                'data' => $token
            ], 200);
        } else {
            return response()->json([
                'error' => 'token is not valid'
            ], 400);
        }
    }









}
