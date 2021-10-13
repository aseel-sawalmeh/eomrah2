<div class="container mt-5 pb-5">
    <div class="row m-5 p-5">
        <div class="col m-2 p-5 text-center">
            <img src="<?= base_url('public_designs/assets/img/logo.png') ?>" class="img-fluid m-5" width="300px" height="180px" alt="">
            <h3 class="text-center"><?= lang("dearuser") ?></h3>
            <h4 class="text-center pt-5"><?= $this->session->flashdata('user_registerd') ?> <i style="color:green" class="fas fa-check"></i></h4>
        </div>
    </div>
</div>