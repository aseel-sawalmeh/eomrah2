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
                <div class="col-xl-4 col-md-12">
                    <div class="info-box bg-white">
                        <span class="info-box-icon bg-light">
                            <i class="ion ion-stats-bars"></i>
                        </span>
                        <div class="info-box-content">
                            <p class="info-box-text">Paid Reservations</p>
                            <h3 class="info-box-number"><?php echo $paidrescount; ?></h3>

                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-12">
                    <div class="info-box bg-white">
                        <div class="info-box-icon bg-light">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <div class="info-box-content">
                            <p class="info-box-text">UnPaid Reservations</p>
                            <h3 class="info-box-number"><?php echo $unpaidrescount; ?></h3>
                        </div>

                    </div>
                </div>
                <div class="col-xl-4 col-md-12">
                    <div class="info-box bg-white">
                        <div class="info-box-icon bg-light">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <div class="info-box-content">
                            <p class="info-box-text">Total Sales</p>
                            <h3 class="info-box-number"><?php echo $totalpaidres; ?>: SAR</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="card">
        <div class="card-header">
            <div class="card-tools">
                <div class="input-group input-group-md">
                    <input type="text" class="form-control float-right" placeholder="Search" id='livesearch' onkeyup="showResult(this.value)" />
                    <div class="input-group-append">
                        <button type="submit" disabled class="btn btn-default"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <span id="currentpage"></span>

    <div class="row">
       <!-- <div class="col-xl-2 col-md-12 pl-3">
            <div class="card">
                <div class="card-body">
                    <ul class="list-unstyled">
                        <?php foreach ($providers as $prov) : ?>
                            <li class="list-item text-center"><a href="<?= base_url('chotel/main/b2c_invoices/') . $prov->Provider_ID ?>">
                                    <?= $hotelname($prov->Hotel_ID) ?></a></li>
                            <hr>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>-->
        <div class="col-xl-12 col-md-12">
            <div class="card">
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover table-borderd text-nowrap">
                        <thead>
                            <tr>
                                <th>Ref:No</th>
                                <th>Guest Name</th>
                                <th>Mobile Number</th>
                                <th>Reservation Date</th>
                                <th>Check In Date</th>
                                <th>Check Out Date</th>
                                <th>Room Count</th>
                                <th>Total Price</th>
                                <th>Payment Method Used</th>
                                <th>Paid</th>
                                <th>State</th>
                            </tr>
                        </thead>
                        <tbody id="s_results">
                            <?php foreach ($reports as $rp) : ?>
                                <tr>
                                    <td><a href="<?= site_url('chotel/main/invoice_details/') . $rp->reservation_ref ?>"><?php echo $rp->reservation_ref; ?>
                                    </td>
                                    <td><?php echo $rp->Public_UserFullName; ?></td>
                                    <td><?php echo $rp->Public_UserPhone; ?></td>
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
                                        <td class="bg-danger"><?php echo 'No'; ?></td>
                                    <?php else : ?>
                                        <td class="bg-success"><?php echo 'Yes'; ?></td>
                                    <?php endif; ?>
                                    <?php if ($rp->confirm == 0) : ?>
                                        <td class="bg-danger">Not Confirmed</td>
                                    <?php else : ?>
                                        <td class="bg-success">Confirmed</td>
                                    <?php endif; ?>

                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            </div>
            <div class="card">
                <div class="card-body mx-auto">
                    <div id="pagination">
                        <div class="mx-auto">
                            <?php echo $paginationlinks; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>