<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use App\Models\Post;

use Auth, Validator;

class PostController extends Controller
{

    private function generateUniqueUUID()
    {
        $uids = Post::pluck('uuid')->toArray();
        $uid = Str::uuid()->toString();
    
        for ($x = 0; $x < count($uids); $x++) {
            if ($uids[$x] === $uid) {
                $uid = Str::uuid()->toString();
                $x = 0;
            }
        }
    
        return $uid;
    }


    public function create(Request $request){
        try {

            $validator = Validator::make($request->all(), [
                'title' => 'required|string',
                'description' => 'string',
                'content' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                    'status' => false,
                ], 400);
            }

            $uuid = $this->generateUniqueUUID(); 

            $post = new Quizz;
            $post->user_id = Auth::id();
            $post->uuid = $uuid;
            $post->title = $request->title;
            $post->description = $request->description;
            $post->content = $request->content;
            $post->save();           
            
            return response()->json([
                'post' => $post,
                'message' => 'Post created successfully',
                'status' => true,
            ], 201);

        }catch(Exception $e){
            return response()->json([
                'message' => $e->message(),
                'status' => false,
            ], 500);
        }
    }


    public function get(Request $request){
        try {

            $posts = Post::where("user_id", Auth::id())
            ->orderBy('id', 'desc')->paginate(20);    
                        
            return response()->json([
                'posts' => $posts,
                'status' => true,
            ], 200);

        }catch(Exception $e){
            return response()->json([
                'message' => $e->message(),
                'status' => false,
            ], 500);
        }
    }


    public function show($uuid){
        try {

            $post = Post::where("uuid", $uuid)->first();

            if(!$post){
                return response()->json([
                    'message' => "Post could not be found",
                    'status' => false,
                ], 404);
            }
            
            return response()->json([
                'post' => $post,
                'status' => true,
            ], 200);

        }catch(Exception $e){
            return response()->json([
                'message' => $e->message(),
                'status' => false,
            ], 500);
        }
    }


    public function edit(Request $request, $uuid){
        try {

            $validator = Validator::make($request->all(), [
                'title' => 'nullable|string',
                'description' => 'nullable|string',
                'content' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                    'status' => false,
                ], 400);
            }

            $post = Post::where("uuid", $uuid)->first();
            
            if(!$post){
                return response()->json([
                    'message' => "Quizz could not be found",
                    'status' => false,
                ], 404);
            }


            $post->title = $request->title ? 
            $request->title : $post->title;

            $post->description = $request->description ? 
            $request->description : $post->description;

            $post->content = $request->content ? 
            $request->content : $post->content;

            $post->save();  
            
            return response()->json([
                'post' => $post,
                'message' => 'Post updated successfully',
                'status' => true,
            ], 200);
            
        }catch(Exception $e){
            return response()->json([
                'message' => $e->message(),
                'status' => false,
            ], 500);
        }
    }


    public function delete(Request $request, $uuid){
        try {

            $post = Post::where("uuid", $uuid)->first();
            
            if(!$post){
                return response()->json([
                    'message' => "Post could not be found",
                    'status' => false,
                ], 404);
            }

            $post->delete();
            
            return response()->json([
                'message' => 'Post deleted successfully',
                'status' => true,
            ], 200);
            
        }catch(Exception $e){
            return response()->json([
                'message' => $e->message(),
                'status' => false,
            ], 500);
        }
    }

}
