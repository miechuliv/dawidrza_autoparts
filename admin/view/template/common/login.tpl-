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
                            of : null, // optional - null (attach to input/textarea) or a jQuery object (attach elsewhere)
                            my : 'center top',
                            at : 'center bottom',
                            at2: 'center bottom' // used when "usePreview" is false (centers keyboard at bottom of the input/textarea)
                        }
                    }
            ).init().addScramble({
                        targetKeys    : /[a-z\d]/i, // keys to randomize
                        byRow         : true,       // randomize by row, otherwise randomize all keys
                        randomizeOnce : true        // if true, randomize only once on keyboard visible
                    });;
            $('input[name="password"]').keyboard({
                        display : {

                            'accept' : 'Akceptuj:Accept (Shift-Enter)',
                            'cancel' : 'Anuluj:Cancel (Esc)'

                        },
                        position     : {
                            of : null, // optional - null (attach to input/textarea) or a jQuery object (attach elsewhere)
                            my : 'center top',
                            at : 'center bottom',
                            at2: 'center bottom' // used when "usePreview" is false (centers keyboard at bottom of the input/textarea)
                        }}
            ).init().addScramble({
                        targetKeys    : /[a-z\d]/i, // keys to randomize
                        byRow         : true,       // randomize by row, otherwise randomize all keys
                        randomizeOnce : true        // if true, randomize only once on keyboard visible
                    });;

        })


</script>
<div id="content">
  <div class="box" style="width: 400px; min-height: 300px; margin-top: 40px; margin-left: auto; margin-right: auto;">
    <div class="heading">
      <h1><img src="view/image/lockscreen.png" alt="" /> <?php echo $text_login; ?></h1>
    </div>
    <div class="content" style="min-height: 150px; overflow: hidden;">
      <?php if ($success) { ?>
      <div class="success"><?php echo $success; ?></div>
      <?php } ?>
      <?php if ($error_warning) { ?>
      <div class="warning"><?php echo $error_warning; ?></div>
      <?php } ?>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table style="width: 100%;">
          <tr>
            <td style="text-align: center;" rowspan="4"><img src="view/image/login.png" alt="<?php echo $text_login; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_username; ?><br />
              <input type="text" name="username" value="<?php echo $username; ?>" style="margin-top: 4px;" />
              <br />
              <br />
              <?php echo $entry_password; ?><br />
              <input type="password"  name="password" value="<?php echo $password; ?>" style="margin-top: 4px;" />
              <?php if ($forgotten) { ?>
              <br />
              <a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a>
              <?php } ?>
              </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td style="text-align: right;"><a onclick="$('#form').submit();" class="button"><?php echo $button_login; ?></a></td>
          </tr>
        </table>
        <?php if ($redirect) { ?>
        <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
        <?php } ?>
      </form>
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