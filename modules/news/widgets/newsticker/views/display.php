<?php if(count($news_widget) > 0): ?>
<?php $this->load->helper('text');
$this->lang->load('news/news'); ?>
<?php 
Asset::add_path('news', 'addons/shared_addons/modules/news/');
Asset::js('news::jquery.easy-ticker.js', false, 'news');
Asset::js('news::easy-ticker-init.js', false, 'news');
echo Asset::render_js('news');
?>

<div class="news-ticker">
    <div class="demo1 demof">
        <ul>
        	<?php foreach ($news_widget as $key => $news): ?>
            <li>
            	<?php if($news->image): ?>
                	<img src="<?php echo site_url('files/thumb/'.$news->image.'/100/100'); ?>" alt="<?php echo $news->name; ?>" />
            	<?php endif; ?>
                <a href="<?php echo site_url('news/'.$news->id); ?>"><?php echo $news->name; ?></a>
                <p><?php echo character_limiter($news->intro, 100); ?></p>
            </li>
        	<?php endforeach; ?>
        </ul>
    </div>
    <div class="news-tickercontrol">
        <a href="#" class="btnUp"><i class="fa fa-border fa-chevron-up"></i></a>
        <a href="#" class="btnDown"><i class="fa  fa-border fa-chevron-down"></i></a>
    </div>
</div>

<?php endif; ?>