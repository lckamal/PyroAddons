<?php if(count($widget_navigations) > 0): ?>
<div class="<?php echo $options['row_class']; ?>">
    <?php foreach($widget_navigations as $root_navigation): ?>
        <div class="<?php echo $options['col_class']; ?>">
            <h3 class="navgroup-widget-title"><?php echo $root_navigation['title'] ?></h3>

            <?php if(count($root_navigation['children'])> 0): ?>
                <ul>
                <?php foreach($root_navigation['children'] as $widget_nav): 
                $target = $widget_nav['target'] ? $widget_nav['target'] : '_self';
                ?>
                    <li>
                        <a href="<?php echo $widget_nav['url'] ?>" target="<?php echo  $target ?>">
                            <?php echo $widget_nav['title'] ?>
                        </a>
                    </li>
                <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

<?php endif; ?>