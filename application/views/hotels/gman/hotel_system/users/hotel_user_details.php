<div class="content-wrapper">
    <div class="container">
        <div class="row">
            <div class="col bg-white mt-3 p-3">
                <?php echo form_open(site_url('gman/hotel_system/husers/activate_hotel_user/'. $details->H_User_ID)) ?>

                <div class="row">
                    <div class="col-xl-6 col-md-12">
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" class="form-control" name="husername" value="<?= $details->H_User_FullName ?>" id="">
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-12">
                        <label>User Name</label>
                        <input type="text" class="form-control" name="hfullname" value="<?= $details->H_UserName ?>" id="">
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-md-12">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="text" class="form-control" name="hemail" value="<?= $details->H_User_Email ?>" id="">
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-12">
                        <label>Contact</label>
                        <input type="text" class="form-control" name="hphone" value="<?= $details->H_User_Phone ?>" id="">
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="text-center">
                            <input type="submit" value="Activate" class="btn btn-block btn-success">
                        </div>
                    </div>
                </div>


                <?= form_close() ?>

            </div>
        </div>


    </div>

</div>