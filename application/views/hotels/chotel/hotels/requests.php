
<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-center">Requested Hotels</h4>
                    <div class="card-tools">
                        <div class="input-group input-group-sm">
                            <a href="<?= site_url("chotel/provider/hotel_inform") ?>" class="btn btn-info">Make New Request</a>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Hotel Name</th>
                                <th>Hotel Address</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($hotel_resquests) : foreach ($hotel_resquests as $requests) : ?>
                                    <tr>
                                        <td><?php echo $requests->Hotel_Name; ?></td>
                                        <td><?php echo $requests->Hotel_Address; ?></td>
                                        <td><?php echo $requests->Hotel_Phone; ?></td>
                                        <td><?php echo $requests->Hotel_Email; ?></td>
                                        <td>

                                            <a href="<?php echo base_url("chotel/hotel/hotel_request_delete/$requests->H_R_ID/$requests->Hotel_ID") ?>">Delete
                                            </a>
                                        </td>
                                <?php endforeach;
                            endif; ?>
                                    </tr>
                        </tbody>
                    </table>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
</div>