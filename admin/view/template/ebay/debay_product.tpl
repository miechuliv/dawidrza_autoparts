<?php echo $header; ?>
<?php echo $debayjs; ?>
<div id="content">
   <form method="post" action="<?php echo $debay_product_add_action; ?>" id="debay-form">
     <div id="lista" style="width:100%" class="procent15">
           <table>
		   <tr class="scalone">
			<td>Rodzaj sprzedaży</td>
			<td>Nazwa produktu</td>
			<td>Czas trwania aukcji</td>
			<td>Czas wysyłki</td>
			<td>Stan przedmiotu</td>
			<td>Ilość</td>
			<td>Szablon</td>
		   </tr>
		   <tr>
		   <td style="text-align:left;">
              <!-- opisem bedzie template -->

              <input type="radio" value="kup-teraz" name="cena" checked="checked" />Kup teraz
              <div id="kup-teraz-cena" style="margin:5px; float:left; width:100%;" >
                   <label for="BuyItNowPrice">Cena kup teraz</label>
                   <input type="text" name="BuyItNowPrice" value="<?php if($BuyItNowPrice1=0){ echo $BuyItNowPrice; } ?>" />
              </div>
              <input type="radio" value="aukcja" name="cena" />Aukcja

         <div id="aukcja-cena" style="display:none; margin:5px; float:left; width:100%;" >
             <label for="StartPrice">Cena poczatkowa</label>
             <input type="text" name="StartPrice" value="<?php echo $StartPrice; ?>" /><br/>
             <label for="ReservePrice">Cena minimalna</label>
             <input type="text" name="ReservePrice" value="<?php echo $ReservePrice; ?>" />
         </div>
		 </td><td>
         <div id="nazwa">
    
              <input type="text" value="<?php echo $Title; ?>" name="Title"/>
              <input type="hidden" value="<?php echo $product_id; ?>" name="product_id" />
         </div>
		 </td><td>
         <div id="czas-aukcji">

             <select name="ListingDuration">
                 <?php foreach($duration_codes as $key => $code){ ?>
                 <option value="<?php echo $key; ?>" ><?php echo $code; ?></option>
                 <?php } ?>
             </select>
         </div>
		 </td><td>
         <div id="czas-wysylki">

             <select name="DispatchTimeMax">
                 <?php foreach($disptach_time_codes as $code){ ?>
                 <?php if(isset($dispatch_code_translate[$code->DispatchTimeMax])){ ?>
                 <option value="<?php echo $code->DispatchTimeMax; ?>" ><?php echo $dispatch_code_translate[$code->DispatchTimeMax]; ?></option>
                 <?php }else{ ?>
                 <option value="<?php echo $code->DispatchTimeMax; ?>" ><?php echo $code->Description; ?></option>
                 <?php } ?>
                 <?php } ?>
             </select>
         </div>
		 </td><td>
         <div id="stan">


             <select name="ConditionID">
                 <?php foreach($conditions as $key => $condition){ ?>
                 <option value="<?php echo $key; ?>" ><?php echo $condition; ?></option>
                 <?php } ?>
             </select>
         </div>
		 
		 </td><td>
		 <div>

              <input type="text" value="1" name="Quantity"/>
		 
		 </div>
		 </td><td>
		 <div>
		    <?php foreach($templates as $key => $template){ ?>
			  <?php if($key==0){ ?>
			  <input type="radio" name="template" value="<?php echo $template['name']; ?>" checked="checked" /><?php echo $template['title']; ?>
			  <?php }else{ ?>
			  <input type="radio" name="template" value="<?php echo $template['name']; ?>" /><?php echo $template['title']; ?>
			  <?php } ?><br/>
 			  <a href="<?php echo $template['href']; ?>" target="_blank">podgląd szablonu</a>
			
		    <?php } ?>
		 </div>
</td></tr></table>


     </div>
     <div id="kategorie">
          <label for="category" ><strong>Główna kategoria</strong></label>
          <select name="category" id="main-category">
		     <option value="none" >Wybierz kategorię</option>
              <?php foreach($main_categories as $main){ ?>
                   <option value="<?php echo $main->CategoryID; ?>" ><?php echo $main->CategoryName; ?></option>
              <?php } ?>
          </select>
          <div id="sub-categories">

          </div>
     </div>
     <div id="atrybuty" style="display:none;">

     </div>
     <div id="dodatkowe" style="clear:both;">

     </div>
	 <input type="hidden" value="false" name="real" checked="checked" id="real"/>
      <a href="javascript:void(0)" class="button" onclick="validate()">Oblicz koszt wystawienia</a>
	  
   </form>
    <div id="confirm-box" style="display:none;">

    </div>
</div>

<div id="ajax-wait"  ><h2>Proszę czekać, trwa przetwarzanie danych</h2></div> 
<?php echo $footer; ?>