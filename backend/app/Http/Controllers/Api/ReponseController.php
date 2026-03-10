<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Reponse;
use Illuminate\Http\Request;

class ReponseController extends Controller
{
    /**
     * Store a new reponse for a question.
     */
    public function store(Request $request, int $questionId)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        Question::findOrFail($questionId);

        $reponse = Reponse::create([
            'content' => $request->content,
            'user_id' => $request->user()->id,
            'question_id' => $questionId,
        ]);

        $reponse->load('user');

        return response()->json([
            'message' => 'Réponse ajoutée avec succès',
            'reponse' => $reponse,
        ], 201);
    }
}
