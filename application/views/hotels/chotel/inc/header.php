<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!doctype html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title><?= $title ?></title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="<?= base_url('admin_design/plugins/fontawesome-free/css/all.min.css') ?>">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="<?= base_url('admin_design/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('admin_design/plugins/icheck-bootstrap/icheck-bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('admin_design/dist/css/adminlte.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('admin_design/dist/css/adminlte.css') ?>">
    <link href="<?=base_url('admin_design/custom.css')?>" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('admin_design/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('admin_design/plugins/daterangepicker/daterangepicker.css') ?>">
    <link rel="stylesheet" href="<?= base_url('admin_design/plugins/summernote/summernote-bs4.css') ?>">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

</head>

<body class="hold-transition">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="<?= base_url() ?>chotel" class="nav-link">Home</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-bell"></i>
                    <span class="badge badge-warning navbar-badge">2</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-footer">See All Notification</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                    <span>Actions</span>
                       
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <a href="<?= base_url('chotel') ?>/logout" class="dropdown-item dropdown-footer">Logout</a>
                    </div>
                </li>
            </ul>
        </nav>


        <aside class="main-sidebar sidebar-dark-info elevation-3">
            <a href="<?= base_url() ?>chotel" class="brand-link">
                <img src="<?=base_url('admin_design/img/logo_sticky.png')?>" class="img-fluid" style="opacity: .8">
            </a>
            <div class="sidebar">
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-copy"></i>
                                <p>
                                    Hotels
                                    <i class="nav-icon fas fa-angle-left right"></i>
                                    <span class="badge badge-info right"></span>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?= base_url('chotel') ?>/provider/pending_list" class="nav-link">
                                        <i class="nav-icon fas fa-hotel"></i>
                                        <p>Pending Hotels List</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= base_url('chotel') ?>/provider/active_list" class="nav-link">
                                        <i class="nav-icon fas fa-hotel"></i>
                                        <p>Active Hotels List</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-chart-pie"></i>
                                <p>
                                    Sales Reports
                                </p>
                                <i class="nav-icon fas fa-angle-left right"></i>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?= base_url('chotel') ?>/main/b2c_invoices" class="nav-link">
                                        <i class="nav-icon fas fa-file-invoice-dollar"></i>
                                        <p>B2C Invoices</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= base_url('chotel') ?>/main/b2b_invoices" class="nav-link">
                                        <i class="nav-icon fas fa-file-invoice-dollar"></i>
                                        <p>B2B Invoices</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fab fa-product-hunt"></i>
                                <p>
                                   Property Listing
                                    <i class="nav-icon fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                               <!-- <li class="nav-item">
                                    <a href="<?= base_url('chotel') ?>/provider/add_provider" class="nav-link">
                                        <i class="nav-icon fas fa-hand-pointer"></i>
                                        <p>Request Providing</p>
                                    </a>
                                </li>-->
                                <li class="nav-item">
                                    <a href="<?= base_url("chotel/huserinit") ?>" class="nav-link">
                                        <i class="nav-icon fas fa-address-card"></i>
                                        <p>Add Property</p>
                                    </a>
                                </li>
                               <!-- <li class="nav-item">
                                    <a href="<?= base_url("chotel/hotel/pending_requests") ?>" class="nav-link">
                                        <i class="nav-icon fas fa-address-card"></i>
                                        <p>Request Status</p>
                                    </a>
                                </li>-->
                            </ul>
                        </li>

                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fab fa-product-hunt"></i>
                                <p>
                                  Profile
                                    <i class="nav-icon fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?= base_url("chotel/main/profile") ?>" class="nav-link">
                                        <i class="nav-icon fas fa-address-card"></i>
                                        <p>Edit Profile</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>

            </div>

        </aside>