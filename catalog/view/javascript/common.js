$(document).ready(function() {

	$('a.cofka').click(function(){
        parent.history.back();
        return false;
	});

    /* Search */
    $('.button-search').bind('click', function() {
        //url = $('base').attr('href') + 'index.php?route=product/search';

        var category = $('select[name=\'category_search\']').find('option:selected').val();

        if(category && category!='xxx')
        {
            var url = $('base').attr('href') + '_c'+category;
        }
        else
        {
            var url = $('base').attr('href') + 'index.php?route=product/category';
        }




        var search = $('input[name=\'search\']').val();



        var current = window.location.pathname;

        var params = getUrlVars(current);


        $.each(params,function(key,val){

            if(val!='route' && val!='search'  && params[val] != undefined)
            {
                url += '&'+val+'='+params[val];
            }

        });



        if (search) {

            url += '&search=' + encodeURIComponent(search);
        }




        location = url;
    });

    function getUrlVars()
    {
        var vars = [], hash;
        var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        for(var i = 0; i < hashes.length; i++)
        {

            hash = hashes[i].split('=');

            vars.push(hash[0]);
            vars[hash[0]] = hash[1];

        }
        return vars;
    }

    $('#header input[name=\'search\']').bind('keydown', function(e) {
        if (e.keyCode == 13) {
            //url = $('base').attr('href') + 'index.php?route=product/search';

            var category = $('select[name=\'category_search\']').find('option:selected').val();

            if(category && category!='xxx')
            {
                var url = $('base').attr('href') + '_c'+category;
            }
            else
            {
                var url = $('base').attr('href') + 'index.php?route=product/category';
            }


            var search = $('input[name=\'search\']').val();

            var current = window.location.pathname;

            var params = getUrlVars(current);


            $.each(params,function(key,val){

                if(val!='route' && val!='search'  && params[val] != undefined)
                {
                    url += '&'+val+'='+params[val];
                }

            });



            if (search) {

                url += '&search=' + encodeURIComponent(search);
            }

            location = url;
        }
    });
	
	/* Ajax Cart 
	$('#cart > .heading a').live('click', function() {
		$('#cart').addClass('active');
		
		$('#cart').load('index.php?route=module/cart #cart > *');
		
		$('#cart').live('mouseleave', function() {
			$(this).removeClass('active');
		});
	});
	*/

	
	$('.success img, .warning img, .attention img, .information img').on('click', function() {
		$(this).parent().fadeOut('slow', function() {
			$(this).remove();
		});
	});	
});

function getURLVar(key) {
	var value = [];
	
	var query = String(document.location).split('?');
	
	if (query[1]) {
		var part = query[1].split('&');

		for (i = 0; i < part.length; i++) {
			var data = part[i].split('=');
			
			if (data[0] && data[1]) {
				value[data[0]] = data[1];
			}
		}
		
		if (value[key]) {
			return value[key];
		} else {
			return '';
		}
	}
} 

function addToCart(product_id, quantity) {
	quantity = typeof(quantity) != 'undefined' ? quantity : 1;


	$.ajax({
		url: 'index.php?route=checkout/cart/add',
		type: 'post',
		data: 'product_id=' + product_id + '&quantity=' + quantity,
		dataType: 'json',
		success: function(json) {
			$('.success, .warning, .attention, .information, .error').remove();
			
			if (json['redirect']) {
				location = json['redirect'];
			}
			
			if (json['success']) {

                $('#black').css('display','block');

                html = cartNotify(json);

                $('#notification').html(html);

                $('.success').fadeIn('slow');

                $('#cart-total').html(json['total']);



			}	
		}
	});
}

function kill() {
    $('#black').css('display','none');
    $('#notification #notification-container').css('display','none');
}


function addToWishList(product_id) {
	$.ajax({
		url: 'index.php?route=account/wishlist/add',
		type: 'post',
		data: 'product_id=' + product_id,
		dataType: 'json',
		success: function(json) {
			$('.success, .warning, .attention, .information').remove();
						
			if (json['success']) {
				$('#notification').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

				$('.success').fadeIn('slow');

				$('#wishlist-total').html(json['total']);

				$('html, body').animate({ scrollTop: 0 }, 'slow');
			}	
		}
	});
}

function addToCompare(product_id) { 
	$.ajax({
		url: 'index.php?route=product/compare/add',
		type: 'post',
		data: 'product_id=' + product_id,
		dataType: 'json',
		success: function(json) {
			$('.success, .warning, .attention, .information').remove();
						
			if (json['success']) {
				$('#notification').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
				
				$('.success').fadeIn('slow');
				
				$('#compare-total').html(json['total']);
				
				$('html, body').animate({ scrollTop: 0 }, 'slow'); 
			}	
		}
	});
}



function ReplaceImageOnHover(product_id) {

    $('#'+product_id+'_first').addClass('DisplayOff');
    $('#'+product_id+'_second').removeClass('DisplayOff');
    $('#'+product_id+'_second').addClass('DisplayOn')
}

function ReplaceImageOnHoverOut(product_id) {
    $('#'+product_id+'_first').removeClass('DisplayOff');
    $('#'+product_id+'_second').removeClass('DisplayOn');
    $('#'+product_id+'_second').addClass('DisplayOff')
}

function ZoomGallery(popup,middle) {

    $('#zoom1').attr('href',popup) ;
    $('#image').attr('src',middle) ;
    $('#zoom1').CloudZoom() ;
}

function cartNotify(json)
{

window.location = json['redirect'];

  /*  var title = json['title'];
    var thumb = json['thumb'];
    var text = json['success'];
    var price = json['price'];
    var total = json['total'];


    var continue_shop = json['continue'];
    var see_cart = json['see_cart'];

    var html = '<div id="notification-container">';
    html +=  '<div id="thumb-template">';
    html +=   '<a class="ui-notify-cross ui-notify-close s_button_remove"  href="javascript:;" onclick="kill();" style="margin-right:10px;">x</a>';
    html +=   '<h2 class="s_icon_24">'+title+'</h2>';
    html +=   '<div class="s_item s_size_1 clearfix" style="background:#fff;">';
    html +=     '<a class="s_thumb" href=""><img src="'+thumb+'" /></a>';
    html +=     '<h3>'+text+'</h3>';
    html +=     '<h4>'+price+'</h4>';
    html +=     '<h5>'+total+'</h5>';
    html +=    '</div>';
    html += '<div class="navu"><a class="butti konti" href="javascript:;" onclick="kill();">'+continue_shop+'</a> <a class="butti" href="./index.php?route=checkout/cart"><span></span>'+see_cart+'</a></div></div>';
  /*  html += '<div id="nothumb-template">';
    html +=   '<a class="ui-notify-cross ui-notify-close s_button_remove" href="javascript:;">x</a>';
    html +=   '<h2 class="s_icon_24">'+title+'</h2>';
    html +=   '<div class="s_item s_size_1 clearfix">';
    html +=     '<h3>'+text+'</h3>';
    html +=   '</div>';
    html += '</div>'; 
    html +='</div>';

    return html;*/
}