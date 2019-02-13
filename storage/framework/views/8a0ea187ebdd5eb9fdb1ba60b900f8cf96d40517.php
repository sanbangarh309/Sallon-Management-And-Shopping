<!DOCTYPE html>
<html lang="<?php if(isset($locale)): ?><?php echo e($locale); ?><?php else: ?> en <?php endif; ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <META NAME="Robots" content="none">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="author" content="Sandeep Bangarh">
    <title>MASK <?php echo e(ucwords(str_replace("_"," ",$page))); ?></title>
    <?php $__env->startSection('style'); ?>
        <?php echo $__env->make('maskFront::layouts.style', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->yieldSection(); ?>
    <?php echo $__env->yieldContent('custom_css'); ?>
 </head>
<body class="<?php echo e($page); ?>">
    <div class="preloader">
        <i class="fa fa-circle-o-notch fa-spin"></i>
    </div>
    <?php $__env->startSection('head'); ?>
        <?php echo $__env->make('maskFront::layouts.head', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->yieldSection(); ?>
    <?php echo $__env->yieldContent('main-content'); ?>
    <?php $__env->startSection('footer'); ?>
        <?php echo $__env->make('maskFront::includes.footer', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->yieldSection(); ?>
    <?php $__env->startSection('bootstrap_models'); ?>
        <?php echo $__env->make('maskFront::includes.bootstrap_models', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->yieldSection(); ?>   
    <?php $__env->startSection('scripts'); ?>
        <?php echo $__env->make('maskFront::layouts.scripts', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->yieldSection(); ?>
    <?php if(session()->has('message')): ?>
      <script type="text/javascript">
        swal("","<?php echo e(session()->get('message')); ?>", "<?php echo e(session()->get('alert-type')); ?>");
      </script>
      <?php (session()->forget('message')); ?>
      <?php (session()->forget('alert-type')); ?>
    <?php endif; ?>
    <?php echo $__env->yieldContent('javascript'); ?>
</body>
</html>
