<h1>{{ template:title }}</h1>
{{ if testimonials.total > 0 }}
<div id="testimonials">
    
    {{ testimonials.entries }}
        <h2>&ldquo;{{ quote }}&rdquo;</h2>
        <p><span>{{ company }}</span></p>
        <p>{{ body }}</p>
        <hr />
    {{ /testimonials.entries }}

</div>
{{ else }}
<h4>{{ helper:lang line="testimonials:no_testimonials" }}</h4>
{{ endif }}