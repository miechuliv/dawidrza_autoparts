<?php echo $header; ?>


<script type="text/javascript">

    $('#8floatactive').click(function(){
        if( $('#8floatactive').is(':checked') ) {
            $('#8float').removeAttr('disabled');
            $('#8float').css('color','#000000');
            $('#6-float').attr('disabled', true);
            $('#6-float').css('color','#cccccc');
            $('#7-float').attr('disabled', true);
            $('#7-float').css('color','#cccccc');
            $('#5-int').val('<?php echo $Product['quantity'];?>') ;
            $('#8float').val('<?php echo str_replace(array('$',' zl',' zł'),'',$Product['price']); ?>') ;
        } else {
            $('#8float').css('color','#cccccc');
            $('#6-float').removeAttr('disabled');
            $('#6-float').css('color','#000000');
            $('#7-float').removeAttr('disabled');
            $('#7-float').css('color','#000000');
            $('#5-int').val('1') ;
        }
    });
    $('#allegrotemplate').change(function(){
        $('#showtemplate').attr('href','<?php echo HTTP_SERVER;?>index.php?route=allegro/template&token=<?php echo $_GET['token'];?>&name='+$('#<?php echo $Product['product_id'];?>-allegrotemplate option:selected').attr('title')+'&product_id=<?php echo $Product['product_id'] ; ?>') ;
    });

    function pricetemplate(){
        var href = "<?php echo HTTP_SERVER;?>index.php?route=allegro/template&token=<?php echo $this->session->data['token'];?>&name=<?php echo $ProductTemplates[0]['name']; ?>&product_id=<?php echo $_GET['product_id'] ; ?>";
        var newhref = href+'&price='+$('input[name=8-float]').val();
        $('#showtemplate').attr('href',newhref);
    }

    $(document).ready(function(){
        pricetemplate();
    });



    $('input[name=8-float]').keyup(function(){
        pricetemplate();

    });

</script>

<?php if ($error_warning) { ?>

<div class="warning"><?php echo $error_warning; ?></div>

<?php } ?>

<?php if ($success) { ?>

<div class="success"><?php echo $success; ?></div>

<?php } ?>

<style>

    table.list td { padding: 5px; }

</style>
<div id="content">
<div class="breadcrumb">
        <a href="#">Start</a> :: <a href="#">Allegro</a>
      </div>
<div class="box">

  <div class="left"></div>

  <div class="right"></div>

  <div class="heading" style="background: #4c4c4c; /* Old browsers */
