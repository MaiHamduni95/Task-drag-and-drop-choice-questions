<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\QuizzesQuestion;
use App\Models\QuizzesQuestionsAnswer;
use App\Models\Translation\QuizzesQuestionsAnswerTranslation;
use App\Models\Translation\QuizzesQuestionTranslation;
use Illuminate\Http\Request;
use App\Models\Quiz;
use Illuminate\Support\Facades\Validator;

class QuizQuestionController extends Controller
{
    public function store(Request $request)
    {
        try {
            $data = $request->get('ajax');
    
            $rules = [
                'quiz_id' => 'required|exists:quizzes,id',
                'type' => 'required',
                'locale' => 'required',
                'grade' => 'required|integer', 
                'image' => 'nullable|max:255',
                'video' => 'nullable|max:255',
            ];
    
            if ($data['type'] == 'dragAndDrop') {
                $rules['questions'] = 'required|array';
                $rules['questions.*.title'] = 'required';
                $rules['questions.*.answer.ans_temp.title'] = 'required';
            } else {
                $rules['questions.*.title'] = 'required';
                $rules['questions.*.answer.ans_temp.title'] = 'required';
            }
    
            $validate = Validator::make($data, $rules);
    
            if ($validate->fails()) {
                return response()->json([
                    'code' => 422,
                    'errors' => $validate->errors()
                ], 422);
            }
    
            $user = auth()->user();
            $quiz = Quiz::findOrFail($data['quiz_id']);
         
            if ($data['type'] == 'dragAndDrop') {
                $question = QuizzesQuestion::create([
                    'quiz_id' => $data['quiz_id'],
                    'creator_id' => $user->id,
                    'grade' => $data['grade'],
                    'type' => $data['type'],
                    'locale' => $data['locale'],
                    'created_at' => time()
                ]);
                foreach ($data['questions'] as $questionData) {
                    QuizzesQuestionTranslation::create([
                        'quizzes_question_id' => $question->id,
                        'locale' => mb_strtolower($data['locale']),
                        'title' => $questionData['title'],
                        'correct' => $questionData['answer']['ans_temp']['title'],
                    ]);
                    if (!empty($questionData['answer']['ans_temp']['title'])) {
                        $questionAnswer = QuizzesQuestionsAnswer::create([
                            'question_id' => $question->id,
                            'creator_id' => $user->id,
                            'title' => $questionData['answer']['ans_temp']['title'],
                            'created_at' => time()
                        ]);
                
                        if (!empty($questionAnswer)) {
                            QuizzesQuestionsAnswerTranslation::updateOrCreate([
                                'quizzes_questions_answer_id' => $questionAnswer->id,
                                'locale' => mb_strtolower($data['locale']),
                            ], [
                                'title' => $questionData['answer']['ans_temp']['title'],
                            ]);
                        }
                    }
                }
            } else {
                $title = $data['title'];
                $answer = $data['answers']['ans_temp']['title'];
    
                $question = QuizzesQuestion::create([
                    'quiz_id' => $data['quiz_id'],
                    'creator_id' => $user->id,
                    'grade' => $data['grade'],
                    'type' => $data['type'],
                    'locale' => $data['locale'],
                    'created_at' => time(),
                ]);
    
                QuizzesQuestionTranslation::create([
                    'quizzes_question_id' => $question->id,
                    'locale' => mb_strtolower($data['locale']),
                    'title' => $title,
                    'answer' => $answer,
                ]);
                
                if ($data['type'] == QuizzesQuestion::$multiple and !empty($data['answers'])) {
                    $answers = $data['answers'];

                    $hasCorrect = false;
                    foreach ($answers as $answer) {
                        if (isset($answer['correct'])) {
                            $hasCorrect = true;
                        }
                    }

                    if (!$hasCorrect) {
                        return response([
                            'code' => 422,
                            'errors' => [
                                'current_answer' => [trans('quiz.current_answer_required')]
                            ],
                        ], 422);
                    }
                }
            }
         
            if ($question->type == QuizzesQuestion::$multiple and !empty($data['answers']['ans_temp']['title'])) {
                foreach ($answers as $answer) {
                    if (!empty($answer['title']) or !empty($answer['file'])) {
                        $questionAnswer = QuizzesQuestionsAnswer::create([
                            'question_id' => $question->id,
                            'creator_id' => $user->id,
                            'image' => $answer['file'] ?? null,
                            'correct' => isset($answer['correct']) ? true : false,
                            'created_at' => time()
                        ]);

                        if (!empty($questionAnswer)) {
                            QuizzesQuestionsAnswerTranslation::updateOrCreate([
                                'quizzes_questions_answer_id' => $questionAnswer->id,
                                'locale' => mb_strtolower($data['locale']),
                            ], [
                                'title' => $answer['title'],
                            ]);
                        }
                    }
                }
            }
            
        
        
            return response()->json([
                'code' => 200
            ], 200);
        } catch (\Exception $e) {
            // Log the exception or handle it appropriately
            return response()->json([
                'code' => 500,
                'error' => 'Internal Server Error: ' . $e->getMessage()
            ], 500);
        }
    }
    public function edit(Request $request, $question_id)
    {
        try {
            $user = auth()->user();
            $question = QuizzesQuestion::where('id', $question_id)
                ->where('creator_id', $user->id)
                ->first();
          
                
            if (!empty($question)) {
                $quiz = Quiz::find($question->quiz_id);
                if (!empty($quiz)) {
                    $locale = $request->get('locale', app()->getLocale());
    
                    // Fetch titles and answers based on question type
                    $titles = [];
                    $answers = [];
                    if ($question->type == 'dragAndDrop') {
                           // Fetch titles of drag-and-drop questions from translations
                           $titles = QuizzesQuestionTranslation::where('quizzes_question_id', $question_id)
                           ->pluck('title')
                           ->toArray();
                       
                       // Fetch answers of drag-and-drop questions from translations
                       $answers = QuizzesQuestionsAnswerTranslation::whereIn('quizzes_questions_answer_id', function($query) use ($question_id) {
                           $query->select('id')
                               ->from('quizzes_questions_answers')
                               ->where('question_id', $question_id);
                       })
                       ->pluck('title')
                       ->toArray();
    
                    }
                    $data = [
                        'pageTitle' => $question->title,
                        'quiz' => $quiz,
                        'question_edit' => $question,
                        'userLanguages' => getUserLanguagesLists(),
                        'locale' => mb_strtolower($locale),
                        'defaultLocale' => getDefaultLocale(),
                        'titles' => $titles,
                        'answers' => $answers,
                    ];
                    
                  
    
                    if ($question->type == 'multiple') {
                     
                        $html = (string)\View::make(getTemplate() . '.panel.quizzes.modals.multiple_question', $data);
                    } elseif ($question->type == 'dragAndDrop') {
                        $html = (string)\View::make(getTemplate() . '.panel.quizzes.modals.dragAndDrop_question', $data);
                    } else {
                        $html = (string)\View::make(getTemplate() . '.panel.quizzes.modals.descriptive_question', $data);
                    }
    
                    return response()->json([
                        'html' => $html
                    ], 200);
                }
            }
    
            return response()->json([], 422);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'error' => 'Internal Server Error: ' . $e->getMessage()
            ], 500);
        }
    }
    public function getQuestionByLocale(Request $request, $id)
    {
        $user = auth()->user();

        $question = QuizzesQuestion::where('id', $id)
            ->where('creator_id', $user->id)
            ->with('quizzesQuestionsAnswers')
            ->first();

        if (!empty($question)) {
            $locale = $request->get('locale', app()->getLocale());

            foreach ($question->translatedAttributes as $attribute) {
                try {
                    $question->$attribute = $question->translate(mb_strtolower($locale))->$attribute;
                } catch (\Exception $e) {
                    $question->$attribute = null;
                }
            }

            if (!empty($question->quizzesQuestionsAnswers) and count($question->quizzesQuestionsAnswers)) {
                foreach ($question->quizzesQuestionsAnswers as $answer) {
                    foreach ($answer->translatedAttributes as $att) {
                        try {
                            $answer->$att = $answer->translate(mb_strtolower($locale))->$att;
                        } catch (\Exception $e) {
                            $answer->$att = null;
                        }
                    }
                }
            }

            return response()->json([
                'question' => $question
            ], 200);
        }

        return response()->json([], 422);
    }
    public function update(Request $request, $id)
    {
        try {
            $data = $request->get('ajax');
    
            $rules = [
                'quiz_id' => 'required|exists:quizzes,id',
                'grade' => 'required',
                'type' => 'required',
                'image' => 'nullable|max:255',
                'video' => 'nullable|max:255',
            ];
    
            if ($data['type'] == 'dragAndDrop') {
                $rules['questions'] = 'required|array';
                $rules['questions.*.title'] = 'required';
                $rules['questions.*.answer.ans_temp.title'] = 'required';
            } else {
                $rules['questions.*.title'] = 'required'; // Update rule to match the structure
                $rules['questions.*.answer.ans_temp.title'] = 'required'; // Update rule to match the structure
            }
    
            $validate = Validator::make($data, $rules);
    
            if ($validate->fails()) {
                return response()->json([
                    'code' => 422,
                    'errors' => $validate->errors()
                ], 422);
            }
    
            if (!empty($data['image']) && !empty($data['video'])) {
                return response()->json([
                    'code' => 422,
                    'errors' => [
                        'image' => [trans('update.quiz_question_image_validation_by_video')],
                        'video' => [trans('update.quiz_question_image_validation_by_video')],
                    ]
                ], 422);
            }
    
            if ($data['type'] == QuizzesQuestion::$multiple && !empty($data['answers'])) {
                $answers = $data['answers'];
    
                $hasCorrect = false;
                foreach ($answers as $answer) {
                    if (isset($answer['correct'])) {
                        $hasCorrect = true;
                    }
                }
    
                if (!$hasCorrect) {
                    return response([
                        'code' => 422,
                        'errors' => [
                            'current_answer' => [trans('quiz.current_answer_required')]
                        ],
                    ], 422);
                }
            }
    
            $user = auth()->user();
    
            $quiz = Quiz::where('id', $data['quiz_id'])
                ->where('creator_id', $user->id)
                ->first();
    
            if (!empty($quiz)) {
                $quizQuestion = QuizzesQuestion::where('id', $id)
                    ->where('creator_id', $user->id)
                    ->where('quiz_id', $quiz->id)
                    ->first();
    
                if (!empty($quizQuestion)) {
                    $quiz_total_grade = $quiz->total_mark - $quizQuestion->grade;
    
                    $quizQuestion->update([
                        'quiz_id' => $data['quiz_id'],
                        'creator_id' => $user->id,
                        'grade' => $data['grade'],
                        'type' => $data['type'],
                        'image' => $data['image'] ?? null,
                        'video' => $data['video'] ?? null,
                        'updated_at' => time()
                    ]);
    
                    if ($data['type'] == 'dragAndDrop' && !empty($data['questions'])) {
                        foreach ($data['questions'] as $questionData) {
                            // Update or create translation for the question
                       
                            $questionTranslation = QuizzesQuestionTranslation::updateOrCreate(
                                [
                                    'quizzes_question_id' => $quizQuestion->id,
                                    'locale' => mb_strtolower($data['locale']),
                                    'title' => $questionData['answer']['ans_temp']['title'],
                                    
                                ],
                                [
                                    'title' => $questionData['title'],
                                ]
                            );
                          
                            // Update or create translation for the answer
                            if (!empty($questionData['answer']['ans_temp']['title'])) {
                                $questionAnswerTranslation = QuizzesQuestionTranslation::updateOrCreate(
                                    [
                                        'quizzes_question_id' => $quizQuestion->id,
                                        'locale' => mb_strtolower($data['locale']),
                                       
                                    ],
                                    [
                                        'title' => $questionData['answer']['ans_temp']['title'],
                                    ]
                                );
                            }
                        }
                    } else {
                        // Update the translation for non-drag-and-drop type questions here...
                        QuizzesQuestionTranslation::updateOrCreate(
                            [
                                'quizzes_question_id' => $quizQuestion->id,
                                'locale' => mb_strtolower($data['locale']),
                            ],
                            [
                                'title' => $data['title'],
                                'correct' => $data['correct'] ?? null,
                            ]
                        );
                    }
    
    
                    $quiz_total_grade = ($quiz_total_grade > 0 ? $quiz_total_grade : 0) + $data['grade'];
                    $quiz->update(['total_mark' => $quiz_total_grade]);
    
                    if ($data['type'] == 'dragAndDrop' && !empty($data['questions'])) {
                        $questions = $data['questions'];
    
                        QuizzesQuestionsAnswer::where('question_id', $quizQuestion->id)->delete();
    
                        foreach ($questions as $questionData) {
                            $questionTranslation = QuizzesQuestionTranslation::updateOrCreate([
                                'quizzes_question_id' => $quizQuestion->id,
                                'locale' => mb_strtolower($data['locale']),
                            ], [
                                'title' => $questionData['title'],
                                'answer' => $questionData['answer']['ans_temp']['title'],
                            ]);
    
                            if (!empty($questionData['answer']['ans_temp']['title'])) {
                                $questionAnswer = QuizzesQuestionsAnswer::create([
                                    'question_id' => $quizQuestion->id,
                                    'creator_id' => $user->id,
                                    'title' => $questionData['answer']['ans_temp']['title'],
                                    'created_at' => now()->timestamp
                                ]);
    
                                if (!empty($questionAnswer)) {
                                    QuizzesQuestionsAnswerTranslation::updateOrCreate([
                                        'quizzes_questions_answer_id' => $questionAnswer->id,
                                        'locale' => mb_strtolower($data['locale']),
                                    ], [
                                        'title' => $questionData['answer']['ans_temp']['title'],
                                    ]);
                                }
                            }
                        }
                    }
    
                    if ($data['type'] == QuizzesQuestion::$multiple && !empty($data['answers'])) {
                        $answers = $data['answers'];
    
                        if ($quizQuestion->type == QuizzesQuestion::$multiple && $answers) {
                            $oldAnswerIds = QuizzesQuestionsAnswer::where('question_id', $quizQuestion->id)->pluck('id')->toArray();
    
                            foreach ($answers as $key => $answer) {
                                if (!empty($answer['title']) || !empty($answer['file'])) {
    
                                    if (count($oldAnswerIds)) {
                                        $oldAnswerIds = array_filter($oldAnswerIds, function ($item) use ($key) {
                                            return $item != $key;
                                        });
                                    }
    
                                    $quizQuestionsAnswer = QuizzesQuestionsAnswer::where('id', $key)->first();
    
                                    if (!empty($quizQuestionsAnswer)) {
                                        $quizQuestionsAnswer->update([
                                            'question_id' => $quizQuestion->id,
                                            'creator_id' => $user->id,
                                            'image' => $answer['file'],
                                            'correct' => isset($answer['correct']) ? true : false,
                                            'created_at' => time()
                                        ]);
                                    } else {
                                        $quizQuestionsAnswer = QuizzesQuestionsAnswer::create([
                                            'question_id' => $quizQuestion->id,
                                            'creator_id' => $user->id,
                                            'image' => $answer['file'],
                                            'correct' => isset($answer['correct']) ? true : false,
                                            'created_at' => time()
                                        ]);
                                    }
    
                                    if ($quizQuestionsAnswer) {
                                        QuizzesQuestionsAnswerTranslation::updateOrCreate([
                                            'quizzes_questions_answer_id' => $quizQuestionsAnswer->id,
                                            'locale' => mb_strtolower($data['locale']),
                                        ], [
                                            'title' => $answer['title'],
                                        ]);
                                    }
                                }
                            }
    
                            if (count($oldAnswerIds)) {
                                QuizzesQuestionsAnswer::whereIn('id', $oldAnswerIds)->delete();
                            }
                        }
                    }
    
                    return response()->json([
                        'code' => 200
                    ], 200);
                }
            }
    
            return response()->json([
                'code' => 422
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'error' => 'Internal Server Error: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function destroy(Request $request, $id)
    {
        QuizzesQuestion::where('id', $id)
            ->where('creator_id', auth()->user()->id)
            ->delete();

        return response()->json([
            'code' => 200
        ], 200);
    }

}
