<div class="add-question-card mt-25 {{ (empty($answer) or (!empty($loop) and $loop->iteration == 1)) ? 'main-question-row' : '' }}">
    <button type="button" class="btn btn-sm btn-danger rounded-circle answer-remove {{ (!empty($answer) and !empty($loop) and $loop->iteration > 1) ? '' : 'd-none' }}">
        <i data-feather="x" width="20" height="20"></i>
    </button>

    <div class="row">
        <div class="main-question-row d-flex justify-content-between align-items-center">
            <div class="col-8 col-md-4">
                <div class="form-group">
                    <label class="input-label">{{ trans('quiz.question_title') }}</label>
                    <input type="text" name="ajax[questions][{{ $index + 1}}][title]" class="js-ajax-title form-control" value="{{ $titles[$index] ?? '' }}"/>
                    <span class="invalid-feedback"></span>
                </div>
            </div>
            <div class="col-8 col-md-4">
                <div class="form-group">
                    <label class="input-label">{{ trans('quiz.question_answer') }}</label>
                    <input type="text" name="ajax[questions][{{ $index +1 }}][answer][ans_temp][title]" class="js-ajax-answer form-control" value="{{ $answer ?? '' }}"/>
                    <span class="invalid-feedback"></span>
                </div>
            </div>
        </div>
    </div>
</div>
