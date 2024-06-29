<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;

/**
 * All the methods required to CRUD a post resource
 *
 * https://laravel.com/docs/7.x/eloquent
 */

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Post::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'slug' => 'required|string|unique:posts',
            'image' => 'required|string',
            'content' => 'required|string'
        ]);

        $post = Post::create([
            'title' => $request->title,
            'slug' => $request->slug,
            'image' => $request->image,
            'content' => $request->content,
            'published_at' => now()
        ]);

        return $post;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return Post::find($post->id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required|string',
            'slug' => 'required|string',
            'image' => 'required|string',
            'content' => 'required|string'
        ]);

        // Post exists?
        $retrievedPost = Post::where('slug', $request->slug)->first();

        if (!$retrievedPost) {
            return response([
                "message" => "Invalid request",
            ], 401);
        }

        // Update process
        $retrievedPost->title = $request->title;
        $retrievedPost->slug = $request->slug;
        $retrievedPost->image = $request->image;
        $retrievedPost->content = $request->content;
        $retrievedPost->published_at = now();

        $retrievedPost->save();

        return $retrievedPost;
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post = Post::find($post->id);
        return $post->delete();
    }
}
