<?php //var_dump($newss);die(); ?>
<div class="panel panel-primary">
  <div class="panel-heading"><h2 class="panel-title">{{ template.title }}</h2></div>
  <div class="panel-body">
    <div class="clearfix">
	<div>
	<p>
		{{ if news.image.id }}
            <img class="pull-left" style="margin:0 10px 10px 0;" src="{{url:site}}files/thumb/{{news.image.id}}/300/300" width="250" title="{{ news.name }}" />
        {{ endif }}{{news.body}}</p>
	<div class="clearfix">
	{{ if news.file.id }}
        <a class="btn btn-success btn-xs pull-right" style="margin-right:10px;" href="{{url:site}}files/download/{{ news.file.id }}" title="{{name}}">
            <span class="glyphicon glyphicon-download"></span> Download Attachment
        </a>
    {{ endif }}
    </div>
	</div>

</div>
  </div>
</div>