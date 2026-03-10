<?php

    namespace App\Http\Repository;

    use App\Models\Favoris;
use App\Models\Question;

class QuestionRepository
{
    public function createQuestion($titre, $description, $user_id,$city)
    {
        $question = Question::create([
            'titre' => $titre,
            'description' => $description,
            'user_id' => $user_id,
            'city' => $city
        ]);

        $question->save();
        return $question;
    }

    public function deletequestion($question_id)
    {
        $question = Question::find($question_id);
       if($question) {
           $question->delete();
       }
    }

    public function modifier($titre, $description, $city, $question_id)
    {
        $update = Question::where('id', $question_id)->update([
            'titre'       => $titre,
            'description' => $description,
            'city'        => $city,
        ]);
        return $update;
    }
}

?>
