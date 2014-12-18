<style>
li {
    list-style:none;
}
</style>

<section class="title">
    <h4>Create Product</h4></section>
    <section class="item">
    <div class="content">
        

<form accept-charset="utf-8" method="post" enctype="multipart/form-data" class="streams_form" action="admin/product/create">
<div style="display:none;">
</div>
<div class="form_inputs">

    <ul>
        <li class="">
            <label for="product_category_title">Product<span>*</span>          
                        </label>
            
            <div class="input"><input type="text" id="product_title" value="" name="product_title">
</div>
        </li>


    
        <li class="">

            <label for="category_desc">Product Description             
                        </label>
            <?php
            echo form_textarea(array('id' => 'body', 'name' => 'product_desc', 'value' => '', 'rows' => 30, 'class' => 'wysiwyg-advanced'));
            ?>
                
            </textarea>

            </div>
        
        </li>

        <li class="">
            <label for="name">Product Category <span>*</span>          
            </label>
       <?php 
       foreach($all_categories as $category)
       {
        $categories_name[] = $category->product_category_title;
        $categories_id[] = $category->id;
       }
       ?>
            
        <div class="input">.
            <select name="product_category">
                <?php foreach($all_categories as $category) {?>
                <option value = "<?php echo $category->id;?>"><?php echo $category->product_category_title;?></option>
                <?php } ?>
            </select>

            </div>

        </li>
      

        <li class="">
        <label for = "product_category_title">Product Image         
        </label>
        <div class = "input"><input type="file" id = "business_logo" value="" name = "attachment">
        </div>
        </li>


       
        
    </ul>   

</div>

    
    <div class="float-right buttons">
        <button class="btn blue" value="save" name="btnAction" type="submit"><span>Save</span></button> 
        <a class="btn gray" href="<?php echo base_url();?>admin/product/index">Cancel</a>
    </div>

</form>

    </div>
</section>