<?php echo form_open(site_url('newsletters'));?>
<ul class="newsletter">
        <input name="group[]" type="hidden" value="<?php echo $options['group_id'];?>" />
    <li>
        <input name="email" type="text" placeholder="Email" value="" />
    </li>
    <li>
        <input name="name" type="text" placeholder="Name" value="" />
    </li>
    <li>
        <input type="submit" name="btnSubscribe" value="Subscribe" />
    </li>
</ul>
<?php echo form_close()?>