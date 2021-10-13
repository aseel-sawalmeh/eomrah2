<div class="container my-5 p-5">
    <div class="row ">
        <div class="col-sm col-md-6 mx-auto">
            <div class="card signinBox">
                <div class="card-header d-flex justify-content-between p-4">
                    <h3> <span class="mcolor">B</span>2<span class="mcolor">B</span> Login</h3>
                    <img src="<?= base_url('public_designs/assets/img/logo.png') ?>" class="img-fluid" width="160px" height="50px" alt="">
                </div>
                <div class="card-body p-3">
                    <?= form_open("b2b/login", ["class" => "form p-5"]) ?>
                    <div class="row mt-4">
                        <div class="form-group col-sm col-md-12">
                            <label>Email</label>
                            <input type="email" class="form-control" name="company_email" />
                            <?php echo form_error("company_email"); ?>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-sm col-md-12">
                            <label>Password</label>
                            <input type="password" class="form-control" name="company_password" />
                            <?php echo form_error("company_password"); ?>
                        </div>
                    </div>
                    <div class="row text-center">
                        <div class="col mx-auto">
                            <input type="submit" class="btn btn-lg ebtn p-2" value="Login">
                            <p class="pt-3 text-center"> <span>Not a member? <a class="clink" href="<?= site_url('b2b/register') ?>">Register</a></span>
                            </p>
                        </div>
                    </div>
                    <?= form_close() ?>
                </div>
            </div>
        </div>
    </div>
</div>