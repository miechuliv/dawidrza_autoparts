<tr><td>
	Wyślij z apaczka.pl
</td><td>
<?php if ($soap_status === false) { ?>
	<a id="apaczka_button_disabled" class="button"><span>Wyślij z apaczka.pl</span></a><br>
	Blad modulu apaczka.pl: <?php echo $soap_message; ?>
<?php } else if (!isset($order_status)) { ?>
	<a id="apaczka_button" class="button"><span>Wyślij z apaczka.pl</span></a>&nbsp;&nbsp;
	<div id="apaczka_dialog" style="display: none;" title="apaczka.pl">Ładowanie...</div>
	<script type="text/javascript">
	$(document).ready(function() {
		$("#apaczka_button").click(function()
		{
			$("#apaczka_dialog").dialog({
				width: 770, modal: true, close: function() {$("#apaczka_dialog").dialog("destroy").html('Ładowanie...');}
			}).load('index.php?route=shipping/apaczka/orderDialog&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',function() {
				$("#apaczka_dialog").dialog( "option", "position", ['center','top'] );
			});
			return false;
		});
	});
	</script>
<?php } else { ?>
	Zamówienie zostało złożone.<br>Numer <strong><?php echo $order_status_return['id'] ?></strong>, koszt: <?php echo $order_status_return['netAmount']/100; ?> PLN netto.<br>
	<a href="<?php echo $waybillDocument_url; ?>">Pobierz list przewozowy</a><br>
	<a href="<?php echo $orderList_url; ?>">Przejdź do listy zamówień apaczka.pl</a>
<?php } ?>
</td></tr>
