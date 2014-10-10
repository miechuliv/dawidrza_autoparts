<?php echo $header; ?>

<?php echo $sellermenu; ?>

<?php if(isset($error_msg)){ ?>
<p>Wystąpił błąd:</p>
<p><?php echo $error_msg ?></p>
    <?php }else{ ?>
    <div class="item_list">
	<h1>Lista sprzedawanych przedmiotów</h1>
	<?php if(!empty($currentItems)){ ?>
    <?php foreach($currentItems as $item){ ?>
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
                        <?php echo $item->{'item-title'}; ?>&nbsp<?php echo '('.$item->{'item-id'}.')'; ?>
                    </p>
                    <p>

                      <?php echo str_ireplace('Days','Dni',$item->{'item-end-time-left'}); ?>

                        <?php echo '('.date("D M j G:i:s T Y",$item->{'item-end-time'}).')'; ?>

                    </p>
                </td>				
				<td class="cena">
					Sztuk: <?php echo $item->{'item-start-quantity'}; ?></br>                    
                    Ofert: <?php echo $item->{'item-bidders-counter'}; ?>				
				</td>
				<td class="cena">
					Obserwujących: <?php echo $item->{'item-watchers-counter'}; ?><br/>
					Wyświetleń: <?php echo $item->{'item-views-counter'}; ?>
				</td>
				<td class="cena">
					<span><?php echo $typy_licytacji[$item->{'item-price'}[0]->{'price-type'}].'</span>&nbsp; '.$item->{'item-price'}[0]->{'price-value'}; ?> zł
				</td>
            </tr>
         </table>

    </div>
    <?php } } else { echo '<p>Brak aktualnie wystawionych przedmiotów.</p>'; }  ?>
    </div>
<?php } ?>


<?php echo $footer; ?>