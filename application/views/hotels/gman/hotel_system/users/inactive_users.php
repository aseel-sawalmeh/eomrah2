<div class="content-wrapper">
    <div class="card p-2">
        <h2 class="text-center mt-2">Hotel System InActive Users</h2>
    </div>
    <div class="card">
        <?php if ($this->session->flashdata('user_add_mgs') !== NULL) {
            echo "<h4 style='text-align:center; color:Green'>";
            echo $this->session->flashdata('user_add_mgs');
            echo "</h4>";
        } ?>
        <div class="row">
            <div class="col-12">
                <table class="table table-borderd table-stripped text-center">
                    <thead>
                        <tr>
                            <th>User Full Name</th>
                            <th>User Login Name</th>
                            <th>User Email</th>
                            <th>Phone</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($next_hotel_users) {
                            foreach ($next_hotel_users as $user) : ?>
                                <tr>
                                    <td><?= $user->H_User_FullName ?></td>
                                    <td><?= $user->H_UserName ?></td>
                                    <td><?= $user->H_User_Email ?></td>
                                    <td><?= $user->H_User_Phone ?></td>
                                    <td>
                                        <?php if ($user->H_Email_Verify == 1) : ?>
                                            <a class="btn btn-outline-success" href="<?= base_url('gman') ?>/hotel_system/husers/hotel_user_details/<?= $user->H_User_ID ?>"> Details</a>
                                        <?php else : ?>
                                            <span style='color:red;'>User Email Not verified yet</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                        <?php endforeach;
                        } else {
                            echo "<tr><h2 style='color:red; text-align:center'> No Active Users Yet </tr>";
                        } ?>

                    </tbody>
                </table>

            </div>
        </div>
    </div>


    <!-- pagination generated -->
    <?= $pagination_links ?>
    <!-- pagination generated -->



</div>