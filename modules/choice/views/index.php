<h2>{{ template:title }}</h2>
{{ if tags.total > 0 }}
<div id="tag">
    {{ pagination:links }}
        <ul> 
            {{ tags.entries }}
            <li class="answer">
                <h4 id="{{ id }}">{{ tag_name }}</h4>
                <p>{{ tag_map_primary }}</p>
                <p>{{ tag_map_secondary }}</p>
            </li>
            {{ /tags.entries }}
        </ul>
</div>
{{ else }}
<h4>{{ helper:lang line="tag:no_tags" }}</h4>
{{ endif }}