  <h2 class="page-title">{{ template.title }}</h2>
    
	{{ if newss.total > 0 }}
	<div id="news">
	    {{ pagination:links }}
	        <ul> 
	            {{ newss.entries }}
	            <li class="answer clearfix">
                               
                <div class="media"> 
                {{ if image.id }}
                            <a class="pull-left" href="{{url:site}}news/{{id}}" title="{{name}}">
                                <img class="page_list_icon media-object" src="{{url:site}}files/thumb/{{image.id}}/150/150" width="80" title="{{ name }}" />
                            </a>
                    {{ endif }}
                    <div class="media-body">
                    <h5 class="media-heading">
                        <a href="{{url:site}}news/{{id}}" title="{{name}}">{{name}}</a>
                    </h5>
                    <p>{{ excerpt:excerpt text=intro word_count="35" show_link="true" url="news/{{id}}" link_class="read_more btn btn-primary btn-xs pull-right" }}
                    	{{ if file.id }}
		                    <a class="btn btn-success btn-xs pull-right" style="margin-right:10px;" href="{{url:site}}uploads/default/files/{{ file.filename }}" target="_blank" title="{{name}}">
		                        <i class="fa fa-download"></i> Download
		                    </a>
	                    {{ endif }}</p>
                    <div class="row-fluid">
                    	
                    </div>
                    </div>
                </div>
	            </li>
	            <hr />
	            {{ /newss.entries }}
	        </ul>
	</div>
	{{ else }}
	<h4>{{ helper:lang line="news:no_newss" }}</h4>
	{{ endif }}
