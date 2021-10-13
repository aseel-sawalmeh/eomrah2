<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title><?php $title?></title>


    <style>

        
        body{
            font-family: tj, sans-serif !important; 
            
        }
    .invoice-box {
        max-width:70%;
        margin: auto;
        padding: 30px;
        
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);

        line-height: 24px;
        font-family: DejaVu Sans, sans-serif;

    }

    .invoice-box table {
        width: 100%;
        line-height: inherit;
        text-align: left;
    }

    .invoice-box table td {
        padding: 5px;
        vertical-align: top;
    }

    .invoice-box table tr.top table td {
        padding-bottom: 20px;
    }

    

    .invoice-box table tr.information table td {
        padding-bottom: 40px;
    }

    .invoice-box table tr.heading td {
        background: #eee;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
    }

    .invoice-box table tr.details td {
        padding-bottom: 20px;
    }

    .invoice-box table tr.item td {
        border-bottom: 1px solid #eee;
    }

    .invoice-box table tr.item.last td {
        border-bottom: none;
    }

    .invoice-box table tr.total td:nth-child(2) {
        border-top: 2px solid #eee;
        font-weight: bold;
    }

    @media only screen and (max-width: 600px) {
        .invoice-box table tr.top table td {
            width: 100%;
            display: block;
            text-align: center;
        }

        .invoice-box table tr.information table td {
            width: 100%;
            display: block;
            text-align: center;
        }
    }

    /** RTL **/
    .invoice-box.rtl {
        direction: rtl;
        font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
    }

    .invoice-box.rtl table {
        text-align: right;
    }

    .invoice-box.rtl table tr td:nth-child(2) {
        text-align: left;
    }
    table, th, td {
  border: 1px solid black;
}
    </style>
</head>

<body>
    <?php if ($idata) : ?>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                                <img src="data:image/png;base64,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"
                                    width="300" alt="" />

                            </td>

                            <td>
                                <b> <?= lang('bookingref') ?></b>:
                                <span><?php echo $idata->reservation_ref; ?></span><br />
                                <?php if ($idata->Paid) : ?>
                                <strong><?= lang('status') ?></strong>: <span
                                    style="color:green"><?= lang('paid') ?></span><br />
                                <?php else : ?>
                                <strong><?= lang('status') ?><strong>: <span
                                            style="color:red"><?= lang('unpaid') ?></span><br />

                                        <?php endif; ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

           

            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                            <td>
                                <strong><?= lang('hd') ?></strong>
                                <hr>
                                <strong><?= lang('hotelname')?>:</strong>
                                <span><?= tolang($idata->Hotel_ID, 'hotelname') ?></span><br>
                                <strong><?= lang('hoteladdress')?>
                                </strong>:<span><?= tolang($idata->Hotel_ID, 'hoteladdress') ?></span>

                            </td>

                            <td style="text-align:right">
                                <strong> <?= lang('guestdetails') ?> </strong>
                                <hr>
                                <span class="d-block"><strong
                                        class="font-weight-bold"><?= lang('guest_name') ?></strong>:
                                    <?= $idata->guest_name ?? $idata->Public_UserFullName ?></span><br>
                                <span class="d-block"><strong
                                        class="font-weight-bold"><?= lang('phone_number') ?></strong>:
                                    <?php echo $idata->Public_UserPhone; ?></span><br>
                                <span class="d-block"><strong class="font-weight-bold"><?= lang("checkin") ?></strong>:
                                    <?php echo  $idata->CheckInDate; ?></span><br>
                                <span class="d-block"><strong class="font-weight-bold"><?= lang("checkout") ?></strong>:
                                    <?php echo $idata->CheckOutDate; ?></span>

                            </td>
                        </tr>
                    </table>
                </td>
            </tr>






        </table>
        <hr>
        <table style="border:1px solid black; padding:3px;">
            <thead>
                <tr>
                    <th><?= lang("roomtype") ?> / <?= lang("mealtype") ?></th>
                    <th><?= (lang("additionals") == null) ? 'additionals' : lang("additionals") ?></th>
                    <th><?= lang("price") ?></th>
                </tr>

            </thead>


            <tbody style="background: #E6A4231A;">
                <?php foreach ($idetails as $rooms) : ?>
                <tr>
                    <td class="qty">
                        <p><b><?php /*lang($rooms->name)*/ echo $rooms->name . ' ( <b>' . $rooms->ratebase . ' </b>) <br/> <small> Guest Name: </small>' . $rooms->guest_name; ?></b>
                        </p>
                        <p>occupancy:
                            <?= $rooms->adults . ' adults ' . (!empty($rooms->children) ? '/ ' . count(explode(',', $rooms->children)) . ' children/s (' . $rooms->children . ')' : '') ?>
                        </p>
                        <!-- <p>additional requests: 545 54 4 54 </p> //needs join data with reqs names after -->
                    </td>
                    <td>extra</td>
                    <td><?php echo $rooms->NightPrice; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
        <hr>

        <table style="border:1px solid black">
            <tbody>
                <tr>
                    
                    <td><?= lang("total_rooms") ?></td>
                    <td><b><?php echo $idata->TotalRoomCount; ?></b></td>
                </tr>
                <tr>
                    
                    <td ><?= lang("subtotal") ?></td>
                    <td><?php echo round($idata->TotalPrice); ?>
                    </td>
                </tr>
                <tr>
                    
                    <td><?= lang("vatinc") ?></td>
                    <td><?php echo round((VAT * $idata->TotalPrice), 2); ?></td>
                </tr>
                <tr>
                    
                    <td><?= "municipality tax" ?></td>
                    <td><?php echo round((MANICIPALITY_TAX * $idata->TotalPrice), 2); ?></td>
                </tr>
                <?php if ($idata->Discountid) : ?>
                <tr>
                    
                    <td><?= lang("disamount") ?></td>
                    <td><?php echo($idata->NetPrice - $idata->TotalPrice); ?>
                    </td>
                </tr>
                <?php endif; ?>
                <tr>
                    
                    <td><?= lang("totalprice") ?></td>
                    <td><?php echo round($idata->NetPrice, 2); ?></td>
                </tr>
                <tr>
                   
                    <td><?= lang("p_method") ?></td>
                    <td>
                        OnLine Payment
                    </td>
                </tr>
            </tbody>
        </table>
        <div>
            <p>Total Payable for this Booking: <b><?= round($idata->NetPrice, 2) . ' ' . userCurShort() ?></b>
            </p>
            <ul>
                <b> Amendment & Cancellation Policy </b>
                <li> Any amendments or cancellations must be done online before the cancellation deadline. </li>
                <li> Amendment on bookings confirmed through Dynamic rates & inventory and 3rd Party supplier is
                    possible only if the service is available at the time of amendment and for the dates
                    required. </li>
                <li> No manual /offline amendment is possible. </li>
                <li> Certain services are non amendable and should be rebooked as per new dates before the
                    cancellation deadline. </li>
                <li> No Passenger Name change allowed </li>
            </ul>
            <?php foreach ($idetails as $rooms) : ?>
            <p>
                <?php
                            echo !empty($rooms->cancellation) ? '<b>Cancellation Rules:</b><br> ' . $rooms->name . ' ( <b>' . $rooms->ratebase . ' </b>)' . lang('cancellation') . ': <br>' . str_replace('<br>', '', $rooms->cancellation) : '' ?>
            </p>
            <?php endforeach; ?>

            <p>Bookings including children will be based on sharing parents bedding and no separate bed for
                children is provided unless otherwise stated</p>
        </div>
    </div>


    <?php endif;?>

</body>

</html>