<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col">
                    <h1 class="text-center mt-2 text-dark">Reservations</h1>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3><?php echo $paidrescount; ?></h3>
                            <p>Paid Reservations</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3><?php echo $unpaidrescount; ?></h3>
                            <p>UnPaid Reservations</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3><?php echo $totalpaidres; ?>: SAR</h3>
                            <p>Total Sales</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="card">
        <div class="card-tools">
            <div class="card-header">
                <div class="input-group input-group-sm" style="width: 150px;">
                    <label>Search</label>
                    <input type='text' id='admin_search'
                        onkeyup="show_provider_res(this.value,<?php echo $provider_id; ?>)" />

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card mx-auto">
                <div class="card-body mx-auto">

                    <table class="table  table-stripper border table-responsive">
                        <thead>
                            <tr>
                                <th>Ref:No</th>
                                <th>Reservation Date</th>
                                <th>Check In Date</th>
                                <th>Check Out Date</th>
                                <th>Room Count</th>
                                <th>Total Price</th>
                                <th>Payment Method Used</th>
                                <th>Paid</th>
                                <th>Res Type</th>
                                <th>Res Status</th>

                            </tr>
                        </thead>
                        <tbody id="provider_results">
                            <?php foreach ($reports as $rp) : ?>
                            <tr>

                                <?php if($rp->b2b == 0):?>

                                <td><a href="<?=site_url('gman/main/invoice_details/').$rp->reservation_ref; ?>" target="_blank"><?php echo $rp->reservation_ref; ?></a>
                                    <?php else:?>

                                <td><a
                                        href="<?=site_url('gman/main/b2b_details/').$rp->reservation_ref; ?>" target="_blank"><?php echo $rp->reservation_ref; ?></a>
                                    <?php endif;?>
                                </td>
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
                                <?php if($rp->b2b == 0):?>
                                <td class="bg-primary">B2C</td>
                                <?php else:?>
                                <td class="bg-secondary">B2B</td>
                                <?php endif;?>
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
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <?=$paginationlinks?>
                </div>
            </div>
        </div>
    </div>
</div>