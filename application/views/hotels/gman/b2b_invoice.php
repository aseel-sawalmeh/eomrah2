<div class="container mb-3">
    <?php if ($idata && $idata->Paid) {
        // echo $profileUpdate ? $editProfile : '';
    }
    ?>

    <!-- // print_r($idata);
    // var_dump($this->rsv->res_details($idata->reservation_ref));
  -->


    <div class="container mt-3">
        <?php if($idata->confirm != 3):?>
        <!-- cancelation area -->
        <div id="inv" class="row">
            <div class="col">
                <!-- Button trigger modal -->
                <!-- <button type="button" class="btn btn-danger font-weight-bold" data-toggle="modal" data-target="#exampleModalCenter" @click="checkCancel">
                    <?= lang('resCancel') ?>
                </button> -->
                <!-- Modal -->
                <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle"></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p><?= lang('cancelWarn') ?> Plus other data and allowance</p>
                                <p v-if="resDataLoading"> Loading ... </p>
                                <p v-if="!resDataLoading && Object.keys(cancellationDetails).length"> cancellation is allowed, charge is {{ cancellationDetails.charge +' '+cancellationDetails.currencyShort }}</p>
                                <p v-if="!resDataLoading && resDataError"> {{ resDataError }}</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-warning font-weight-bold" data-dismiss="modal"><?= lang('noThanks') ?></button>
                                <?= form_open("user/res_cancel/$idata->reservation_ref/" . md5($idata->Public_UserEmail) . '/yes') ?>
                                <input type="hidden" name="resref" value="<?= $idata->reservation_ref ?>" />
                                <input type="hidden" name="resmail" value="<?= md5($idata->Public_UserEmail) ?>" />
                                <input type="hidden" name="resrefid" value="<?= $idata->ID ?>" />
                                <input type="hidden" name="servicecode" :value="cancellationDetails.serviceCode" />
                                <input type="hidden" name="charge" :value="cancellationDetails.charge" />
                                <button type="submit" class="btn btn-success font-weight-bold"><?= lang('yesSure') ?></button>
                                <?= form_close() ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /cancelation area -->
        <?php endif;?>

        <?php if ($idata) : ?>
            <!-- invoice -->
            <div class="card">
                <div class="card-header p-3">
                    <p><span class="mcolor"><?= lang("invoicenumber") ?> </span><strong class="text-black"><?php echo $idata->reservation_ref; ?></strong></p>
                    <?php if ($idata->Paid) : ?>
                        <span class="text-black"> <?= lang('status') ?> <span class="text-success"><?= lang('paid') ?></span></span>
                    <?php else : ?>
                        <span class="text-black font-weight-bold"> <?= lang('status') ?> <span class="warn"><?= lang('unpaid') ?></span></span>
                    <?php endif; ?>
                </div>

                <div class="card-body p-4">
                    <!-- Invoice Head -->
                    <div class="row mb-4 mt-3">
                        <div class="col-sm-6">
                            <img src="<?php echo  base_url('public_designs/assets/img/logo.png'); ?>" class="img-fluid" alt="Eomrah" width="300">
                        </div>
                        <div class="col-sm-6 d-flex justify-content-end">
                            <div class="m-1">
                                <p class="text-dark font-weight-bold"><?= lang('bookingref') ?>: <span class="mcolor"><?= $idata->reservation_ref ?></span></p>
                                <p class="text-dark font-weight-bold">
                                    <?php
                                    echo lang('bookingstatus') . ': ' . (($idata->confirm == 0 ||  $idata->confirm == 1) ? '<span class="warn">' . lang('onRequest') . '</span>' : '');
                                    echo ($idata->confirm == 2) ? '<span class="text-success">' . lang('confirmed') . '</span> <br> ConfirmRef: bookingRefNumber.db' : '';
                                    echo ($idata->confirm == 3) ? '<span class="text-danger">' . lang('canceled') . '</span>' : ''; ?>
                                </p>
                            </div>
                            <div class="text-center p-3">
                                <!-- qrCode($idata->lat, $idata->lng) -->
                                <img src="<?php echo  qrCode($idata->lat, $idata->lng); ?>" class="img-fluid" width="120" alt="Eomrah">
                                <span class="d-block scan-me">Scan Me</span>
                            </div>
                        </div>

                    </div>
                    <!-- Passenger Detials -->
                    <div class="row justify-content-between mb-4">
                        <div class="col-md-6 col-sm-12 pt-2">
                            <h5 class="font-weight-bold"><?= lang('hd') ?>: </h5>
                            <span class="d-block"><strong class="font-weight-bold"><?= lang('hotelname') . '</strong>: ' . tolang($idata->Hotel_ID, 'hotelname') ?></span>
                            <span class="d-block"><strong class="font-weight-bold"><?= lang('hoteladdress') . '</strong>: ' . tolang($idata->Hotel_ID, 'hoteladdress') ?></span>
                        </div>
                        <div class="col-md-4 col-sm-12 pt-2 align-self-end">
                            <h5 class="font-weight-bold"> <?= lang('guestdetails') ?> </h5>
                            <span class="d-block"><strong class="font-weight-bold"><?= lang('guest_name') ?></strong>: <?= $idata->guest_name ?? $idata->C_FullName ?></span>
                            <span class="d-block"><strong class="font-weight-bold"><?= lang('phone_number') ?></strong>: <?php echo $idata->C_PhoneNumber; ?></span>
                            <span class="d-block"><strong class="font-weight-bold"><?= lang("checkin") ?></strong>: <?php echo  $idata->CheckInDate; ?></span>
                            <span class="d-block"><strong class="font-weight-bold"><?= lang("checkout") ?></strong>: <?php echo $idata->CheckOutDate; ?></span>
                        </div>
                    </div>
                    <div class="table-responsive-sm">
                        <table class="table table-striped">
                            <thead>
                                <tr style="background-color: #FFB300;">
                                    <th class="font-weight-bold"><?= lang("roomtype") ?> / <?= lang("mealtype") ?></th>
                                    <th class="font-weight-bold"><?= (lang("additionals") == null) ? 'additionals' : lang("additionals") ?></th>
                                    <th class="font-weight-bold"><?= lang("price") ?></th>
                                </tr>
                            </thead>
                            <tbody style="background: #E6A4231A 0% 0% no-repeat padding-box; opacity: 1;">
                                <?php foreach ($idetails as $rooms) : ?>
                                    <tr>
                                        <td class="qty">
                                            <p><b><?php /*lang($rooms->name)*/ echo $rooms->name . ' ( <b>' . $rooms->ratebase . ' </b>) <br/> <small> Guest Name: </small>' . $rooms->guest_name; ?></b></p>
                                            <p>occupancy: <?= $rooms->adults . ' adults ' . (!empty($rooms->children) ? '/ ' . count(explode(',', $rooms->children)) . ' children/s (' . $rooms->children . ')' : '') ?> </p>
                                            <!-- <p>additional requests: 545 54 4 54 </p> //needs join data with reqs names after -->
                                        </td>
                                        <td class="no">extra</td>
                                        <td class="font-weight-bold"><?php echo $rooms->NightPrice; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-sm-5 ml-auto text-center">
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
                                        <td><?php echo round($idata->TotalPrice); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"></td>
                                        <td colspan="2"><?= lang("vatinc") ?></td>
                                        <td><?php echo round((VAT * $idata->TotalPrice), 2); ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"></td>
                                        <td colspan="2"><?= "municipality tax" ?></td>
                                        <td><?php echo round((MANICIPALITY_TAX * $idata->TotalPrice), 2); ?></td>
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
                                        <td colspan="2" class="font-weight-bold"><?= lang("totalprice") ?></td>
                                        <td class="font-weight-bold"><?php echo round($idata->NetPrice, 2); ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"></td>
                                        <td colspan="2"><?= lang("p_method") ?></td>
                                        <td>
                                            OnLine Payment
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <!-- <?php if (!$idata->Paid && $idata->confirm != 3) : ?>
                                <a class="btn pbtn p-3 font-weight-bold" href="<?= site_url("payment/pay/$idata->reservation_ref/$mailHash") ?>"> <?= lang('paynow') ?></a>
                            <?php endif; ?> -->

                        </div>
                    </div>
                    <?php if ($this->session->userdata('User_data')) : ?>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="text-center">
                                    <a class='btn-block btn-primary' href='<?php echo base_url('user/profile'); ?>'>Go Back</a>
                                </div>

                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-footer invoice-footer">
                    <p>Total Payable for this Booking: <b><?= round($idata->NetPrice, 2) . ' ' . userCurShort() ?></b> </p>
                    <ul>
                        <b> Amendment & Cancellation Policy </b>
                        <li> Any amendments or cancellations must be done online before the cancellation deadline. </li>
                        <li> Amendment on bookings confirmed through Dynamic rates & inventory and 3rd Party supplier is possible only if the service is available at the time of amendment and for the dates required. </li>
                        <li> No manual /offline amendment is possible. </li>
                        <li> Certain services are non amendable and should be rebooked as per new dates before the cancellation deadline. </li>
                        <li> No Passenger Name change allowed </li>
                    </ul>
                    <?php foreach ($idetails as $rooms) : ?>
                        <p>
                            <?php
                            echo !empty($rooms->cancellation) ? '<b>Cancellation Rules:</b><br> ' . $rooms->name . ' ( <b>' . $rooms->ratebase . ' </b>)' . lang('cancellation') . ': <br>' . str_replace('<br>', '', $rooms->cancellation) : '' ?>
                        </p>
                    <?php endforeach; ?>

                    <p>Bookings including children will be based on sharing parents bedding and no separate bed for children is provided unless otherwise stated</p>
                </div>
                <!-- invoice -->
            </div>

        <?php else : ?>

            <!-- invoice is not exist -->
            <div class="container mt-5 pb-5 error" style="content:'Not Found';">
                <div class="row m-5 p-5">
                    <div class="col m-2 p-5 text-center">
                        <img src="<?= base_url('public_designs/assets/img/logo.png') ?>" class="img-fluid m-5" width="300px" height="180px" alt="">
                        <h2 class="text-center font-weight-bold">Sorry, not valid</h2>
                        <p class="text-center mt-5 font-weight-bold"> This invoice is`t exist or there is an error.</p>
                    </div>
                </div>
            </div>
            <!-- / invoice is not exist -->
        <?php endif; ?>
    </div>
</div>