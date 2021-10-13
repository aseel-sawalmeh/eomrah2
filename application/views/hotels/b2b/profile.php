
    <div class="container-fluid">
        <div class="row p-2 bg-white">
            <div class="col-12">
                
                    <div class="header">
                        <h4><?=lang('edit_profile')?></h4>
                        <?=$this->session->flashdata('b2b_updated')?>
                    </div>
                    <hr>
                    <div class="content">
                        <?php echo form_open('b2b/dashboard/update_profile');?>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><?=lang('companyname')?></label>
                                        <input type="text" class="form-control" disabled value="<?php echo $this->session->userdata("Company_Name");?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><?=lang('phone_number')?></label>
                                        <input type="text" class="form-control" name="c_phone" value="<?php echo $user->C_PhoneNumber;?>" placeholder="Company Phone" >
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?=lang('em')?></label>
                                        <input type="email" name="c_email" class="form-control" placeholder="Email" 
                                        value="<?php echo $user->C_Email;?>"  >
                                    </div>
                                </div>  
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?=lang('country')?></label>
                                        <input type="text" class="form-control" disabled placeholder="Country" 
                                        value="<?php echo $this->session->userdata("Country");?>" >
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label><?=lang('fullname')?></label>
                                        <input type="text" class="form-control" name="c_fullname"  placeholder="Full Name" 
                                        value="<?php echo $user->C_FullName;?>">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label><?=lang('mobilenumber')?></label>
                                        <input type="text" class="form-control" name="c_mobile" placeholder="Last Name" value="<?php echo $user->C_MobileNumber;?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?=lang('iban')?></label>
                                        <input type="text" class="form-control" disabled placeholder="IBAN"  
                                        value="<?php echo $this->session->userdata("IBAN");?>" >
                                    </div>
                                </div> 
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?=lang('pass')?></label>
                                        <input type="password" class="form-control" disabled value="thepasswordofyourregisterdb2baccount">
                                        <small><a href="<?=site_url('b2b/dashboard/changepassword')?>">
                                       <?=lang('changepass')?></a></small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?=lang("vat")?></label>
                                        <input type="text" class="form-control" disabled 
                                        value="<?php echo $this->session->userdata("C_Vat");?>" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?=lang('regnumber')?></label>
                                        <input type="text" class="form-control" disabled 
                                        value="<?php echo $this->session->userdata("C_Reg");?>" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?=lang('hajjlicense')?></label>
                                        <input type="number" class="form-control" disabled value="<?php echo $this->session->userdata("C_License");?>">
                                    </div>
                                </div>
                            </div>

                            <input  type="submit" class="btn btn-lg ebtn p-2" value="<?=lang("conf")?>">
                            <div class="clearfix"></div>
                        <?php echo form_close();?>
                    </div>
              
            </div>
        </div>
    </div>
