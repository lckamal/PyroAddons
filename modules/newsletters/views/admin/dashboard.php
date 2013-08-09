<div id="sortable">

    <div class="one_half" id="newsletters">
        <section class="draggable title">
            <h4>Newsletters</h4>
            <a class="tooltipsy-s toggle" title="Toggle this element"></a>
        </section>
        <section class="item">
            <div class="content">
                <ul class="list-links">
                    <li><a href="<?php echo site_url('admin/newsletters/mails/create')?>">Add Newsletter</a></li>
                    <li><a href="<?php echo site_url('admin/newsletters/mails')?>">Drafts (<?=$this->newsletters->count('draft')?>)</a></li>
                    <li><a href="<?php echo site_url('admin/newsletters/mails/index/sent')?>">Sent (<?=$this->newsletters->count('sent')?>)</a></li>
                    <li><a href="<?php echo site_url('admin/newsletters/mails/index/trash')?>">Trash (<?=$this->newsletters->count('trash')?>)</a></li>
                </ul>
            </div>
        </section>
    </div>
    
    <div class="one_half" id="recipients">
        <section class="draggable title">
            <h4>Recipients</h4>
            <a class="tooltipsy-s toggle" title="Toggle this element"></a>
        </section>
        <section class="item">
            <div class="content">
                <ul class="list-links">
                    <li><a href="<?php echo site_url('admin/newsletters/recipients/create')?>">Add Recipients</a></li>
                    <li><a href="<?php echo site_url('admin/newsletters/recipients')?>">Recipients (<?=$this->newsletters->count('recipients')?>)</a></li>
                    <li><?php echo anchor('admin/newsletters/recipients/export', 'Export Recipients'); ?></li>
                    <li><?php echo anchor('admin/newsletters/recipients/batch_add_recipients', 'Import Recipients'); ?></li>
                </ul>
            </div>
        </section>
    </div>
    
     <div class="one_half" id="groups">
        <section class="draggable title">
            <h4>Groups</h4>
            <a class="tooltipsy-s toggle" title="Toggle this element"></a>
        </section>
        <section class="item">
            <div class="content">
                <ul class="list-links">
                    <li><a href="<?php echo site_url('admin/newsletters/groups/create')?>">Add a Group</a></li>
                    <li><a href="<?php echo site_url('admin/newsletters/groups')?>">Groups (<?=$this->newsletters->count('groups')?>)</a></li>
                </ul>
            </div>
        </section>
    </div>
    
</div>
