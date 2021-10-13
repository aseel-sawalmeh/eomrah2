<!doctype html>
<html lang="<?= userlang() ?>" dir="<?= lngdir() ?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Invoice</title>

    <style>
        .invoice-box {
            max-width: 90vw;
            margin: auto;
            padding: 30px;
            border: 1px solid #868181;
            box-shadow: 0 0 10px rgba(0, 0, 0, .15);
            font-size: 16px;
            line-height: 1.6rem;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        }

        .grid {
            display: flex;
            justify-content: space-between;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
        }

        tbody .room {
            background-color: #f2f2f2;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 10px;
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

        <?php if (lngdir() == "rtl") : ?>.rtl {
            direction: rtl;
            font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        }

        .rtl table {
            text-align: right;
        }

        .rtl table tr td:nth-child(2) {
            text-align: left;
        }

        <?php endif; ?>
    </style>
</head>

<body>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr class="grid">
                            <td>
                                <?php if (lngdir() == "rtl") : ?>
                                    <img class="logo" src="<?= base_url('public_designs/assets/img/logo-ar.png') ?>" style="width:100%; max-width:300px;" />
                                <?php else : ?>
                                    <img class="logo" src="<?= base_url('public_designs/assets/img/logo.png') ?>" style="width:100%; max-width:300px;" />
                                <?php endif; ?>
                            </td>
                            <td>
                                <b><?= lang("invoicenumber") ?></b>: <?= $idata->reservation_ref ?><br>
                                <b> <?= lang('status') . '</b>: ' . (($idata->Paid) ? '<b style="color:green">' . lang('paid') . '</b>' : '<b style="color:red">' . lang('unpaid') . '</b>'); ?>
                                    </span><br>
                                    <b><?= lang('bookingstatus') . '</b>: ' . (($idata->confirm == 0 ||  $idata->confirm == 1) ? '<b style="color:orange">' . lang('onRequest') . '</b>' : '<b style="color:green">' . lang('confirmed') . '</b>'); ?>
                                        </span><br>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                            <strong><?= lang('hd') ?> :</strong>
                            <td>
                                <b> <?= lang('hotelname') . '</b>: ' . $idata->Hotel_Name ?><br />
                                    <b><?= lang('hoteladdress') . '</b>: ' . $idata->Hotel_Address ?><br />
                                        <b></b><?= lang('hotelphone') . '</b>: ' . ($idata->Hotel_Phone ?? '966') ?><br />
                            </td>
                            <td style="text-align: center;">
                                <img class="logo" src="<?= base_url('public_designs/assets/img/eomrahqr.png') ?>" style="width:100%; max-width:100px;" />
                                <br /> <small><?= lang('scancode') ?></small>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                            <strong><?= lang('guestdetails') ?> :</strong>
                            <td>
                                <b><?= lang('guest_name') . '</b>: ' . $idata->Public_UserFullName ?><br />
                                    <b><?= lang('phone_number') . '</b>: ' . $idata->Public_UserPhone ?><br />
                                        <b><?= lang('checkin') . '</b>: ' . $idata->CheckInDate ?><br />
                                            <b><?= lang('checkout') . '</b>: ' . $idata->CheckOutDate ?><br />
                            </td>
                            <td style="text-align: center;">
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="information">
                <td>
                    <table>
                        <thead>
                            <tr>
                                <td><?= lang("roomtype") ?> / <?= lang("mealtype") ?></td>
                                <td><?= (lang("additionals") == null) ? 'additionals' : lang("additionals") ?></td>
                                <td><?= lang("price") ?></td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($idetails as $room) : ?>
                                <tr class="room">
                                    <td>
                                        <?= $room->name ?> <br />
                                       <?= lang('guest_name').': '.$room->guest_name ?>
                                    </td>
                                    <td>
                                        <?= $room->ratebase ?>
                                    </td>
                                    <td>
                                        <?= $room->NightPrice ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </td>
            </tr>

            <tr class="information">
                <td>
                    <table>
                        <tr class="item">
                            <td>
                                <?= lang("totalrooms") ?>
                            </td>
                            <td>
                                <?= $idata->TotalRoomCount ?>
                            </td>
                        </tr>
                        <tr class="item">
                            <td><?= lang("subtotal") ?></td>
                            <td><?php echo $idata->TotalPrice - (0.05 * $idata->NetPrice);; ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?= lang("vatinc") ?></td>
                            <td><?php echo (0.05 * $idata->TotalPrice); ?></td>
                        </tr>
                        <tr class="total">
                            <td>
                                <?= lang("totalprice") ?>
                            </td>

                            <td>
                                <?= $idata->NetPrice ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

        </table>
        <hr>
        <div style="border:0.3px solid grey; margin-top:1.5rem; padding:1rem;">

            <?php foreach ($idetails as $rooms) : ?>
                <p>
                    <?php echo $rooms->name . ' ( <b>' . $rooms->ratebase . ' </b>)' . lang('cancellation') . ': <br>' . str_replace('<br>', '', $rooms->cancellation); ?>
                </p>
            <?php endforeach; ?>
        </div>
    </div>
</body>

</html>