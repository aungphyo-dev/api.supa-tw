<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use App\Http\Requests\StoreTweetRequest;
use App\Http\Requests\UpdateTweetRequest;
use Illuminate\Support\Facades\Storage;

class TweetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tweets = Tweet::with("author")->latest("id")->paginate(20);
        return response()->json(compact("tweets"));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTweetRequest $request)
    {
        $tweet = new Tweet();
        if ($request->hasFile("image")) {
            $photo = $request->file("image");
            $extension = $photo->extension();
            $photoName = "tweet_photo" . time() . rand(0,100000) . "." . $extension;
            $photo->storeAs("tweets", $photoName, "public");
            $tweet->image = $photoName;
        }
        $tweet->user_id = $request->user_id;
        $tweet->context = $request->context;
        $tweet->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Tweet is successfully created',
            'data' => $tweet
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tweet = Tweet::find($id);
        if (!$tweet) {
            return response()->json([
                'status' => 'Not Found',
                'message' => "Tweet not found!",
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Tweet is successfully retrieved',
            "data" => $tweet,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTweetRequest $request,string $id)
    {
        $tweet = Tweet::find($id);
        if (!$tweet) {
            return response()->json([
                'status' => 'Not Found',
                'message' => "Tweet not found!",
            ], 404);
        }

        if ($request->hasFile("image")) {
            $photoPath = "public/tweets/" . $tweet->image;
            if (Storage::exists($photoPath)) {
                Storage::delete($photoPath);
            }
            $photo = $request->file("image");
            $extension = $photo->extension();
            $photoName = "tweet_photo" . time() . rand(0,100000) . "." . $extension;
            $photo->storeAs("tweets", $photoName, "public");
            $tweet->image = $photoName;
        }
        $tweet->context = $request->context;
        $tweet->update();
        return response()->json([
            'status' => 'success',
            'message' => 'Tweet is successfully updated',
            'data' => $tweet
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tweet = Tweet::find($id);
        if (!$tweet) {
            return response()->json([
                'status' => 'Not Found',
                'message' => "Tweet not found!",
            ], 404);
        }

        $photoPath = "public/tweets/" . $tweet->image;
        if (Storage::exists($photoPath)) {
            Storage::delete($photoPath);
        }
        $tweet->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Tweet is successfully deleted',
        ], 204);
    }
}
