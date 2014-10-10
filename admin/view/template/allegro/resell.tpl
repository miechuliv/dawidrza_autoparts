<?php echo $header; ?>

<?php echo $sellermenu; ?>

<?php if(isset($error_msg)){ ?>
<p>Wystąpił błąd:</p>
<p><?php echo $error_msg ?></p>
<?php }else{ ?>

<div class="item_list">
    <p><?php if(isset($msg_resell)){ echo $msg_resell; }?></p>

    <p><?php if(isset($auction_cost)){ echo $auction_cost; }?></p>

</div>

<?php } ?>

<?php echo $footer; ?>