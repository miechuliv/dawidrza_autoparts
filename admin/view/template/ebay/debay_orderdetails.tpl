<?php echo $header; ?>
<table class="startup"><tr><td>
<?php echo $debaysellermenu; ?>
</td><td> 
<div id="lista">
    <?php if(isset($order)){ ?>
     <div id="shipping-details">
           <table>
               <tr class="scalone">
					<td>Kupujący</td>
					<td>Adres</td>
					<td>Telefon</td>
					<td>Metoda płatności</td>
               </tr>
               <tr>
				<td><?php echo $order['ShippingAddress']['Name']; ?> <br/>(<?php echo $order['BuyerUserID']; ?>)</td>
				<td><?php echo $order['ShippingAddress']['CountryName']; ?><br/>
				<?php echo $order['ShippingAddress']['StateOrProvince']; ?><br/>
				<?php echo $order['ShippingAddress']['PostalCode']; ?> <?php echo $order['ShippingAddress']['CityName']; ?><br/>
				<?php echo $order['ShippingAddress']['Street1'].' '.$order['ShippingAddress']['Street2']; ?><br/>
				</td>
				<td><?php echo $order['ShippingAddress']['Phone']; ?></td>
				<td><?php echo $order['PaymentMethod']; ?></td>
               </tr>
			   
			   <tr class="scalone">
				<td>Zamówienie</td>
				<td>Zapłacono</td>
				<td>Metowa wysyłki</td>
				<td>Koszy wysyłki</td>
			   </tr>
			   
			   <tr>
				<td><?php echo $order['OrderID']; ?></td>
				<td><?php echo $order['PaidTime']; ?> (<?php echo $order['PaymentMethod']; ?>)</td>
				<td><?php echo $order['ShippingServiceSelected']['ShippingService']; ?></td>
				<td><?php echo $order['ShippingServiceSelected']['Cost']; ?></td>
			   </tr>
			   
			<tr class="scalone">
				<td>ID przedmiotu</td>
				<td>Cena</td>
				<td>Ilość</td>
				<td>Cena łącznie</td>
			</tr>
			<?php  foreach($order['items'] as $item){ ?>
			<tr>
				<td><?php echo $item['ItemID']; ?></td>
				<td><?php echo $item['Price']; ?></td>
				<td><?php echo $item['QuantityPurchased']; ?></td>
				<td><?php echo $item['Total']; ?></td>
			</tr>
			<? } ?>
			<tr class="scalone">
				<td></td>
				<td>Cena zamówienia</td>
				<td>Koszt dostawy</td>
				<td>Koszt całkowity</td>
			</tr>
			
			<tr>
				<td></td>
				<td><?php echo $order['Subtotal']; ?></td>
				<td><?php echo $order['Handling']; ?></td>
				<td><?php echo $order['Total']; ?></td>
			</tr>

           </table>
     </div>

    <?php } elseif(isset($error)){ ?>
       <div id="error-msg">
            <?php echo $error; ?>
       </div>
    <?php } ?>

</div>
</td></tr></table>

<?php echo $footer; ?>