<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Purifier;
use JWTAuth;
use Auth;
use App\Comment;
use App\Article;
use Response;


class CommentsController extends Controller
{
  public function __construct(){
    $this->middleware("jwt.auth", ["only" => ["store"]]);
  }
    public function index($id)
    {
      $comments = Comment::where("comments.articleID", "=", $id)
        ->join("users", "comments.userID", "=", "users.id")
        ->select("comments.id", "comments.body", "comments.created_at", "users.name")
        ->orderBy("id", "desc")
        ->get();

        foreach($comments as $key => $comment)
        {
          $comment->commentDate = Carbon::createFromTimeStamp(strtotime($comment->created_at))->diffForHumans();
        }

        return Response::json($comments);
    }
    public function store(Request $request)
    {
      $rules = [
        "commentBody" => "required",
        "articleID" => "required",
      ];

      $Validator = Validator::make(Purifier::clean($request->all()),$rules);

      if($Validator->fails())
      {
        return Response::json(["error" => "you need to fill out all fields"]);
      }
      $user = Auth::user();
      $check = Article::find($request->input("articleID"));
      if(empty($check))
      {
        return Response::json(["error" => "Article Not Found!"]);
      }
      $comment = new Comment;

      $comment->userID = $user->id;
      $comment->articleID = $request->input("articleID");
      $comment->body = $request->input("commentBody");
      $comment->save();

      return Response::json(["Success" => "You did it!"]);
    }
}
