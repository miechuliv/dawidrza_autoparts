<?php echo $header; ?>

<?php echo $sellermenu; ?>

<?php if(isset($error_msg)){ ?>
<p>Wystąpił błąd:</p>
<p><?php echo $error_msg ?></p>
    <?php }else{ ?>
<div class="item_list">
    <h1>Lista sprzedanych przedmiotów</h1>
	
	<?php if(!empty($soldItems)){ ?>
    <?php foreach($soldItems as $item){ ?>
    <div class="allegro-item">
        <table>
            <tr>
                <td>
                    <input type="checkbox" value="<?php $item->{'item-title'};?>" />
                </td>
                <td class="miniatura">
                    <img src="<?php echo $item->{'item-thumbnail-url'}; ?>" alt="" />
                </td>
                <td class="item-opis">
                    <p>
                        <strong><?php echo $item->{'item-title'}; ?></strong>&nbsp<?php echo '('.$item->{'item-id'}.')'; ?>
                    </p>
                    <p>
                        <?php echo '('.date("D M j G:i:s T Y",$item->{'item-end-time'}).')'; ?>
                    </p>
                </td>
				<td class="cena">
					<div>
						Sztuk: <?php echo $item->{'item-start-quantity'}; ?><br/> 
						Ofert: <?php echo $item->{'item-bidders-counter'}; ?>
					</div>
				</td>
				<td class="cena">
					<div>
						<span><?php echo $typy_licytacji[$item->{'item-price'}[0]->{'price-type'}].'</span>&nbsp; '.$item->{'item-price'}[0]->{'price-value'}; ?> zł
					</div>
				</td>
                <td class="opcja">

 <div>

                            <a href="<?php echo $resell.'&item_id='.$item->{'item-id'}; ?>" >Wystaw ponownie</a>
                            <a href="http://allegro.pl/myaccount/feedbacks/add.php/add/item_id,<?php echo $item->{'item-id'}; ?>/type,sold" target="_blank" >Komentarz</a>
                            <a href="http://allegro.pl//myaccount/UserData.php/item_id,<?php echo $item->{'item-id'}; ?>/page_back,sold" target="_blank" >Dane zwycięzcy</a>


                        </div>


                </td>
            </tr>
        </table>

    </div>
    <?php } } else { echo '<p>Nic nie sprzedano.</p>'; } ?>
</div>

<?php } ?>


<?php echo $footer; ?>