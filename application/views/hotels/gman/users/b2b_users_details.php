<div class="content-wrapper">
    <style>
    label {
        font-size: 15px;
    }
    </style>
    <div class="container">
        <div class="row">
            <div class="col mt-4 p-3 bg-white ">
                <form>

                    <div class="row mt-4">
                        <div class="col-xl-6 col-md-12">
                            <div class="form-group">
                                <label><?=lang('fullname')?></label>
                                <input type="text" class="form-control" value="<?= $details->C_FullName ?>" />
                            </div>
                        </div>
                        <div class="col-xl-6 col-md-12">
                            <div class="form-group">
                                <label><?=lang('companyname')?></label>
                                <input type="text" class="form-control" value="<?= $details->Company_Name ?>" />
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-md-12">
                            <div class="form-group">
                                <label><?=lang('country')?></label>
                                <input type="text" class="form-control" value="<?= $details->Country ?>" />
                            </div>
                        </div>
                        <div class="col-xl-6 col-md-12">
                            <div class="form-group">
                                <label><?=lang('mobilenumber')?></label>
                                <input type="text" class="form-control" value="<?= $details->C_MobileNumber ?>" />
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-md-12">
                            <div class="form-group">
                                <label><?=lang('phone_number')?></label>
                                <input type="text" class="form-control" value="<?= $details->C_PhoneNumber ?>" />
                            </div>
                        </div>
                        <div class="col-xl-6 col-md-12">
                            <div class="form-group">
                                <label><?=lang('em')?></label>
                                <input type="text" class="form-control" value="<?= $details->C_Email ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-md-12">
                            <div class="form-group">
                                <label><?=lang('iban')?></label>
                                <input type="text" class="form-control" value="<?= $details->IBAN ?>" />
                            </div>
                        </div>
                        <div class="col-xl-6 col-md-12">
                            <div class="form-group">
                                <label><?=lang('vat_num')?></label>
                                <input type="text" class="form-control" value="<?= $details->C_Vat ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-md-12">
                            <div class="form-group">
                                <label><?=lang('regnumber')?></label>
                                <input type="text" class="form-control" value="<?= $details->C_Reg ?>" />
                            </div>
                        </div>
                        <div class="col-xl-6 col-md-12">
                            <div class="form-group">
                                <label><?=lang('hajjlicense')?></label>
                                <input type="text" class="form-control" value="<?= $details->C_License ?>" />
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
        <?= form_open("gman/users/b2b_add_deposit/$details->C_ID")?>
        <div class="row mt-3 bg-white">
            <div class="col">
                <div class="form-group">
                    <label><?=lang('deposit')?></label>

                    <input type="number" value="<?=$details->Deposit?>" name="amount" class="form-control" />
                </div>
            </div>

        </div>
        <div class="row bg-white p-2">
            <div class="col">
                <div class="text-center">
                    <input type="submit" value="<?=lang('conf')?>" class="btn btn-warning">
                </div>
            </div>
        </div>
        </form>

        <div class="row mb-4 mt-3">
            <div class="col sm-6">
                <div class="card">
                    <div class="card-header">
                        <h4><?=lang('paidres')?></h4>
                    </div>
                    <div class="card-body">
                        <h4><?= $paidres?></h4>
                    </div>
                </div>

            </div>
            <div class="col sm-6">
                <div class="card">
                    <div class="card-header">
                        <h4><?=lang('unpaidres')?></h4>
                    </div>
                    <div class="card-body">
                        <h4><?= $unpaidres ?></h4>
                    </div>
                </div>

            </div>
            <div class="col sm-6">
                <div class="card">
                    <div class="card-header">
                        <h4><?=lang('confres')?></h4>
                    </div>
                    <div class="card-body">
                        <h4><?=$confirmres?></h4>
                    </div>
                </div>

            </div>
        </div>
        <div class="row mb-4 mt-3">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <h4><?=lang('totalsales')?></h4>
                    </div>
                    <div class="card-body">
                        <h4><?=$sales?></h4>
                    </div>
                </div>

            </div>

        </div>

        <div class="row bg-white">
            <div class="col-12">

                <div class="table-responsive">


                    <table class="table table-borderd table-hover">
                        <thead>
                            <tr>
                                <th><?= lang('resno')?></th>
                                <th><?= lang('resdate')?></th>
                                <th><?= lang('checkin')?></th>
                                <th><?= lang('checkout')?></th>
                                <th><?= lang('totalrooms')?></th>
                                <th><?= lang('totalprice')?></th>
                                <th><?=lang('p_method')?></th>
                                <th><?=lang('status')?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($list): foreach($list as $l): ?>
                            <tr>
                                <td><a target="__blank"  target="__blank"
                                        href="<?=site_url('gman/main/b2b_details/').$l->reservation_ref?>"><?=$l->reservation_ref?></a>
                                </td>
                                <td><?=$l->ResDate?></td>
                                <td><?=$l->CheckInDate?></td>
                                <td><?=$l->CheckOutDate?></td>
                                <td><?=$l->TotalRoomCount?></td>
                                <td><?=$l->NetPrice?></td>
                                <?php if($l->Payment_method == 1):?>
                                <td>Pay At Hotel</td>
                                <?php else:?>
                                <td>Pay By Credit Card</td>
                                <?php endif;?>
                                <?php if($l->Paid == 0):?>
                                <td>Not Paid</td>
                                <?php else:?>
                                <td>Paid</td>
                                <?php endif;?>

                            </tr>
                            <?php endforeach; endif;?>

                        </tbody>

                    </table>
                </div>


            </div>
        </div>
        <div class="row bg-white">
            <div class="col">
                <?=$pagination_links?>
            </div>
        </div>





    </div>
</div>



</div>
</div>