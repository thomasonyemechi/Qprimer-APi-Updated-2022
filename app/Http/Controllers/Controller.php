<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function win_hash($length)
    {
        return substr(str_shuffle(str_repeat('123456789', $length)), 0, $length);
    }




    function processAnswer($arr, $testId)
    {
        $a = 0 ; $newarr = [];
        foreach($arr as $an)
        {
            $op = $an['op'] ?? '';
            $ans = ($op == $an['ca']) ? 1 : 0 ;
            $a += ($op == $an['ca']) ? 1 : 0 ;
            $newarr[] = [
                'question_id' => $an['qsn'],
                'test_id' => $testId,
                'qn' => $an['q'],
                'option' => $op,
                'score' => $ans,
            ];
        }
        return json_encode(['answers' => $newarr, 'total' => $a]);
    }



    function pickQuestion($program)
    {
        $arr = [];
        $questions = Question::where('program_id', $program->id)->inRandomOrder()->get();
        foreach($questions as $question){
            $arr[] = [
                'id' => $question->id,
                'topic' => $question->topic->topic,
                'qn' => $question->qn,
                'question' => $question->question,
                'a' => $question->a,
                'b' => $question->b,
                'c' => $question->c,
                'd' => $question->d,
                'ca' => $question->ca,
                'option' => '',
            ];
        }
        return $arr;
    }



}
