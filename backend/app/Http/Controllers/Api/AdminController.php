<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Reponse;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Get admin dashboard statistics.
     */
    public function dashboard(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Accès interdit'], 403);
        }

        $questions = Question::with('user')->latest()->get();
        $reponses = Reponse::with(['user', 'question'])->latest()->get();
        $users = User::where('role', 'user')->get();

        return response()->json([
            'stats' => [
                'total_questions' => $questions->count(),
                'total_reponses' => $reponses->count(),
                'total_users' => $users->count(),
            ],
            'questions' => $questions,
            'reponses' => $reponses,
            'users' => $users,
        ]);
    }
}
