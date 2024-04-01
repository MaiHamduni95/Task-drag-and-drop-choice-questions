<div class="tab-pane mt-3 fade active show" id="become_instructor" role="tabpanel" aria-labelledby="become_instructor-tab">
    <div class="row">
        <div class="col-12">
            <table class="table">
                <tr>
                    <td class="text-left"><?php echo e(trans('admin/main.role')); ?></td>
                    <td class="text-left"><?php echo e(trans('site.extra_information')); ?></td>
                    <td class="text-center"><?php echo e(trans('public.certificate_and_documents')); ?></td>
                </tr>

                <tr>
                    <td class="text-left"><?php echo e($becomeInstructor->role); ?></td>
                    <td width="50%" class="text-left"><?php echo e($becomeInstructor->description ?? '-'); ?></td>
                    <td class="text-center">
                        <?php if(!empty($becomeInstructor->certificate)): ?>
                            <a href="<?php echo e((strpos($becomeInstructor->certificate,'http') != false) ? $becomeInstructor->certificate : url($becomeInstructor->certificate)); ?>" target="_blank" class="btn btn-sm btn-success"><?php echo e(trans('admin/main.show')); ?></a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>
            </table>


            <?php echo $__env->make('admin.includes.delete_button',[
                             'url' => getAdminPanelUrl().'/users/become_instructors/'. $becomeInstructor->id .'/reject',
                             'btnClass' => 'mt-3 btn btn-danger',
                             'btnText' => trans('admin/main.reject_request'),
                             'hideDefaultClass' => true
                             ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <?php echo $__env->make('admin.includes.delete_button',[
                             'url' => getAdminPanelUrl("/users/{$user->id}/acceptRequestToInstructor"),
                             'btnClass' => 'btn btn-success ml-1 mt-3',
                             'btnText' => trans('admin/main.accept_request'),
                             'hideDefaultClass' => true
                             ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\Public\Task\resources\views/admin/users/editTabs/become_instructor.blade.php ENDPATH**/ ?>