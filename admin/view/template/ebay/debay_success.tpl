<?php echo $header; ?>

   <div id="content">
             
			 <?php if(!isset($error)){ ?>
			      Aukcja została pomyslnie wystawiona!
			      <table>
				      <tr>
					    <td>Id aukcji</td><td><?php echo $auction_id; ?><td>
						
			          </tr>
					 <!--  <tr>
					    <td>Czas rozpoczęcia</td><td><?php echo $start_time; ?><td>
			          </tr>
					   <tr>
					    <td>CZas zakończenia</td><td><?php echo $end_time; ?><td>
			          </tr> -->
					  <tr>
					     <td>Opłaty:</td>
					  </tr>
					  <?php foreach($fees as $fee){ ?>
					  
					    <tr>
						   <td><?php echo $fee['name']; ?></td><td><?php echo $fee['amount']; ?></td>
						</tr>
					  <?php } ?>
					  <tr>
					     <td><?php echo $total; ?></td>
					  </tr>

					  
						
			       </table>
				   
				   
			 <?php }else{ ?>
 
                  <div><?php echo $error; ?></div>
            <?php } ?>
   </div>


<?php echo $footer; ?>