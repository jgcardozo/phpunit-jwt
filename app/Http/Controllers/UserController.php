<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{

    public function enable()
    {
        try {
            $users = User::all()->toArray();
            $randId = array_rand($users, 1);
            $randomUserId = $users[$randId]['id'];
            $usersCollection = collect($users);
            $randChosen = $usersCollection->where('id', $randomUserId)->first();

            if ($randChosen) {
                $user = User::find($randomUserId);
                $user->active = !$user->active;
                $user->save();
                return response()->json(['message' => $user]);
            } else {
                return response()->json(['error' => 'Usuario no encontrado.'], 404);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    } //enable






}//class
