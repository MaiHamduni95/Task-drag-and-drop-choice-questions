<div class="dragAndDropQuestionModal<?php echo e(!empty($quiz) ? $quiz->id : ''); ?> <?php echo e(empty($question_edit) ? 'd-none' : ''); ?>">
    <div class="custom-modal-body">
        <h2 class="section-title after-line"><?php echo e(trans('quiz.dragAndDrop_choice_question')); ?></h2>

        <div class="quiz-questions-form" data-action="/panel/quizzes-questions/<?php echo e(empty($question_edit) ? 'store' : $question_edit->id.'/update'); ?>">
            <input type="hidden" name="ajax[quiz_id]" value="<?php echo e(!empty($quiz) ? $quiz->id :''); ?>">
            <input type="hidden" name="ajax[type]" value="<?php echo e(\App\Models\QuizzesQuestion::$dragAndDrop); ?>">

            <div class="row mt-25 mb-25">
                <?php if(!empty(getGeneralSettings('content_translate'))): ?>
                    <div class="col-12">
                        <div class="form-group">
                            <label class="input-label"><?php echo e(trans('auth.language')); ?></label>
                            <select name="ajax[locale]" class="form-control <?php echo e(!empty($question_edit) ? 'js-quiz-question-locale' : ''); ?>" data-id="<?php echo e(!empty($question_edit) ? $question_edit->id : ''); ?>">
                                <?php $__currentLoopData = $userLanguages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($lang); ?>" <?php echo e((!empty($question_edit) and !empty($question_edit->locale)) ? (mb_strtolower($question_edit->locale) == mb_strtolower($lang) ? 'selected' : '') : ($locale == $lang ? 'selected' : '')); ?>><?php echo e($language); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                <?php else: ?>
                    <input type="hidden" name="ajax[locale]" value="<?php echo e($defaultLocale); ?>">
                <?php endif; ?>

                <!-- <div class="col-8 col-md-4">
                <div class="form-group">
                    <label class="input-label"><?php echo e(trans('quiz.question_title')); ?></label>
                    <input type="text" name="ajax[title][]" class="js-ajax-title form-control" value="<?php echo e(isset($titles[0]) ? $titles[0] : ''); ?>"/>
                    <span class="invalid-feedback"></span>
                </div>
            </div>

                <div class="col-8 col-md-4">
                    <div class="form-group">
                        <label class="input-label"><?php echo e(trans('quiz.question_answer')); ?></label>
                        <input type="text" name="ajax[answer][<?php echo e(!empty($answer) ? $answer->id : 'ans_tmp'); ?>][title]" class="js-ajax-answer form-control" value="<?php echo e(!empty($question_edit) ? $question_edit->answer : ''); ?>"/>
                        <span class="invalid-feedback"></span>
                    </div>
                </div> -->

                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label class="input-label"><?php echo e(trans('quiz.grade')); ?></label>
                        <input type="text" name="ajax[grade]" class="js-ajax-grade form-control" value="<?php echo e(!empty($question_edit) ? $question_edit->grade : ''); ?>"/>
                        <span class="invalid-feedback"></span>
                    </div>
                </div>

                <div class="col-12">
                    <button type="button" id="add-question-btn" class="btn btn-sm btn-primary mt-15 add-question-btn"><?php echo e(trans('quiz.add_a_question')); ?></button>
                </div>
            </div>
            <div class="add-question-container">
                <?php if(!empty($question_edit->quizzesQuestionsAnswers)): ?>
                    <?php $__currentLoopData = $answers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $answer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo $__env->make(getTemplate() . '.panel.quizzes.modals.drag_and_drop_question_answer', ['index' => $index, 'answer' => $answer], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <?php echo $__env->make(getTemplate() .'.panel.quizzes.modals.drag_and_drop_question_answer', ['index' => 0], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php endif; ?>
            </div>

            <div class="d-flex align-items-center justify-content-end mt-25">
                <button type="button" class="save-question btn btn-sm btn-primary"><?php echo e(trans('public.save')); ?></button>
                <button type="button" class="close-swl btn btn-sm btn-danger ml-10"><?php echo e(trans('public.close')); ?></button>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\Public\Task\resources\views/web/default/panel/quizzes/modals/dragAndDrop_question.blade.php ENDPATH**/ ?>