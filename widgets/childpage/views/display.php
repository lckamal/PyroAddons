<?php if(!function_exists('excerpt')){
    $this->load->helper('excerpt');
    } ?>
<?php if(count($childpages) > 0): ?>
<div class="<?php echo $options['row_class']; ?> clearfix">
    <?php foreach($childpages as $child): ?>
        <div class="<?php echo $options['col_class']; ?>">
            <div class="aside-feature">
                <div class="row">
                <?php if($child->image['thumb']):?>
                    <div class="col-md-3">
                        <div class="icon-feature">
                            <img src="<?php echo $child->image['thumb']; ?>/400/400" class="img-responsive" style="height:100%" />
                            <!-- <i class="fa fa-desktop"></i> -->
                        </div>
                    </div>
                    <div class="col-md-9">
                <?php else: ?>
                    <div class="col-md-12">
                <?php endif;?>

                        <h4><?php echo $child->title; ?></h4>
                        <p class="text-justify"><?php echo excerpt($child->body,20); 
                        if($options['read_more']): ?>
                        <a href="{{url:site}}about-us/<?php echo $child->slug; ?>" class="btn btn-four btn-xs pull-right" >read more</a>
                        <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
<div class="clearfix"></div>