<?php defined('BASEPATH') or exit('No Direct Access Allowd'); ?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?= $title ?></title>

  <meta name="viewport" content="width=device-width, initial-scale=1">


  <link rel="stylesheet" href="<?= base_url('admin_design/plugins/fontawesome-free/css/all.min.css') ?>">

  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

  <link rel="stylesheet" href="<?= base_url('admin_design/plugins/icheck-bootstrap/icheck-bootstrap.min.css') ?>">

  <link rel="stylesheet" href="<?= base_url('admin_design/dist/css/adminlte.min.css') ?>">

  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <div class="login-logo">
      <img src="<?= base_url('admin_design/img/logo_sticky.png') ?>" class="img-fluid" alt="">
    </div>

    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">Provider Panel</p>

        <?php echo form_open('chotel/login'); ?>
        <div class="text-center">
            <?= form_error('login_username') ?>
          </div>

        <div class="input-group p-3">
          <input class="form-control" type="text" id="login_username" name="login_username" />
         
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="text-center">
          <?= form_error('login_password') ?>
        </div>
        <div class="input-group p-3">
          <input class="form-control" type="password" id="login_password" name="login_password">


          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember">
              <label for="remember">
                Remember Me
              </label>
            </div>
          </div>

          <div class="col-4">
            <input type="submit" class="btn btn-primary btn-block" value="Submit">
          </div>

        </div>
        </form>
      </div>

    </div>
  </div>



  <script src="<?= base_url('admin_design/plugins/jquery/jquery.min.js') ?>"></script>

  <script src="<?= base_url('plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

  <script src="<?= base_url('dist/js/adminlte.min.js') ?>"></script>

</body>

</html>