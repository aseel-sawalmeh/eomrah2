<!DOCTYPE html>
<html lang="<?= userlang() ?>" dir="<?= lngdir() ?>">
<?php $altlang = (userlang() == 'ar') ? 'en' : 'ar';
$altlangname = (userlang() == 'ar') ? 'english' : 'العربية';
$ifget = $_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '';
$ogurl =  site_url() . str_replace('/index', '', get_instance()->uri->ruri_string()) . $ifget;
?>

<head>
    <title> eomrah.com | <?= lang('eomrah') . ' | ' . $title ?> </title>
    <meta name="robots" content="index, follow">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= isset($hotel->Hotel_Description) ? $hotel->Hotel_Description : lang('bookwithus'); ?>" />
    <meta name="keywords" content="<?= lang('site_kwords') ?>" />
    <link rel="canonical" href="<?= $ogurl ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="eomrah.com | <?= lang('eomrah') ?>" />
    <meta property="fb:pages" content="112931067233997" />
    <meta property="og:locale" content="<?= userlang() ?>" />
    <meta property="og:locale:alternate" content="<?= $altlang ?>" />
    <meta property="og:title" content="eomrah.com | <?= lang('eomrah') . ' | ' . $title ?>" />
    <meta property="og:description" content="<?= lang('bookwithus'); ?>" />
    <meta property="og:image" content="<?= (isset($metaImg) ? $metaImg : base_url('public_designs/assets/icons/eomrah512.png')) ?>" />
    <meta property="og:image:type" content="image/png" />
    <meta property="og:image:width" content="500" />
    <meta property="og:image:height" content="500" />
    <meta property="og:url" content="<?= $ogurl ?>" />
    <meta http-equiv="content-language" content="<?= userlang() ?>" />
    <meta http-equiv="content-script-type" content="text/javascript" />
    <meta http-equiv="content-style-type" content="text/css" />
    <link rel="icon" href="<?= base_url('public_designs/assets/icons/eomrah.ico') ?>" />
    <link rel="shortcut icon" type="image/png" href="<?= base_url('public_designs/assets/icons/eomrah32.png') ?>" />
    <link rel="apple-touch-icon" type="image/png" href="<?= base_url('public_designs/assets/icons/apple-touch-icon.png') ?>" />

    <link rel="alternate" type="text/html" hreflang="<?= $altlang ?>" href="<?= chLang2() ?>" title="<?= $altlangname ?>" />
    <link rel="help" href="<?= site_url('faq') ?>" />
    <link rel="author" href="<?= base_url() ?>" />
    <!--Twitter Meta Tags-->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="eomrah.com: <?= lang('bookwithus'); ?>">
    <meta name="twitter:description" content="<?= lang('site_desc') ?>">
    <meta name=”twitter:image” content=”<?= base_url('public_designs/assets/img/logo.png') ?>”>
    <meta name=”twitter:site” content=”@eomrahofficial>
    <meta name=”twitter:creator” content=”@eomrahofficial>

    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "company",
            "url": "https://www.eomrah.com/",
            "potentialAction": {
                "@type": "SearchAction",
                "target": "https://eomrah.com/<?= userlang() ?>/hotels/search?dest={city}",
                "query-input": "required name=city"
            }
        }
    </script>
    <?php if (lngdir() == "rtl") : ?>
        <link href="<?= base_url('public_designs/assets') ?>/css/bootstrap.rtl.min.css" rel="stylesheet">
        <link href="<?= base_url('public_designs/assets') ?>/css/mdb.min.css" rel="stylesheet">
        <link href="<?= base_url('public_designs/assets') ?>/css/custom-rtl.css" rel="stylesheet">
    <?php else : ?>
        <link href="<?= base_url('public_designs/assets') ?>/css/bootstrap-4.4.1-min.css" rel="stylesheet">
        <link href="<?= base_url('public_designs/assets') ?>/css/mdb.min.css" rel="stylesheet">
        <link href="<?= base_url('public_designs/assets') ?>/css/custom.css" rel="stylesheet">
    <?php endif; ?>
    <link href="<?= base_url('public_designs/assets') ?>/css/slick.css" rel="stylesheet">
    <link href="<?= base_url('public_designs/assets') ?>/css/slick-theme.css" rel="stylesheet">
    <script src="<?php echo base_url('public_designs/assets') ?>/js/jquery-3.4.1-slim-min.js"></script>
    <script src="<?php echo base_url('public_designs/assets') ?>/js/fa.js" defer></script>
    <script src="<?php echo base_url('public_designs/assets') ?>/js/vplugs/ax.js" defer></script>
    <script src="<?php echo base_url('public_designs/assets') ?>/js/vplugs/vxplugmain.js" defer></script>
    <script src="<?php echo base_url('public_designs/assets') ?>/js/vplugs/vplugmain.js"></script>
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-28LN8NH045"></script>
    
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'G-28LN8NH045');
    </script>
</head>


