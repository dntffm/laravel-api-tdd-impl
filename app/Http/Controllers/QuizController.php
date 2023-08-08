<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QuizController extends Controller
{
    /**
     * Save user quiz answer.
     *
     * @return Response
     */
    public function saveAnswer(Request $request)
    {
        $user = auth()->user();

        $answer = $request->answer;

        //CHECK COURSE QUIZ PICKED BY USER
        #code

        $user->answers()->sync($answer);

        return $this->sendResponse("Answer saved!");

    }
}
