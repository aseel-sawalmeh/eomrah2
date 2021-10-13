<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!doctype html>
<html lang="<?= userlang() ?>" dir="<?= lngdir() ?>">
<?php $altlang = (userlang() == 'ar') ? 'en' : 'ar';
$altlangname = (userlang() == 'ar') ? 'english' : 'العربية';
$ifget = $_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '';
$ogurl =  site_url() . str_replace('/index', '', get_instance()->uri->ruri_string()) . $ifget;
?>


<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title><?= $title ?></title>

    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- Bootstrap 4 RTL -->
    <?php if(lngdir() == "rtl"):?>
    <link rel="stylesheet" href="<?= base_url('admin_design/rtl/plugins/fontawesome-free/css/all.min.css') ?>">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
   
    <link rel="stylesheet" href="<?= base_url('admin_design/rtl/dist/css/adminlte.min.css') ?>">
    <link href="<?= base_url('admin_design/custom.css') ?>" rel="stylesheet">
    <link rel="stylesheet"
        href="<?= base_url('admin_design/rtl/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('admin_design/rtl/plugins/daterangepicker/daterangepicker.css') ?>">
    <link rel="stylesheet" href="<?= base_url('admin_design/rtl/plugins/summernote/summernote-bs4.css') ?>">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.rtlcss.com/bootstrap/v4.2.1/css/bootstrap.min.css">
    <!-- Custom style for RTL -->
    <link rel="stylesheet" href="<?=base_url('admin_design/rtl/dist/css/custom.css')?>">
    <?php else:?>
    <link rel="stylesheet" href="<?= base_url('admin_design/plugins/fontawesome-free/css/all.min.css') ?>">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet"
        href="<?= base_url('admin_design/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('admin_design/plugins/icheck-bootstrap/icheck-bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('admin_design/dist/css/adminlte.min.css') ?>">
    <link href="<?= base_url('admin_design/custom.css') ?>" rel="stylesheet">
    <link rel="stylesheet"
        href="<?= base_url('admin_design/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('admin_design/plugins/daterangepicker/daterangepicker.css') ?>">
    <link rel="stylesheet" href="<?= base_url('admin_design/plugins/summernote/summernote-bs4.css') ?>">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">


    <?php endif;?>



</head>



