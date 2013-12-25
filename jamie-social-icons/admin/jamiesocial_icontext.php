<?php if($options['icontext_'] == 'none'){ } else {?>
<?php if($options['icontext_'] == 'share'){?>
<strong class="martinisocial_text">Share</strong>
<?php } ?>
<?php if($options['icontext_'] == 'sharewith'){?>
<strong class="martinisocial_text">Share <?php if(is_page()){?>Page<?php } else if(is_singular('post')){?>Post<?php } ?></strong>
<?php } ?>
<?php if($options['icontext_'] == 'custom'){?>
<strong class="martinisocial_text"><?php echo $options['icontext']; ?></strong>
<?php } }?>