<body <?= (lngdir() == "rtl") ? 'style="text-align:right"' : '' ?>>
    <script>
        <?php
        $falldf = $this->input->get('dt1') ?? date('Y-m-d', strtotime('now +2 days'));
        $falldt = $this->input->get('dt2') ?? date('Y-m-d', strtotime('now +5 days'));
        //all needed parameters for all
        $params = [
            'surl' => site_url(),
            'burl' => base_url(),
            $this->security->get_csrf_token_name() => $this->security->get_csrf_hash(),
            'fdf' => $falldf,
            'fdt' => $falldt,
            'trkeys' => [
                'add' => lang('add'),
                'delete' => lang('delete'),
                'book' => lang('book'),
                'roomnotavailable' => lang('roomnotavailable'),
                'roomOcc' => lang('roomOcc'),
                'mxadults' => lang('mxadults'),
                'mxchilds' => lang('mxchilds'),
                'mxbeds' => lang('mxbeds'),
                'usererror'=> lang('user_error'),
                'usertaken' => lang('usernametaken'),
                'emailtaken' => lang("emailtaken"),
                'phonetaken' => lang('phone_exists')
            ],
        ];
        echo 'var dataParams =' . json_encode($params);
        ?>
    </script>
    <header class="mh sticky-top">
        <div class="container pt-1 pb-1 px-0">
            <nav class="navbar navbar-expand-lg">
                <a class="navbar-brand" href="<?= site_url() ?>">
                    <?php if (lngdir() == "rtl") : ?>
                        <img src="<?= base_url('public_designs/assets/img/logo.png') ?>" class="img-fluid" width="160px" height="50px" alt="">
                    <?php else : ?>
                        <img src="<?= base_url('public_designs/assets/img/logo.png') ?>" class="img-fluid" width="160px" height="50px" alt="">
                    <?php endif; ?>
                </a>
                <a class="d-block d-md-none mcolor" href="<?= chLang2() ?>">
                    <?= langlabel() ?></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainMenu" aria-controls="mainMenu" aria-expanded="false" aria-label="Toggle navigation">
                    <!-- <small class="align-self-center">menu</small> -->
                    <span class="navbar-toggler-icon text-center"><i class="fa fa-bars mcolor"></i><small style="font-size: 60%;"><?= lang('menu') ?></small></span>
                </button>

                <div class="collapse navbar-collapse" id="mainMenu">
                    <ul class="navbar-nav mx-auto mt-3">
                        <li class="nav-item">
                            <a class="nav-link <?= $this->router->fetch_class() == 'home' ? 'active' : '' ?>" href="<?= site_url('home') ?>"><?= lang('home') ?></a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link <?= $this->router->fetch_class() == 'hotels' ? 'active' : '' ?>" href="<?php echo site_url('hotels'); ?>"><?= lang('hotels') ?></a>
                        </li>
                        <!-- 
                        <li class="nav-item">
                            <a class="nav-link <?= $this->router->fetch_class() == 'hotel_registration' ? 'active' : '' ?>" href="<?php echo site_url('hotel_registration'); ?>"><?= lang('b_reg') ?></a>
                        </li> -->

                        <?php if (!$this->session->userdata('user_data')['loggedIn']) : ?>
                            <li class="nav-item pt-1">
                                <button class="nav-link ebtn" data-toggle="modal" href="#" data-target="#signin"><i class="fa fa-user"></i><?= lang('login') ?></button>
                            </li>
                        <?php else : ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="user_dropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo $this->session->userdata('user_data')['userFname']; ?>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="user_dropdown">
                                    <a href="<?= site_url('user/profile') ?>" class="dropdown-item"><?= lang('prof') ?></a>
                                    <div class="dropdown-divider"></div>
                                    <a href="<?= site_url('plogin/puserlogout') ?>" class="dropdown-item"><?= lang('logout') ?></a>
                                </div>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <?php if (false) : ?>
                        <div class="pt-3 mx-5">
                            <ul class="navbar-nav">
                                <div class="b2b">
                                    <li class="nav-item dropdown clink">
                                        <a class="nav-link dropdown-toggle" href="#" id="user_dropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="mcolor">B</span>2<span class="mcolor">B</span>
                                        </a>
                                        <div class="dropdown-menu b2b-dropdown" aria-labelledby="user_dropdown">
                                            <a href="<?php echo site_url('b2b/register'); ?>" class="dropdown-item" target="__blank"><?= lang('b2breg') ?></a>
                                            <a href="<?php echo site_url('b2b/login'); ?>" class="dropdown-item" target="__blank"><?= lang('b2blogin') ?></a>
                                        </div>
                                    </li>
                                </div>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <div class="pt-3">
                        <ul class="navbar-nav">
                            <div class="currencies">
                                <?php if($this->router->fetch_class() == 'reservation' || $this->router->fetch_class() == 'user'): ?>
                                <li class="nav-item dropdown d-none">
                                    <a class="dropdown-toggle nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <?= usercur() ?>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown" style="overflow: scroll; height:80vh;">
                                        <?php if (currencies()) : foreach (currencies() as $cur) : ?>
                                                <a href="#" class="dropdown-item" onclick="chcur('<?= $cur['shortcut'] ?>')">
                                                    <?= ((userlang() == 'ar') ? $cur['name_ar'] : $cur['name_ar']) . ' <strong>' . $cur['shortcut'] . '</strong>'  ?></a>
                                        <?php endforeach;
                                        endif; ?>
                                    </div>
                                </li>
                                <?php else:?>
                                    <li class="nav-item dropdown">
                                    <a class="dropdown-toggle nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <?= usercur() ?>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown" style="overflow: scroll; height:80vh;">
                                        <?php if (currencies()) : foreach (currencies() as $cur) : ?>
                                                <a href="#" class="dropdown-item" onclick="chcur('<?= $cur['shortcut'] ?>')">
                                                    <?= ((userlang() == 'ar') ? $cur['name_ar'] : $cur['name_ar']) . ' <strong>' . $cur['shortcut'] . '</strong>'  ?></a>
                                        <?php endforeach;
                                        endif; ?>
                                    </div>
                                </li>
                                <?php endif; ?>

                            </div>
                            <div class="langs">
                                <li class="nav-item dropdown">
                                    <a class="nav-link" href="<?= chLang2() ?>">
                                        <?= langlabel() ?>
                                    </a>
                            </div>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </header>