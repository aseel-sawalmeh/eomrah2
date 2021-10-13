<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <?php if ($this->session->flashdata('provider_mgs') !== NULL) {
                        echo "<h4 style='text-align:center; color:red'>";
                        echo $this->session->flashdata('provider_mgs');
                        echo "</h4>";
                    } ?>

                </div>

                <div class="card-body table-responsive p-0">
                    <table id="resulttable" class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>User Requested</th>
                                <th>Hotel Name</th>
                                <th>Hotel Address</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($inactive_providers) : foreach ($inactive_providers as $inactive_provider) : ?>
                                    <tr>
                                        <td>
                                            <?= $this->HotelSysUsers_model->get_that_h_user($inactive_provider->HuserID)->H_UserName ?>
                                        </td>
                                        <td><?= $inactive_provider->Hotel_Name ?></td>
                                        <td><?= $inactive_provider->Hotel_Address ?></td>
                                        <td><?= $inactive_provider->Hotel_Phone ?></td>
                                        <td><?= $inactive_provider->Hotel_Email ?></td>
                                        <td>
                                            <?php if (!$this->session->userdata('Suser')) : ?>
                                                <a class="md-btn md-btn-danger" href="<?= base_url('chotel') ?>/provider/delete/<?= $inactive_provider->Provider_ID ?>">
                                                    Delete</a>
                                            <?php else : ?>
                                                <a class="btn btn-success" href="<?= base_url('gman') ?>/providers/activate/<?= $inactive_provider->Provider_ID ?>">
                                                    Activate</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                            <?php
                                endforeach;
                            endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body mx-auto">
                    <?= $pagination_links ?>
                </div>
            </div>
        </div>
    </div>
</div>