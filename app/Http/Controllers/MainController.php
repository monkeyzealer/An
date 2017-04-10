<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use App\Article;


class MainController extends Controller
{
    public function storeArticle(Request $request)
    {
      $article = new Article;
      $article->subject = $request->input("subject");
      $article->body = $request->input("body");
      $image = $request->file("image");
      $imageName = $image->getClientOriginalName();
      $image->move("storage/".$imageName);
      $article->image = "public/storage/".$imageName;
      $article->save();

      return Response::json(["success" => "you did"]);
    }
}
