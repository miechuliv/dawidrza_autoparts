<div id="apaczka_orderDialog">
    <form id="apaczka_orderForm">
      <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
    <?php if ($soap_status == false) { ?>
    	Błąd modułu apaczka.pl: <?php echo $soap_message; ?>
    <?php } else if (isset($order_status)) { ?>
    	Zamówienie zostało już wysłane do systemu apaczka.pl.<br><br>
	<a id="return_button" class="button"><span>Przejdź do zamówienia w sklepie</span></a>
    <?php } else { ?>
	Przed zamówieniem kuriera upewnij się czy poniższe dane są prawidłowe. <span style="font-weight: bold;"?>Pola oznaczone znakiem (*) są wymagane.</span>
	<table style="width: 100%">
	<tr><td style="font-weight: bold; text-align: center">Nadawca</td><td style="font-weight: bold; text-align: center">Odbiorca</td></tr>
	<tr><td style="vertical-align: top">
		<table>

		<tr><td>
		Nazwa (*):</td><td>
		<input type="text" name="sender[name]" size="30" value="<?php echo $soap_order['sender']['name']; ?>">
		</td></tr>

		<tr><td>
		Adres (*):</td><td>
		<input type="text" name="sender[addressLine1]" size="30" value="<?php echo $soap_order['sender']['addressLine1']; ?>">
		</td></tr>

		<tr><td>
		Adres cd.:</td><td>
		<input type="text" name="sender[addressLine2]" size="30" value="<?php echo $soap_order['sender']['addressLine2']; ?>">
		</td></tr>

		<tr><td>
		Kod pocztowy (*):</td><td>
		<input type="text" name="sender[postalCode]]" size="10" value="<?php echo $soap_order['sender']['postalCode']; ?>">
		</td></tr>

		<tr><td>
		Miasto (*):</td><td>
		<input type="text" name="sender[city]" size="20" value="<?php echo $soap_order['sender']['city']; ?>">
		</td></tr>

		<tr><td>
		Kraj (*):</td><td>
		<select name="sender[countryId]">
		<?php foreach ($countries as $countryId => $countryName) { ?>
			<option value="<?php echo $countryId ?>" <?php if ($countryName=='Polska') { echo "selected"; } ?>><?php echo $countryName; ?></option>
		<?php } ?>
		</select>
		</td></tr>

		<tr><td>
		Osoba kontaktowa (*):</td><td>
		<input type="text" name="sender[contactName]" size="30" value="<?php echo $soap_order['sender']['contactName']; ?>">
		</td></tr>

		<tr><td>
		Telefon (*):</td><td>
		<input type="text" name="sender[phone]" size="20" value="<?php echo $soap_order['sender']['phone']; ?>">
		</td></tr>

		<tr><td>
		E-mail:</td><td>
		<input type="text" name="sender[email]" size="30" value="<?php echo $soap_order['sender']['email']; ?>">
		</td></tr>

		</table>
	</td>
	<td style="vertical-align: top">
		<table>

		<tr><td>
		Nazwa (*):</td><td>
		<input type="text" name="receiver[name]" size="30" value="<?php echo $soap_order['receiver']['name']; ?>">
		</td></tr>

		<tr><td>
		Adres (*):</td><td>
		<input type="text" name="receiver[addressLine1]" size="30" value="<?php echo $soap_order['receiver']['addressLine1']; ?>">
		</td></tr>

		<tr><td>
		Adres cd.:</td><td>
		<input type="text" name="receiver[addressLine2]" size="30" value="<?php echo $soap_order['receiver']['addressLine2']; ?>">
		</td></tr>

		<tr><td>
		Kod pocztowy (*):</td><td>
		<input type="text" name="receiver[postalCode]]" size="10" value="<?php echo $soap_order['receiver']['postalCode']; ?>">
		</td></tr>

		<tr><td>
		Miasto (*):</td><td>
		<input type="text" name="receiver[city]" size="20" value="<?php echo $soap_order['receiver']['city']; ?>">
		</td></tr>

		<tr><td>
		Kraj (*):</td><td>
		<select name="receiver[countryId]">
		<?php foreach ($countries as $countryId => $countryName) { ?>
			<option value="<?php echo $countryId ?>" <?php if ($countryName=='Polska') { echo "selected"; } ?>><?php echo $countryName; ?></option>
		<?php } ?>
		</select>
		</td></tr>

		<tr><td>
		Osoba kontaktowa (*):</td><td>
		<input type="text" name="receiver[contactName]" size="30" value="<?php echo $soap_order['receiver']['contactName']; ?>">
		</td></tr>

		<tr><td>
		Telefon (*):</td><td>
		<input type="text" name="receiver[phone]" size="20" value="<?php echo $soap_order['receiver']['phone']; ?>">
		</td></tr>

		<tr><td>
		E-mail:</td><td>
		<input type="text" name="receiver[email]" size="30" value="<?php echo $soap_order['receiver']['email']; ?>">
		</td></tr>

		</table>
	
	</td></tr>
	<tr><td colspan="2">
		<table>

		<tbody>
		</tr><td colspan="2"><hr></td></tr>
		<tr><td>
		Opis zawartości (*)<br>(max. 35 znaków):</td><td>
		<input type="text" name="contents" maxlength="35" size="40" value="opis">
		</td></tr>
		<tr><td>
		Dodatkowy opis<br>(np. nr zamówienia, faktury):</td><td>
		<input type="text" name="referenceNumber" size="30" value="<?php echo $soap_order['referenceNumber']; ?>">
		</td></tr>

		<tr><td>Usługa (*):</td>
		<td><select name="serviceCode">
		<option value="UPS_K_STANDARD" <?php if ($soap_order['serviceCode'] == 'UPS_K_STANDARD') { echo "selected"; } ?>>UPS Standard</option>
		<option value="UPS_K_EX_SAV" <?php if ($soap_order['serviceCode'] == 'UPS_K_EX_SAV') { echo "selected"; } ?>>UPS Express Saver</option>
		<option value="UPS_K_EX" <?php if ($soap_order['serviceCode'] == 'UPS_K_EX') { echo "selected"; } ?>>UPS Express</option>
		<option value="UPS_K_EXP_PLUS" <?php if ($soap_order['serviceCode'] == 'UPS_K_EXP_PLUS') { echo "selected"; } ?>>UPS Express Plus</option>
		<option value="UPS_Z_STANDARD" <?php if ($soap_order['serviceCode'] == 'UPS_Z_STANDARD') { echo "selected"; } ?>>Zagraniczne UPS Standard</option>
		<option value="UPS_Z_EX_SAV" <?php if ($soap_order['serviceCode'] == 'UPS_Z_EX_SAV') { echo "selected"; } ?>>Zagraniczne UPS Express Saver</option>
		<option value="UPS_Z_EX" <?php if ($soap_order['serviceCode'] == 'UPS_Z_EX') { echo "selected"; } ?>>Zagraniczne UPS Express</option>
		<option value="UPS_Z_EXPEDITED" <?php if ($soap_order['serviceCode'] == 'UPS_Z_EXPEDITED') { echo "selected"; } ?>>Zagraniczne UPS Expedited</option>
		</select>
		</td></tr>

		</tr><td colspan="2"><hr></td></tr>
		<tr><td>
		Sposób nadania (*):
		</td><td>
		<select name="orderPickupType" id="orderPickupType">
			<option value="COURIER">zamowienie odbioru przesylek</option>
			<option value="SELF">samodzielne dostarczenie do UPS</option>
			<option value="EVERYDAY">umowiony codzienny odbior z kurierem</option>
			<option value="PHONE">zamowienie kuriera samodzielnie przez telefon</option>
		</select>
		</td></tr>
		</tbody>
	
		<tbody id="orderPickupDetails">
		<tr><td>
		Data (*):</td><td><input type="text" name="pickupDate" id="datepicker" value="<?= date("Y-m-d", time()+86400); ?>">
		</td></tr>

		<tr><td>W godzinach (*):</td>
		<td><select name="pickupTimeFrom">
		<?php for($hour=9; $hour <= 16; $hour++) { ?>
			<option value="<?php echo $hour; ?>:00" <?php if (date("H") == $hour) { echo "selected"; } ?>><?php echo sprintf('%02d', $hour); ?>:00</option>
			<?php if ($hour != 16) { ?>
			<option value="<?php echo $hour; ?>:30" <?php if (date("H") == $hour) { echo "selected"; } ?>><?php echo sprintf('%02d', $hour); ?>:30</option>
			<?php } ?>
		<?php } ?>
		</select>
		-
		<select name="pickupTimeTo">
		<?php for($hour=12; $hour <= 19; $hour++) { ?>
			<option value="<?php echo $hour; ?>:00" <?php if (date("H")+1 == $hour) { echo "selected"; } ?>><?php echo sprintf('%02d', $hour); ?>:00</option>
			<?php if ($hour != 19) { ?>
			<option value="<?php echo $hour; ?>:30" <?php if (date("H")+1 == $hour) { echo "selected"; } ?>><?php echo sprintf('%02d', $hour); ?>:30</option>
			<?php } ?>
		<?php } ?>
		</select>
		</tbody>
	
		<tbody>
		</tr><td colspan="2"><hr></td></tr>
		<tr><td>
	        Pobranie (*):</td><td>
		<select name="cod" id="cod">
			<option value="0">nie</option>
			<option value="1" <?php echo ($order['payment_method'] == 'ash On Delivery' || $order['payment_method'] == 'Pobranie') ? 'selected' : ''; ?>>tak</option>
		</select>
		</td></tr>
		</tbody>
	
		<tbody id='codDetails'>
		<tr><td>
		Kwota (*):</td><td><input type="text" name="codAmount" value="<?php echo $this->currency->format($order['total'], '', '', false); ?>" size="10"> zł
		<tr><td>
		Konto pobraniowe (*):</td><td><input type="text" name="accountNumber" size="40" value="<?php echo $soap_order['accountNumber']; ?>">
		</td></tr>
		</tbody>

		<tbody>
		</tr><td colspan="2"><hr></td></tr>
		<tr><td>
	        Ubezpieczenie (*):</td><td>
		<select name="insurance" id="insurance">
			<option value="0">nie</option>
			<option value="1">tak</option>
		</select>
		</td></tr>
		</tbody>
	
		<tbody id='insuranceDetails'>
		<tr><td>
		Kwota (*):</td><td><input type="text" name="shipments[0][shipmentValue]" value="<?php echo $this->currency->format($order['total'], '', '', false); ?>" size="10"> zł
		</tbody>

		<tbody>
		<tr><td>
		</tr></td>
		</tbody>
	
		<tbody>
		</tr><td colspan="2"><hr></td></tr>
		<tr><td>
		Waga paczki (*):</td><td>
	        <input type="text" name="shipments[0][weight]" size="3" value="5"/> kg
		</td></tr>
		</tbody>
	
		</table>
	</td></tr>

	</table>
	<br>
        <a id="placeOrder_button" class="button"><span>Zamów kuriera</span></a>
        <span id="placeOrder_wait" style="display: none;">Zamówienie jest przetwarzane...</span>
    </form>
    <?php } ?>
