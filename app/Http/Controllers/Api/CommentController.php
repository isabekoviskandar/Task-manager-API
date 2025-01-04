<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Http\Resources\CommentsResource;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'task_id' => 'required|unique:comments,task_id',
            'text' => 'required|max:300',
        ]);

        $comment = Comment::create($request->all());

        return new CommentResource($comment);
    }
}
