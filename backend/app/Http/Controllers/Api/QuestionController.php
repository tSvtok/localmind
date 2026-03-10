<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * List all questions (with optional search).
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Question::with(['user', 'reponses.user']);

        if ($search) {
            $query->where('titre', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('city', 'like', "%{$search}%");
        }

        $questions = $query->latest()->get();

        return response()->json($questions);
    }

    /**
     * Create a new question.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'city' => 'required|string|max:255',
        ]);

        $question = Question::create([
            'titre' => $request->titre,
            'description' => $request->description,
            'city' => $request->city,
            'user_id' => $request->user()->id,
        ]);

        $question->load(['user', 'reponses.user']);

        return response()->json([
            'message' => 'Question créée avec succès',
            'question' => $question,
        ], 201);
    }

    /**
     * Show a single question.
     */
    public function show(int $id)
    {
        $question = Question::with(['user', 'reponses.user'])->findOrFail($id);

        return response()->json($question);
    }

    /**
     * Update a question (only the owner can update).
     */
    public function update(Request $request, int $id)
    {
        $question = Question::findOrFail($id);

        if ($question->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $request->validate([
            'titre' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'city' => 'sometimes|required|string|max:255',
        ]);

        $question->update($request->only(['titre', 'description', 'city']));
        $question->load(['user', 'reponses.user']);

        return response()->json([
            'message' => 'Question modifiée avec succès',
            'question' => $question,
        ]);
    }

    /**
     * Delete a question (only the owner can delete).
     */
    public function destroy(Request $request, int $id)
    {
        $question = Question::findOrFail($id);

        if ($question->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $question->delete();

        return response()->json([
            'message' => 'Question supprimée avec succès',
        ]);
    }
}