background: -moz-linear-gradient(top,  #4c4c4c 0%, #595959 12%, #666666 25%, #474747 39%, #2c2c2c 50%, #2b2b2b 76%, #1c1c1c 91%, #131313 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#4c4c4c), color-stop(12%,#595959), color-stop(25%,#666666), color-stop(39%,#474747), color-stop(50%,#2c2c2c), color-stop(76%,#2b2b2b), color-stop(91%,#1c1c1c), color-stop(100%,#131313)); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top,  #4c4c4c 0%,#595959 12%,#666666 25%,#474747 39%,#2c2c2c 50%,#2b2b2b 76%,#1c1c1c 91%,#131313 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top,  #4c4c4c 0%,#595959 12%,#666666 25%,#474747 39%,#2c2c2c 50%,#2b2b2b 76%,#1c1c1c 91%,#131313 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top,  #4c4c4c 0%,#595959 12%,#666666 25%,#474747 39%,#2c2c2c 50%,#2b2b2b 76%,#1c1c1c 91%,#131313 100%); /* IE10+ */
background: linear-gradient(to bottom,  #4c4c4c 0%,#595959 12%,#666666 25%,#474747 39%,#2c2c2c 50%,#2b2b2b 76%,#1c1c1c 91%,#131313 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#4c4c4c', endColorstr='#131313',GradientType=0 ); /* IE6-9 */
border:1px solid #2b2b2b;">

    <h1 style="background-image: url('view/image/product.png'); padding-left:30px; color:#fff;"><?php echo $heading_title; ?></h1>

    <div class="buttons" style="padding-top:6px;">

        <a onclick="$('form').submit();" class="button" style="background:#ff5a00;">

            <span style="background:#ff5a00;"><?php echo $button_insert; ?></span>

        </a>

        <a onclick="location = '<?php echo $cancel; ?>'" class="button" style="background:#ff5a00;">

            <span style="background:#ff5a00;"><?php echo $button_cancel; ?></span>

        </a>

    </div>

  </div>

  <div class="content">

    <form action="<?php echo $insert; ?>" method="post" enctype="multipart/form-data" id="form">

        <input type="hidden" name="9-int" value="1">

      <table class="list">

        <thead>

          <tr>

            <!--<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>-->

            <td class="left">Tytuł</td>

            <td class="left">Ilość</td>

            <td class="left">Kup Teraz</td>

            <td class="left">Wywoławcza</td>

            <td class="left">Minimalna</td>

            <td class="left">Długość</td>

           <td class="left" style="visibility=hidden;">Zdjęcia</td> 

            <td class="left">Szablon</td>

          </tr>

        </thead>

        <tbody>

        <script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>

          <?php if ($Product) { ?>

          <div id="desc<?php echo $Product['product_id'];?>" style="display:none;position:absolute;top:100px;left:25%;">

            <div style="text-align: center;background:#D3D3D3;border:#D3D3D3 solid 1px;font-size:13px;height:20px;vertical-align:middle">

                <a href="<?php echo HTTP_SERVER;?>index.php?route=allegro/product&token=<?php echo $this->session->data['token'];?>&product_id=<?php echo $_GET['product_id'] ; ?>#" onclick="hidedesc('desc<?php echo $Product['product_id'];?>')"><strong>zamknij edytor</strong></a>

            </div>

            <div><textarea name="24-string"><?php echo $Product['description'] ; ?></textarea></div>

            <script type="text/javascript"><!--

            CKEDITOR.replace('24-string', {

            	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $_GET['token']; ?>',

            	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $_GET['token']; ?>',

            	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $_GET['token']; ?>',

            	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $_GET['token']; ?>',

            	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $_GET['token']; ?>',

            	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $_GET['token']; ?>'

            });

            //--></script>

          </div>

          <tr>

                <!--<td style="text-align: center;"><?php /*if ($Product['selected']) { ?>

                    <input type="checkbox" name="selected[]" value="<?php echo $Product['product_id']; ?>" checked="checked" />

                    <?php } else { ?>

                    <input type="checkbox" name="selected[]" value="<?php echo $Product['product_id']; ?>" />

                    <?php } */?>

                </td>-->

                <td class="left"><input name="1-string" type="text" style="width: 290px;" value="<?php echo $Product['name']; ?>" maxlength="50"> (max 50 znaków)</td>

                <td class="left">

                    <input id="5-int" name="5-int" type="text" style="width: 40px;" value="<?php echo $Product['quantity']; ?>"> 

                    <select name="28-int" style="width: 50px;">

                        <option value="0">szt</option>
<?/*
                        <option value="1">kompl</option>

                        <option value="2">pary</option>
*/?>
                    </select>

                </td>

                <td class="left">

                    <input id="8floatactive" name="8floatactive" type="checkbox" value="1" checked="checked">

                    <input id="8float" name="8-float" type="text" style="width: 60px;" value="<?php if( isset($ProductSpecials[0]['special']) ) { echo $ProductSpecials[0]['special']; } else { echo str_replace(array(',','zł',' ',''),array('.','','',''),$Product['price']); } ?>" > PLN</td>

                <td class="left"><input id="6-float" name="6-float" type="text" style="width: 60px; color:gray;" value="<?php if (isset($ProductSpecials[0]['special'])) { echo $ProductSpecials[0]['special']-1; } else { echo (int)str_replace(array(',','zł',' ',''),array('.','','',''),$Product['price'])-0.01; } ?>" disabled="disabled"> PLN</td> 

                <td class="left"><input id="7-float" name="7-float" type="text" style="width: 60px; color:gray;" value="<?php if( isset($ProductSpecials[0]['special']) ) { echo $ProductSpecials[0]['special']; } else { echo str_replace(array(',','zł',' ',''),array('.','','',''),$Product['price']); } ?>" disabled="disabled"> PLN</td>
				

				
                <td class="left">

                    <select name="4-int" style="width: 60px;">

                        <option value="0">3 dni</option>

                        <option value="1">5 dni</option>

                        <option value="2">7 dni</option>

                        <option value="3">10 dni</option>

                        <option value="4">14 dni (wymaga dopłaty)</option>

                    <!-- <option value="5" selected="">30 dni (sklep)</option>  -->

                    </select>

                </td>

            <td class="left" style="visibility=hidden;">

                    <?php  for( $i = 0 ; $i < count($ProductImages) ; $i++ ) { ?>

                        <?php if ( $i == 0 ) { ?>

                            <img src="<?php echo HTTP_IMAGE.str_replace(' ','%20',$ProductImages[$i]['image']); ?>" width="45"> 

                            <input checked="" name="16-image" type="checkbox" value="<?php echo urlencode(DIR_IMAGE.$ProductImages[$i]['image']); ?>">

                        <?php } else { ?>

                            <br><img src="<?php echo HTTP_IMAGE.str_replace(' ','%20',$ProductImages[$i]['image']); ?>" width="45"> 

                            <input name="imgs<?php echo $i;?>" type="checkbox" value="<?php echo urlencode(DIR_IMAGE.$ProductImages[$i]['image']); ?>">

                        <?php } ?>

                    <?php } ?> 

                </td> 

                <td class="left">

                    <select name="allegrotemplate" id="allegrotemplate">

                        <?php foreach( $ProductTemplates as $Template ) { ?>

                            <option title="<?php echo $Template['name']; ?>" value="<?php echo base64_encode($Template['value']); ?>"><?php echo $Template['title']; ?></option>

                        <?php } ?>

                    </select>

                    <br>

               <br />

                    <a  href="<?php echo HTTP_SERVER;?>index.php?route=allegro/template&token=<?php echo $this->session->data['token'];?>&name=<?php echo $ProductTemplates[0]['name']; ?>&product_id=<?php echo $_GET['product_id'] ; ?>" id="showtemplate" target="_blank">Podgląd Szablonu</a>

                    <br />

                    <a href="<?php echo HTTP_SERVER;?>index.php?route=allegro/product&token=<?php echo $this->session->data['token'];?>&product_id=<?php echo $_GET['product_id'] ; ?>#" onclick="showdesc('desc<?php echo $Product['product_id'];?>')">Zmień Opis</a>

                </td>


          </tr>

          <tr style="height:1px;">
                <td colspan="8">
                   <div>
                    <div class="list CategoryOptions" >
                     <!--   <tr class="CategoryOptions" title="<?php echo $Product['product_id'];?>"> -->

                     <!--   </tr> -->
                    </div>
                    </div>
                </td>
          </tr>
					
					<tr>
					
					
                <td class="left" colspan="8" style="background:#ff5a00; color:#fff; border-top:1px solid #dd4e00; text-shadow:1px 1px 0 #dd4e00;">
<div style="float:left; margin-right:20px;"><strong>Opcje dodatkowe (<a href="http://allegro.pl/country_pages/1/0/z4.php" style="color:#fff;" target="_blank">zapoznaj się z cennikiem opcji dodatkowych</a>):</strong></div>
                    <div style="float:left; margin-right:20px;"><input name="15-int[]" value="1" type="checkbox" style="float:left;margin:1px 3px 0 0;">&nbsp; Pogrubienie</div>

                    <div style="float:left; margin-right:20px;"><input name="15-int[]" value="2" type="checkbox" style="float:left;margin:1px 3px 0 0;">&nbsp; Miniaturka</div>

                    <div style="float:left; margin-right:20px;"><input name="15-int[]" value="4" type="checkbox" style="float:left;margin:1px 3px 0 0;">&nbsp; Podświetlenie</div>

                    <div style="float:left; margin-right:20px;"><input name="15-int[]" value="8" type="checkbox" style="float:left;margin:1px 3px 0 0;">&nbsp; Wyróżnienie</div>

                    <div style="float:left; margin-right:20px;"><input name="15-int[]" value="16" type="checkbox" style="float:left;margin:1px 3px 0 0;">&nbsp; Strona kategorii</div>

                    <div style="float:left; margin-right:20px;"><input name="15-int[]" value="32" type="checkbox" style="float:left;margin:1px 3px 0 0;">&nbsp; Strona główna</div>

                 <? //   <div style="float:left; margin-right:20px;"><input name="15-int[]" value="64" type="checkbox" style="float:left;margin:1px 3px 0 0;">&nbsp; Znak wodny</div> ?>

                </td>
								
								</tr>

        </tbody>

      </table>

      <table class="list">

        <tr>

            <td style="background:#efefef;"><strong style="float:left; margin:3px;">Wybierz kategorię</strong></td>

        </tr>

        <tr>

            <td>

                <select id="cat-select">
                    
                    <option>Wybierz kategorię główną...</option>
                    
                    <?php foreach( $AllegroCategories as $Category ) { ?>
                        
                        <option value="<?=$Category['cat_id']?>"><?=$Category['cat_name']?></option>
                        
                    <?php } ?>

                </select>
                
                <select id="2-int" name="2-int">
                    <option>Wybierz subkategorię...</option>
                </select>
                
                <div id="CategoryOptionsInfo"></div>
                
                <script>

                    $('#cat-select').change(function() {

                        $('#CategoryOptionsInfo').html('Wczytywanie kategorii. Proszę czekać...') ;
                        
                        $.ajax({
                        	type: "get",
                        	url:  '<?=$_SESSION['getcategorybyparent']?>allegro_category_id='+$('#cat-select').val(),
                        	success: function(msg){                             
                                if ( msg != '<option>Wybierz subkategorię...</option>') {
                                    $("#2-int").html(msg) ;
                                    $('#CategoryOptionsInfo').html('') ;
                                }
                        	}
                    	});
                    });
                
                    $('#2-int').change(function() {
                        
                        $('#CategoryOptionsInfo').html('Wczytywanie kategorii. Proszę czekać...') ;
                        
                        $.ajax({
                        	type: "get",
                        	url:  '<?=$_SESSION['getcategorybyparent']?>allegro_category_id='+$('#2-int').val(),
                        	success: function(msg){
                                
                                if ( msg != '<option>Wybierz subkategorię...</option>') {
                                    $("#2-int").html(msg) ;
                                    $('#CategoryOptionsInfo').html('') ;
                                }
                                else {
                                    
                                    $('#CategoryOptionsInfo').html('Wczytywanie parametrów kategorii. Proszę czekać...') ;
                        
                                    $.ajax({
                                    	type: "get",
                                    	url:  '<?=$_SESSION['getcategoryoptions']?>allegro_category_id='+$('#2-int').val(),
                                    	success: function(msg){
                                            //var Data = $.parseJSON(msg);
                                           // alert('works');
										   
                                            $(".CategoryOptions").html(msg) ;
										//	$(".CategoryOptions").parent().parent().parent().parent().css('display','block');
											$(".CategoryOptionTitle").css('float','left');
											$(".CategoryOptionTitle").css('clear','left');
											$(".CategoryOption").css('float','left');
											$(".CategoryOption").css('clear','left');
                                            $('#CategoryOptionsInfo').html('Opcje dodatkowe wybranej kategorii zostały dodane do produktów') ;
                                    	}
                                	});
                                }
                        	}
                    	});
                    });
                    
                    $('.CategoryOption').live('change',function(){
                        
                        var OptionId = $(this).attr('title') ;
                        //var OptionId = OptionId[0] ;
                        
                        //$(this).attr('disabled',true) ;
                        
                        $.ajax({
                        	type: "get",
                        	url:  'index.php?route=allegro/category/Checkandgetchildcatoptions&allegro_option_id='+OptionId+'&allegro_parent_value='+$(this).val()+'&allegro_category_id='+$('#2-int').val()+'&kkk=0&token=<?=$_GET['token']?>',
                        	success: function(msg){
                                
                                if ( msg != '' ) {
                                    
                                    $(".CategoryOptions").append(msg) ;
                                    $('#CategoryOptionsInfo').html('Opcje dodatkowe wybranej kategorii zostały dodane do produktów') ;
                            	   
                                    
                                }
                        	}
                    	});
                    }) ;
                </script>

            </td>

        </tr>

      </table>

      <table style="width: 100%;">

        <tr>

            <td style="width: 32%;vertical-align:top;">

              <table class="list">

                <tr>

										 <td style="background:#efefef;" colspan="2"><strong style="float:left; margin:3px;">Opcje płatności i dostawy</strong></td>

                </tr>

                <tr>

                    <td>Województwo:</td>

                    <td>

                        <select name="10-int">

                            <?php foreach( $AllegroStates as $AllegroState ) { ?>

                                <option <?php if($saved['10']==$AllegroState['state-id']){echo 'selected=""';}?> value="<?php echo $AllegroState['state-id'] ; ?>"><?php echo $AllegroState['state-name'] ; ?></option>

                            <?php } ?>

                        </select>

                    </td>

                </tr>

                <tr>

                    <td>Miasto:</td>

                    <td><input name="11-string" type="text" value="<?php echo $saved['11'];?>"></td>

                </tr>

                <tr>

                    <td>Kod pocztowy:</td>

                    <td><input name="32-string" type="text" value="<?php echo $saved['32'];?>"></td>

                </tr>

                <tr>

                    <td>Przesyłka:</td>

                    <td>

                        <select name="12-int">

                            <option <?php if($saved['12']==1){echo 'selected=""';}?> value="1">Kupujący</option>

                            <option <?php if($saved['12']==0){echo 'selected=""';}?> value="0">Sprzedający</option>

                        </select>

                    </td>

                </tr>

                <tr>

                    <td>Odbiór osobisty:</td>

                    <td><input name="35-int[]" type="checkbox" value="1"></td>

                </tr>

                <tr>

                    <td>Odbiór osobisty po przedpłacie:</td>

                    <td><input name="35-int[]" type="checkbox" value="4"></td>

                </tr>

                <tr>

                    <td>Wysyłka email:</td>

                    <td><input name="35-int[]" value="2" type="checkbox"></td>

                </tr>

                <tr>

                    <td>Wystawiam faktury VAT:</td>

                    <td><input name="14-int[]" value="8" type="checkbox"></td>

                </tr>

                <tr>

                    <td>Wysyłam za granicę:</td>

                    <td><input name="13-int[]" value="32" type="checkbox"></td>

                </tr>

                <tr>

                    <td>Szczegóły wysyłki:</td>

                    <td><input name="13-int[]" value="16" type="checkbox"></td>

                </tr>

                <tr>

                    <td>Wpisz szczegóły wysyłki</td>

                    <td><textarea name="27-string"><?php echo $saved['27'];?></textarea></td>

                </tr>

                <tr>

                    <td>Płatność przelewem:</td>

                    <td><input checked="" name="14-int[]" value="1" type="checkbox"></td>

                </tr>

                <tr>

                    <td>Szczegóły w opisie:</td>

                    <td><input name="14-int[]" value="4" type="checkbox"></td>

                </tr>

              </table>

            </td>

            <td style="width: 32%;vertical-align:top;">

              <table class="list">

                <tr>

										<td style="background:#efefef;" colspan="3"><strong style="float:left; margin:3px;">Rodzaj i cena dostawy</strong></td>

                </tr>

                <?php foreach( $AllegroShipments as $AllegroShipment ) { ?>

                    <tr>

                        <td><?php echo $AllegroShipment['Title'] ; ?></td>

                        <td><input value="<?php echo $saved[$AllegroShipment['FormId']];?>" name="<?php echo $AllegroShipment['FormId'].'-'.$AllegroShipment['FormType']; ?>" type="text" style="width: 80px;"></td>

                        <td>PLN</td>

                    </tr>

                <?php } ?>     

              </table>

            </td>

            <td style="width: 32%;vertical-align:top;">

              <table class="list">

                <tr>
										<td style="background:#efefef;" colspan="2"><strong style="float:left; margin:3px;">Opcje dodatkowe</strong></td>

                </tr>

                <tr>

                    <td>Data rozpoczęcia aukcji:</td>

                    <td><input name="3-datetime" type="text" value="<?php echo date('d/m/Y') ; ?>"></td>

                </tr>

                <tr>

                    <td>Mój numer konta:</td>

                    <td><input name="33-string" value="<?php echo $saved['33'];?>" type="text"></td>

                </tr>

                <tr>

                    <td>Sklep na Allegro.pl:</td>

                    <td>

                        <select id="29-int" name="29-int">

                            <option value="0">nie</option>

                            <option value="1">tak</option>

                        </select>

                </tr>

                <tr>

                    <td>Automatyczne wznowienie oferty:</td>

                    <td>

                        <select name="30-int">

                            <option value="0" <?php if($saved['30']==0){echo 'selected=""';}?>>Nie wznawiaj</option>

                            <option value="1" <?php if($saved['30']==1){echo 'selected=""';}?>>Wznów z pełnym zestawem przedmiotów</option>

                            <option value="2" <?php if($saved['30']==2){echo 'selected=""';}?>>Wznów tylko z przedmiotami niesprzedanymi</option>

                        </select>

                    </td>

                </tr>

              </table>

            </td>

        </tr>

      </table>

      <?php } else { ?>

      <table class="list">

          <tr>

            <td class="center" colspan="6">brak produktów do wyświetlenia</td>

          </tr>

      </table>

      <?php } ?>

    </form>

  </div>
</div>
</div>

<?php echo $footer; ?>