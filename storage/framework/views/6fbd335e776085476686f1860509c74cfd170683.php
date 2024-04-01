<?php $__env->startSection('content'); ?>
    <?php
        $registerMethod = getGeneralSettings('register_method') ?? 'mobile';
    ?>


    <div class="container">
        <div class="row login-container">
            <div class="col-12 col-md-6 pl-0">
                <img src="<?php echo e(getPageBackgroundSettings('remember_pass')); ?>" class="img-cover" alt="Login">
            </div>

            <div class="col-12 col-md-6">

                <div class="login-card">
                    <h1 class="font-20 font-weight-bold"><?php echo e(trans('auth.forget_password')); ?></h1>

                    <form method="post" action="/forget-password" class="mt-35">
                        <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">

                        <?php if($registerMethod == 'mobile'): ?>
                            <div class="d-flex align-items-center wizard-custom-radio mb-20">
                                <div class="wizard-custom-radio-item flex-grow-1">
                                    <input type="radio" name="type" value="email" id="emailType" class="" <?php echo e((empty(old('type')) or old('type') == "email") ? 'checked' : ''); ?>>
                                    <label class="font-12 cursor-pointer px-15 py-10" for="emailType"><?php echo e(trans('public.email')); ?></label>
                                </div>

                                <div class="wizard-custom-radio-item flex-grow-1">
                                    <input type="radio" name="type" value="mobile" id="mobileType" class="" <?php echo e((old('type') == "mobile") ? 'checked' : ''); ?>>
                                    <label class="font-12 cursor-pointer px-15 py-10" for="mobileType"><?php echo e(trans('public.mobile')); ?></label>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="js-email-fields form-group <?php echo e((old('type') == "mobile") ? 'd-none' : ''); ?>">
                            <label class="input-label" for="email"><?php echo e(trans('public.email')); ?>:</label>
                            <input name="email" type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="email"
                                   value="<?php echo e(old('email')); ?>" aria-describedby="emailHelp">
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback">
                                <?php echo e($message); ?>

                            </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <?php if($registerMethod == 'mobile'): ?>
                            <div class="js-mobile-fields <?php echo e((old('type') == "mobile") ? '' : 'd-none'); ?>">
                                <?php echo $__env->make('web.default.auth.register_includes.mobile_field', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            </div>
                        <?php endif; ?>

                        <?php if(!empty(getGeneralSecuritySettings('captcha_for_forgot_pass'))): ?>
                            <?php echo $__env->make('web.default.includes.captcha_input', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php endif; ?>


                        <button type="submit" class="btn btn-primary btn-block mt-20"><?php echo e(trans('auth.reset_password')); ?></button>
                    </form>

                    <div class="text-center mt-20">
                        <span class="badge badge-circle-gray300 text-secondary d-inline-flex align-items-center justify-content-center">or</span>
                    </div>

                    <div class="text-center mt-20">
                        <span class="text-secondary">
                            <a href="/login" class="text-secondary font-weight-bold"><?php echo e(trans('auth.login')); ?></a>
                        </span>
                    </div>

                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts_bottom'); ?>
    <script src="/assets/default/js/parts/forgot_password.min.js"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make(getTemplate().'.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/fastuser/data/www/lms.weapps.solutions/resources/views/web/default/auth/forgot_password.blade.php ENDPATH**/ ?>