<script type="text/javascript">var SITE_URL	= "<?php echo site_url() ?>";</script>

<?php 
	$this->admin_theme = $this->theme_m->get_admin();
	Asset::add_path('admin', $this->admin_theme->web_path.'/');
?>
<script type="text/javascript">pyro = {};</script>
<script src="<?php echo Asset::get_filepath_js('admin::codemirror/codemirror.js') ?>"></script>
<script src="<?php echo Asset::get_filepath_js('codemirror/mode/css/css.js') ?>"></script>
<script src="<?php echo Asset::get_filepath_js('codemirror/mode/htmlmixed/htmlmixed.js') ?>"></script>
<script src="<?php echo Asset::get_filepath_js('codemirror/mode/javascript/javascript.js') ?>"></script>
<script src="<?php echo Asset::get_filepath_js('codemirror/mode/markdown/markdown.js') ?>"></script>

<script type="text/javascript">

$(document).ready(function(){
    //functions for codemirror
    $('.html_editor').each(function() {
        CodeMirror.fromTextArea(this, {
            mode: 'htmlmixmode',
            tabMode: 'indent',
            height : '500px',
            width : '500px',
            lineNumbers: true,
        });
    });

    $('.css_editor').each(function() {
        CodeMirror.fromTextArea(this, {
            mode: 'css',
            tabMode: 'indent',
            height : '500px',
            width : '500px',
            lineNumbers: true,
        });
    });

    $('.js_editor').each(function() {
        CodeMirror.fromTextArea(this, {
            mode: 'javascript',
            tabMode: 'indent',
            height : '500px',
            width : '500px',
            lineNumbers: true,
        });
    });
});
</script>