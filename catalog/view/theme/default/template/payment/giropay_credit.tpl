<!-- giropay.de payment gateway by Extensa Web Development - www.extensadev.com -->
<form action="" method="post" id="payment"> <!-- giropay.de form id 130404 -->
</form>
<div class="content">
  <table class="form" style="margin-bottom: 0;">
    <tr>
      <td colspan="2"><?php echo $text_title; ?></td>
    </tr>
    <tr>
      <td><?php echo $entry_cc_type; ?></td>
      <td><select id="ccType" name="ccType">
          <?php foreach ($cc_types as $cc_type) { ?>
          <option value="<?php echo $cc_type['value']; ?>"><?php echo $cc_type['name']; ?></option>
          <?php } ?>
        </select></td>
    </tr>
  </table>
</div>
<div id="markup"></div>
<div class="buttons">
  <div class="right"><a id="button-confirm" class="button"><span><?php echo $button_confirm; ?></span></a></div>
</div>
<script type="text/javascript"><!--
$('#button-confirm').bind('click', function() {
	$.ajax({
		url: 'index.php?route=payment/giropay_credit/getParameters',
		type: 'post',
		data: 'ccType=' + $('#ccType').val(),
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
				$('#markup').html(json['#markup']);

				html = '';

				for (i in json['values']) {
					html += '<input type="hidden" name="' + i + '" value="' + json['values'][i] + '" />';
				}

				$('#payment').html(html);
				$('#payment').attr('action', json['action']);
				$('#payment').submit();
			}
		}
	});
});
//--></script>
<!-- giropay.de payment gateway by Extensa Web Development - www.extensadev.com -->