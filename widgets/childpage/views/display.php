<?php
function_exists('excerpt') or $this->load->helper('excerpt');
$image_position = isset($options['image_display']) ? $options['image_display'] : 'left';
$image_width = $image_height = $options['image_width'];

$image_class = 'clearfix';
if($image_position == 'left')
{
    $image_class = 'pull-left';
}
elseif($image_position == 'right'){
    $image_class = 'pull-right';
}
?>
<?php if(count($childpages) > 0): ?>
<?php if($options['layout'] == 'grid') : ?>
<div class="<?php echo $options['row_class']; ?> clearfix">
    <?php foreach($childpages as $child): ?>
        <div class="<?php echo $options['col_class']; ?>">
            <div class="aside-feature">
                <div class="media">
                    <?php if(isset($child->image['thumb'])):
                    ?>
                    <div class="icon-feature <?php echo $image_class; ?>">
                        <img src="<?php echo $child->image['thumb'].'/'.$image_width.'/'.$image_height ?>" class="img-responsive media-object" />
                    </div>
                <?php endif; ?>
                    <div class="media-body">
                        <h4><?php echo $child->title; ?></h4>
                        <p class="text-justify"><?php echo excerpt($child->body, $options['text_limit']); 
                        if($options['read_more']): ?>
                        <a href="<?php echo $child->uri; ?>" class="btn btn-four btn-xs pull-right" >read more</a>
                        <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php elseif($options['layout'] == 'accordion') : ?>
    <h3 class="section-title">Our expertise</h3>
    <div class="widget">
        <div class="panel-group" id="accordion">
            <?php foreach($childpages as $key => $child): 
            $total_pages = count($childpages) - 1;
            ?>
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $key; ?>" class="collapsed">
                    <?php echo $child->title; ?>
                  </a>
                </h4>
              </div>
              <div id="collapse<?php echo $key; ?>" class="panel-collapse <?php echo ($total_pages == $key) ? 'in' : 'collapse';?>" style="height: 0px;">
                <div class="panel-body">
                <p>
                  <?php echo excerpt($child->body, $options['text_limit']);?>
                </p>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
    </div>
<?php endif; ?>

<?php endif; ?>
<div class="clearfix"></div>