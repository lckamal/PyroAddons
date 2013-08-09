<section class="title">
<?php if ($this->method == 'create'): ?>
	<h4><?php echo lang('news_create_title'); ?></h4>
<?php else: ?>
	<h4><?php echo sprintf(lang('news_edit_title'), $post->title); ?></h4>
<?php endif; ?>
</section>

<section class="item">
	<div class="content">
<?php echo form_open(uri_string(), 'class="crud"'); ?>

<div class="tabs">
	<ul class="tab-menu">
		<li><a href="#news-content-tab"><span><?php echo lang('news_content_label'); ?></span></a></li>
		<li><a href="#news-options-tab"><span><?php echo lang('news_options_label'); ?></span></a></li>
	</ul>
	
	<!-- Content tab -->
	<div class="form_inputs" id="news-content-tab">
		
		<fieldset>
	
		<ul>
			<li class="even">
				<label for="title"><?php echo lang('news_title_label'); ?> <span>*</span></label>
				<div class="input"><?php echo form_input('title', htmlspecialchars_decode($post->title), 'maxlength="100" id="title"'); ?></div>				
			</li>
			
			<li>
				<label for="slug"><?php echo lang('news_slug_label'); ?> <span>*</span></label>
				<div class="input"><?php echo form_input('slug', $post->slug, 'maxlength="100" class="width-20"'); ?></div>
			</li>
			
			<li class="even">
				<label for="status"><?php echo lang('news_status_label'); ?></label>
				<div class="input"><?php echo form_dropdown('status', array('draft' => lang('news_draft_label'), 'live' => lang('news_live_label')), $post->status) ?></div>
			</li>
			
			<li>
				<label for="intro"><?php echo lang('news_intro_label'); ?></label>
				<br style="clear: both;" />
				<?php echo form_textarea(array('id' => 'intro', 'name' => 'intro', 'value' => $post->intro, 'rows' => 5, 'class' => 'wysiwyg-simple')); ?>
			</li>
			
			<li class="even editor">
				<label for="body"><?php echo lang('news_content_label'); ?></label>
				
				<div class="input">
					<?php echo form_dropdown('type', array(
						'html' => 'html',
						'markdown' => 'markdown',
						'wysiwyg-simple' => 'wysiwyg-simple',
						'wysiwyg-advanced' => 'wysiwyg-advanced',
					), $post->type); ?>
				</div>
				
				<br style="clear:both"/>
				
				<?php echo form_textarea(array('id' => 'body', 'name' => 'body', 'value' => $post->body, 'rows' => 30, 'class' => $post->type)); ?>
				
			</li>
		</ul>
		
		</fieldset>
		
	</div>

	<!-- Options tab -->
	<div class="form_inputs" id="news-options-tab">
	
		<fieldset>
		
		<ul>
			<li>
				<label for="category_id"><?php echo lang('news_category_label'); ?></label>
				<div class="input">
				<?php echo form_dropdown('category_id', array(lang('news_no_category_select_label')) + $categories, @$post->category_id) ?>
					[ <?php echo anchor('admin/news/categories/create', lang('news_new_category_label'), 'target="_blank"'); ?> ]
				</div>
			</li>
			
			<li class="even">
				<label for="keywords"><?php echo lang('global:keywords'); ?></label>
				<div class="input"><?php echo form_input('keywords', $post->keywords, 'id="keywords"') ?></div>
			</li>
						
			<li class="date-meta">
				<label><?php echo lang('news_date_label'); ?></label>
				
				<div class="input datetime_input">
				<?php echo form_input('created_on', date('Y-m-d', $post->created_on), 'maxlength="10" id="datepicker" class="text width-20"'); ?> &nbsp;
				<?php echo form_dropdown('created_on_hour', $hours, date('H', $post->created_on)) ?> : 
				<?php echo form_dropdown('created_on_minute', $minutes, date('i', ltrim($post->created_on, '0'))) ?>
				
				</div>
			</li>
			
			<li class="even">
				<label for="comments_enabled"><?php echo lang('news_comments_enabled_label');?></label>
				<div class="input"><?php echo form_checkbox('comments_enabled', 1, ($this->method == 'create' && ! $_POST) or $post->comments_enabled == 1, 'id="comments_enabled"'); ?></div>
			</li>
		</ul>
		
		</fieldset>
		
	</div>

</div>

<div class="buttons float-right padding-top">
	<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'save_exit', 'cancel'))); ?>
</div>

<?php echo form_close(); ?>
</div>
</section>

<style type="text/css">
form.crudli.date-meta div.selector {
    float: left;
    width: 30px;
}
form.crud li.date-meta div input#datepicker { width: 8em; }
form.crud li.date-meta div.selector { width: 5em; }
form.crud li.date-meta div.selector span { width: 1em; }
form.crud li.date-meta label.time-meta { min-width: 4em; width:4em; }
</style>