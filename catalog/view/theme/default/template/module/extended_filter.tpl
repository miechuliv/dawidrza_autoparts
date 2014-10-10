<div class="box mobilehide" id="mobilefiltry">
    <div class="box-content">
    <form id="extended_search" method="get" action="<?php echo $action; ?>" >
        <?php /*
        <?php foreach($filters as $filter){ ?>
            <?php if($filter['type'] == 'option'){ ?>
            <div>
                <label for="filter_option[<?php echo $filter['id']; ?>]" ><?php echo $filter['name']; ?></label>
                <select name="filter_option[<?php echo $filter['id']; ?>]" >
                        <option></option>
                        <?php foreach($filter['values'] as $value){ ?>
                            <option <?php if($filter['selected'] == $value['option_value_id']){ echo 'selected="selected"';  } ?> value="<?php echo $value['option_value_id']; ?>" ><?php echo $value['name']; ?></option>
                        <?php } ?>
                </select>
            </div>
            <?php } ?>

        <?php if($filter['type'] == 'attribute'){ ?>
        <div>
            <label for="filter_attribute[<?php echo $filter['id']; ?>]" ><?php echo $filter['name']; ?></label>
            <select name="filter_attribute[<?php echo $filter['id']; ?>]" >
                <option></option>
                <?php foreach($filter['values'] as $value){ ?>
                <option <?php if($filter['selected'] == $value['text']){ echo 'selected="selected"';  } ?> value="<?php echo $value['text']; ?>" ><?php echo $value['text']; ?></option>
                <?php } ?>
            </select>
        </div>
        <?php } ?>
        */ ?>
		
		
        <?php foreach($filters as $filter){ ?>
		    <?php if($filter['type'] == 'price'){ ?>
				<div class="filter-price">
					<h2><?php echo $filter['name']; ?> <i class="fa fa-caret-down mobileshow mobileshowinline"></i></h2>
					<div class="mobilehide">
						<label for="filter_price_min" ><?php echo $this->language->get('text_price_min'); ?></label>
						<input name="filter_price_min" type="text" value="<?php echo $filter['min_current']; ?>" />
					</div>
					<div class="mobilehide">
						<label for="filter_price_max" ><?php echo $this->language->get('text_price_max'); ?></label>
						<input name="filter_price_max" type="text" value="<?php echo $filter['max_current']; ?>" />
					</div>
				</div> 
			<?php }else if($filter['type'] == 'option'){ ?>
				<div class="filter-cat">
					<h2><?php echo $filter['name']; ?> <i class="fa fa-caret-down mobileshow mobileshowinline"></i></h2>
					<div class="mobilehide">
					<?php foreach($filter['values'] as $value){ ?>
						<div>
							<input <?php if(in_array($value['option_value_id'],$filter['selected'])){ echo 'checked="checked"';  } ?>  type="checkbox" name="filter_option[<?php echo $filter['id']; ?>][]" id="filtr<?php echo $value['option_value_id']; ?>" value="<?php echo $value['option_value_id']; ?>" /><label for="filtr<?php echo $value['option_value_id']; ?>"><?php echo $value['name']; ?></label>
						</div>
					<?php } ?>
					</div>
				</div>
			<?php }else if($filter['type'] == 'attribute'){ ?>
				<?php if($filter['values']) { ?>
					<div class="filter-cat">
						<h2><?php echo $filter['name']; ?> <i class="fa fa-caret-down mobileshow mobileshowinline"></i></h2>
						<div class="mobilehide">
						<?php foreach($filter['values'] as $value){ ?>
							<div>
								<input <?php if(in_array($value['text'],$filter['selected'])){ echo 'checked="checked"';  } ?>  type="checkbox" name="filter_attribute[<?php echo $filter['id']; ?>][]" value="<?php echo $value['text']; ?>" id="filtr_<?php echo $value['text']; ?>" /><label for="filtr_<?php echo $value['text']; ?>"><?php echo $value['text']; ?></label>
							</div>
						<?php } ?>
						</div>
					</div>
				<? } ?>
			<?php } ?>
		<?php } ?>


        <?php /* <input type="submit" value="<?php echo $this->language->get('text_submit'); ?>" /> */ ?>
    </form>
    </div>
</div>
<script>

    $('#extended_search input').change(function(){
        searchExt();
    });


    <!-- ajax doładowanie wyników -->

    <?php if(isset($this->request->get['page'])){ ?>
        var page = <?php echo $this->request->get['page']; ?> + 1;
    <?php } else { ?>
        var page = 2;
    <?php } ?>

    $(document).ready(function(){
        $(window).scroll(function() {
		if(flaga_blokada)
		{
			return false;
		}
			var prodlist = $('.product-list').height() - 1500;


            //if($(window).scrollTop() >= prodlist && $(window).scrollTop() <= prodlist + 15) {
			if($(window).scrollTop() >= prodlist) {

			   
                loadMore();
				

            }
        });
    });
	var flaga_blokada = false;
    function loadMore()
    {
        url = '<?php echo $action; ?>';
		flaga_blokada = true;

        $('#extended_search input[type=\'checkbox\']').each(function(key,elem){
         
            if($(elem).prop('checked'))
            {
                url += '&'+$(elem).prop('name')+'='+$(elem).val();
            }
        });

        if($('input[name=\'filter_price_min\']').val() != undefined)
        {
            url += '&filter_price_min='+$('input[name=\'filter_price_min\']').val();
        }

        if($('input[name=\'filter_price_max\']').val() != undefined)
        {
            url += '&filter_price_max='+$('input[name=\'filter_price_max\']').val();
        }



        url += '&ajax=true';
        url += '&page='+page;

        $.ajax({
            url: url,
            type: 'get',
            dataType: 'json',
            success: function(json){

                if(json['result'] != 'no_result')
                {
                    $('.product-list').append(json['html']);
                    page++;

                   // $(window).scrollTop($(window).scrollTop()-100);
                }

                if(json['result'] != 'stop_loading')
                {
                    flaga_blokada = false;
                }

            }
        });
    }


    function searchExt()
    {
        url = '<?php echo $action; ?>';

        $('#extended_search input[type=\'checkbox\']').each(function(key,elem){
            console.log(elem);
            if($(elem).prop('checked'))
            {
                url += '&'+$(elem).prop('name')+'='+$(elem).val();
            }
        });

        url += '&filter_price_min='+$('input[name=\'filter_price_min\']').val();
        url += '&filter_price_max='+$('input[name=\'filter_price_max\']').val();

        url += '&ajax=true';

        $.ajax({
            url: url,
            type: 'get',
            dataType: 'json',
            success: function(json)
            {
                $('.product-list').html(json['html']);
            }

        })
    }
</script>
