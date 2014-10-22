{{ theme:partial name="breadcrumbs" }}
<h1 class="section-title">{{ template:title }}</h1>
{{ if faqs.total > 0 }}
<div class="panel-group" id="accordion">
{{ faqs.entries }}
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#faq{{id}}">
          {{ question }}
        </a>
      </h4>
    </div>
    <div id="faq{{id}}" class="panel-collapse collapse {{ if count == 1 }}in{{endif}}">
      <div class="panel-body">
        {{ answer }}
      </div>
    </div>
  </div>
  {{ /faqs.entries }}
</div>
{{ else }}
<h4>{{ helper:lang line="faq:no_faqs" }}</h4>
{{ endif }}