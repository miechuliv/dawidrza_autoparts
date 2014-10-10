<?php echo $header; ?>

<?php if ($error_warning) { ?>

<div class="warning"><?php echo $error_warning; ?></div>

<?php } ?>

<?php if ($success) { ?>

<div class="success"><?php echo $success; ?></div>

<?php } ?>

<style>

    table.list td { padding: 5px; }

</style>

<div class="box">

  <div class="left"></div>

  <div class="right"></div>

  <div class="heading">

    <h1 style="background-image: url('view/image/product.png');"><?php echo $heading_title; ?></h1>

    <div class="buttons">

        <a onclick="$('form').submit();" class="button">

            <span><?php echo $button_insert; ?></span>

        </a>

        <a onclick="location = '<?php echo $cancel; ?>'" class="button">

            <span><?php echo $button_cancel; ?></span>

        </a>

    </div>

  </div>

  <div class="content">

    <form action="<?php echo $insert; ?>" method="post" enctype="multipart/form-data" id="form">

        <input type="hidden" name="k[9-int]" value="1">

      <table class="list">

        <thead>

          <tr>

            <!--<td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>-->

            <td class="left">Wystaw</td>

            <td class="left">Tytuł</td>

            <td class="left">Ilość</td>

            <td class="left">Kup Teraz</td>

            <td class="left">Wywoławcza</td>

            <td class="left">Minimalna</td>

            <td class="left">Długość</td>

            <td class="left">Zdjęcia</td>

            <td class="left">Szablon</td>

            <td class="left">Opcje</td>

          </tr>

        </thead>

        <tbody>

        <script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>

          <?php if ( isset($Products)) { ?>

                <?php foreach( $Products as $Product ) { ?>
                    
					<div id="" style="display:none"></div>	
                    <input type="hidden" name="p[<?php echo $Product['ProductId'];?>][model]" value="<?=$Product['ProductId'];?>">
        
                      <div id="desc<?php echo $Product['ProductId'];?>" style="display:none;position:absolute;top:30%;left:25%;">

                        <div style="text-align: center;background:#D3D3D3;border:#D3D3D3 solid 1px;font-size:13px;height:20px;vertical-align:middle">

                            <a href="<?php echo HTTP_SERVER;?>index.php?route=allegro/category&token=<?php echo $this->session->data['token'];?>&category_id=<?php echo $_GET['category_id'] ; ?>#" onclick="hidedesc('desc<?php echo $Product['ProductId'];?>')"><strong>zamknij edytor</strong></a>

                        </div>

                        <div><textarea name="p[<?php echo $Product['ProductId'];?>][24-string]"><?php echo $Product['Product']['description'] ; ?></textarea></div>

                        <script type="text/javascript"><!--

                        CKEDITOR.replace('p[<?php echo $Product['ProductId'];?>][24-string]', {

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

                            <td class="left"><input checked="" name="a[]" type="checkbox" value="<?php echo $Product['ProductId'];?>"></td>

                            <td class="left"><input name="p[<?php echo $Product['ProductId'];?>][1-string]" type="text" style="width: 100px;" value="<?php echo $Product['Product']['name']; ?>"></td>

                            <td class="left">

                                <input id="<?php echo $Product['ProductId'];?>-5-int" name="p[<?php echo $Product['ProductId'];?>][5-int]" type="text" style="width: 40px;" value="<?php echo $Product['Product']['quantity']; ?>"> 

                                <select name="p[<?php echo $Product['ProductId'];?>][28-int]" style="width: 50px;">

                                    <option value="0">szt</option>

                                    <option value="1">kompl</option>

                                    <option value="2">pary</option>

                                </select>

                            </td>

                            <td class="left">

                                <input id="<?php echo $Product['ProductId'];?>-8floatactive" onclick="floatactive(<?php echo $Product['ProductId'];?>,<?php echo $Product['Product']['quantity'];?>,<?php echo str_replace(array('$',' zl',' zł'),'',$Product['Product']['price']); ?>)" checked="" name="p[<?php echo $Product['ProductId'];?>][8floatactive]" type="checkbox" value="1">

                                <input id="<?php echo $Product['ProductId'];?>-8float" name="p[<?php echo $Product['ProductId'];?>][8-float]" type="text" style="width: 60px;" value="<?php if ( isset($Product['ProductSpecials'][0]['special']) ) { echo substr($Product['ProductSpecials'][0]['special'],0,-2); } else { echo str_replace(array(',','zł',' ','<spanclass="s_currencys_after"></span>'),array('.','','',''),$Product['Product']['price']); } ?>"></td>

                            <td class="left"><input disabled="" id="<?php echo $Product['ProductId'];?>-6-float" name="p[<?php echo $Product['ProductId'];?>][6-float]" type="text" style="width: 60px;" value="<?php if ( isset($Product['ProductSpecials'][0]['special']) ) { echo $Product['ProductSpecials'][0]['special']-2; } else { echo (int)str_replace(array(',','zł',' ','<spanclass="s_currencys_after"></span>'),array('.','','',''),$Product['Product']['price'])-0.01; } ?>"></td>

                            <td class="left"><input disabled="" id="<?php echo $Product['ProductId'];?>-7-float" name="p[<?php echo $Product['ProductId'];?>][7-float]" type="text" style="width: 60px;" value="<?php if ( isset($Product['ProductSpecials'][0]['special']) ) { echo $Product['ProductSpecials'][0]['special']-1; } else { echo str_replace(array(',','zł',' ','<spanclass="s_currencys_after"></span>'),array('.','','',''),$Product['Product']['price']); } ?>"></td>
							
							<script>
								$('#8floatactive').click(function(){
									if( $('#<?php echo $Product['ProductId'];?>-8floatactive').is(':checked') ) {
										$('#<?php echo $Product['ProductId'];?>-8float').removeAttr('disabled');
										$('#<?php echo $Product['ProductId'];?>-6-float').attr('disabled', true);
										$('#<?php echo $Product['ProductId'];?>-7-float').attr('disabled', true);
										$('#<?php echo $Product['ProductId'];?>-5-int').val('<?php echo $Product['Product']['quantity'];?>') ;
										$('#<?php echo $Product['ProductId'];?>-8float').val('<?php echo str_replace(array('$',' zl',' zł'),'',$Product['Product']['price']); ?>') ;             
									} else {
										$('#<?php echo $Product['ProductId'];?>-6-float').removeAttr('disabled');
										$('#<?php echo $Product['ProductId'];?>-7-float').removeAttr('disabled');
										$('#<?php echo $Product['ProductId'];?>-5-int').val('1') ;
									}
								});  
								$('#allegrotemplate').change(function(){
									$('#showtemplate').attr('href','<?php echo HTTP_SERVER;?>index.php?route=allegro/template&token=<?php echo $_GET['token'];?>&name='+$('#<?php echo $Product['ProductId'];?>-allegrotemplate option:selected').attr('title')+'&product_id=<?php echo $Product['ProductId'] ; ?>') ;
								});
								
							</script>
							
                            <td class="left">

                                <select name="p[<?php echo $Product['ProductId'];?>][4-int]" style="width: 60px;">

                                    <option value="0">3 dni</option>

                                    <option value="1">5 dni</option>

                                    <option value="2">7 dni</option>

                                    <option value="3">10 dni</option>

                                    <option value="4">14 dni (+ 50 gr)</option>

                                    <option selected="" value="5">30 dni (sklep)</option>

                                </select>

                            </td>

                            <td class="left">

                                <?php for( $i = 0 ; $i < count($Product['ProductImages']) ; $i++ ) { ?>

                                    <?php if ( $i == 0 ) { ?>

                                        <img src="<?php echo HTTP_IMAGE.str_replace(' ','%20',$Product['ProductImages'][$i]['image']); ?>" width="45"> 

                                        <input checked="" name="p[<?php echo $Product['ProductId'];?>][16-image]" type="checkbox" value="<?php echo urlencode(DIR_IMAGE.$Product['ProductImages'][$i]['image']); ?>">

                                    <?php } else { ?>

                                        <br><img src="<?php echo HTTP_IMAGE.str_replace(' ','%20',$Product['ProductImages'][$i]['image']); ?>" width="45"> 

                                        <input name="p[<?php echo $Product['ProductId'];?>][imgs<?php echo $i;?>]" type="checkbox" value="<?php echo urlencode(DIR_IMAGE.$Product['ProductImages'][$i]['image']); ?>">

                                    <?php } ?>

                                <?php } ?>

                            </td>

                            <td class="left">

                                <select onchange="allegrotemplate(<?php echo $Product['ProductId'];?>)" name="p[<?php echo $Product['ProductId'];?>][allegrotemplate]" id="<?php echo $Product['ProductId'];?>-allegrotemplate">

                                    <?php foreach( $Product['ProductTemplates'] as $Template ) { ?>

                                        <option title="<?php echo $Template['name']; ?>" value="<?php echo base64_encode($Template['value']); ?>"><?php echo $Template['title']; ?></option>

                                    <?php } ?>

                                </select>

                                <br>

                           

                                <a href="<?php echo HTTP_SERVER;?>index.php?route=allegro/template&token=<?php echo $this->session->data['token'];?>&name=<?php echo $Product['ProductTemplates'][0]['name']; ?>&product_id=<?php echo $Product['ProductId'] ; ?>" id="<?php echo $Product['ProductId'];?>-showtemplate" target="_blank">Podgląd Szablonu</a>

                                <br><br>

                                <a href="<?php echo HTTP_SERVER;?>index.php?route=allegro/category&token=<?php echo $this->session->data['token'];?>&category_id=<?php echo $_GET['category_id'] ; ?>#" onclick="showdesc('desc<?php echo $Product['ProductId'];?>')">Zmień Opis</a>

                            </td>

                            <td class="left">

                                <input name="p[<?php echo $Product['ProductId'];?>][15-int][]" value="1" type="checkbox"> Pogrubienie<br>

                                <input name="p[<?php echo $Product['ProductId'];?>][15-int][]" value="2" type="checkbox"> Miniaturka<br>

                                <input name="p[<?php echo $Product['ProductId'];?>][15-int][]" value="4" type="checkbox"> Podświetlenie<br>

                                <input name="p[<?php echo $Product['ProductId'];?>][15-int][]" value="8" type="checkbox"> Wyróżnienie<br>

                                <input name="p[<?php echo $Product['ProductId'];?>][15-int][]" value="16" type="checkbox"> Strona kat.<br>

                                <input name="p[<?php echo $Product['ProductId'];?>][15-int][]" value="32" type="checkbox"> Strona gł.<br>

                                <input name="p[<?php echo $Product['ProductId'];?>][15-int][]" value="64" type="checkbox"> Znak wodny<br><br>

                            </td>

                      </tr>

            <?php } ?>
                        <tr>
                            <td colspan="10">
                                <table class="list">
                                    <tr class="CategoryOptions" title="<?php echo $Product['ProductId'];?>">
                                        
                                    </tr>
                                </table>
                            </td>
                      </tr>
        </tbody>

      </table>

      <table class="list">

        <tr>

            <td><h3>Wybierz kategorię</h3></td>

        </tr>

        <tr>

            <td>

                <select id="cat-select">
                    
                    <option>Wybierz kategorię główną...</option>
                    
                    <?php foreach( $AllegroCategories as $Category ) { ?>
                        
                        <option value="<?=$Category['cat_id']?>"><?=$Category['cat_name']?></option>
                        
                    <?php } ?>

                </select>
                
                <select id="2-int" name="k[2-int]">
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
                                    	url:  '<?=$_SESSION['getcategoryoptions']?>kkk=1&allegro_category_id='+$('#2-int').val(),
                                    	success: function(msg){
                                            //var Data = $.parseJSON(msg);
                                            
                                            $(".CategoryOptions").html(msg) ;
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
                        	url:  'index.php?route=allegro/category/Checkandgetchildcatoptions&allegro_option_id='+OptionId+'&allegro_parent_value='+$(this).val()+'&allegro_category_id='+$('#2-int').val()+'&kkk=1&token=<?=$_GET['token']?>',
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

                    <td colspan="2"><h3>Opcje płatności i dostawy</h3></td>

                </tr>

                <tr>

                    <td>Województwo:</td>

                    <td>

                        <select name="k[10-int]">

                            <?php foreach( $AllegroStates as $AllegroState ) { ?>

                                <option <?php if($saved['10']==$AllegroState['state-id']){echo 'selected=""';}?> value="<?php echo $AllegroState['state-id'] ; ?>"><?php echo $AllegroState['state-name'] ; ?></option>

                            <?php } ?>

                        </select>

                    </td>

                </tr>

                <tr>

                    <td>Miasto:</td>

                    <td><input name="k[11-string]" type="text" value="<?php echo $saved['11'];?>"></td>

                </tr>

                <tr>

                    <td>Kod pocztowy:</td>

                    <td><input name="k[32-string]" type="text" value="<?php echo $saved['32'];?>"></td>

                </tr>

                <tr>

                    <td>Przesyłka:</td>

                    <td>

                        <select name="k[12-int]">

                            <option <?php if($saved['12']==1){echo 'selected=""';}?> value="1">Kupujący</option>

                            <option <?php if($saved['12']==0){echo 'selected=""';}?> value="0">Sprzedający</option>

                        </select>

                    </td>

                </tr>

                <tr>

                    <td>Odbiór osobisty:</td>

                    <td><input name="k[35-int][]" type="checkbox" value="1"></td>

                </tr>

                <tr>

                    <td>Odbiór osobisty po przedpłacie:</td>

                    <td><input name="k[35-int][]" type="checkbox" value="4"></td>

                </tr>

                <tr>

                    <td>Wysyłka email:</td>

                    <td><input name="k[35-int][]" value="2" type="checkbox"></td>

                </tr>

                <tr>

                    <td>Wystawiam faktury VAT:</td>

                    <td><input checked="" name="k[14-int][]" value="8" type="checkbox"></td>

                </tr>

                <tr>

                    <td>Wysyłam za granicę:</td>

                    <td><input name="k[13-int][]" value="32" type="checkbox"></td>

                </tr>

                <tr>

                    <td>Szczegóły wysyłki:</td>

                    <td><input name="k[13-int][]" value="16" type="checkbox"></td>

                </tr>

                <tr>

                    <td>Wpisz szczegóły wysyłki</td>

                    <td><textarea name="k[27-string]"><?php echo $saved['27'];?></textarea></td>

                </tr>

                <tr>

                    <td>Płatność przelewem:</td>

                    <td><input checked="" name="k[14-int][]" value="1" type="checkbox"></td>

                </tr>

                <tr>

                    <td>Szczegóły w opisie:</td>

                    <td><input name="k[14-int][]" value="4" type="checkbox"></td>

                </tr>

              </table>

            </td>

            <td style="width: 32%;vertical-align:top;">

              <table class="list">

                <tr>

                    <td colspan="3"><h3>Rodzaj i cena dostawy</h3></td>

                </tr>

                <?php foreach( $AllegroShipments as $AllegroShipment ) { ?>

                    <tr>

                        <td><?php echo $AllegroShipment['Title'] ; ?></td>

                        <td><input value="<?php echo $saved[$AllegroShipment['FormId']];?>" name="k[<?php echo $AllegroShipment['FormId'].'-'.$AllegroShipment['FormType']; ?>]" type="text" style="width: 80px;"></td>

                        <td>PLN</td>

                    </tr>

                <?php } ?>     

              </table>

            </td>

            <td style="width: 32%;vertical-align:top;">

              <table class="list">

                <tr>

                    <td colspan="2"><h3>Opcje dodatkowe</h3></td>

                </tr>

                <tr>

                    <td>Data rozpoczęcia aukcji:</td>

                    <td><input name="k[3-datetime]" type="text" value="<?php echo date('d/m/Y') ; ?>"></td>

                </tr>

                <tr>

                    <td>Mój numer konta:</td>

                    <td><input name="k[33-string]" type="text" value="<?php echo $saved['33'];?>"></td>

                </tr>

                <tr>

                    <td>Sklep na Allegro.pl:</td>

                    <td>

                        <select id="29-int" name="k[29-int]">

                            <option value="0">nie</option>

                            <option value="1">tak</option>

                        </select>

                </tr>

                <tr>

                    <td>Automatyczne wznowienie oferty:</td>

                    <td>

                        <select name="k[30-int]">

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

<?php echo $footer; ?>