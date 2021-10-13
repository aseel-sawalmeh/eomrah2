<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <div class="card mt-3">
                <div class="card-header">
                    <?php if ($this->session->flashdata('provider_mgs') !== NULL) {
                        echo "<h4 style='text-align:center; color:red'>";
                        echo $this->session->flashdata('provider_mgs');
                        echo "</h4>";
                    } ?>
                    <?php echo $this->session->flashdata('a_r'); ?>

                    <div class="card-tools">
                        <div class="input-group input-group-md">
                            <input type="text" id='h_search' onkeyup='show_h(this.value)' name="table_search" class="form-control float-right" placeholder="Search">

                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body table-responsive p-0">
                    <table id="resulttable" class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>User Providing</th>
                                <th>Hotel Name</th>
                                <th>Hotel Address</th>
                                <th>Hotel Phone</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($active_providers) : foreach ($active_providers as $active_provider) : ?>
                                    <tr>
                                        <td><?= $this->HotelSysUsers_model->get_that_h_user($active_provider->HuserID)->H_UserName ?></td>
                                        <td><?= $active_provider->Hotel_Name ?></td>
                                        <td><?= $active_provider->Hotel_Address ?></td>
                                        <td><?= $active_provider->Hotel_Phone ?></td>
                                        <td><?= $active_provider->Hotel_Email ?></td>
                                        <td><?= ($this->pm->request_state($active_provider->Provider_ID)) ? "<span style='color:red'>waiting delete confirm</span>" : "Active" ?></td>
                                        <td>
                                            <?php if (!$this->session->userdata('Suser')) : ?>
                                                <?php if (!$this->pm->request_state($active_provider->Provider_ID)) : ?>
                                                    <a class="btn btn-outline-success" href="<?= base_url("chotel/hotel_manage/comset/{$active_provider->Provider_ID}") ?>">
                                                        Pricing and Availability</a>
                                                    <a class="btn btn-outline-danger" href="<?= base_url('chotel') ?>/provider/delete_request/<?= $active_provider->Provider_ID ?>">
                                                        Delete</a>
                                                <?php else : ?>
                                                    <a class="btn btn-outline-danger" href="<?= base_url('chotel') ?>/provider/cancel_delete/<?= $active_provider->Provider_ID ?>">
                                                        Cancel Delete Request</a>
                                                <?php endif;
                                            else : ?>
                                                <?php if (!$this->pm->request_state($active_provider->Provider_ID)) : ?>
                                                    <a class="btn btn-outline-danger" href="<?= base_url('gman') ?>/providers/admin_delete/<?= $active_provider->Provider_ID ?>">Delete</a>
                                                    <a class="btn btn-outline-success" href="<?= base_url('gman') ?>/providers/provider_res/<?= $active_provider->Provider_ID ?>">Details</a>
                                                <?php else : ?>
                                                    <a class="btn btn-outline-danger" href="<?= base_url('chotel') ?>/provider/confirm_delete_request/<?= $active_provider->Provider_ID ?>">
                                                        confirm delete request</a>
                                            <?php endif;
                                            endif; ?>
                                        </td>
                                    </tr>
                            <?php endforeach;
                            endif; ?>

                        </tbody>
                    </table>
                    <table id="searchresults" class="table table-hover text-nowrap" style="display:none">
                        <thead>
                            <tr>
                                <th>User Providing</th>
                                <th>Hotel Name</th>
                                <th>Hotel Address</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id='replaced_result'>

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card">
                <div class="card-body mx-auto">
                    <?= $pagination_links ?>
                </div>
            </div>
        </div>
    </div>

</div>


</div>