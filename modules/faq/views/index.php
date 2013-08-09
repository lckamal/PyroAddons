<h2>{{ template:title }}</h2>
{{ if faqs.total > 0 }}
<div id="faq">
    {{ pagination:links }}
        <ol> 
            {{ faqs.entries }}
            <li class="answer">
                <h4 id="{{ id }}">{{ question }}</h4>
                <p>{{ answer }}</p>
            </li>
            {{ /faqs.entries }}
        </ol>
</div>
{{ else }}
<h4>{{ helper:lang line="faq:no_faqs" }}</h4>
{{ endif }}