<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Auth;
use App\User;
use App\LikeHateFavorite;

class ActionController extends Controller
{
    public function setAction(Request $request) {
        
        $rules = [
            'actions' => 'required',
            'pokemon_id' => 'required',
        ];
        $validator = Validator::make($request->all(),$rules);

        if($validator->passes()) {
            if(LikeHateFavorite::where('user_id', Auth::user()->id)->where('pokemon_id',$request->pokemon_id)->count()) {
                $toUpdate = LikeHateFavorite::where('user_id', Auth::user()->id)->where('pokemon_id',$request->pokemon_id);
                $toUpdate->update([
                    'like' => $request->actions[0]['active'],
                    'hate' => $request->actions[1]['active'],
                    'favorite' => $request->actions[2]['active'],
                ]);
            } else {
                LikeHateFavorite::create([
                    'user_id' => Auth::user()->id,
                    'pokemon_id' => $request->pokemon_id,
                    'like' => $request->actions[0]['active'],
                    'hate' => $request->actions[1]['active'],
                    'favorite' => $request->actions[2]['active'],
                ]);
            }
            return response()->json(['success' => true, 'message' => 'Request successfully processed'], 200);
        }
        else {
            return response()->json(['success' => false, 'errors' =>$validator->errors(), 'message' => 'Something went wrong on your request'],422);
        }

    }

    public function getPokemonActionStatus(Request $request) {
        return LikeHateFavorite::where('user_id', Auth::user()->id)->where('pokemon_id', $request->pokemon_id)->first();
    }

    public function getLikes() {
        return LikeHateFavorite::where('user_id', Auth::user()->id)->where('like', 1)->get();
    }

    public function getHates() {
        return LikeHateFavorite::where('user_id', Auth::user()->id)->where('hate', 1)->get();
    }

    public function getFavorites() {
        return LikeHateFavorite::where('user_id', Auth::user()->id)->where('favorite', 1)->get();
    }

    public function getLikesByUserId($id) {

    }

    public function getHatesByUserId($id) {

    }
}
