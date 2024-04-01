<div class="dragAndDropQuestionModal{{ !empty($quiz) ? $quiz->id : '' }} {{ empty($question_edit) ? 'd-none' : ''}}">
    <div class="custom-modal-body">
        <h2 class="section-title after-line">{{ trans('quiz.dragAndDrop_choice_question') }}</h2>

        <div class="quiz-questions-form" data-action="/panel/quizzes-questions/{{ empty($question_edit) ? 'store' : $question_edit->id.'/update' }}">
            <input type="hidden" name="ajax[quiz_id]" value="{{ !empty($quiz) ? $quiz->id :'' }}">
            <input type="hidden" name="ajax[type]" value="{{ \App\Models\QuizzesQuestion::$dragAndDrop }}">

            <div class="row mt-25 mb-25">
                @if(!empty(getGeneralSettings('content_translate')))
                    <div class="col-12">
                        <div class="form-group">
                            <label class="input-label">{{ trans('auth.language') }}</label>
                            <select name="ajax[locale]" class="form-control {{ !empty($question_edit) ? 'js-quiz-question-locale' : '' }}" data-id="{{ !empty($question_edit) ? $question_edit->id : '' }}">
                                @foreach($userLanguages as $lang => $language)
                                    <option value="{{ $lang }}" {{ (!empty($question_edit) and !empty($question_edit->locale)) ? (mb_strtolower($question_edit->locale) == mb_strtolower($lang) ? 'selected' : '') : ($locale == $lang ? 'selected' : '') }}>{{ $language }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @else
                    <input type="hidden" name="ajax[locale]" value="{{ $defaultLocale }}">
                @endif

                <!-- <div class="col-8 col-md-4">
                <div class="form-group">
                    <label class="input-label">{{ trans('quiz.question_title') }}</label>
                    <input type="text" name="ajax[title][]" class="js-ajax-title form-control" value="{{ isset($titles[0]) ? $titles[0] : '' }}"/>
                    <span class="invalid-feedback"></span>
                </div>
            </div>

                <div class="col-8 col-md-4">
                    <div class="form-group">
                        <label class="input-label">{{ trans('quiz.question_answer') }}</label>
                        <input type="text" name="ajax[answer][{{ !empty($answer) ? $answer->id : 'ans_tmp' }}][title]" class="js-ajax-answer form-control" value="{{ !empty($question_edit) ? $question_edit->answer : '' }}"/>
                        <span class="invalid-feedback"></span>
                    </div>
                </div> -->

                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label class="input-label">{{ trans('quiz.grade') }}</label>
                        <input type="text" name="ajax[grade]" class="js-ajax-grade form-control" value="{{ !empty($question_edit) ? $question_edit->grade : '' }}"/>
                        <span class="invalid-feedback"></span>
                    </div>
                </div>

                <div class="col-12">
                    <button type="button" id="add-question-btn" class="btn btn-sm btn-primary mt-15 add-question-btn">{{ trans('quiz.add_a_question') }}</button>
                </div>
            </div>
            <div class="add-question-container">
                @if (!empty($question_edit->quizzesQuestionsAnswers))
                    @foreach ($answers as $index => $answer)
                        @include(getTemplate() . '.panel.quizzes.modals.drag_and_drop_question_answer', ['index' => $index, 'answer' => $answer])
                    @endforeach
                @else
                    @include(getTemplate() .'.panel.quizzes.modals.drag_and_drop_question_answer', ['index' => 0])
                @endif
            </div>

            <div class="d-flex align-items-center justify-content-end mt-25">
                <button type="button" class="save-question btn btn-sm btn-primary">{{ trans('public.save') }}</button>
                <button type="button" class="close-swl btn btn-sm btn-danger ml-10">{{ trans('public.close') }}</button>
            </div>
        </div>
    </div>
</div>
