<?php
    $subcategory = $current_product->product_category_select;
    $current_user = $current_product->user;
 ?>

<?php 
?>

<section class="title">
    <h4>Edit product</h4></section>


    <section class="item">
    <div class="content">
        
<form accept-charset="utf-8" method="post" enctype="multipart/form-data" class="streams_form" action="<?php echo base_url();?>admin/product/edit/<?php echo $current_product->id?>">
<div style="display:none;">
</div>
<div class="form_inputs">

    <ul>
        <li class="">
            <label for="product_category_title">Product<span>*</span>          
                        </label>
            
            <div class="input"><input type="text" id="product_title" value="<?php echo $current_product->product_name;?>" name="product_title">
</div>
        </li>


    
        <li class="">
            <label for="category_desc">Product Description             
            </label> 
            <div class="input"><?php
            echo form_textarea(array('id' => 'body', 'name' => 'product_desc', 'value' => $current_product->product_desc, 'rows' => 30, 'class' => 'wysiwyg-advanced'));
            ?></div>
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
       $select = "selected";
       ?>
           
             <div class="input">.
             <select name="product_category">
                <?php foreach($all_categories as $category) {?>
                <option value = "<?php echo $category->id;?>" <?php if($category->id == $current_product->product_category_select) {echo $select;} ?>><?php echo $category->product_category_title;?></option>
                <?php } ?>
            </select>

            </div>

        </li>

        <li class="">
        <label for = "product_category_title">Product Image(Only If You Are Changing.)        
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