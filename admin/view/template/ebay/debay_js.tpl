<script>
     $(document).ready(function(){

     // ładuje podkategorie dla wybranej głównej kategorii
     $('#main-category').change(function(){

             var category_id = $('#main-category').find(":selected").val();

         $.ajax({
             url: 'index.php?route=ebay/debayproduct/getsubcategory&token=<?php echo $this->session->data["token"]; ?>',
             dataType: 'json',
             type: 'post',
             data     : {
                 category_id : category_id

             },
             success: function(json) {
                 if(json['error'])
                 {
                        alert('brak kategorii do wyswietlenia');
                 }
                 else
                 {
                       html = '<select name="subcategory-1"  class="subcategory">';

                       html += '<option>Wybierz podkategorię</option>';

                     jQuery.each( json['categories'] , function(index, value) {

                         html+='<option value="'+value.CategoryID+'" >'+value.CategoryName+'</option>';
                     });

                     html +='</select>';

                     $('#sub-categories').html(html);

                     $('#atrybuty').empty();

                 }

             },
             error: function(xhr, ajaxOptions, thrownError) {
                 alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
             }
         });


     });

                 // ładuje podkategorie dla wybranej podkategorii
                 $('.subcategory').live('change',function(){

                     var category_id = $(this).find(":selected").val();

                     if(!$(this).is(':last-child'))
                     {

                         $(this).nextAll('.subcategory').remove();
                     }

                     $.ajax({
                         url: 'index.php?route=ebay/debayproduct/getsubcategory&token=<?php echo $this->session->data["token"]; ?>',
                         dataType: 'json',
                         type: 'post',
                         data     : {
                             category_id : category_id

                         },
                         success: function(json) {
                             if(json['error'])
                             {
                                 alert('brak kategorii do wyswietlenia');
                             }
                             else if(!json['empty'])
                             {
                                 var level = $('.subcategory').size()+1;

                                 html = '<select name="subcategory-'+level+'"  class="subcategory">';

                                 html += '<option>Wybierz podkategorię</option>';

                                 jQuery.each( json['categories'] , function(index, value) {

                                     html+='<option value="'+value.CategoryID+'" >'+value.CategoryName+'</option>';
                                 });

                                 html +='</select>';




                                 $('#sub-categories').append(html);

                                 $('#atrybuty').empty();

                             }else if(json['empty'])
                             {
                                   getattributes(category_id);
                             }

                         },
                         error: function(xhr, ajaxOptions, thrownError) {
                             alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                         }
                     });


                 });


             $('input[name=cena]').change(function(){

                            if($(this).val()=='kup-teraz')
                         {
                             $('#kup-teraz-cena').css('display','block');
                             $('#aukcja-cena').css('display','none');
                         }
                         if($(this).val()=='aukcja')
                         {
                             $('#kup-teraz-cena').css('display','none');
                             $('#aukcja-cena').css('display','block');
                         }

                     }
             )
			 
			 $('body').ajaxStart(function(){
			 
			         $('#ajax-wait').css('display','block');
			 })
			 
			  $('body').ajaxStop(function(){
			 
			         $('#ajax-wait').css('display','none');
			 })

             }
     );

     function getattributes(category_id)
     {
         $.ajax({
             url: 'index.php?route=ebay/debayproduct/getattributes&token=<?php echo $this->session->data["token"]; ?>',
             dataType: 'json',
             type: 'post',
             data     : {
                 category_id : category_id

             },
             success: function(json) {
                 if(json['error'])
                 {
                     alert('brak atrybutów do wyświetlenia');
                 }
                 else if(!json['empty'])
                 {





                     html = '<table>';


                     var i = 6;

                     var x = 7;

                     jQuery.each( json['attributes'] , function(index, value) {

                         var rem = i % 6;
                         var rem2 = x % 6;

                         if(rem==0)
                         {
                             html += '<tr>'
                         }

                              html +='<td>'+value+'</td>';

                         if(rem2==0)
                         {
                             html += '</tr>'
                         }


                          i++;
                          x++;
                     });

                     var rem2 = (x-1) % 6;

                     if(rem2!=0)
                     {
                         html += '</tr>';
                     }


                     html +='</table>';

					 
					 
                     $('#atrybuty').html(html);
                     $('#atrybuty').css('display','block');


                 }else if(json['empty'])
                 {
                       alert('Brak atrybutów dla wybranej kategorii');
                 }

             },
             error: function(xhr, ajaxOptions, thrownError) {
              //   alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
             }
         });
     }
	 
	 
	 function validate()
	 {
	 
	           $.ajax({
             url: 'index.php?route=ebay/debayproduct/ajaxvalidate&token=<?php echo $this->session->data["token"]; ?>',
             dataType: 'json',
             type: 'post',
             data     : $('input[type=\'text\'], textarea, select, input[type=\'checkbox\'], input[type=\'radio\']:checked, input[type=\'password\'], input[type=\'hidden\']'),
                   beforeSend: function(){
                       $('input[name=\'Title\']').next('.eba_error').empty();
                       $('input[name=\'BuyItNowPrice\']').next('.eba_error').empty();
                       $('input[name=\'StartPrice\']').next('.eba_error').empty();
                       $('input[name=\'ReservePrice\']').next('.eba_error').empty();
                       $('input[name=\'Quantity\']').next('.eba_error').empty();
                       $('#sub-categories').next('.eba_error').empty();
                       $('select[name=\'category\']').find('.eba_error').empty();
                   },
             success: function(json) {
                 if(json['error'])
                 {
				 
				    if(json['error']['Title'])
					{
					    $('input[name=\'Title\']').after('<div class="eba_error" >'+json['error']['Title']+'</div>');
					}
					
					if(json['error']['BuyItNowPrice'])
					{
					    $('input[name=\'BuyItNowPrice\']').after('<div class="eba_error" >'+json['error']['BuyItNowPrice']+'</div>');
					}
				    
                     if(json['error']['StartPrice'])
					{
					    $('input[name=\'StartPrice\']').after('<div class="eba_error" >'+json['error']['StartPrice']+'</div>');
					}
					
					 if(json['error']['ReservePrice'])
					{
					    $('input[name=\'ReservePrice\']').after('<div class="eba_error" >'+json['error']['ReservePrice']+'</div>');
					}
					
					 if(json['error']['Quantity'])
					{
					    $('input[name=\'Quantity\']').after('<div class="eba_error">'+json['error']['Quantity']+'</div>');
					}
					
					 if(json['error']['subcategory'])
					{
					    $('#sub-categories').after('<div class="eba_error" >'+json['error']['subcategory']+'</div>');
					}
					
					 if(json['error']['category'])
					{
					    $('select[name=\'category\']').after('<div class="eba_error" >'+json['error']['category']+'</div>');
					}

                 }
                 else
                 {


				              $.ajax({
             url: 'index.php?route=ebay/debayproduct/add&token=<?php echo $this->session->data["token"]; ?>',
             dataType: 'json',
             type: 'post',
             data     : $('input[type=\'text\'], textarea, select, input[type=\'checkbox\'], input[type=\'radio\']:checked, input[type=\'password\'], input[type=\'hidden\']'),
             success: function(json) {
                 if(!json['response']['success'])
                 {

                     if(json['response']['error'])
                     {
                         alert('Wystapił błąd: '+ json['response']['error']);
                     }
                     else
                     {
                         alert('Wystapił nieznany błąd aplikacji');
                     }



                 }
                 else
                 {

				    // todo pokazać koszta aukcji i przycisk do wyslania
                     alert('werfykacja pomyslna!');

                     html = '<table>';
                     html += '<tr><td colspan="2">Koszty wystawienia aukcji</td></tr>'
                     jQuery.each( json['response']['fees'] , function(index, value) {

                         html+='<tr><td>'+value['name']+'</td><td>'+value['amount']+'</td></tr>';
                     });

                     html += '<tr><td colspan="2">Koszt całkowity</td></tr>';

                     html += '<tr><td colspan="2">'+json['response']['total']+'</td></tr>';

                     html += '<tr><td colspan="2" ><a class="button" href="javascript:void(0)" onclick="finalize()">Aby wystawić przedmiot kliknij tutaj</a></td></tr>';
                     
                     $('#confirm-box').html(html);
                     $('#confirm-box').css('display','block');
                 }

             },
             error: function(xhr, ajaxOptions, thrownError) {
                // alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                 alert('Wystapił nieznany błąd aplikacji');
             }
         });
                    

                 }

             },
             error: function(xhr, ajaxOptions, thrownError) {
                 alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
             }
         });
	 
	 }


     function finalize(){
         $('#real').val('true');
         $('#debay-form').submit();

     }
</script>
