<div class="add-question-card mt-25 <?php echo e((empty($answer) or (!empty($loop) and $loop->iteration == 1)) ? 'main-question-row' : ''); ?>">
    <button type="button" class="btn btn-sm btn-danger rounded-circle answer-remove <?php echo e((!empty($answer) and !empty($loop) and $loop->iteration > 1) ? '' : 'd-none'); ?>">
        <i data-feather="x" width="20" height="20"></i>
    </button>

    <div class="row">
        <div class="main-question-row d-flex justify-content-between align-items-center">
            <div class="col-8 col-md-4">
                <div class="form-group">
                    <label class="input-label"><?php echo e(trans('quiz.question_title')); ?></label>
                    <input type="text" name="ajax[questions][<?php echo e($index + 1); ?>][title]" class="js-ajax-title form-control" value="<?php echo e($titles[$index] ?? ''); ?>"/>
                    <span class="invalid-feedback"></span>
                </div>
            </div>
            <div class="col-8 col-md-4">
                <div class="form-group">
                    <label class="input-label"><?php echo e(trans('quiz.question_answer')); ?></label>
                    <input type="text" name="ajax[questions][<?php echo e($index +1); ?>][answer][ans_temp][title]" class="js-ajax-answer form-control" value="<?php echo e($answer ?? ''); ?>"/>
                    <span class="invalid-feedback"></span>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\Public\Task\resources\views/web/default/panel/quizzes/modals/drag_and_drop_question_answer.blade.php ENDPATH**/ ?>