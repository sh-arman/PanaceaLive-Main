<html>

<style>
    td, th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
    }
</style>
<table style="border: 1px #000000 solid">
    <th>Date</th>
    <th>Maxpro verified</th>
    <th>Maxpro repeat</th>
    <th>Rolac verified</th>
    <th>Rolac repeat</th>
    <th>Mistaken Code</th>
    <th>Not Verified</th>
    <th>SMS</th>
    <th>Mobile</th>
    <th>Web</th>
    <th>Messenger</th>
    <th>Free Basics</th>

<?php
    $date = '2015-12-06';
    // End date
    $end_date = '2017-1-11';

    while (strtotime($date) <= strtotime($end_date)) { ?>
    <tr>
        <td><?=date("d-m-Y", strtotime($date))?></td>
    <?php
            $found=0;
        foreach($check2 as $cdata){
            if($cdata->created_at_date == $date) { ?>
                <td> <?=$cdata->maxpro_verified?> </td>
                <td> <?=$cdata->maxpro_repeat?></td>
              <?php
            $found=1;
            break;
            }
        }
            if($found==0){
                ?>
                <td>0</td>
                <td>0</td>
        <?php
            }
    ?>
        <?php
        $found2=0;
        foreach($check3 as $cdata){
                if($cdata->created_at_date == $date) { ?>
                    <td> <?=$cdata->rolac_verified?> </td>
                    <td> <?=$cdata->rolac_repeat?></td>
                <?php
        $found2=1;
        break;
                }
            }
        if($found2==0){
        ?>
        <td>0</td>
        <td>0</td>
        <?php
        }
        ?>

    <?php
            $found3=0;
        foreach($check as $cdata){
            if($cdata->created_at_date == $date) { ?>
                <td><?=$cdata->invalid_code?></td>
                <td><?=$cdata->not_verified?></td>
                <td><?=$cdata->total_sms?></td>
                <td><?=$cdata->total_mobile?></td>
                <td><?=$cdata->total_web?></td>
                <td><?=$cdata->total_messenger?></td>
                <td><?=$cdata->total_free_basics?></td>
    <?php
            $found3=1;
            break;
        }
            }
        if($found3==0){
        ?>
        <td>0</td>
        <td>0</td>
        <td>0</td>
        <td>0</td>
        <td>0</td>
        <td>0</td>
        <td>0</td>
    <?php
        }

        ?>
    </tr>
    <?php
    $date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));

        }
    ?>


</table>
</html>