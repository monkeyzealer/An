<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use App\Article;
use Illuminate\Support\Facades\Validator;
use Purifier;
use JWTAuth;
use Auth;
use File;

class MainController extends Controller
{
  public function __construct(){
    $this->middleware("jwt.auth", ["only" => ["storeArticle", "destroyArticle"]]);
  }
  public function home()
  {
    return File::get("index.html");
  }
  public function index()
  {
    $articles = Article::orderBy("id", "desc")->take(4)->get();
    foreach($articles as $key => $article)
    {
      if (strlen($article->body) > 340) {
        $article->body = substr($article->body, 0, 340) . "...";
      }
    }
    return Response::json($articles);
  }
    public function storeArticle(Request $request)
    {
      $rules = [
        "subject" => "required",
        "body" => "required",
        "image" => "required"
      ];

       $Validator = Validator::make(Purifier::clean($request->all()),$rules);//passes data

       if($Validator->fails())
       {
         return Response::json(["error" => "You need to fill out all fields"]);
       }

      $user = Auth::user();
      if($user->roleID != 1)
      {
        return Response::json(["error" => "Your not authorized to do this"]);
      }

      $article = new Article;
      $article->subject = $request->input("subject");
      $article->body = $request->input("body");
      $image = $request->file("image");
      $imageName = $image->getClientOriginalName();
      $image->move("storage/",$imageName);
      $article->image = $request->root()."/storage/".$imageName;
      $article->save();

      return Response::json(["success" => "you did it"]);
    }
    public function updateArticle($id, Request $request)
    {
      $rules = [
        "subject" => "required",
        "body" => "required",
      ];

       $Validator = Validator::make(Purifier::clean($request->all()),$rules);//passes data

       if($Validator->fails())
       {
         return Response::json(["error" => "You need to fill out all fields"]);
       }

       $user = Auth::user();
       if($user->roleID != 1)
       {
         return Response::json(["error" => "Your not authorized to do this"]);
       }

      $article = Article::find($id);
      $article->subject = $request->input("subject");
      $article->body = $request->input("body");

      $image = $request->file("image");
      if(!empty($image))
      {
        $imageName = $image->getClientOriginalName();
        $image->move("storage/",$imageName);
        $article->image = $request->root()."/storage/".$imageName;
      }
      $article->save();

      return Response::json(["success" => "Post Updated"]);
    }
    public function showArticle($id)
    {
      $article = Article::find($id);
      return Response::json($article);
    }
    public function destroyArticle($id) {
      $user = Auth::user();
      if($user->roleID != 1)
      {
        return Response::json(["error" => "Your not authorized to do this"]);
      }
      $article = Article::find($id);
      $article->delete();
      return Response::json(["success" => "Deleted Article"]);
  }
}
