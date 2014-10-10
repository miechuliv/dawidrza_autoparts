<?php echo $header; ?>



<div class="box">

  <div class="left"></div>

  <div class="right"></div>

  <div class="heading">

    <h1 style="background-image: url('view/image/product.png');">

        <?php if ( isset($_GET['checkauction']) ) { ?>

            Sprawdź aukcję przed wystawieniem

        <?php } else { ?>

            Aukcja została wystawiona

        <?php } ?>

    </h1>

    <?php if ( isset($_GET['checkauction']) ) { ?>

        <div class="buttons">

            <a onclick="$('form').submit();" class="button">

                <span>Wystaw</span>

            </a>

            <?php if ( isset($_GET['product_id']) ) { ?>

                <a href="index.php?route=allegro/product&token=<?php echo $this->session->data['token'] ;?>&product_id=<?php echo $_GET['product_id'] ;?>" class="button">

            <?php } else { ?>

                <a href="index.php?route=allegro/category&token=<?php echo $this->session->data['token'] ;?>&category_id=<?php echo $_GET['category_id'] ;?>" class="button">

            <?php } ?>

                <span>Popraw</span>

            </a>

        </div>

    <?php } ?>

  </div>

  <div class="content">

    

    <?php if ( isset($_GET['checkauction']) ) { ?>

        <div style="display: none;">

            <?php if ( isset($_GET['product_id']) ) { ?>

                <form action="index.php?route=allegro/product/insert&token=<?php echo $this->session->data['token'] ;?>&product_id=<?php echo $_GET['product_id'] ;?>" method="post" enctype="multipart/form-data" id="form">

            <?php } else { ?>

                <form action="index.php?route=allegro/category/insert&token=<?php echo $this->session->data['token'] ;?>&category_id=<?php echo $_GET['category_id'] ;?>" method="post" enctype="multipart/form-data" id="form">

            <?php } ?>

                <?php foreach( $_POST as $PostKey => $PostValue ) { ?>

                    <?php if ( isset($_GET['product_id']) ) { ?>

                        <?php if ( strpos($PostKey,'24-string') !== FALSE ) { ?>

                            <input type="hidden" name="<?php echo $PostKey;?>" value="<?php echo base64_encode($PostValue);?>">

                        <?php } else { ?>

                            <input type="hidden" name="<?php echo $PostKey;?>" value="<?php echo $PostValue;?>">

                        <?php } ?>

                    <?php } else { ?>

                        <?php foreach( $_POST[$PostKey] as $PostNextKey => $PostNextValue ) { ?>

                            <?php if (!is_array( $_POST[$PostKey][$PostNextKey] )) { ?>

                                <input type="hidden" name="<?php echo $PostKey.'['.$PostNextKey.']' ?>" value="<?php echo $PostNextValue ;?>">

                            <?php } else { ?>

                                <?php foreach( $_POST[$PostKey][$PostNextKey] as $PostNextNextKey => $PostNextNextValue ) { ?>

                                    <?php if (!is_array( $_POST[$PostKey][$PostNextKey][$PostNextNextKey] )) { ?>

                                        <?php if ( strpos($PostNextNextKey,'24-string') !== FALSE ) { ?>

                                        <input type="hidden" name="<?php echo $PostKey.'['.$PostNextKey.']['.$PostNextNextKey.']';?>" value="<?php echo base64_encode($PostNextNextValue);?>">

                                        <?php } else { ?>

                                            <input type="hidden" name="<?php echo $PostKey.'['.$PostNextKey.']['.$PostNextNextKey.']';?>" value="<?php echo $PostNextNextValue;?>">

                                        <?php } ?>

                                    <?php } else { ?>

                                        <?php foreach( $_POST[$PostKey][$PostNextKey][$PostNextNextKey] as $PostNextNextNextKey => $PostNextNextNextValue ) { ?>

                                            <input type="hidden" name="<?php echo $PostKey.'['.$PostNextKey.']['.$PostNextNextKey.']['.$PostNextNextNextKey.']';?>" value="<?php echo $PostNextNextNextValue;?>">

                                        <?php } ?>

                                    <?php } ?>

                                <?php } ?>

                            <?php } ?>

                        <?php } ?>

                    <?php } ?>

                <?php } ?>

            </form>

        </div>

    <?php } ?>

    

    <?php foreach( $Data as $D ) { ?>

        <table class="list">

            <?php if ( isset($_GET['checkauction']) ) { ?>

                <?php if ( isset($D['KosztAukcji']) ) { ?>

                    <tr>

                        <td style="height: 30px;">Koszt aukcji:</td>

                        <td style="height: 30px;"><?php echo $D['KosztAukcji'];?></td>

                    </tr>

                    <tr>

                        <td style="height: 30px;">Opis kosztu aukcji:</td>

                        <td style="height: 30px;"><?php echo $D['OpisKosztuAukcji'];?></td>

                    </tr>

                <? } ?>                

            <? } else { ?>

                <?php if ( isset($D['KosztAukcji']) ) { ?>

                    <tr>

                        <td style="height: 30px;">Koszt aukcji:</td>

                        <td style="height: 30px;"><?php echo $D['KosztAukcji'];?></td>

                    </tr>

                    <tr>

                        <td style="height: 30px;">Opis kosztu aukcji:</td>

                        <td style="height: 30px;"><?php echo $D['OpisKosztuAukcji'];?></td>

                    </tr>

                    <tr>

                        <td style="height: 30px;">Identyfikator aukcji:</td>

                        <td style="height: 30px;"><?php echo $D['Identyfikator'];?></td>

                    </tr>

                    <tr>

                        <td style="height: 30px;">Link do aukcji:</td>

                        <td style="height: 30px;"><?php echo $D['Link'];?></td>

                    </tr>

                <?php } ?>

            <?php } ?>

            <?php if ( isset($D['AllegroData']) ) { ?>

                <tr>

                    <td>Allegro Data:</td>

                    <td><?php echo $D['AllegroData'];?></td>

                </tr>

            <?php } ?>

            <?php if ( isset($D['Fault']) ) { ?>

                <tr>

                    <td>Wystąpił błąd:</td>

                    <td><?php echo $D['Fault'];?></td>

                </tr>

            <?php } ?>

        </table>

    <?php } ?>

  </div>

</div>

<?php echo $footer; ?>