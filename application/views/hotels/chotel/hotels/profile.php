<div class="content-wrapper">
    <div class="container">
        <h2 class="text-center p-3">Edit Profile</h2>
        <h4><?=$this->session->flashdata('user_updated')?></h4>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <?=form_open('chotel/main/edit/'.$user->H_User_ID)?>
                            <div class="row">
                                <div class="col-xl-6 col-md-12">
                                    <div class="form-group">
                                        <label>User Name</label>
                                        <input type="text" class="form-control" name="husername" value="<?=$user->H_UserName?>"/>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-12">
                                    <div class="form-group">
                                        <label>Full Name</label>
                                        <input type="text" class="form-control" name="hfullname" value="<?=$user->H_User_FullName?>"/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-4 col-md-12">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" class="form-control"  name="hemail" value="<?=$user->H_User_Email?>"/>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-12">
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input type="text" class="form-control"  name="hphone" value="<?=$user->H_User_Phone?>"/>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-12">
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input type="password" class="form-control" value="passwordforyourpanel" disabled/>
                                        <span class="text-muted"><a href="<?=site_url('chotel/main/changepassword')?>">Change Password</a></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="text-center">
                                        <input type="submit" value="Edit" class="btn btn-block btn-outline-primary"/>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>