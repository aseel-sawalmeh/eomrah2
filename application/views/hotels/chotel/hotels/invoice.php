<div class="content-wrapper" >
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <h4 class="text-center">User Invoice</h4>
                </div>
            </div>
        </div>
    </div>
    <?php $timeout = date('Y-m-d H:i:s', strtotime("$idata->ResDate +" . (int) $rs_timeout . " DAYS"));
    $now = new DateTime('now');
    $restimeout = new DateTime($timeout);
    $expired =  ($now > $restimeout) ? true : false;
    ?>
    <section class="content bg-light">
        <div class="card">
            <div class="card-header">
                <div class="text-center">
                    <?= $this->session->flashdata('marked') ?>

                </div>
            </div>
            <div class="card-body">
                <div class="container mb-3">
                    <div class="container mt-5">
                        <div class="card">
                            <div class="card-header">
                                <?= lang("invoicenumber") ?><strong><?php echo $idata->reservation_ref; ?></strong>
                                <?php if ($idata->Paid) : ?>
                                    <span class="float-right">
                                        <strong><?= lang("status") ?>:</strong><?= lang("paid") ?></span>
                                <?php else : ?>
                                    <span class="float-right">
                                        <strong><?= lang("status") ?>:</strong><?= lang("unpaid") ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="card-body">
                                <div class="row mb-4">
                                    <div class="col-sm-6">
                                        <h6 class="mb-3"><?= lang("from") ?>:</h6>
                                        <div>
                                            <strong><img src="<?php echo  base_url('public_designs/assets/img/logo.png'); ?>" class="img-fluid" alt="" width="150" height="100"></strong>
                                        </div>
                                        <div>Saudi Arabia Makkah Al Mukarama</div>
                                        <div>An Nuzha Near Old Jeddah Makkah Road</div>
                                        <div>Email: contact@reyadatalada.com</div>
                                        <div>Phone: +966594548301</div>
                                    </div>

                                    <div class="col-sm-6">
                                        <h6 class="mb-3"><?= lang("to") ?>:</h6>
                                        <div>
                                            <strong> <?php if ($idata->guest_name) {
                                                            echo $idata->guest_name;
                                                        } else {
                                                            echo $idata->Public_UserFullName;
                                                        } ?></strong>
                                        </div>
                                        <div><?= lang("em") ?>: <?php if ($idata->guest_email) {
                                                                    echo $idata->guest_email;
                                                                } else {
                                                                    echo $idata->Public_UserEmail;
                                                                } ?></div>
                                        <div><?= lang("phone_number") ?><?php echo $idata->Public_UserPhone; ?></div>
                                        <div><?= lang("stayingat") ?>:<?php echo $h_name; ?></div>
                                        <div><?= lang("from") ?>:<?php echo  $idata->CheckInDate; ?></div>
                                        <div><?= lang("to") ?>:<?php echo $idata->CheckOutDate; ?></div>
                                    </div>
                                </div>
                                <div class="table-responsive-sm">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th class="unit"><?= lang("roomtype") ?>/<?= lang("mealtype") ?></th>
                                                <th class="text-left"><?= lang("room_count") ?></th>
                                                <th class="qty"><?= lang("price") ?></th>
                                                <th class="qty"><?= lang("totalprice") ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($idetails($idata->resrefid) as $rooms) : ?>
                                                <tr>
                                                    <td class="qty">
                                                        <b><?php echo lang($rooms->R_Type_Sn) . " -- " . lang($rooms->Meal_Sn); ?></b>
                                                    </td>
                                                    <td class="no"><?php echo $rooms->count; ?></td>
                                                    <td class="qty"><?php echo $rooms->NightPrice; ?></td>
                                                    <td class="qty"><?php echo $rooms->NightPrice * $idata->nights; ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="row">

                                    <div class="col-lg-4 col-sm-5 ml-auto">
                                        <table class="table table-clear">
                                            <tbody>

                                                <tr>
                                                    <td colspan="2"></td>
                                                    <td colspan="2"><?= lang("total_rooms") ?></td>
                                                    <td><b><?php echo $idata->TotalRoomCount; ?></b></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2"></td>
                                                    <td colspan="2"><?= lang("subtotal") ?></td>
                                                    <td><?php echo $idata->NetPrice - (0.05 * $idata->NetPrice);; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2"></td>
                                                    <td colspan="2"><?= lang("vatinc") ?></td>
                                                    <td><?php echo (0.05 * $idata->TotalPrice); ?></td>
                                                </tr>


                                                <?php if ($idata->Discountid) : ?>
                                                    <tr>
                                                        <td colspan="2"></td>
                                                        <td colspan="2"><?= lang("disamount") ?></td>
                                                        <td><?php echo ($idata->NetPrice - $idata->TotalPrice); ?>
                                                        </td>
                                                    </tr>
                                                <?php endif; ?>
                                                <tr>
                                                    <td colspan="2"></td>
                                                    <td colspan="2"><?= lang("totalprice") ?></td>
                                                    <td><?php echo $idata->NetPrice; ?></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2"></td>
                                                    <td colspan="2"><?= lang("p_method") ?></td>
                                                    <td>
                                                        <?php if ($idata->Payment_method == 1) : ?>
                                                            Pay At Hotel
                                                        <?php elseif ($idata->Payment_method == 2) : ?>
                                                            Pay By Credit Card
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="text-center">
                                            <a class='btn-block btn-primary' href='<?php echo base_url('chotel/main/b2c_invoices'); ?>'>Go Back</a>
                                            <?php if ($idata->Payment_method == 1 && $idata->confirm == 0) : ?>
                                                <a class='btn-block btn-success' href='<?= site_url('chotel/main/paid/') . $idata->reservation_ref ?>'>Confirm Reservation</a>
                                       
                                                <?php elseif($idata->Payment_method == 2 && $idata->confirm == 0):?>
                                                <a  href='<?= site_url('chotel/main/confirm/') . $idata->reservation_ref ?>' class="btn-block btn-success">Confirm Reservation</a>
                                             
                                                 
                                                <?php endif;?>
                                            <?php if (!$idata->is_online) : ?>
                                                <p class='text-muted'>Please check all the details from the guest and collect the payment before confirm invoice
                                                </p>
                                            <?php  endif; ?>
                                                <?php if ($expired) : ?>
                                                    <p class='text-danger'><b>This Invoice Is Expired</b></p>
                                            <?php
                                            endif; ?>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>