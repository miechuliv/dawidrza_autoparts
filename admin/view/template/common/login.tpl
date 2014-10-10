<?php echo $header; ?>
<script type="text/javascript" src="view/javascript/jquery/keyboard/jquery.keyboard.js" ></script>
<script type="text/javascript" src="view/javascript/jquery/keyboard/jquery.keyboard.extension-scramble.js" ></script>
<script type="text/javascript">

        $(document).ready(function(){

            $('input[name="username"]').keyboard({
                    display : {

                        'accept' : 'Akceptuj:Accept (Shift-Enter)',
                        'cancel' : 'Anuluj:Cancel (Esc)'

                    },
                        position     : {
                            of : '#keyb', // optional - null (attach to input/textarea) or a jQuery object (attach elsewhere)
                            my : 'center top',
                            at : 'center bottom',
                            at2: 'center bottom' // used when "usePreview" is false (centers keyboard at bottom of the input/textarea)
                        }
                    }
            ).init();
            $('input[name="password"]').keyboard({
                        display : {

                            'accept' : 'Akceptuj:Accept (Shift-Enter)',
                            'cancel' : 'Anuluj:Cancel (Esc)'

                        },
                        position     : {
                            of : '#keyb', // optional - null (attach to input/textarea) or a jQuery object (attach elsewhere)
                            my : 'center top',
                            at : 'center bottom',
                            at2: 'center bottom' // used when "usePreview" is false (centers keyboard at bottom of the input/textarea)
                        }}
            ).init().addScramble({
                        targetKeys    : /[a-z\d]/i, // keys to randomize
                        byRow         : true,       // randomize by row, otherwise randomize all keys
                        randomizeOnce : true        // if true, randomize only once on keyboard visible
                    });;

            $("#keyb-container").draggable();

        })


</script>
<div id="content">
  <div class="box login">  
    <div class="content" style="min-height: 150px; overflow: hidden;">		 <img src="<?php echo HTTP_CATALOG; ?>image/<?php echo $this->config->get('config_logo'); ?>" alt="<?php echo $this->config->get('config_name'); ?>"/>	    <h1><?php echo $text_login; ?></h1>
      <?php if ($success) { ?>
      <div class="success"><?php echo $success; ?></div>
      <?php } ?>
      <?php if ($error_warning) { ?>
      <div class="warning"><?php echo $error_warning; ?></div>
      <?php } ?>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table style="width: 100%;">
          <tr>
            <td>
              <input type="text" name="username" value="<?php echo $username; ?>" placeholder="<?php echo $entry_username; ?>" />
              <br />
              <input type="password"  name="password" value="<?php echo $password; ?>" placeholder="<?php echo $entry_password; ?>" />
              <?php if ($forgotten) { ?>

              <?php } ?>
              </td>
          </tr>		  		   <tr>            <td style="text-align: right;"><a onclick="$('#form').submit();" class="button"><?php echo $button_login; ?></a>			<a href="<?php echo $forgotten; ?>" style="margin:auto; display:table;"><?php echo $text_forgotten; ?></a></td>          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
   
        </table>
        <?php if ($redirect) { ?>
        <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
        <?php } ?>
      </form>
        <div id="keyb-container" >
            <div id="keyb" >

            </div>
        </div>

    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		$('#form').submit();
	}
});
//--></script> 
<?php echo $footer; ?>