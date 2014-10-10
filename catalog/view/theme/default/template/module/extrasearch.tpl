<div id="search-prawa">

    <ul>
        <form id="miechu_form" action="<?php echo $search_action; ?>" method="post">
            <table cellspacing="5" cellpadding="5">  <tr><td><h2 style="color:#555 !important; font-weight:bold; font-size:13px; margin-top:5px; padding:0;  font-family:arial;"><?php echo $text_price_search ?></h2></td></tr>
                <tr>
                    <td><div id="slider_price" style='width:192px'></div></td>

                    <td><input type="hidden" id="price_search"  value=""></td>


                </tr>
                <tr><td>
                        <div style="float:left; font-size:13px; position:relative;"><span style="position:absolute; right:10px; top:4px; line-height:23px;"><?php echo $symbol_currency; ?></span><input type="text" id="cena_min" class="sliderValue" data-index="0" style="width:85px;" name="filters[cena_min]" value="<?php if(isset($this->session->data['filters']['cena_min'])){ echo $this->session->data['filters']['cena_min']; }?>"></div><div style="float:left; font-size:13px; position:relative;"><span style="position:absolute; right:5px; top:4px; line-height:23px;"><?php echo $symbol_currency; ?></span><input type="text" id="cena_max" class="sliderValue" data-index="1" style="width:85px; margin-right:0;" name="filters[cena_max]" value="<?php if(isset($this->session->data['filters']['cena_max'])){ echo $this->session->data['filters']['cena_max']; }?>">
                        </div>
                    </td></tr>
            </table>
            <table>

                <?php foreach($options as $option){ ?>

                <?php if($option['type']=='select'){ ?>

                <tr><td><h2 style="color:#555 !important; font-weight:bold;font-size:13px; margin-top:5px; margin:0;  padding:0;  font-family:arial;"><?php echo $option['name'] ?></h2></td></tr>
                <tr><td><select id="<?php echo $option['name'] ?>" name="filters[options][<?php echo $option['name'] ?>]">
                            <option value="null"></option>
                            <?php foreach($option['option_value'] as $value){ ?>


                            <?php if($filters['options'][$option['name']]==$value['name']){ ?>
                            <option class="option_left"  value="<?php echo $value['name'] ?>" selected="selected"><?php echo $value['name']  ?></option>
                            <?php }else{ ?>
                            <option class="option_left"  value="<?php echo $value['name'] ?>"><?php echo $value['name'] ?></option>
                            <?php } ?>




                            <?php   } ?>
                        </select></td></tr>


                <?php   } ?>

                <?/* <?php if($option['type']=='radio'){ ?>

                <tr><td><?php echo $option['name'] ?></td></tr>


                <?php  foreach($option['option_value'] as $value){ ?>

                <tr><td><input type="radio" name="<?php echo $option['name'] ?>" value="<?php echo $value['name'] ?>"><?php echo $value['name'] ?></option></td></tr>
                <?php   } ?>

                <?php   } ?>

                <?php if($option['type']=='checkbox'){ ?>
                <tr><td><?php echo $option['name'] ?></td></tr>


                <?php  foreach($option['option_value'] as $value){ ?>

                <tr><td><input type="checkbox" name="<?php echo $option['name'] ?>" value="<?php echo $value['name'] ?>"><?php echo $value['name'] ?></option></td></tr>
                <?php   } ?>

                <?php   } ?>*/?>

                <?php } ?>
                <tr><td><h2 style="color:#555 !important; font-weight:bold;  font-size:13px; margin:0; padding:0; font-family:arial;"><?php echo $text_manufacturer_search ?></h2></td></tr>
                <tr><td><select id="producent_search" name="filters[manufacturer]">
                            <option class="option_left" value="null" ></option>
                            <?php  foreach($manufacturers as $manufacturer){ ?>

                            <?php if($filters['manufacturer']==$manufacturer['name']){ ?>
                            <option class="option_left" selected="selected"><?php echo $manufacturer['name'] ?></option>
                            <?php }else{ ?>
                            <option class="option_left"><?php echo $manufacturer['name'] ?></option>
                            <?php } ?>
                            <?php } ?>

                        </select></td></tr>
                <!-- miechu czyszczenie wyszukiwarki -->
                <input id="searchClear" type="hidden" name="filters[clear]" value="false"/>
                <tr><td><button onclick="clearSearch()" style="float:left;background:none;border:none;margin:5px 0;"><?php echo $text_reset; ?></button></td></tr>
                <!-- miechu czyszczenie wyszukiwarki -->
            </table>
    </ul>
    <?/*<input type="submit" value="Szukaj"></input>*/?>
    </form>
</div>