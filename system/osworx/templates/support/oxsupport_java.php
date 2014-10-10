<?php
/**
 * @version		$Id: oxsupport_java.php 3216 2013-04-14 14:30:00Z mic $
 * @package		Support
 * @author		mic - http://osworx.net
 * @copyright	2013 OSWorX - http://osworx.net
 * @license		OCL OSWorX Commercial - http://osworx.net
 */
?>
        /* support functions */
        jQuery('#isValidUntil').bind('click', function() {
            jQuery.ajax({
        		url: '<?php echo $links['isValidUntil']; ?>',
        		type: 'post',
                data: { token: '<?php echo $token; ?>', json: true },
        		dataType: 'json',
                beforeSend: function() {
        			jQuery('#validMsg').html('<div class="attention"><span class="wait">&nbsp;<img src="view/image/loading.gif" alt="" \/>&nbsp;<?php echo $text_checking_date; ?><\/span><\/div>');
        		},
                success: function(json) {
                    jQuery('.success, .warning, .attention, .error, .wait').remove();

                    if( json['error'] ) {
                        jQuery('#validUntil').html( '<div class="warning">' + json['error'] + '<\/div>' );
                    }

                    if( json['success'] ) {
                        jQuery('#validUntil').html( json['date'] );
                        jQuery('#validMsg').html('<div class="success">' + json['success'] + '<span class="support-close">&times;<\/span><\/div>');
                        jQuery('.success').delay(4000).fadeOut(1000);
                    }
                }
            });
        });

        jQuery('#checkVersion').bind('click', function() {
            jQuery.ajax({
        		url: '<?php echo $links['checkVersion']; ?>',
        		type: 'post',
                data: { token: '<?php echo $token; ?>', json: true },
        		dataType: 'json',
                beforeSend: function() {
        			jQuery('#backupHelp').before('<div class="attention"><span class="wait">&nbsp;<img src="view/image/loading.gif" alt="" \/>&nbsp;<?php echo $text_checking_version; ?><\/span><\/div>');
        		},
                success: function(json) {
                    jQuery('.success, .warning, .attention, .error, .wait').remove();

                    if( json['error'] ) {
                        jQuery('#checkVersion').html( '<div class="warning">' + json['error'] + '<\/div>' );
                    }

                    if( json['success'] ) {
                        if( json['class'] == 'orange' ) {
                            jQuery('#version').html( '<span class="' + json['class'] + '">' + '<?php echo $version; ?>' + '<\/span>' );
                            jQuery('#newVersion').remove();
                            jQuery('#changelog').html( '<div class="warning">' + json['changelog'] + '<\/div>' );
                        }else{
                            var content = '';

                            if( json['class'] != 'green' ) {
                                content = jQuery('#changelog').html() + json['changelog'];
                            }else{
                                content = '<span class="green bold">' + json['changelog'] + '<\/span>';
                            }

                            jQuery('#version').html('<span class="' + json['class'] + '">' + '<?php echo $version; ?>' + '<\/span>');
                            jQuery('#newVersion').html('<span class="green bold">' + json['version'] + '<\/span>');
                            jQuery('#changelog').html(content);
                            jQuery('#supportMsg').html('<div class="success">' + json['success'] + '<span class="support-close">&times;<\/span><\/div>');
                        }

                        jQuery('#changelog').show(1200);
                    }
                }
            });
        });

        function updateNow() {
            var backup = jQuery('#backup').is(':checked');

            jQuery.ajax({
        		url: '<?php echo $links['updateNow']; ?>',
        		type: 'post',
                data: { token: '<?php echo $token; ?>', json: true, backup: backup },
        		dataType: 'json',
                beforeSend: function() {
                    jQuery('.success, .warning, .attention, .error, .wait').remove();
        			jQuery('#supportMsg').html('<div class="attention"><span class="wait">&nbsp;<img src="view/image/loading.gif" alt="" \/>&nbsp;<?php echo $text_updating_system; ?><\/span><\/div>');
        		},
                success: function(json) {
                    jQuery('.success, .warning, .attention, .error, .wait, #backupHelp').remove();

                    if( json['error'] ) {
                        jQuery('#changelog').html( '<div class="warning">' + json['error'] + '<\/div>' );
                    }

                    if( json['success'] ) {
                        var text = '<span class="green">' + json['version'] + '<\/span>';

                        if( json['debug'] ) {
                            text += '<hr \/>' +  json['debug'] + '<hr \/>';
                        }

                        jQuery('#version').html( text );
                        jQuery('#newVersion, #changelog').fadeOut(1000);
                        jQuery('#supportMsg').html('<div class="success">' + json['success'] + '<span class="support-close">&times;<\/span><\/div>');
                    }
                }
            });
        };

        function displayHelp() {
            var isChecked = jQuery('#backup').is(':checked');

            if( isChecked ) {
                jQuery('#backupHelp').slideDown('slow');
            }else{
                jQuery('#backupHelp').slideUp('slow');
            }

        };

        $('.success, .success img, .warning img, .attention img, .information img').live('click', function() {
    		$(this).parent().fadeOut('slow', function() {
    			$(this).remove();
    		});
    	});