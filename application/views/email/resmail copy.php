
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>User Invoice</title>

    <style>
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, .15);
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
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
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">

                                <?php if (lngdir() == "rtl") : ?>
                                    <img class="logo" src="<?= base_url('public_designs/assets/img/logo-ar.png') ?>" style="width:100%; max-width:300px;" />
                                <?php else : ?>
                                    <img class="logo" src="<?= base_url('public_designs/assets/img/logo.png') ?>" style="width:100%; max-width:300px;" />
                                <?php endif; ?>
                            </td>

                            <td>
                                <?= lang("invoicenumber") ?>:<?= $u_details->reservation_ref ?><br>
                                <?= lang("createdat") ?>: <?= date("Y/m/d") ?><br>
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
                                Near Old Jeddah Makkah Road.<br>
                                Nuzha Makkah<br>
                                Saudi Arabia<br>
                                eomrah.com<br>
                                sales@eomrah.com
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="item">
                <td>
                    <?= lang("resref") ?>
                </td>

                <td>

                </td>
            </tr>

            <tr class="item">
                <td>
                    <?= lang("totalrooms") ?>
                </td>

                <td>
                    <?= $u_details->TotalRoomCount ?>
                </td>
            </tr>

            <tr class="item">
                <td>
                    <?= lang("checkin") ?>
                </td>

                <td>
                    <?= $u_details->CheckInDate ?>
                </td>
            </tr>

            <tr class="item">
                <td>
                    <?= lang("checkout") ?>
                </td>

                <td>
                    <?= $u_details->CheckOutDate ?>
                </td>
            </tr>
            <tr class="item">
                <td>
                    <?= lang("nonights") ?>
                </td>

                <td>
                    <?= $u_details->nights ?>
                </td>
            </tr>
            <tr class="item">
                <td><?= lang("subtotal") ?></td>
                <td><?php echo $u_details->NetPrice - (0.05 *$u_details->NetPrice);; ?>
                </td>
            </tr>
            <tr>
                <td><?= lang("vatinc") ?></td>
                <td><?php echo (0.05 * $u_details->TotalPrice); ?></td>
            </tr>
            <tr class="total">
                <td>
                    <?= lang("totalprice") ?>
                </td>

                <td>
                    <?= $u_details->TotalPrice ?>
                </td>
            </tr>
            <tr class="total">
                <td>
                    <?= lang("p_method") ?>
                </td>
                <td>
                    <?php if ($u_details->Paid) {
                        echo $u_details->Pay_m_name;
                    } else {
                        if ($u_details->is_online) {
                            echo '<a href="' . base_url('payment/pay/') . $u_details->reservation_ref . '"> Please Pay Now </a>';
                        } else {
                            echo $u_details->Pay_m_name;
                        }
                    } ?>
                </td>
            </tr>
        </table>
        <div style="border:1px solid grey; margin-top:15px;padding:2px;">
            <div>
                <p>
                    <?=lang("invoicefooternote")?>
                </p>
            </div>
        </div>
    </div>
</body>

</html>