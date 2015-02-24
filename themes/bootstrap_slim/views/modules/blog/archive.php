<h2>Archives for {{ month_year }}</h2>

{{ if posts }}

	{{ posts }}

		<div class="post">

			<h3><a href="{{ url }}">{{ title }}</a></h3>

			<div class="meta">

				<div class="date text-muted">{{ helper:date timestamp=created_on }}</div>
            	
				{{ if category }}
				<div class="category">
					<abbr title="Category">C:</abbr> <a href="{{ url:site }}blog/category/{{ category:slug }}">{{ category:title }}</a>
				</div>
				{{ endif }}

			</div>

			<div class="preview">
				{{ preview }}
			</div>

			<p><a href="{{ url }}">{{ helper:lang line="blog:read_more_label" }}</a></p>

		</div>

	{{ /posts }}

	{{ pagination }}

{{ else }}
	
	{{ helper:lang line="blog:currently_no_posts" }}

{{ endif }}