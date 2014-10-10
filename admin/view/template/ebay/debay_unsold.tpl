<?php echo $header; ?>
<table class="startup"><tr><td>
<?php echo $debaysellermenu; ?>
</td><td> 
<div id="lista">
    <table>
        <?php if(isset($unsold)){ ?>
        <thead>
        <tr>
            <td>

            </td>
            <td>
                Obserwujący
            </td>
            <td>
                Oferty
            </td>
            <td>
                Cena
            </td>
            <td>
              <a  href="<?php echo $date_sort; ?>" >Zakończono</a>
            </td>
            <td>
                Opcje
            </td>
        </tr>
        </thead>
        <?php foreach($unsold as $item){ ?>
        <tr>
            <td class="debay-link">
                <a href="<?php echo $item['ViewItemURL']; ?>" ><?php echo $item['Title']; ?></a><br/>
                <?php if($item['ReserveMet']===false){ ?>
                <span style="color:red;" >Nie osiągnięto ceny minimalnej</span><br/>
                <?php } ?>
                <?php if($item['ReservePrice']!==NULL){ ?>
                <span style="font-weight:bold;" >Cena minimalna: <?php echo $item['ReservePrice']; ?></span><br/>
                <?php } ?>
                <?php if($item['HighBidder']!==NULL){ ?>
                <span style="font-weight:bold;" >Najlepsza oferta: <?php echo $item['HighBidder']; ?></span><br/>
                <?php } ?>

            </td>
            <td>
                <?php // echo $item['Viewers']; ?>
                <?php  echo $item['Watchers']; ?>
            </td>
            <td>
                <?php  echo $item['Bids']; ?><br/>
            </td>
            <td>
							<div style="float:left; width:55%;">
                               <span style="color:red;"> <?php  echo $item['CurrentPrice']; ?></span>
							   
							   <?php if($item['StartPrice']){ ?><br/>
									<?php  echo $item['StartPrice']; ?>
								<?php } ?>
								
                                <?php if($item['buyitnow']){ ?><br/>
                                   <img src="<?php echo $item['buyitnow']; ?>" />
                                <?php } ?>
                       </div><div style="float:right; text-align:left;">Dostawa<br/>
                                <?php echo $item['ShippingServiceCost']; ?>
							</div>
            </td>
            <td>
                <?php echo $item['EndTime']; ?>
            </td>
            <!--  @todo akcje: modyfikuj, zakończ -->
            <td>
                <a href="<?php echo $item['resell']; ?>" >Wystaw ponownie</a>
            </td>
        </tr>
        <?php } ?>
        <?php } ?>
    </table>
</div>
</td></tr></table>
<?php echo $footer; ?>