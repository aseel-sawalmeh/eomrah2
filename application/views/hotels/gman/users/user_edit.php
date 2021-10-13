<!-- Next User Add Page -->
<div class="content-wrapper">

    <?php if ($this->session->flashdata('user_edit_msg') !== NULL) {
        echo '<h3 class="text-center text-success">'.$this->session->flashdata('user_edit_msg').'</h3>';
    } ?>
    <?= validation_errors() ?>
    <?php echo form_open('gman/users/edit/'.$user->gman_ID); ?>
    <div class="container-fluid mt-3">
        <h3 class="text-center p-2">Edit <span class="text-muted"><?= $user->gman_FullName ?></span>Details</h3>
        <div class="card p-4">
            <div class="card-card-body">
                <input type="hidden" name="user_id" value="<?= $user->gman_ID ?>" />
                <div class="row">
                    <div class="col-xl-6 col-md-12" data-uk-grid-margin>
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" class="form-control" name="FullName"
                                value="<?= $user->gman_FullName ?>" />
                        </div>
                    </div>
                    <div class="col-xl-6 col-mad-12">
                        <div class="form-group">
                            <label>User Name</label>
                            <input type="text" class="form-control" name="LoginName" value="<?= $user->gman_Login ?>" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-4 col-md-12">
                        <div class="form-group">
                            <label>Passsword</label>
                            <input type="password" class="form-control" name="PassWord"
                                value="passwordforyourgmanaccount" disabled />
                            <span class="text-muted"><a href="#">Change Password</a></span>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-12">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="text" class="form-control" name="Email" value="<?= $user->gman_Email ?>" />
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-12">
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="text" class="form-control" name="PhoneNum" value="<?= $user->gman_phone ?>" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="text-center">
                        <input type="submit" value="Save" class="btn btn-block btn-outline-primary" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo form_close();?>
</div>