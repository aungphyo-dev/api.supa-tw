<?php

namespace App\Http\Controllers;
use App\Models\Following;
use App\Models\Tweet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResource;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            "name" => "required|max:50",
            "email" => "required|email|unique:users,email",
            "password" => "required|min:8|max:20|confirmed"
        ]);
        User::create([
            'name' => $request->name,
            'username' => $request->name . rand(1,1000000),
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
        ]);
    }

    public function login (Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        $attemptUser = Auth::attempt($credentials);
        if (!$attemptUser) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user();
        return response()->json([
            'status' => 'success',
            'user' => $user,
            'authorization' => [
                "type" => "Bearer",
                'token' => $user->createToken('auth_token')->plainTextToken,
            ]
        ]);
    }

    public function logout()
    {
        $user = Auth::user();
        if ($user) {
            Auth::user()->currentAccessToken()->delete();
            return response()->json(['message' => 'Logged out successfully']);
        }
        return response()->json(['message' => 'User not found'], 404);
    }
    public function profile()
    {
        $user = User::find(Auth::id());
        return response()->json([
            'status' => 'success',
            'message' => 'User detail!',
            'user' => new UserResource($user),
        ]);
    }

    public function update (Request $request)
    {
        $user = User::find(Auth::id());
        $request->validate([
            "name" => "max:50",
            "bio" => "max:150",
            "location" => "max:100",
        ]);
        return $request;
        if (!$request->name && !$request->bio && !$request->location && !$request->website && !$request->profile_avatar && !$request->cover_avatar){
            return response()->json([
                "error" => "Your are not update!"
            ]);
        }

        if ($request->hasFile("profile_avatar")) {
            $photo = $request->file("profile_avatar");
            $extension = $photo->extension();
            $photoName = "user_photo" . time() . rand(0,100000) . "." . $extension;
            $photo->storeAs("users", $photoName, "public");
            $user->profile_avatar = $photoName;
        }
        if ($request->hasFile("cover_avatar")) {
            $photo = $request->file("cover_avatar");
            $extension = $photo->extension();
            $photoName = "user_photo" . time() . rand(0,100000) . "." . $extension;
            $photo->storeAs("users", $photoName, "public");
            $user->cover_avatar = $photoName;
        }

        if ($request->name){
            $user->name = $request->name;
        }
        if ($request->bio){
            $user->bio = $request->bio;
        }
        if ($request->website){
            $user->website = $request->website;
        }
        if ($request->location){
            $user->location = $request->location;
        }
        $user->update();
        return  response()->json([
            "success" => "User successfully updated"
        ]);
    }

    public function index ()
    {
        $users = User::with("followers")->latest("id")->get();
        return response()->json(compact("users"));
    }

    public function followingTweet()
    {
        $followingIds = Following::where("follower_id",Auth::id())->pluck("following_id");
        $tweets = Tweet::with("author")->whereIn("user_id",$followingIds)->paginate(20);
        return response()->json(compact("tweets"));
    }
    public function followingByAuth()
    {
        $followings = Following::with("followingUser","followerUser")->where("follower_id",Auth::id())->get();
        return response()->json(compact("followings"));
    }
    public function followersByAuth()
    {
        $followers = Following::with("followingUser","followerUser")->where("following_id",Auth::id())->get();
        return response()->json(compact("followers"));
    }
     public function UserById(string $id)
    {
        $user = User::find($id);
        if ( is_null($user)){
            return response()->json([
                "error" => "User not found",
            ]);
        };

        return response()->json([
            'status' => 'success',
            'message' => 'User detail!',
            'user' => new UserResource($user)
        ]);
    }
}
