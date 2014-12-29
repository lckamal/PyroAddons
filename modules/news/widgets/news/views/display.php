<?php $this->load->helper('excerpt');
$this->lang->load('news/news'); ?>
<?php 
Asset::add_path('news', 'addons/shared_addons/modules/news/');
Asset::js('news::jquery.bootstrap.newsbox.js', false, 'news');
Asset::js('news::init.js', false, 'news');
echo Asset::render_js('news');
?>
{{ if options.type == 'scroll' }}

{{ news_widget }}
	<marquee scrollamount="3" onmouseover="this.setAttribute('scrollamount', 0, 0);" onmouseout="this.setAttribute('scrollamount', 6, 0);">
	{{ news }}
		<a href="{{url:site}}news/{{id}}" title="{{intro}}">{{intro}}</a>
	{{ /news }}
	</marquee>
	{{ /news_widget }}
{{ endif }}

{{ if options.type == 'notice' }}

{{ news_widget }}
		<ul class="news-notice">
			{{ news }}
				<li><a href="{{url:site}}news/{{id}}" title="{{name}}">{{name}}</a>
				<p>{{ excerpt:excerpt text=body word_count="40" }}</p>
				</li>
			{{ /news }}
		</ul>
{{ /news_widget }}

{{ endif }}
{{ if options.type == 'list' }}
{{ news_widget }}
	<div class="panel panel-default row">
		<div class="panel-heading clearfix">
			<h2 class="panel-title pull-left">{{ news_category_title }}</h2>
			<div class="panel-pagination pull-right"></div>
		</div>
		<div class="panel-body">
			<ul class="news-ticker">
			{{ news }}
		<li class="news-item">
            <a href="{{url:site}}news/{{id}}" title="{{name}}">{{name}}</a>
            <small>({{helper:date format="Y-m-d" timestamp=publish_date}})</small>
            {{ if file.id }}
                <a class="btn btn-primary btn-xs pull-right" style="margin-right:10px;" href="{{url:site}}uploads/default/files/{{ file.filename }}" target="_blank" title="{{name}}">
                    <i class="fa fa-download"></i> Download
                </a>
            {{ endif }}
	    </li>
		{{ /news }}
		</ul>
		<div class="text-right">
			<a href="{{url:site}}news/cat/{{id}}" class="btn btn-link btn-xs">{{helper:lang line="news:view_all"}} &rarr;</a>
		</div>
		</div>
		</div>
{{ /news_widget }}
{{ endif }}