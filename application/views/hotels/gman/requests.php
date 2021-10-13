

<div class="content-wrapper">
    <div class="card">
        <div class="card-header">
            <div class="text-center">
                <h3>Hotel Requests</h3>
                <?php echo $this->session->flashdata('hotelinform_mgs'); ?>
            </div>
        </div>
        <div class="card-body mx-auto">
            <table class="table table-responsive border">
                <thead>
                    <tr>
                        <?php if ($this->session->userdata('Suser')) {
                            echo '<th>Requestor Name</th>';
                        } ?>
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
                                
                                <?php if ($this->session->userdata('Suser')) {
                                    echo "<td>$requests->H_User_FullName</td>";
                                } ?>
                                <td><?php echo $requests->Hotel_Name; ?></td>
                                <td><?php echo $requests->Hotel_Address; ?></td>
                                <td><?php echo $requests->Hotel_Phone; ?></td>
                                <td><?php echo $requests->Hotel_Email; ?></td>
                                <td>
                                    <?php if ($this->session->userdata('Suser')) : ?>
                                        <a class="btn btn-success" href="<?php echo base_url("gman/providers/validate_request/$requests->H_R_ID") ?>">Activate
                                        </a>
                                    <?php endif; ?>
                                    <a class="btn btn-danger" href="<?php echo base_url("chotel/hotel/hotel_request_delete/$requests->H_R_ID/$requests->Hotel_ID") ?>">Delete
                                    </a>
                                </td>
                        <?php endforeach;
                    endif; ?>
                            </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>