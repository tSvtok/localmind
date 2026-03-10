<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favoris;
use App\Models\Question;
use Illuminate\Http\Request;

class FavorisController extends Controller
{
    /**
     * Get the current user's favoris.
     */
    public function index(Request $request)
    {
        $favoris = Favoris::where('user_id', $request->user()->id)
            ->with('question.user')
            ->get();

        return response()->json($favoris);
    }

    /**
     * Toggle a question as favori.
     */
    public function store(Request $request, int $questionId)
    {
        Question::findOrFail($questionId);

        $existing = Favoris::where('user_id', $request->user()->id)
            ->where('question_id', $questionId)
            ->first();

        if ($existing) {
            $existing->delete();

            return response()->json([
                'message' => 'Favori retiré',
                'favorited' => false,
            ]);
        }

        $favoris = Favoris::create([
            'user_id' => $request->user()->id,
            'question_id' => $questionId,
        ]);

        return response()->json([
            'message' => 'Ajouté aux favoris',
            'favorited' => true,
            'favoris' => $favoris,
        ], 201);
    }

    /**
     * Remove a favori by ID.
     */
    public function destroy(Request $request, int $id)
    {
        $favoris = Favoris::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $favoris->delete();

        return response()->json([
            'message' => 'Favori supprimé',
        ]);
    }
}
