<?php echo $header; ?>

<?php echo $sellermenu; ?>

<script>
    function showrespond(elem)
    {

        $(elem).parent().next().next().css("display","block");
    }
</script>

<?php if(isset($error_msg)){ ?>
    <p>Wystąpił błąd:</p>
    <p><?php echo $error_msg ?></p>
<?php }else{ ?>
    <div id="comment-list">
       <table>
           <thead>
               <tr>
                  <td>
                    <?php if($mode==="fb_recvd"){ ?>
                      Od kogo
                     <?php }else{ ?>
                      Komu
                     <?php } ?>
                  </td>
                  <td>
                      Typ
                  </td>
                   <td>
                       Data
                   </td>
                   <td>
                       Numer aukcji
                   </td>
                   <?php if($mode==="fb_recvd"){ ?>
                   <td>
                       Opcje
                   </td>
                   <?php } ?>
               </tr>
               <?php foreach($comments as $comment){ ?>
               <tr>
                   <td>
                       <?php if($mode==="fb_recvd"){ ?>
                       <a href="http://allegro.pl/show_user.php?uid=<?php echo $comment['commenting_user_id'] ?>" ><?php echo $comment['commenter_name'] ?></a><?php  echo " (".$comment['commenter_pts'].")"; ?>
                       <?php }else{ ?>

                       <a href="http://allegro.pl/show_user.php?uid=<?php echo $comment['commented_user_id'] ?>" ><?php echo $comment['commenter_name'] ?></a><?php  echo " (".$comment['commenter_pts'].")"; ?>
                       <?php } ?>
                       <?php  echo  "( ".$sides[$comment['side']]." )"?>
                   </td>
                   <td>
                       <?php  echo  $comment_types[$comment['type']] ?>
                   </td>
                   <td>
                       <?php  echo  $comment['date']; ?>
                   </td>
                   <td>
                      <a href="http://allegro.pl/i<?php echo  $comment['offer_id']; ?>.html" ><?php echo  $comment['offer_id']; ?></a>
                   </td>
                   <?php if($mode==="fb_recvd"){ ?>
                   <td>
                       <a href="http://allegro.pl/myaccount/feedbacks/feedbacks.php/addCorrection/fb_id,<?php echo $comment['comment_id'] ?>/p,1" >Odpowiedz</a>
                   </td>
                   <?php } ?>

               </tr>
               <tr class="bor-stop">
                   <td colspan="5">
                       <?php  echo  $comment['content']; ?>
                   </td>

               </tr>
               <tr style="display:none">
                   <td colspan="5">
                       <?php if($comment['respond_content']){ ?>
                       <?php  echo  $comment['respond_content']; ?>
                       <br/> <?php  echo  $comment['respond_date']; ?>
                       <?php } ?>
                   </td>
               </tr>
               <?php } ?>
           </tbody>
       </table>
    </div>
<?php } ?>


<?php echo $footer; ?>