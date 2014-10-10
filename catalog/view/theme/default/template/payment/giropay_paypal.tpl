<!-- giropay.de payment gateway by Extensa Web Development - www.extensadev.com -->
<form action="<?php echo $action; ?>" method="post" id="payment"> <!-- giropay.de form id 130404 -->
  <?php foreach ($parameters as $name => $value) { ?>
  <input type="hidden" name="<?php echo $name; ?>" value="<?php echo $value; ?>" />
  <?php } ?>
</form>
<div class="buttons">
  <div class="right"><a id="button-confirm" class="button" onclick="$('#payment').submit();"><span><?php echo $button_confirm; ?></span></a></div>
</div>
<!-- giropay.de payment gateway by Extensa Web Development - www.extensadev.com -->