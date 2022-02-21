<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::post('/forgot_password', [\App\Http\Controllers\AuthController::class, 'forgotPassword']);
Route::post('/verify_otp', [\App\Http\Controllers\AuthController::class, 'verifyOTP']);
Route::post('/reset_password', [\App\Http\Controllers\AuthController::class, 'resetPassword']);
Route::post('/signup', [\App\Http\Controllers\AuthController::class, 'signup']);






Route::group(['middleware' => ['checker']  ], function (){
    Route::get('/get_exam_packages/{user_id}', [\App\Http\Controllers\fetcherController::class, 'getExamPackages']);
    Route::get('/get_exam_subjects/{exam_id}', [\App\Http\Controllers\fetcherController::class, 'getExamSubjects']);
    Route::get('/get_exam_years/{exam_id}', [\App\Http\Controllers\fetcherController::class, 'getExamYears']);
    Route::get('/year_programs/{year_id}', [\App\Http\Controllers\fetcherController::class, 'getProgramsByYear']);
    Route::get('/subject_programs/{subject_id}', [\App\Http\Controllers\fetcherController::class, 'getExamSubjectPprogram']);
    Route::get('/program_question/{program_id}', [\App\Http\Controllers\fetcherController::class, 'getExamQuestions']);
    Route::get('/history/{user_id}', [\App\Http\Controllers\fetcherController::class, 'fetchAllExamHistory']);
    Route::get('/history_info/{test_id}', [\App\Http\Controllers\fetcherController::class, 'historyInfo']);
    Route::post('/submit_answer', [\App\Http\Controllers\fetcherController::class, 'answerProcessor']);
});



