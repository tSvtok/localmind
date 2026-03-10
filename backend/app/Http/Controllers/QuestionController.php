<?php

namespace App\Http\Controllers;

use App\Http\Services\QuestionService;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    private $questionService;

    public function __construct(QuestionService $questionService)
    {
        $this->questionService = $questionService;
       
    }

    // Create a question
    public function Question(Request $request)
    {

        $titre = $request->input('titre');
        $description = $request->input('description');
        $city = $request->input('city');
        $user_id = Auth::id();

        if ($this->questionService->createQuestion($titre, $description, $user_id, $city)) {
            return redirect()->route('affichage');
        }
    }

    // Delete a question
    public function deletequestion(Request $request)
    {
        $question_id = $request->input('questionid');

        if ($this->questionService->deletequestion($question_id)) {
            return redirect()->route('affichage');
        }
    }

    // Update a question
    public function update(Request $request)
    {
        $titre = $request->input('titre');
        $description = $request->input('description');
        $city = $request->input('city');
        $question_id = $request->input('question_id');

        if ($this->questionService->modifier($titre, $description, $city, $question_id)) {
            return redirect()->route('affichage');
        }
    }

    // Display questions
    public function index(Request $request)
    {
        $search = $request->input('search');

        if ($search) {
            $questions = Question::where('titre', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->get();
        } else {
            $questions = Question::all();
        }

        return view('affichage', compact('questions'));
    }
}
