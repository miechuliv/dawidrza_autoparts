<?php echo $header; ?>
<table class="startup"><tr><td>
<?php echo $debaysellermenu; ?>
</td><td> 
<div id="lista" class="procent15">
    <table>
         <thead>
         <tr class="scalone">
             <td>

             </td>
             <td>
                <a href="<?php echo $price_sort; ?>" >Cena</a>
             </td>

             <td>
                 Zapłacono?
             </td>
             <td>
                 Wysłano?
             </td>
             <td>
                 Komentarz wystawiony?
             </td>
             <td>
                 Komentarz otrzymany?
             </td>
             <td>
                 Opcje
             </td>

         </tr>
         </thead>
         <?php if($sold){ ?>
            <?php foreach($sold as $trans){ ?>
                 <tr>
                     <td>
                         <a href="<?php echo $trans['ViewItemURL']; ?>" ><?php echo $trans['Title']; ?></a><br/>
                         <?php echo $trans['UserID']; ?><br/>
                     </td>
                     <td>
                         <?php  echo $trans['CurrentPrice']; ?><br/>
                         <?php if($trans['buyitnow']){ ?>
                         <img src="<?php echo $item['buyitnow']; ?>" />
                         <?php } ?>
                         <br/>
                         <?php echo $trans['ShippingServiceCost']; ?>
                     </td>
                     <td>
                         <?php if($trans['SellerPaidStatus']=='MarkedAsPaid' OR $trans['SellerPaidStatus']=='PaidWithPayPal' OR $trans['SellerPaidStatus']=='Paid' ){ ?>
                                  Tak
                         <?php }else{ ?>
                                Nie
                         <?php } ?>
                     </td>
                     <td>
                         <?php if($trans['ShippedTime']!==NULL){ ?>
                         Tak
                         <?php }else{ ?>
                         Nie
                         <?php } ?>
                     </td>
                     <td>
                         <?php if($trans['FeedbackLeft']!==NULL){ ?>
                         Tak
                         <?php }else{ ?>
                         Nie
                         <?php } ?>
                     </td>
                     <td>
                         <?php if($trans['FeedbackReceived']!==NULL){ ?>
                         Tak
                         <?php }else{ ?>
                         Nie
                         <?php } ?>
                     </td>
                     <td>
                         <!-- @todo akcje -->
                         <a href="<?php echo $trans['resell']; ?>" >Wystaw ponownie</a>
                     </td>
                 </tr>
            <?php } ?>
         <?php } ?>

         <?php if($orders){ ?>
            <?php foreach($orders as $order){ ?>
             <?php if($order['Total_items']){ ?>
                  <tr class="scalone">
                       <td>Ilość przedmiotów: <?php echo $order['Total_items']; ?></td>

                       <td><?php echo $order['Total']; ?></td>

                     <!-- @todo data zakończenia transakcji -->
<td colspan="4"></td>
                      <td><a href="<?php echo $order['order_details']; ?>"  >Szczeguły</a></td>
					  
                  </tr>
                  <?php foreach($order['items'] as $trans){ ?>
        <tr>
            <td>
                <a href="<?php echo $trans['ViewItemURL']; ?>" ><?php echo $trans['Title']; ?></a><br/>
                <?php echo $trans['UserID']; ?><br/>
            </td>
            <td>
                <?php  echo $trans['CurrentPrice']; ?><br/>
                <?php if($trans['buyitnow']){ ?>
                <img src="<?php echo $item['buyitnow']; ?>" />
                <?php } ?>
                <br/>
                <?php echo $trans['ShippingServiceCost']; ?>
            </td>
            <td>
                <?php if($trans['SellerPaidStatus']=='MarkedAsPaid' OR $trans['SellerPaidStatus']=='PaidWithPayPal' OR $trans['SellerPaidStatus']=='Paid' ){ ?>
                Tak
                <?php }else{ ?>
                Nie
                <?php } ?>
            </td>
            <td>
                <?php if($trans['ShippedTime']!==NULL){ ?>
                Tak
                <?php }else{ ?>
                Nie
                <?php } ?>
            </td>
            <td>
                <?php if($trans['FeedbackLeft']!==NULL){ ?>
                Tak
                <?php }else{ ?>
                Nie
                <?php } ?>
            </td>
            <td>
                <?php if($trans['FeedbackReceived']!==NULL){ ?>
                Tak
                <?php }else{ ?>
                Nie
                <?php } ?>
            </td>
            <td>
                <!-- @todo akcje -->
                <a href="<?php echo $trans['resell']; ?>" >Wystaw ponownie</a>
            </td>
        </tr>
                  <?php } ?>
        <?php } ?>
         <?php } ?>
        <?php } ?>
    </table>
</div>
</td></tr></table>
<?php echo $footer; ?>