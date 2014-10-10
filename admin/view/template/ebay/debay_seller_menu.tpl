<div id="debay-menu">
    <ul>
        <li>
            <a href="<?php echo $messages; ?>" >Wiadomości</a>
        </li>
        <li>
            <a href="<?php echo $active; ?>" >Aktywne aukcje</a>
        </li>
        <li>
            <a href="<?php echo $sold; ?>" >Sprzedane</a>
        </li>
        <li>
            <a href="<?php echo $unsold; ?>" >Niesprzedane</a>
        </li>
    </ul>
   <?php if(isset($summary)){ ?>
    <table>
        <tr>
            <td colspan="2">Podsumowanie</td>

        </tr>
        <tr>
            <td colspan="2">Obecnie</td>
        </tr>
        <tr>
            <td>Ofert:</td><td><?php echo $summary['AuctionBidCount']; ?></td>
        </tr>
        <tr>
            <td>Kwota:</td><td><?php echo $summary['TotalAuctionSellingValue']; ?></td>
        </tr>

        <tr>
            <td colspan="2" style="padding:10px 0;"><strong>Sprzedane (ostatnie 31 dni)</strong></td>
        </tr>
        <tr>
            <td>Sprzedano:</td><td><?php echo $summary['TotalSoldCount']; ?></td>
        </tr>

        <tr>
            <td>Kwota:</td><td><?php echo $summary['TotalSoldValue']; ?></td>
        </tr>





    </table>
    <?php } ?>
</div>