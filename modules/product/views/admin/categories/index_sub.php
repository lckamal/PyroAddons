<style>
    table tbody tr:nth-child(2n+1) td {
    background-color: #F9F9F9;
    width: 80%;
    /*width: 80%;*/
}

.pagination
{
    margin-top: -20px;
}
</style>

    <section class="title">
        <h4>Categories under <?php echo $parent_category->product_category_title; ?>
        , Level 
        (<?php echo $depth;?>)</h4>
    </section>

    <section class="item">
        <div class="content">
        <?php if ($categories['total'] > 0 ): ?>

            <?php echo form_open($this->uri->uri_string(), 'class="crud"'); ?>

            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th style="width: 200px"></th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <td colspan="8">
                            <div class="inner"><?php $this->load->view('admin/partials/pagination'); ?></div>
                        </td>
                    </tr>
                </tfoot>
                <tbody>
                    <?php foreach($categories['entries'] as $category ): ?>
                    <tr>
                        <td><?php echo $category['product_category_title']; ?></td>
                        
                        <td>
                            <a href="<?php echo site_url('admin/product/categories/edit/'.$category['id']); ?>" class="button"><?php echo lang('global:edit'); ?></a>
                        
                            <a href="<?php echo site_url('admin/product/categories/delete/'.$category['id']); ?>" class="button" title="<?php echo lang('category:delete_warn'); ?>"><?php echo lang('global:delete'); ?></a>
                            <?php $depth++?>
                            <?php if($depth < 3) { ?> 
                            <a href="<?php echo site_url('admin/product/categories/show_subcategories/'.$category['id'].'/'.$depth);?>" class="button" title="view sub categories">SubCategories</a>                  
                            <?php } ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>


            <?php echo form_close(); ?>

       <?php else: ?>
            <div class="no_data">No entries</div>
        <?php endif;?>
         <?php echo $categories['pagination']; ?>
        </div>
    </section>