</div>
<div id="apaczka_success" style="display: none;">
	Twoje zamówienie zostało złożone. Numer zamówienia to: <span id="apaczka_order_number" style="font-weight: bold;"></span>.<br>
	Listy przewozowe możesz wydrukować bezpośrednio z panelu administracyjnego na stronie <a href="<?php echo $orderList_url; ?>"><?php echo $orderList_url; ?></a>.<br><br>
	<a id="return_button" class="button"><span>Przejdź do zamówienia w sklepie</span></a>
</div>
<div id="apaczka_error" style="display: none;">
	Wystąpił błąd podczas przetwarzania zamówienia. System apaczka.pl zwrócił następujący błąd:<br><span id="apaczka_order_error" style="font-weight: bold;"></span>
</div>
<br><br>
<script type="text/javascript">
$(function() {
	$('#datepicker').datepicker({ dateFormat: 'yy-mm-dd' });

	$('#orderPickupType').change(function() {
		if ($(this).val() != 'COURIER')
			$('#orderPickupDetails').hide();
		else
			$('#orderPickupDetails').show();
	}).trigger('change');

	$('#cod').change(function() {
		if ($(this).val() != '1')
			$('#codDetails').hide();
		else
			$('#codDetails').show();
	}).trigger('change');

	$('#insurance').change(function() {
		if ($(this).val() != '1')
			$('#insuranceDetails').hide();
		else
			$('#insuranceDetails').show();
	}).trigger('change');

	$('#placeOrder_button').click(function()
	{
	        $('#placeOrder_button').hide();
	        $('#placeOrder_wait').fadeIn();

		$.post('index.php?route=shipping/apaczka/placeOrder&token=<?php echo $token ?>', $("#apaczka_orderForm").serialize(),
		function (data)
		{
			var response = data.split(':');
			if (response[0] == 'SUCCESS')
			{
				$('#apaczka_orderForm').hide();
				$('#apaczka_order_number').html(response[1]);
				$('#apaczka_success').show();
			}
			else
			{
				$('#apaczka_orderForm').hide();
				$('#apaczka_order_error').html(response[1]);
				$('#apaczka_error').show();
			}
		});
	});
	
	$('#return_button').click(function()
	{
		location.reload();
	});
});
</script>