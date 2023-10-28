<?php

namespace App\Http\Controllers;
use App\Models\Following;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowingController extends Controller
{

    public function follow(Request $request)
    {
        $follow = new Following();
        $followings = Following::where("follower_id",Auth::id())->get();
        if ($followings->contains("following_id",$request->following_id)) {
            return response()->json(['error'=>'You are already following ' . $request->following_id]);
        }
        $follow->following_id = $request->following_id;
        $follow->follower_id = Auth::id();
        $follow->save();
        return response()->json(['message' => 'You are now following ' . $request->following_id]);
    }

    public function unfollow(Request $request)
    {
        $followings = Following::where("follower_id",Auth::id())->get();
        // Check if the current user is already following the target user
        if (!$followings->contains("following_id",$request->unfollow_id)) {
            return response()->json(['error' => 'You are not following']);
        }

        Following::where("following_id",$request->unfollow_id)->delete();
        return response()->json(['message' => 'You are now unfollow']);
    }
}
