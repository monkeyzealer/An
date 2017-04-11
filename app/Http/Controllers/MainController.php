<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use App\Article;


class MainController extends Controller
{
  public function index()
  {
    $articles = Article::orderBy("id", "desc")->take(4)->get();

    return Response::json($articles);
  }
    public function storeArticle(Request $request)
    {
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
}
