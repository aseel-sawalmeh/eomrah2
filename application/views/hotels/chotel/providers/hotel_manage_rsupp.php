<!--<div class="md-card uk-margin-medium-bottom">
    <div class="md-card-content">
        <div class="uk-overflow-container">
              
            <div class="md-card">
                <div class="md-card-content">
            <h2 class="uk-text-center">Hotel Supplement</h2>
         
          
        </div>
        </div>
        
</div>
</div>-->

    <div class="card">
        <div class="card-header">
            <?php if ($this->session->flashdata('Supplement_mgs') !== NULL) {
                echo $this->session->flashdata('Supplement_mgs');
            } ?>
            <?= $this->session->flashdata('Periodmsg') ?>
            <h4 class="text-center">Room Supplements</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-6 mx-auto">
                    <?php if ($roomSuppTypes) : foreach ($roomSuppTypes as $rstype) : ?>
                            <?= form_open(site_url("chotel/hotel_manage/editroom_supp"))?>
                          

                                <input type="hidden" name="rsupp_id" value="<?= $rstype->R_SuppType_ID ?>" />
                                <input type="hidden" name="provider_id" value="<?= $provider->Provider_ID ?>" />

                                <label class="uk-form-label" for="En">En</label>

                                <input class="form-control" id="En" type="text" name="rsupp_name" value="<?= $rstype->R_SuppType_Name ?>" />
                                <br>
                                <a class="btn btn-success" href="trans">Update Language</a>
                                <input class="btn btn-primary" type="submit" value="Edit" />
                                <a class="btn btn-danger" href="<?= base_url("chotel/hotel_manage/delroom_supptype/{$provider->Provider_ID}/{$rstype->R_SuppType_ID}") ?>">Delete</a>
                            <?=form_close()?>
                            
                        <?php endforeach;
                    else : ?>
                        <h3 class="text-center">This Hotel Does Not Have Any Supplements</h3>
                    <?php endif; ?>
                    <hr>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 mx-auto">


                    <h4 class="text-center">Add New Supplement : </h4>
                   
                    <?=form_open(site_url("chotel/hotel_manage/addroom_supp"))?>
                        <input type="hidden" name="provider_id" value="<?= $provider->Provider_ID ?>" />

                        <label for="En">Supplement : </label>

                        <input class="form-control" id="En" type="text" name="rsupp_name" />
                        <br>
                        <input class="btn btn-success" type="submit" value="Add" />
                        <input class="btn btn-danger" type="submit" value="Cancel" />


                    <?=form_close()?>


                </div>
            </div>
        </div>
    </div>
</div>