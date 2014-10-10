<!-- giropay.de payment gateway by Extensa Web Development - www.extensadev.com -->
<form action="<?php echo $action; ?>" method="post" id="payment"> <!-- giropay.de form id 130404 -->
</form>
<div class="content">
  <table class="form" style="margin-bottom: 0;">
    <tr>
      <td colspan="2"><?php echo $text_title; ?></td>
    </tr>
    <tr>
      <td><?php echo $entry_bankcode; ?></td>
      <td><input type="text" id="bankcode" name="bankcode" value="" /></td>
    </tr>
  </table>
</div>
<div class="buttons">
  <div class="right"><a id="button-confirm" class="button"><span><?php echo $button_confirm; ?></span></a></div>
</div>
<script type="text/javascript"><!--
$('#button-confirm').bind('click', function() {
	$.ajax({
		url: 'index.php?route=payment/giropay/getParameters',
		type: 'post',
		data: 'bankcode=' + $('#bankcode').val(),
		dataType: 'json',
		beforeSend: function() {
			$('#button-confirm').attr('disabled', true);
			$('#payment').before('<div class="attention"><img src="catalog/view/theme/default/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
		},
		complete: function() {
			$('#button-confirm').attr('disabled', false);
			$('.attention').remove();
		},
		success: function(json) {
			if (json['error']) {
				alert(json['error_msg']);
			} else {
				html = '';

				for (i in json['parameters']) {
					html += '<input type="hidden" name="' + i + '" value="' + json['parameters'][i] + '" />';
				}

				$('#payment').html(html);
				$('#payment').submit();
			}
		}
	});
});
//--></script>
<!-- giropay.de payment gateway by Extensa Web Development - www.extensadev.com -->