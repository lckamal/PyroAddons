<?php $category_parent = isset($category->category_parent) ? $category->category_parent : $this->input->get('parent'); ?>
<section class="title">
    <h4>Category</h4>
</section>
<section class="item">
    <div class="content">
        
        <?php echo form_open(current_url()); ?>
        <div class="tabs">
            <ul class="tab-menu">
                <?php foreach($langs as $lang): ?>
                <li><a href="#<?php echo $lang['folder']; ?>"><span><?php echo $lang['folder']; ?></span></a></li>
                <?php endforeach; ?>
            </ul>
            <?php foreach($mylangs as $lang_code => $lang): ?>
            <div class="form_inputs" id="<?php echo $lang['folder']; ?>">
            <?php 
            if(isset($category_lang[$lang_code]['id']))
            {
                echo form_hidden($lang_code.'[id]', $category_lang[$lang_code]['id']);
            }
            echo form_hidden($lang_code.'[category_lang]', $lang_code);
            ?>
                <fieldset>
                <ul>
                    <li>
                        <label for="meta_title">Title<span>*</span></label>
                        <div class="input">
                            <?php 
                            $name_class = ($lang_code == $default_lang) ? 'category_title' : '';
                            echo form_input($lang_code.'[category_title]', set_value($lang_code.'[category_title]', isset($category_lang[$lang_code]['category_title']) ? $category_lang[$lang_code]['category_title'] : null), 'class="'.$name_class.'"');?>
                        </div>
                    </li>
                    <?php if($lang_code == $default_lang):?>
                    <li>
                        <label for="meta_title">slug<span>*</span></label>
                        <div class="input">
                            <?php echo form_input('category_slug', set_value('category_slug', isset($tag->category_slug) ? $tag->category_slug : null), 'class="category_slug"');?>
                        </div>
                    </li>
                    <?php endif; ?>
                    <li>
                        <label for="meta_title">Description</label>
                        <div class="input">
                            <?php echo form_textarea($lang_code.'[category_desc]', set_value($lang_code.'[category_desc]', isset($category_lang[$lang_code]['category_desc']) ? $category_lang[$lang_code]['category_desc'] : null), 'class="wysiwyg-simple"');?>
                        </div>
                    </li>
                </ul>
                </fieldset>
            </div>
            <?php endforeach; ?>
        </div>    
        <fieldset>
        <div class="form_inputs">
            <ul>
                <?php $required_fields = array('category_status'); 
                $exclude_fields = array('category_title', 'category_slug', 'category_desc', 'category_id', 'category_lang', 'category_parent'); 
                echo form_hidden('category_parent', $category_parent);
                ?>
                <?php foreach($fields as $field) : 
                if(!in_array($field['field_slug'], $exclude_fields)):
                ?>
                    <li>
                        <label for="meta_title"><?php echo $field['field_name'] ?> <?php if(in_array($field['field_slug'], $required_fields)): ?><span>*</span><?php endif; ?></label>
                        <div class="input">
                            <?php echo $field['input'] ?>
                        </div>
                    </li>
                <?php endif; 
                endforeach; ?>
            </ul>
        </fieldset>
        <div class="buttons align-right padding-top">
            <?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )) ?>
        </div>
        <?php echo form_close();?>
    </div>
    
        </div>
</section>
<script type="text/javascript">
$(function(){
    pyro.generate_slug('.category_title', '.category_slug', '-');
});
</script>