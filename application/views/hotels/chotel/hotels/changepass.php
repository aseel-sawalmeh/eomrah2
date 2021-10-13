<div class="content-wrapper">
    <div class="container">

        <h2 class="text-center p-3">Update Password</h2>
        <?php echo $this->session->flashdata('huser_pass_updated');?>


        <div class="row">
            <div class="col-md-8 mx-auto">
               
                <div class="card">
                    <div class="card-body">
                        <?= form_open('chotel/main/changepassword') ?>
                        <div class="form-group">
                            <label>Old Password</label>
                            <input type="password" name="oldpass" placholder="Enter Your Old Password" class="form-control" />
                            <?= form_error('oldpass') ?>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="pass" placholder="Enter Your Password" class="form-control" />
                            <?= form_error('pass') ?>
                        </div>
                        <div class="form-group">
                            <label>Confirm Password</label>
                            <input type="password" name="confirm_pass" placholder="Confirm Password" class="form-control" />
                            <?= form_error('confirm_pass') ?>
                        </div>
                        <div class="text-center">
                            <input type="submit" class="btn btn-block btn-info" value="Update Password" />
                        </div>
                        <?= form_close() ?>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>