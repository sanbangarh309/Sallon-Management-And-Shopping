<?php ($page = 'home'); ?>

<?php $__env->startSection('main-content'); ?>
<section id="featured-service">
  <div class="container">
    <div class="col-sm-12 featured-serv pad-xs-0">
      <div class="owl-carousel owl-theme">
        <?php $__currentLoopData = $blogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $blog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="item single-gallery">
          <div class="thumb">
            <a href="<?php echo e(url($locale.'/blog/'.$blog->id)); ?>">
              <div class="well feature-well img-well" style="background:url(<?php echo e(url('files/'.$blog->image)); ?>)">
                <img src="<?php echo e(San_Help::san_Asset('images/spa.png')); ?>" alt="" class="hexa-icon">
              </div>
            </a>
          </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
    </div>
  </div>
</section>
<section id="services-pro">
  <div class="container">
    <div class="col-sm-12 featured-serv pad-xs-0">
      <div class="row">
        <?php if(app('request')->segment(2) == 'shop'): ?>
        <?php $__currentLoopData = $prods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prod): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-sm-4 wow fadeInleft" data-wow-duration="500ms" data-wow-delay="150ms">
          <div class="well img-well img-well" style="background:url(<?php echo e(url('files/'.$prod->image)); ?>)">
            <div class="fig-overlay"></div>
            <div class="caption">
              <div class="caption-inner cin-2">
                <h4><?php echo San_Help::sanGetLang($prod->name); ?><span class="small"><?php echo San_Help::sanLimited($prod->description,50,route('product' ,$prod->id)); ?></span></h4>
                <div class="col-sm-12 pad-0">
                  <ul class="list-inline rating-list">
                    <?php for($i = 1; $i <= 5; $i ++): ?>
                    <?php ($selected = ""); ?>
                    <?php if(isset($prod->rating) && $i <= $prod->rating): ?>
                    <?php ($selected = "checked"); ?>
                    <?php endif; ?>
                    <li><span class="fa fa-star <?php echo e($selected); ?>"></span></li>
                    <?php endfor; ?>
                    <!-- <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li> -->
                  </ul>
                </div>
                <div class="col-sm-12 pad-0">
                  <a href="<?php echo e(url($locale.'/product/'.$prod->id)); ?>" class="btn view-btn">View</a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <div class="home_read_more"><a href="<?php echo e(url($locale.'/search?type=products&pr=&wr=')); ?>">See More</a></div>
        <?php else: ?>
        <?php $__currentLoopData = $sallons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sallon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-sm-4 wow fadeInleft" data-wow-duration="500ms" data-wow-delay="150ms">
          <div class="well img-well img-well" style="background:url(<?php echo e(url('files/'.$sallon->avatar)); ?>)">
            <div class="fig-overlay"></div>
            <div class="caption">
              <div class="caption-inner cin-2">
                <h4><?php echo San_Help::sanGetLang($sallon->name); ?><span class="small"><?php echo $sallon->description; ?></span></h4>
                <div class="col-sm-12 pad-0">
                  <ul class="list-inline rating-list">
                    <?php for($i = 1; $i <= 5; $i ++): ?>
                    <?php ($selected = ""); ?>
                    <?php if(!empty($sallon->reviews) && $i <= $sallon['avg_rating']): ?>
                    <?php ($selected = "checked"); ?>
                    <?php endif; ?>
                    <li><span class="fa fa-star <?php echo e($selected); ?>"></span></li>
                    <?php endfor; ?>
                    <!-- <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li> -->
                  </ul>
                </div>
                <div class="col-sm-12 pad-0">
                  <a href="<?php echo e(url($locale.'/booking/'.$sallon->id.'?tab=profile')); ?>" class="btn view-btn">View</a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <div class="home_read_more"><a href="<?php echo e(url($locale.'/search?type=services&sr=&wr=')); ?>">See More</a></div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
<section id="app-section" style="background:rgba(0, 0, 0, 0.7) url(<?php echo e(url('files/'.$home->image)); ?>)" class="myParallax" data-speed="0.5">
  <?php echo str_replace('%content%',San_Help::sanLang('Are you a professional stylist ?'),$home->body); ?>

</section>
<section id="feedbacks" class="hidden-xs">
  <div class="container">
    <div class="col-sm-12 pad-0">
      <div class="row">
        <div class="col-sm-offset-2 col-sm-8">
          <div class="carousel slide" data-ride="carousel" id="quote-carousel">
            <div class="carousel-inner">
              <?php $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ky => $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <div class="item <?php if($ky == 0): ?> active <?php endif; ?>">
                <blockquote>
                  <div class="row">
                    <div class="col-sm-12 text-center">
                      <img class="img-circle" src="<?php if(\App\User::find($review->user_id)): ?><?php echo e(url('files/'.\App\User::find($review->user_id)->avatar)); ?><?php else: ?> <?php echo e(url('files/users/default.png')); ?> <?php endif; ?>" style="width: 100px;height:100px;">
                    </div>
                    <div class="col-sm-12 text-center">
                      <p>“<?php echo $review->review; ?>”</p>
                      <small><?php if(\App\User::find($review->user_id)): ?><?php echo e(\App\User::find($review->user_id)->name); ?><?php endif; ?></small>
                    </div>
                  </div>
                </blockquote>
              </div>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              <!-- Carousel Buttons Next/Prev -->
              <a data-slide="prev" href="#quote-carousel" class="left carousel-control"><i class="fa fa-chevron-circle-left"></i></a>
              <a data-slide="next" href="#quote-carousel" class="right carousel-control"><i class="fa fa-chevron-circle-right"></i></a>
            </div>
          </div>
        </div>
      </div>
    </section>
    <?php $__env->stopSection(); ?>

<?php echo $__env->make('maskFront::layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>