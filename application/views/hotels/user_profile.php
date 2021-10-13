<div class="container-fluid mt-4">


    <div class="row">
        <div class="col-sm-2 bg-white p-3">
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home"
                    role="tab" aria-controls="v-pills-home" aria-selected="true"><?= lang('home')?></a>
                <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab"
                    aria-controls="v-pills-profile" aria-selected="false"><?= lang('prof') ?></a>

            </div>
        </div>
        <div class="col-sm-9 mx-auto bg-white p-3">
            <div class="tab-content" id="v-pills-tabContent">
                <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel"
                    aria-labelledby="v-pills-home-tab">
                    <table class="table table-borderd table-responsive table-hover">
                        <thead class="">
                            <tr>
                                <th><?= lang('resno')?></th>
                                <th><?= lang('resdate')?></th>
                                <th><?= lang('totalrooms')?></th>
                                <th><?= lang('totalprice')?></th>
                                <th><?= lang('checkin')?></th>
                                <th><?= lang('checkout')?></th>
                                <th><?= lang('guest_name')?></th>
                                <th><?= lang('guest_email')?></th>
                                <th><?=lang('status')?></th>

                            </tr>
                        </thead>
                        <?php foreach ($resheads as $ur) : ?>
                        <tbody>
                            <tr>
                                <td><a href="<?= site_url("user/invoice/$ur->reservation_ref/".md5($user_info->Public_UserEmail)); ?>">
                                        <?php echo $ur->reservation_ref; ?></a></td>
                                <td><?php echo $ur->ResDate; ?></td>
                                <td><?php echo $ur->TotalRoomCount; ?></td>
                                <td><?php echo $ur->NetPrice; ?></td>
                                <td><?php echo $ur->CheckInDate; ?></td>
                                <td><?php echo $ur->CheckOutDate; ?></td>
                                <?php if ($ur->guest_name) {
                                        echo "<td>$ur->guest_name</td>";
                                    } else {
                                        echo '<td>No Guest Reserved</td>';
                                    }
                                    ?>
                                <?php if ($ur->guest_email) {
                                        echo "<td>$ur->guest_email</td>";
                                    } else {
                                        echo '<td>No Emails Reserved</td>';
                                    }
                                    ?>
                                <td><?= $ur->Paid ? 'Paid' : 'Unapid' ?></td>
                            </tr>
                        </tbody>
                        <?php endforeach; ?>
                    </table>
                    <div class="row">
                        <div class="col-12">
                           <div class="mx-auto mt-2">
                           <?php echo $pagination_links; ?>
                           </div>
                        
                              
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-warning">
                                <div class="card-header">
                                    <h3 class="text-center"><?= lang('userinfo') ?></h3>
                                </div>
                                <div class="card-body">
                                    <input type="hidden" name="hotelUser" />
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">

                                                <label><?= lang('fullname') ?></label>
                                                <input type="text" class="form-control"
                                                    value="<?= $user_info->Public_UserFullName ?>" readonly />
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?= lang('uname') ?></label>
                                                <input type="text" class="form-control"
                                                    value="<?= $user_info->Public_UserName ?>" readonly />

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?= lang('em') ?></label>
                                                <input type="email" value="<?= $user_info->Public_UserEmail ?>"
                                                    class="form-control" readonly />
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?= lang('pass') ?></label>
                                                <input type="password" class="form-control"
                                                    value="<?= $user_info->Public_UserPassword ?>" readonly />
                                                <span><a href="<?= site_url('user/changepassword') ?>"><?= lang('changepass') ?></a></span>
                                            </div>
                                        </div>


                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label><?= lang('country') ?></label>
                                                <select class="form-control" disabled>
                                                    <?php foreach ($countries as $country) : ?>
                                                    <option value="<?= $country->country_code ?>"
                                                        <?= ($user_info->country == $country->country_code) ? 'SELECTED' : ''; ?>>
                                                        <?= $country->country_name ?> </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?= lang('contact') ?></label>
                                                <input value="<?= $user_info->Public_UserPhone ?>" type="text"
                                                    class="form-control" readonly />
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?= lang('zip') ?></label>
                                                <input value="<?= $user_info->Zip_Code ?>" type="text"
                                                    class="form-control" readonly />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>


    </div>
    <script src="<?php echo base_url('public_designs/assets', PROTO) ?>/js/jquery-3.4.1-slim-min.js"></script>
    <script src="<?php echo base_url('public_designs/assets',) ?>/js/bootstrap-4.4.1.js"></script>
    <script src="<?php echo base_url('public_designs/assets') ?>/js/popper.js"></script>
</div>