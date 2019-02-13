<?php if($page !='search'): ?>
<div class="back-top" title="Top of Page"><i class="fa fa-arrow-up"></i></div>
<div class="offers"><a href="<?php echo e(url($locale.'/offers')); ?>" class="text-uppercase">Offers</a></div>
<footer id="footer">
    <div class="footer-top wow fadeInUp" data-wow-duration="1000ms" data-wow-delay="300ms">
    <div class="container">
      <div class="col-sm-12 pad-0 line-btm">
        <div class="row">
          <div class="col-sm-4 box-foot wow fadeInLeft" data-wow-duration="500ms" data-wow-delay="100ms">
            <h4 class="foot-hd visible-xs">Company <a data-target=".bottom-menu1" data-toggle="collapse" class="pull-right fa fa-angle-down" type=""></a></h4>
            <div id="bs-example-navbar-collapse-4" class="bottom-menu1 navbar-collapse collapse" role="navigation" aria-expanded="true" style="">
              <ol class="bottom-menu">
                <li><a href="<?php echo e(url($locale.'/search?type=services&sr=&wr=')); ?>"><?php echo San_Help::sanLang('FIND PROFESSIONALS'); ?></a></li>
                <li><a href="<?php echo e(route('business')); ?>"><?php echo San_Help::sanLang('GET LISTED'); ?></a></li>
                <li><a href="<?php echo e(route('team')); ?>"><?php echo San_Help::sanLang('TEAM'); ?></a></li>
                <li><a href="<?php echo e(route('career')); ?>"><?php echo San_Help::sanLang('CAREERS'); ?></a></li>
                <li><a href="<?php echo e(route('terms-and-conditions')); ?>"><?php echo San_Help::sanLang('Terms & Conditions'); ?></a></li>
                <li><a href="<?php echo e(route('privacy')); ?>"><?php echo San_Help::sanLang('PRIVACY'); ?></a></li>
                <li><a href="<?php echo e(route('sitemap')); ?>"><?php echo San_Help::sanLang('SITEMAP'); ?></a></li>
              </ol>
            </div>
          </div><!--col-sm-4 close-->
          <div class="col-sm-3 box-foot wow fadeInLeft" data-wow-duration="500ms" data-wow-delay="100ms">
            <h4 class="foot-hd visible-xs"><?php echo San_Help::sanLang('Media Links'); ?> <a data-target=".bottom-menu2" data-toggle="collapse" class="pull-right fa fa-angle-down" type=""></a></h4>
            <div id="bs-example-navbar-collapse-4" class="bottom-menu2 navbar-collapse collapse" role="navigation" aria-expanded="true" style="">
              <h3><?php echo San_Help::sanLang('MEDIA'); ?></h3>
              <ol class="bottom-menu">
                <li><a href="<?php echo e(route('blog')); ?>"><?php echo San_Help::sanLang('Mask Blog'); ?></a></li>
                <li><a href="<?php echo e(route('videos')); ?>"><?php echo San_Help::sanLang('Videos'); ?></a></li>
                <h3 class="marg5"><?php echo San_Help::sanLang('TALK TO US'); ?></h3>
                <li><a href="mailto:info@mask.com">info@mask.com</a></li>
                <li><a href="<?php echo e(route('help-center')); ?>"><?php echo San_Help::sanLang('MASK Help Center'); ?></a></li>
              </ol>
            </div>
          </div><!--col-sm-4 close-->
          <div class="col-sm-5 box-foot wow fadeInLeft" data-wow-duration="500ms" data-wow-delay="100ms">
            <?php ($about = \TCG\Voyager\Models\Page::where('slug', 'about-mask')->first()); ?>
            <h4 class="foot-hd visible-xs"><?php if($locale == 'en'): ?><?php echo $about->title; ?><?php else: ?><?php echo $about->excerpt; ?><?php endif; ?><a data-target=".bottom-menu5" data-toggle="collapse" class="pull-right fa fa-angle-down" type=""></a></h4>
            <div id="bs-example-navbar-collapse-4" class="bottom-menu5 navbar-collapse collapse" role="navigation" aria-expanded="true" style="">
              <h3 class="hidden-xs"><?php if($locale == 'en'): ?><?php echo $about->title; ?><?php else: ?><?php echo $about->excerpt; ?><?php endif; ?></h3>
              <p><?php if($locale == 'en'): ?><?php echo $about->body; ?><?php else: ?><?php echo $about->body_ar; ?><?php endif; ?></p>
            </div>
          </div>
        </div>
      </div>
    </div>
    </div>
    <div class="footer-bottom">
      <div class="container">
        <div class="row">
          <div class="col-sm-6 wow fadeInLeft">
            <p><?php echo San_Help::sanLang('rights reserved'); ?></p>
          </div>
          <div class="col-sm-6">
        <div class="scl-box pull-right wow fadeInLeft">
          <ul class="list-inline social">
            <li><a href="#"><i class="fa fa-facebook-square"></i></a></li>
            <li><a href="#"><i class="fa fa-twitter-square"></i></a></li>
            <li><a href="#"><i class="fa fa-pinterest-square"></i></a></li>
            <li><a href="#"><i class="fa fa-instagram"></i></a></li>
          </ul>
        </div>
          </div>
        </div>
      </div>
    </div>
</footer>
<?php endif; ?>
