<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php if (lngdir() == "rtl") : ?>
        <link href="<?= base_url('public_designs/assets') ?>/css/bootstrap.rtl.min.css" rel="stylesheet">
    <?php else : ?>
        <link href="<?= base_url('public_designs/assets') ?>/css/bootstrap-4.4.1-min.css" rel="stylesheet">
    <?php endif; ?>

    <title>Document</title>
</head>

<body>

    <div class="container bg-light mx-auto mt-5">
        <div class="row mx-auto">
            <div class="col p-5">
                <h4 class="text-center">Dear User</h4>
                <h1 class="text-cente p-3"><?= $this->session->flashdata("b2b_registerd") ?></h1>
                <h6 class="text-center p-3">Kindly Check Your Email</h6>
            </div>
        </div>
    </div>



    <footer>
        <script src="<?php echo base_url('public_designs/assets', PROTO) ?>/js/jquery-3.4.1-slim-min.js"></script>
        <script src="<?php echo base_url('public_designs/assets',) ?>/js/bootstrap-4.4.1.js"></script>
        <script src="<?php echo base_url('public_designs/assets') ?>/js/popper.js"></script>
    </footer>

</body>

</html>