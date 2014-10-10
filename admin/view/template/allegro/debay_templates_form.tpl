<?php echo $header; ?>

   <div id="content">

  <?php  if(isset($error_msg)){ echo $error_msg; }?>

       <form method="post" action="<?php echo $debay_config_action;?>" >
       <?php $i=true; ?>
       <table style="display:block">
            <?php foreach($shipping_services as $service){ ?>

         <?php if($i){ echo '<tr>'; } ?>
              <td>    <label for="debay_shipping_<?php echo $service->ShippingService; ?>" >Metoda wysyłki</label> </td>
               <td>   <input type="text" value="<?php echo $service->Description; ?>" name="debay_shipping_<?php echo $service->ShippingService; ?>" disabled="disabled" /></td>
               <td>   <label for="debay_shipping_cost_<?php echo $service->ShippingService; ?>" >Koszt wysyłki</label></td>
               <td>   <input type="text" value="<?php echo $service->Cost; ?>" name="debay_shipping_cost_<?php echo $service->ShippingService; ?>" /></td>

           <?php if(!$i){ echo '</tr>'; } ?>
           <?php if($i){ $i=false; }else{ $i=true; }            } ?>

          <tr>
              <label for="debay_PaymentInstructions" >Instrukcje płatności, wyświetlą się na stronie ebay po dokonaniu zakupu</label>
              <textfield  name="debay_PaymentInstructions" value="<?php echo $debay_PaymentInstructions ?>" >

              </textfield>
          </tr>
       </table>

       <div>
           Polityka zawrotów
       </div>
        <table>
            <tr>
                <td>
                    <label for="debay_ReturnsAccepted">Czy przymujesz zwroty?</label>
                    <?php if($debay_ReturnsAccepted){ ?>
                    <input type="checkbox" name="debay_ReturnsAccepted" value="1" checked="checked" >Akceptuj zwroty
                    <?php }else{ ?>
                    <input type="checkbox" name="debay_ReturnsAccepted" value="1"  >Akceptuj zwroty
                    <?php } ?>
                </td>
                <td>
                    <label for="debay_ReturnsWithinOption">Ile dni na zwrot?</label>
                    <select name="debay_ReturnsWithinOption"  >
                          <?php foreach($return_duration_codes as $key => $code){ ?>
                               <?php if($key == $debay_ReturnsWithinOption ){ ?>
                                   <option value="<?php echo $key ?>" selected="selected" ><?php echo $code; ?></option>
                               <?php }else{ ?>
                        <option value="<?php echo $key ?>"  ><?php echo $code; ?></option>

                               <?php } ?>
                          <?php } ?>
                    </select>
                </td>
                <td>
                    <label for="debay_Description">Instrukcje do zwrotu</label>
                    <textarea name="debay_Description"  >
                        <?php echo $debay_Description; ?>
                    </textarea>
                </td>

            </tr>
			<!-- chwilowo wyłączone ze względu na problem z przekzaniem wielokrotnej płatności -->
         <!--   <tr>
                Metody płatności
            </tr>
            <tr>

                    <?php foreach($payment_methods as $key => $method){ ?>
                   <td><label for="debay_payment_method_<?php echo $key; ?>" ><?php echo $method; ?></label></td>
                   <td><input type="checkbox" value="<?php echo $key; ?>" name="debay_payment_method_<?php echo $key; ?>" <?php if($debay_payment_method[$key]){ echo 'checked="checked"'; } ?>  /></td>
                    <?php } ?>
                   <td>
                       <label for="debay_paypal_email" >Adres email PayPal</label>
                       <input type="text" name="debay_paypal_email" value="<?php echo $debay_paypal_email; ?>" />
                   </td>
            </tr> -->
        </table>

       <input type="submit" value="Zapisz" />
       </form>
   </div>


<?php echo $footer; ?>