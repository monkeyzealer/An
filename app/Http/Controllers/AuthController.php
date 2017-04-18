<?php

namespace App\Http\Controllers;

use App\User;
use Hash;
use Response;
use JWTAuth;
use Illuminate\Http\Request;
use Auth;

class AuthController extends Controller
{
   public function __construct()
   {
     //this makes it so it excepts sign in and sign up
     $this->middleware("jwt.auth",["except" => ["signIn","signUp"]]);
   }
  //sign in function lets you sign in and be able to see content that only users can see
   public function signIn(Request $request)
   {
     $email = $request->input("email");
     $password = $request->input("password");
     //checks to see if the email and password are in the database and aren't null
     $check = User::where("email","=",$email)->where("password","!=",NULL)->first();
     //if check come up with the email and password matching whats in the database it will sign in
     if(!empty($check)){
       $cred = ["email", "password"];
       $credentials = compact("email","password",$cred);

       $token = JWTAuth::attempt($credentials);

       return Response::json(compact("token"));
     }
      //if check comes up with the email or password not being in the database it will return saying user not found
      else {
        return Response::json(["empty" => "user not found"]);
      }
   }
   //sign up function lets you sign up and if successful will allow you to sign in
   public function signUp(Request $request)
   {
     $email = $request->input("email");
     $password = $request->input("password");
     $username = $request->input("username");
     //checks to see if email and username are in the database
     $check = User::where("email","=", $email)->orWhere("name","=",$username)->first();
     //if check results comes up with that there is no user in the database with the username and password then it will put your username, email, and password in the database
     if(empty($check)){
       $user = new User;
       $user->name = $username;
       $user->email = $email;
       $user->roleID = 2;
       //hash:make() makes the password encrypted in the database
       $user->password = Hash::make($password);
       $user->save();
       //this is a pop up that comes up if your sign up was successful
       return Response::json(["success" => "Successful Sign Up"]);
     }
     //if check results comes up with that there is a existing user with the username and email it will fail and pop will come up saying user already exists
     else {
       return Response::json(["error" => "user already exists"]);
     }
   }
   public function getUser(){
     $user = Auth::user();

     $user = User::find($user->id);

     return Response::json(["user" => $user]);
   }
}
