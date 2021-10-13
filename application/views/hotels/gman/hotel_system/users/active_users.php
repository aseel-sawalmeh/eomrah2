<div class="content-wrapper">
  
    <div class="card">
    <h2 class="text-center">Hotel System Active Users</h2>
        <div class="card-body">
            <table class="table table-stripped">
                <thead>
                    <tr>
                       
                        <th>User Full Name</th>
                        <th>User Login Name</th>
                        <th>User Email</th>
                        <th>Phone</th>
                       <!-- <th>Actions</th>-->
                    </tr>
                </thead>
                <tbody>
                    <?php if ($next_hotel_users) {
                        foreach ($next_hotel_users as $user) : ?>
                            <tr>
                               
                                <td><?= $user->H_User_FullName ?></td>
                                <td ><?= $user->H_UserName ?></td>
                                <td><?= $user->H_User_Email ?></td>
                                <td><?= $user->H_User_Phone ?></td>
                               <!-- <td>
                                    <a href="<?= base_url('gman') ?>/hotel_system/husers/edit/<?= $user->H_User_ID ?>"class="btn btn-primary">Edit</a>
                                  
                                </td>-->
                            </tr>
                    <?php endforeach;
                    } else {
                        echo "<tr><h4 style='color:red; text-align:center'>No Active Users Yet</h4> </tr>";
                    } ?>

                </tbody>
            </table>
        </div>
    </div>
</div>
