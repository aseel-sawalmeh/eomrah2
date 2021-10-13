<?php defined('BASEPATH') or exit('No direct script access allowed');?>
<!doctype html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title><?=$title?></title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="<?=base_url('admin_design/plugins/fontawesome-free/css/all.min.css')?>">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet"
        href="<?=base_url('admin_design/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')?>">
    <link rel="stylesheet" href="<?=base_url('admin_design/plugins/icheck-bootstrap/icheck-bootstrap.min.css')?>">
    <link rel="stylesheet" href="<?=base_url('admin_design/dist/css/adminlte.min.css')?>">
    <link href="<?=base_url('admin_design/custom.css')?>" rel="stylesheet">
    <link rel="stylesheet" href="<?=base_url('admin_design/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')?>">
    <link rel="stylesheet" href="<?=base_url('admin_design/plugins/daterangepicker/daterangepicker.css')?>">
    <link rel="stylesheet" href="<?=base_url('admin_design/plugins/summernote/summernote-bs4.css')?>">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <script src="<?=base_url('admin_design/js/vplugs/ax.js')?>"></script>
    <script src="<?=base_url('admin_design/js/vplugs/vxplugmain.js')?>"></script>
    <script src="<?=base_url('admin_design/js/vplugs/vplugmain.js')?>"></script>

</head>

<body class="hold-transition">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
                </li>
              
            </ul>
            <ul class="navbar-nav ml-auto">
               
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <span>Actions</span>

                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <a href="<?=base_url('chotel')?>/logout" class="dropdown-item dropdown-footer">Logout</a>
                    </div>
                </li>
            </ul>
        </nav>


        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="#" class="brand-link">
                <img src="<?=base_url('admin_design/img/logo_sticky.png')?>" class="img-fluid" style="opacity: .8">

            </a>
            <div class="sidebar">
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                       
                    
                    </ul>
                </nav>

            </div>

        </aside>