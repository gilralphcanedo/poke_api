<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Auth;
use App\User;

class UserController extends Controller
{
    public function getAllUsers() {
        return User::with('likeHateFavorites')->get();
    }
    public function getAllOtherUsers() {
        return User::where('id','!=',Auth::user()->id)->with('likeHateFavorites')->get();
    }
    public function getUserProfile() {
        return User::find(Auth::user()->id)->with('likes','hates','favorites')->first();
    }

    public function setUserProfile(Request $request) {
        $rules = [
            'name' => 'required',
            'birthdate' => 'required',
        ];

        $validator = Validator::make($request->all(),$rules);

        if($validator->passes()) {
            User::find(Auth::user()->id)->update([
                'name' => $request->name,
                'birthdate' => $request->birthdate,
            ]);
            return response()->json(['success' => true, 'message' => 'Request successfully processed'], 200);
        }
        else {
            return response()->json(['success' => false, 'errors' => $validator->errors(), 'message' => 'Something went wrong on your request'],422);
        }
    }
}
