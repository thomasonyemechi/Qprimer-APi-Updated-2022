<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Subject;
use App\Models\Subscription;
use App\Models\Question;
use App\Models\Test;
use App\Models\Type;
use App\Models\User;
use App\Models\Year;
use Illuminate\Http\Request;


class fetcherController extends Controller
{
    function index()
    {

    }


    function historyInfo($test_id) {
        $questions = Test::where(['id' => $test_id])->first('answers'); $quest_array = [];
        foreach(json_decode($questions->answers) as $ques) {
            $question = Question::with(['topic:id,topic'])->find($ques->question_id);
            $question['option'] = $ques->option;
            $question['score'] = $ques->score;
            $arr[] = $question;
        }
        return response($arr);
    }


    function fetchAllExamHistory($user_id)
    {
        $test = Test::where(['user_id' => $user_id])->orderBy('id', 'desc')->paginate(20,['id', 'user_id', 'program_id', 'questions', 'correct', 'start', 'end']);
        return response($test);
    }


    /*
        $user is the users id
        used the used id to search through the subscription table and fetch the quetion which the student has bought
        then i retruned in json format
    */
    function getExamPackages($user)
    {
        $subs = Subscription::where('buyer_id', $user)->get(); $exams = [];
        foreach($subs as $sub){
            $exams[] = $sub->exam;
        }
        return response($exams);
    }


    /*
        take the id of am exam package
        retruns the subjects
    */
    function getExamSubjects($exam_id){
        $subs = Subject::where('type_id', $exam_id)->get(); $data = [];
        foreach($subs as $sub){
            $data [] = [
                'id' => $sub->id,
                'subject' => $sub->subject,
                'code' => $sub->code,
                'topics' => $sub->topics->count(),
                'exams' => $sub->programs->count(),
            ];
        }
        return response( $data );
    }



    /*
        take the id of am exam package
        retruns the years
    */

    function getExamYears($exam_id) {
        $years = Year::where('type_id', $exam_id)->get(['id', 'year']); $subjects = [];
        foreach($years as $year){
            $count = Program::where('year', $year->id)->count();
            if($count > 0) {
                $year['programs'] = $count;
                $subjects[] =  $year;
            }
        }
        return response($subjects);
    }


    function getProgramsByYear($year_id) {
        $programs = Program::where('year', $year_id)->get(); $data = [];
        foreach($programs as $pro){
            $data [] = [
                'id' => $pro->id,
                'subject' => $pro->sub->subject,
                'year' => $pro->yer->year,
                'questions' => $pro->questions->count(),
            ];
        }
        return response($data);
    }




    /*
        take the id of am exam package
        retruns the Programs
    */
    function getExamPrograms($exam_id){
        $programs = Program::where('type', $exam_id)->get(); $data = [];
        foreach($programs as $pro){
            $data [] = [
                'id' => $pro->id,
                'subject' => $pro->sub->subject,
                'year' => $pro->yer->year,
                'questions' => $pro->questions->count(),
            ];
        }
        return response( $data );
    }


    /*
        take the the id of a Subject
        retruns the Programs
    */
    function getExamSubjectPprogram($subject_id){
        $programs = Program::where('subject', $subject_id)->get(); $data = [];
        foreach($programs as $pro){
            $data [] = [
                'id' => $pro->id,
                'subject' => $pro->sub->subject,
                'year' => $pro->yer->year,
                'questions' => $pro->questions->count(),
            ];
        }
        return response( $data );
    }



    /*
        take the the id of a Programs
        retruns the Questions
    */

    function getExamQuestions($program_id)
    {
        $program = Program::where('id', $program_id)->first();
        $data = [
            'info' => [
                'id' => $program->id,
                'subject' => $program->sub->subject,
                'year' => $program->yer->year,
                'type' => $program->typ->type,
                'student' => '',
                'start' => '',
                'end' => '',
                'total_question' => '',
                'total_correct' => ''
            ],
            'questions' => $this->pickQuestion($program)
        ];
        return response($data);
    }


    function answerProcessor(Request $request)
    {

        $info = $request->info; $questions = $request->questions;
        //create a summary of the test
        $test = Test::create([
            'user_id' => $info['student'],
            'program_id' => $info['id'],
            'questions' => $info['total_question'],
            'correct' => $info['total_correct'] ?? 0,
            'start' => $info['start'],
            'end' => $info['end'] ?? time(),
            'answered' => $info['total_question'],
        ]);
        $testId = $test->id;
        //please Process the answer for each question
        $to = $this->processAnswer($questions, $testId);
        $to = json_decode($to);
        Test::where('id', $testId)->update([ "correct" => $to->total,  "answers" => json_encode($to->answers) ]);
        return response([
            'message' => 'Exam has been completed',
            'success' => true,
        ]);
    }
}
