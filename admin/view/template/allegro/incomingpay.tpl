<?php echo $header; ?>

<?php echo $sellermenu; ?>

<?php if(isset($error_msg)){ echo $error_msg; }?>

        <div id="platnosci">
            <table>
                <thead>
                   <tr>
                       <td>
                           Płatności
                       </td>
                       <td>
                           Transakcja
                       </td>
                       <td>
                           Kupujący
                       </td>
                       <td>
                           Cena przedmiotu
                       </td>
                       <td>
                           Sztuk
                       </td>
                       <td>
                           Dostawa
                       </td>
                       <td>
                           Kwota wpłaty
                       </td>
                       <td>
                           Data zakończenia
                       </td>
                   </tr>
                <?php foreach($results as $result){ ?>
                   <tr>
                       <td>
                           <?php echo $result->{"pay-trans-id"}; ?>
                       </td>
                       <td>
                           <?php echo $result->{"pay-trans-it-id"}; ?>
                       </td>
                       <td>
                           <a href="http://allegro.pl/show_user.php?uid=<?php echo $result->{'pay-trans-buyer-id'}; ?>" target="_blank" ><?php echo $result->{"pay-trans-buyer-id"}; ?></a>
                       </td>
                       <td>
                           <?php echo $result->{"pay-trans-price"}; ?>
                       </td>
                       <td>
                           <?php echo $result->{"pay-trans-count"}; ?>
                       </td>

                       <td>
                           <?php echo $result->{"pay-trans-postage-amount"}; ?>
                       </td>
                       <td>
                           <?php echo $result->{"pay-trans-amount"}; ?>
                       </td>
                       <td>
                           <?php echo date("y-m-d h-m-s",$result->{"pay-trans-recv-date"}); ?>
                       </td>
                   </tr>
                <?php } ?>

                </tbody>
            </table>
        </div>


<?php echo $footer; ?>