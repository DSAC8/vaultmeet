<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use App\Models\gallery_users;
use App\Models\user;
use Illuminate\Support\Str;
use App\Models\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Models\Kulcsszo;
use App\Models\Valaszok;    

class gallery_usersController extends Controller
{

    

  public function events_list()
    {
        $events = Event::with('creatorUser')->orderBy('start_time', 'asc')->get();

    $events = $events->map(function($event) {
        return [
            'id' => $event->id,
            'title' => $event->title,
            'description' => $event->description,
            'location' => $event->location,
            'start_time' => $event->start_time,
            'end_time' => $event->end_time,
            'creator_id' => $event->creator,
            'creator_name' => $event->creatorUser ? $event->creatorUser->name : null,
        ];
    });

    return response()->json($events);
}

public function events_store(Request $request)
{
 
    $validator = Validator::make($request->all(), [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'location' => 'nullable|string|max:255',
        'start_time' => 'required|date',
        'end_time' => 'required|date|after_or_equal:start_time',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    // LÉTREHOZÁS
    $event = new Event();
    $event->title = $request->title;
    $event->description = $request->description;
    $event->location = $request->location;
    $event->start_time = $request->start_time;
    $event->end_time = $request->end_time;
    $event->creator = auth()->id(); // EZ FONTOS: csak belépett user csinálhat eventet
    $event->save();

    return response()->json([
        'message' => 'Event created successfully',
        'event' => $event
    ], 201);
}


    public function forgot_password(Request $request)
    {
        $user = gallery_users::where('email', $request->input('email'))->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $token = Str::random(16);
        $user->update(['pass_token' => $token]);

        $frontendUrl = 'http://localhost:8080/new-pass'; // vagy http://127.0.0.1:8080/new-pass
        $resetLink = "$frontendUrl?token=$token";

        $message = "<html>
            <body>
                <div style='background: linear-gradient(to bottom, rgba(28, 143, 151, 0.31), rgba(165, 8, 165, 0.31)), url(\"https://fiverr-res.cloudinary.com/images/t_main1,q_auto,f_auto,q_auto,f_auto/gigs/152164099/original/d793cc44e3997aa1d090c5d4d8041d7feb28e235/create-pixel-art-backgrounds.png\"); background-size: cover; border-radius: 10px 70% 37%  10px;  color:#c9f7cd; padding: 20px;'>
                    <h1 style='color:#c9f7cd;'>A password reset request was made for your account, $user->name!</h1>
                    <h2 style='color:#c9f7cd;'>
                        To reset your password:
                        <a href='$resetLink' style='color:#FFD700 ;text-decoration: underline;'>Reset Password</a>
                    </h2>
                    <h3 style='color:#c9f7cd;'>Request time: " . now() . "</h3>
                    <img style='border-radius: 10px;' src='' alt='Map' />
                    </div>
                </body>
            </html>
        ";

        Mail::raw($message, function ($mail) use ($request) {
            $mail->to($request->input('email'))
                ->subject('Forgot Password!');
        });

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

    public function user(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }

        return response()->json($user);
    }


public function pass_update(Request $request)
{
    $request->validate([
        'token' => 'required',
        'password' => 'required|confirmed|min:8',
    ]);

    $user = User::where('pass_token', $request->token)->first();
    if (!$user) {
        return response()->json(['message' => 'Érvénytelen vagy lejárt token!'], 400);
    }

    $user->password = bcrypt($request->password);
    $user->pass_token = null;
    $user->save();

    return response()->json(['message' => 'Jelszó sikeresen frissítve!']);
}
public function delete_event($id)
{
    $event = Event::find($id);

    if (!$event) {
        return response()->json(['message' => 'Event not found'], 404);
    }

    
    if ($event->creator !== auth()->id()) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $event->delete();

    return response()->json(['message' => 'Event deleted successfully']);
}
public function update_event(Request $request, $id)
{
    $event = Event::find($id);

    if (!$event) {
        return response()->json(['message' => 'Event not found'], 404);
    }

   
    if ($event->creator !== auth()->id()) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $validator = Validator::make($request->all(), [
        'description' => 'required|string',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $event->description = $request->description;
    $event->save();

    return response()->json([
        'message' => 'Description updated successfully',
        'event' => $event
    ]);
}
public function valasz(Request $request)
{
    $kerdes = strtolower($request->input('kerdes'));
    $szavak = preg_split('/[\s,.!?]+/', $kerdes, -1, PREG_SPLIT_NO_EMPTY);
    $kulcsszavak = Kulcsszo::with('valaszok')->get();
    $talalatok = [];

    // Kulcsszavas találatok
    foreach ($kulcsszavak as $kulcsszo) {
        if (str_contains($kerdes, strtolower($kulcsszo->szo))) {
            $valasz = $kulcsszo->valaszok->first()->valasz ?? null;
            if ($valasz) {
                $talalatok[] = $valasz;
            }
        }
    }

    if (!empty($talalatok)) {
        return response()->json(['valasz' => implode("\n\n", $talalatok)]);
    }

    // Dátum alapján
    if (preg_match('/\d{4}[-. ]\d{2}[-. ]\d{2}/', $kerdes, $matches)) {
        $datum = date('Y-m-d', strtotime($matches[0]));
        $esemenyek = Event::with('creatorUser')->whereDate('start_time', $datum)->get();

        if ($esemenyek->isEmpty()) {
            return response()->json(['valasz' => '📅 Ezen a napon nincsenek események.']);
        }

        $valaszok = $esemenyek->map(function ($e, $i) {
            return
                "📌 **Esemény #".($i+1)."**\n" .
                "🎉 Cím: {$e->title}\n" .
                "📍 Helyszín: {$e->location}\n" .
                "🕒 Kezdés: {$e->start_time}\n" .
                "🕕 Befejezés: {$e->end_time}\n" .
                "👤 Létrehozó: " . ($e->creatorUser?->name ?? 'ismeretlen') . "\n" .
                "📝 Leírás: {$e->description}\n";
        });

        return response()->json(['valasz' => $valaszok->implode("\n-------------------------\n")]);
    }

    // Felhasználónév alapján (szavanként)
    foreach ($szavak as $szo) {
        $user = gallery_users::whereRaw('LOWER(name) LIKE ?', ["%$szo%"])->first();
        if ($user) {
            $esemenyek = Event::with('creatorUser')->where('creator', $user->id)->get();

            if (!$esemenyek->isEmpty()) {
                $valaszok = $esemenyek->map(function ($e, $i) {
                    return
                        "📌 **Esemény #".($i+1)."**\n" .
                        "🎉 Cím: {$e->title}\n" .
                        "📍 Helyszín: {$e->location}\n" .
                        "🕒 Kezdés: {$e->start_time}\n" .
                        "🕕 Befejezés: {$e->end_time}\n" .
                        "👤 Létrehozó: " . ($e->creatorUser?->name ?? 'ismeretlen') . "\n" .
                        "📝 Leírás: {$e->description}\n";
                });

                return response()->json(['valasz' => $valaszok->implode("\n-------------------------\n")]);
            }
        }
    }

    // Helyszín alapján (szavanként)
    foreach ($szavak as $szo) {
        $helyszinesEsem = Event::with('creatorUser')
            ->whereRaw('LOWER(location) LIKE ?', ["%$szo%"])
            ->get();

        if (!$helyszinesEsem->isEmpty()) {
            $valaszok = $helyszinesEsem->map(function ($e, $i) {
                return
                    "📌 **Esemény #".($i+1)."**\n" .
                    "🎉 Cím: {$e->title}\n" .
                    "📍 Helyszín: {$e->location}\n" .
                    "🕒 Kezdés: {$e->start_time}\n" .
                    "🕕 Befejezés: {$e->end_time}\n" .
                    "👤 Létrehozó: " . ($e->creatorUser?->name ?? 'ismeretlen') . "\n" .
                    "📝 Leírás: {$e->description}\n";
            });

            return response()->json(['valasz' => $valaszok->implode("\n-------------------------\n")]);
        }
    }

    // Esemény cím alapján (szavanként)
    foreach ($szavak as $szo) {
        $event = Event::with('creatorUser')
            ->whereRaw('LOWER(title) LIKE ?', ["%$szo%"])
            ->first();

        if ($event) {
            return response()->json(['valasz' =>
                "🎉 **Esemény**: {$event->title}\n" .
                "📝 Leírás: {$event->description}\n" .
                "📍 Helyszín: {$event->location}\n" .
                "🕒 Kezdés: {$event->start_time}\n" .
                "🕕 Befejezés: {$event->end_time}\n" .
                "👤 Létrehozó: " . ($event->creatorUser?->name ?? 'ismeretlen')
            ]);
        }
    }

    // Ha semmi se talált
    return response()->json(['valasz' => '❌ Nem találtam választ. Próbálkozz másképp.'], 404);
}


}