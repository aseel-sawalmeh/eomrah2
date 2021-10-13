<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col">
                    <h1 class="text-center mt-2 text-dark"><?=lang('reservation')?></h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">


                    <div class="info-box bg">
                        <div class="info-box-icon bg-light">
                            <i class="ion ion-clipboard"></i>
                        </div>
                        <div class="info-box-content">
                            <p class="info-box-text black text-bold"><?=lang('paidres')?></p>
                            <h1 class="info-box-number m-color text-bold"><?php echo $paidrescount; ?></h1>

                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="info-box bg">
                        <div class="info-box-icon bg-light icon">
                            <i class="ion ion-document-text"></i>
                        </div>
                        <div class="info-box-content">
                            <p class="info-box-text text-bold black"><?=lang('unpaidres')?></p>
                            <h1 class="info-box-number m-color text-bold"><?php echo $unpaidrescount; ?></h1>

                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="info-box bg">
                        <div class="info-box-icon bg-light icon">
                            <i class="ion ion-briefcase"></i>
                        </div>
                        <div class="info-box-content">
                            <p class="info-box-text text-bold black"><?=lang('totalsales')?></p>
                            <h1 class="info-box-number m-color text-bold"><?php echo $totalpaidres; ?></h1>

                        </div>
                    </div>
                </div>
            </div>


        </div>
    </section>

    <div class="card">
        <div class="card-tools">
            <div class="card-header">
                <form method="get" />
                <div class="form-group">
                    <label><?=lang('sea').lang('res')?></label>
                    <!-- <input type='text' class="form-control" id='admin_search' onkeyup="showb2b(this.value)" />
                    <span style="display:none;" id="currentpage"></span> -->
                    <input type="text" class="form-control" name="search" />
                    <input type="submit" value="<?=lang('sea')?>" class="btn btn-warning mt-2" />


                </div>
                </form>
                
            </div>
            <div class="card-footer">
            <a class="btn btn-danger" href="<?=site_url('gman/main/b2b_reports')?>"><?=lang('totalres')?></a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderd text-nowrap">
                            <thead>
                                <tr>
                                    <th><?=lang('bookingref')?></th>
                                    <th><?=lang('guest_name')?></th>
                                    <th><?=lang('mobilenumber')?></th>
                                    <th><?=lang('resdate')?></th>
                                    <th><?=lang('checkin')?></th>
                                    <th><?=lang('checkout')?></th>
                                    <th><?=lang('room_count')?></th>
                                    <th><?=lang('netprice')?></th>
                                    <th><?=lang('p_method')?></th>
                                    <th><?=lang('paid')?></th>
                                    <th><?=lang('status')?></th>
                                </tr>
                            </thead>
                            <tbody id="admin_results">
                                <?php foreach ($reports as $rp) : ?>
                                <tr>
                                    <td><a href="<?=site_url('gman/main/b2b_details/').$rp->reservation_ref?>">
                                            <?php echo $rp->reservation_ref; ?></a></td>
                                    <td><?php echo $rp->C_FullName; ?></td>
                                    <td><?php echo $rp->C_MobileNumber; ?></td>
                                    <td><?php echo $rp->ResDate; ?></td>
                                    <td><?php echo $rp->CheckInDate; ?></td>
                                    <td><?php echo $rp->CheckOutDate; ?></td>
                                    <td><?php echo $rp->TotalRoomCount; ?></td>
                                    <td><?php echo $rp->TotalPrice; ?></td>
                                    <?php if ($rp->Payment_method == 1) : ?>
                                    <td class="bg-warning"><?php echo 'Pay At Hotel'; ?></td>
                                    <?php else : ?>
                                    <td class="bg-info"><?php echo 'Online'; ?></td>
                                    <?php endif; ?>
                                    <?php if ($rp->Paid == 0) : ?>
                                    <td class="bg-danger"><?php echo 'Not Paid'; ?></td>
                                    <?php else : ?>
                                    <td class="bg-success"><?php echo 'Paid'; ?></td>
                                    <?php endif; ?>
                                    <?php if($rp->confirm == 0):?>
                                    <td class="bg-danger">Not Confirmed</td>
                                    <?php else:?>
                                    <td class="bg-success">Confirmed</td>
                                    <?php endif;?>


                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3 text-center">
        <div class="card mx-auto">
            <div class="card-body">
                <div class="col-12">
                    <div id="pagination">
                        <?= $paginationlinks ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>