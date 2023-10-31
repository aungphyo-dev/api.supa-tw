<?php

namespace App\Http\Controllers;

use App\Models\Likes;
use App\Http\Requests\StoreLikesRequest;
use App\Http\Requests\UpdateLikesRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikesController extends Controller
{
    public function likeTweet(Request $request)
    {
        $likes = Likes::where("tweet_id", $request->tweet_id)->where("like_user_id", Auth::id())->count();
        if ($likes === 1) {
            return response()->json(['error' => 'You are already like ' . $request->tweet_id]);
        };
        $like = new Likes();
        $like->tweet_id = $request->tweet_id;
        $like->like_user_id = Auth::id();
        $like->save();
        return response()->json(["message" => "You are now like"]);
    }

    public function unLikeTweet(Request $request)
    {
        $likes = Likes::where("tweet_id", $request->tweet_id)->where("like_user_id", Auth::id())->count();
        if ($likes === 0) {
            return response()->json(['error' => 'You are not like' . $request->tweet_id]);
        };
        $like =  Likes::where("tweet_id", $request->tweet_id)->where("like_user_id", Auth::id())->delete();
        return response()->json(["message" => "You are now like"]);
    }
}
