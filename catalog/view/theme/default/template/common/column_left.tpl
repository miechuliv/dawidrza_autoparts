<?php if ($modules) { ?>
  <div id="column-left">
  <?php if(Utilities::isController('product/category')) { ?>
  <div class="kotwica"></div>
	<div class="mobileshow">
		<div class="table dwa full">
			<div class="center paddingrighr">
				<span id="mobilekategoriebut"><?php echo $this->language->get('show_categories'); ?> <i class="fa fa-times"></i></span>
			</div>
			<div class="center paddingleft">
				<span id="mobilefiltrybut"><?php echo $this->language->get('show_filters'); ?> <i class="fa fa-times"></i></span>
			</div>
		</div>
	</div>
	<?php } ?>
	  <?php foreach ($modules as $module) { ?>
	  <?php echo $module; ?>
	  <?php } ?>
  </div>
<?php } ?>

<script>
	var kategoriebut = $('#mobilekategoriebut');
	var filtrybut = $('#mobilefiltrybut');
	var kategoriediv = $('#mobilekategorie');
	var filtrydiv = $('#mobilefiltry');	
	
	function posto(){
		 $('html, body').animate({
			scrollTop: $(".kotwica").offset().top
		}, 500);
	}
	
	function posto2(){
		 $('html, body').animate({
			scrollTop: $(".product-list").offset().top
		}, 500);
	}
	
	kategoriebut.click(function(){
		kategoriediv.toggleClass('mobileblock'); 
		$(this).toggleClass('wybrane');
		filtrybut.removeClass('wybrane');
		filtrydiv.removeClass('mobileblock');	
		posto();
	});
	
	filtrybut.click(function(){
		filtrydiv.toggleClass('mobileblock'); 
		$(this).toggleClass('wybrane');
		kategoriebut.removeClass('wybrane');
		kategoriediv.removeClass('mobileblock');
		posto();
	});
	
	function jumptomobile(){
		posto();
		kategoriediv.addClass('mobileblock');
		kategoriebut.addClass('wybrane');
		filtrybut.removeClass('wybrane');
		filtrydiv.removeClass('mobileblock');
	}	
	
	$('#extended_search > div h2').bind('click',function(){
		$(this).parent().find('.mobilehide').toggleClass('mobileshowinline');
	});
	
	$('.filter-cat label').bind('click',function(){
		posto2();
	});
	
</script>