<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand">
            <ul class="navbar-nav">
                <li class="nav-item h-nav">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block h-nav">
                    <a href="<?= site_url() ?>gman" class="nav-link"><?=lang('home')?></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block h-nav">
                     <a href="<?= site_url() ?>gman/logout" class="nav-link"><?=lang('logout')?></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block h-nav">
                    <a class="nav-link" href="<?= chLang2() ?>"> <?= langlabel() ?></a>
                </li>

            </ul>
            <ul class="navbar-nav ml-auto">
                <!-- <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-bell"></i>
                        <span class="badge badge-warning navbar-badge">2</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-footer">See All Notification</a>
                    </div>
                </li> -->
              
            </ul>
        </nav>
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="<?= site_url() ?>gman" class="brand-link">
                <img src="<?= base_url('admin_design/img/logo_sticky.png') ?>" class="img-fluid" style="opacity: .8">

            </a>
            <div class="sidebar">
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">

                        <li class="nav-item has-treeview ">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-copy"></i>
                                <p>
                                   <?=lang('users')?>
                                    <i class="nav-icon fas fa-angle-left right"></i>
                                    <span class="badge badge-info right"></span>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?= site_url('gman') ?>/users/list" class="nav-link">
                                        <p><?=lang('adminusers')?></p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= site_url('gman') ?>/users/add" class="nav-link">
                                        <p><?=lang('addadmin')?></p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= site_url('gman') ?>/users/b2b_users" class="nav-link">
                                        <p><?=lang('b2busers')?></p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="<?= site_url('gman') ?>/users/b2c_users" class="nav-link">
                                        <p><?=lang('b2cusers')?></p>
                                    </a>
                                </li>
                                <!-- <li class="nav-item">
                                    <a href="<?= site_url('gman') ?>/hotel_system/husers/active" class="nav-link">
                                        <p>Active Hotel Users</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= site_url('gman') ?>/hotel_system/husers/inactive" class="nav-link">
                                        <p>Inactive Hotel Users</p>
                                    </a>
                                </li> -->

                            </ul>
                        </li>


                        
                       



                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-chart-pie"></i>
                                <p>
                                    <?=lang('blog')?>
                                </p>
                                <i class="nav-icon fas fa-angle-left right"></i>
                            </a>
                            <ul class="nav nav-treeview">
                                <!-- <li class="nav-item">
                                    <a class="nav-link" href="<?= site_url('gman') ?>/pcategory/list"><?=lang('blogcat')?></a>

                                </li> -->
                                <li class="nav-item"><a class="nav-link"
                                        href="<?= site_url('gman') ?>/products/active"><?=lang('activebloglist')?></a></li>
                                <li class="nav-item"><a class="nav-link"
                                        href="<?= site_url('gman') ?>/products/pending"><?=lang('inactivebloglist')?></a></li>
                                <li class="nav-item"><a class="nav-link" href="<?= site_url('gman') ?>/products/add"><?=lang('addblog')?></a></li>
                                <!-- <li class="nav-item"><a class="nav-link"
                                        href="<?= site_url('gman') ?>/prodblocks/list">Product position</a></li> -->
                            </ul>
                        </li>
                        <!-- <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fab fa-product-hunt"></i>
                                <p>
                                    Hotel Providers
                                    <i class="nav-icon fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">

                                <li class="nav-item">
                                    <a href="<?= site_url('gman/providers') ?>/active_list" class="nav-link">Active
                                        Providers</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= site_url('gman/providers') ?>/pending_list" class="nav-link">Inactive
                                        Providers</a>
                                </li>
                                <li class="nav-item"><a href="<?= site_url('gman/providers') ?>/requested_hotel"
                                        class="nav-link">New Requests</a></li>


                            </ul>
                        </li> -->
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-copy"></i>
                                <p>
                                   <?=lang('reports')?>
                                    <i class="nav-icon fas fa-angle-left right"></i>
                                    <span class="badge badge-info right"></span>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?= site_url('gman') ?>/main/b2c_reports" class="nav-link">
                                        <p><?= lang('b2cinvoice')?></p>
                                    </a>
                                </li>
                            </ul>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?= site_url('gman') ?>/main/b2b_reports" class="nav-link">
                                        <p><?= lang('b2binvoice')?></p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-copy"></i>
                                <p>
                                    <?=lang('pages')?>
                                    <i class="nav-icon fas fa-angle-left right"></i>
                                    <span class="badge badge-info right"></span>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?= site_url('gman/main/terms') ?>" class="nav-link">
                                        <p><?=lang('terms')?></p>
                                    </a>
                                    <a href="<?= site_url('gman/main/about') ?>" class="nav-link">
                                        <p><?=lang('aboutus')?></p>
                                    </a>
                                    <a href="<?= site_url('gman/faqs') ?>" class="nav-link">
                                        <p><?=lang('faqs')?></p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item has-treeview d-xl-none d-sm-block">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-cogs "></i>
                                
                                <p>
                                   <?=lang('options')?>
                                    <i class="nav-icon fas fa-angle-left right"></i>
                                    <span class="badge badge-info right"></span>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                   <a href="<?= site_url() ?>gman" class="nav-link"><p><?=lang('home')?></p></a>
                                </li>
                                <li class="nav-item">
                                <a href="<?= site_url() ?>gman/logout" class="nav-link"><p><?=lang('logout')?></p></a>
                                </li>
                               
                            </ul>
                        </li>


                    </ul>
                </nav>

            </div>

        </aside>