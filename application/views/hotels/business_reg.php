
<div class="container p-4 bg-white mt-4">
    <div class="row">
        <div class="col-sm-12">
        <h3 class="text-center"><?=lang("dearuser")?></h3>
            <hr>
            <div class="text-center">
                <span><?=$this->session->flashdata('b_register')?>  </span><i style="color:green" class="fas fa-check"></i>
            </div>
         
            <hr>
        </div>
    </div>
</div>
<script src="<?php echo base_url('public_designs/assets') ?>/js/popper.js"></script>
<script src="<?php echo base_url('public_designs/assets',) ?>/js/bootstrap-4.4.1.js"></script>