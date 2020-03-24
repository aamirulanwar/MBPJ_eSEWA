<!DOCTYPE html>
<!--
* CoreUI - Free Bootstrap Admin Template
* @version v2.0.0
* @link https://coreui.io
* Copyright (c) 2018 creativeLabs Łukasz Holeczek
* Licensed under MIT (https://coreui.io/license)
-->

<html lang="en">
<head>
    <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="CoreUI - Open Source Bootstrap Admin Template">
    <meta name="author" content="Łukasz Holeczek">
    <meta name="keyword" content="Bootstrap,Admin,Template,Open,Source,jQuery,CSS,HTML,RWD,Dashboard">
    <title>Sistem Pengurusan Sewaan MPKj</title>
    <!-- Icons-->
    <link rel="icon" href="<?=base_url()?>/favicon.ico" type="image/x-icon">
    <link href="/assets/node_modules/@coreui/icons/css/coreui-icons.min.css" rel="stylesheet">
    <link href="/assets/node_modules/flag-icon-css/css/flag-icon.min.css" rel="stylesheet">
    <link href="/assets/node_modules/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="/assets/node_modules/simple-line-icons/css/simple-line-icons.css" rel="stylesheet">
    <!-- Main styles for this application-->
    <link href="/assets/css/style.css" rel="stylesheet">
    <link href="/assets/css/style_extend.css" rel="stylesheet">
    <link href="/assets/node_modules/pace-progress/css/pace.min.css" rel="stylesheet">
    <!-- Global site tag (gtag.js) - Google Analytics-->
    <!-- <script async="" src="https://www.googletagmanager.com/gtag/js?id=UA-118965717-3"></script> -->
    <!-- Date picker -->
    <link href="/assets/jquery-ui/jquery-ui.min.css" rel="stylesheet">
    <link href="/assets/jquery-ui/jquery-ui.structure.min.css" rel="stylesheet">
    <script src="/assets/node_modules/jquery/dist/jquery.min.js"></script>
    <script src="/assets/Chart.bundle.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.js" integrity="sha256-qSIshlknROr4J8GMHRlW3fGKrPki733tLq+qeMCR05Q=" crossorigin="anonymous"></script> -->
    <!-- select2 -->
    <link href="/assets/select2/dist/css/select2.min.css" rel="stylesheet">
    <link href="/assets/select2/dist/css/bootstrap-select2.css" rel="stylesheet">
    <script src="/assets/select2/dist/js/select2.min.js"></script>
    <!-- dataTables -->
    <link href="/assets/dataTables/jquery.dataTables.min.css" rel="stylesheet">
    <link href="/assets/dataTables/buttons.dataTables.min.css" rel="stylesheet">
    <script src="/assets/dataTables/jquery.dataTables.min.js"></script>
    <script src="/assets/dataTables/dataTables.buttons.min.js"></script>
    <script src="/assets/dataTables/buttons.print.js"></script>
    <script>
        const DEPENDENT_LIST_ARR = '<?php echo json_encode(DEPENDENT_LIST_ARR)?>';
    </script>

    <style>
        .need-print{
            display: none;
        }
        .select2-container .select2-selection--single {
            height: 36px;
        }
        .select2-container--default .select2-selection--single {
            border: 1px solid #dedede;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #808080;
            line-height: 33px;
        }
        @media print{
            .breadcrumb{
                display: none;
            }

            .form-control{
                border: 0px;
            }

            .btn{
                display: none;
            }
            .no-need-print{
                display: none;
            }

            .card{
                border: 0px;
            }
            .need-print{
                display: block;
            }
            .table-responsive{
                overflow: visible;
            }

            .table-own{
                /*overflow: hidden;*/
                height: 100% !important;
            }
        }

        .table-scroll{
            /*width:100%; */
            display: block;
            empty-cells: show;

            /* Decoration */
            border-spacing: 0;
            /*border: 1px solid;*/
        }

        .table-scroll thead{
            /*background-color: #f1f1f1;*/
            position:relative;
            display: block;
            width:100%;
            overflow-y: scroll;
        }

        .table-scroll tbody{
            /* Position */
            display: block; position:relative;
            width:100%; overflow-y:scroll;
        }

        .table-scroll tr{
            width: 100%;
            display:flex;
        }

        .table-scroll td,.table-scroll th{
            /*flex-basis:100%;*/
            /*flex-grow:2;*/
            display: block;
            /*text-align:center;*/
        }

        .table-scroll th{
            text-align: center;
        }

        /* Other options */

        .table-scroll.small-first-col td:first-child,
        .table-scroll.small-first-col th:first-child{
            flex-basis:20%;
            flex-grow:1;
        }

        .table-scroll tbody tr:nth-child(2n){
            /*background-color: rgba(130,130,170,0.1);*/
        }

        tbody{
            max-height: 50vh;
        }

        @media print{
            .table-scroll tbody{
                /* Position */
                display: block;
                position:relative;
                width:100%;
                overflow-y:hidden;
            }

            .table-scroll thead{
                /*background-color: #f1f1f1;*/
                position:relative;
                display: block;
                width:100%;
                overflow-y: hidden;
            }

            .table-scroll tbody{
                /*max-height: 100%;*/
            }
        }
    </style>

</head>
<body class="app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show">
<header class="app-header navbar">
    <button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" type="button" data-toggle="sidebar-show">
        <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="#">
        <img class="navbar-brand-full" src="/assets/images/sys-logo.png" width="120" alt="MPKj">
    </a>
    <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button" data-toggle="sidebar-lg-show">
        <span class="navbar-toggler-icon"></span>
    </button>
<!--    <ul class="nav navbar-nav d-md-down-none">-->
<!--        <li class="nav-item px-3">-->
<!--            <a class="nav-link" href="#">Dashboard</a>-->
<!--        </li>-->
<!--        <li class="nav-item px-3">-->
<!--            <a class="nav-link" href="#">Users</a>-->
<!--        </li>-->
<!--        <li class="nav-item px-3">-->
<!--            <a class="nav-link" href="#">Settings</a>-->
<!--        </li>-->
<!--    </ul>-->
    <ul class="nav navbar-nav ml-auto">
<!--        <li class="nav-item d-md-down-none">-->
<!--            <a class="nav-link" href="#">-->
<!--                <i class="icon-bell"></i>-->
<!--                <span class="badge badge-pill badge-danger">0</span>-->
<!--            </a>-->
<!--        </li>-->
<!--        <li class="nav-item d-md-down-none">-->
<!--            <a class="nav-link" href="#">-->
<!--                <i class="icon-list"></i>-->
<!--            </a>-->
<!--        </li>-->
<!--        <li class="nav-item d-md-down-none">-->
<!--            <a class="nav-link" href="#">-->
<!--                <i class="icon-location-pin"></i>-->
<!--            </a>-->
<!--        </li>-->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="/profile" role="button" aria-haspopup="true"
               aria-expanded="false">
                <img class="img-avatar" src="/assets/images/user.png" alt="administrator"><?php echo $this->curuser['USER_NAME']?>&nbsp;&nbsp;
            </a>
            <div class="dropdown-menu dropdown-menu-right">
<!--                <div class="dropdown-header text-center">-->
<!--                    <strong>Account</strong>-->
<!--                </div>-->
<!--                <a class="dropdown-item" href="#">-->
<!--                    <i class="fa fa-bell-o"></i> Updates-->
<!--                    <span class="badge badge-info">42</span>-->
<!--                </a>-->
<!--                <a class="dropdown-item" href="#">-->
<!--                    <i class="fa fa-envelope-o"></i> Messages-->
<!--                    <span class="badge badge-success">42</span>-->
<!--                </a>-->
<!--                <a class="dropdown-item" href="#">-->
<!--                    <i class="fa fa-tasks"></i> Tasks-->
<!--                    <span class="badge badge-danger">42</span>-->
<!--                </a>-->
<!--                <a class="dropdown-item" href="#">-->
<!--                    <i class="fa fa-comments"></i> Comments-->
<!--                    <span class="badge badge-warning">42</span>-->
<!--                </a>-->
<!--                <div class="dropdown-header text-center">-->
<!--                    <strong>Settings</strong>-->
<!--                </div>-->
                <a class="dropdown-item" href="/profile">
                    <i class="fa fa-user"></i> Profil</a>
<!--                <a class="dropdown-item" href="#">-->
<!--                    <i class="fa fa-wrench"></i> Settings</a>-->
<!--                <a class="dropdown-item" href="#">-->
<!--                    <i class="fa fa-usd"></i> Payments-->
<!--                    <span class="badge badge-secondary">42</span>-->
<!--                </a>-->
<!--                <a class="dropdown-item" href="#">-->
<!--                    <i class="fa fa-file"></i> Projects-->
<!--                    <span class="badge badge-primary">42</span>-->
<!--                </a>-->
<!--                <div class="divider"></div>-->
<!--                <a class="dropdown-item" href="#">-->
<!--                    <i class="fa fa-shield"></i> Lock Account</a>-->
                <a class="dropdown-item" href="/logout">
                    <i class="fa fa-lock"></i> Log keluar        </a>
            </div>
        </li>
    </ul>
<!--    <button class="navbar-toggler aside-menu-toggler d-md-down-none" type="button" data-toggle="aside-menu-lg-show">-->
<!--        <span class="navbar-toggler-icon"></span>-->
<!--    </button>-->
<!--    <button class="navbar-toggler aside-menu-toggler d-lg-none" type="button" data-toggle="aside-menu-show">-->
<!--        <span class="navbar-toggler-icon"></span>-->
<!--    </button>-->
</header>
<div class="app-body">
    <div class="sidebar">
        <nav class="sidebar-nav">
            <ul class="nav">
                <?php if($this->auth->access_main_view($this->curuser,array(1000))):?>
                    <?php if($this->auth->access_view($this->curuser,array(1001))):?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo set_active_menu(uri_segment(1),array('dashboard'))?>" href="/">
                            <i class="nav-icon icon-speedometer"></i> Dashboard
                        </a>
                    </li>
                    <?php endif;?>
                <?php endif;?>

                <?php if($this->auth->access_main_view($this->curuser,array(2000))):?>
                <li class="nav-item nav-dropdown <?php echo set_active_dropdown(uri_segment(1),array('department','user_group','user','audit_trail'))?>">
                    <a class="nav-link nav-dropdown-toggle <?php echo set_active_menu(uri_segment(1),array('department','user_group','user','audit_trail'))?>" href="#1">
                        <i class="nav-icon icon-key"></i> Pentadbir</a>
                    <ul class="nav-dropdown-items">
                        <?php if($this->auth->access_view($this->curuser,array(2001))):?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo set_active_menu(uri_segment(1),array('department'))?>" href="/department">
                                <i class="nav-icon fa fa-circle fa-sm"></i> Jabatan/Bahagian</a>
                        </li>
                        <?php endif;?>
                        <?php if($this->auth->access_view($this->curuser,array(2005))):?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo set_active_menu(uri_segment(1),array('user_group'))?>" href="/user_group">
                                <i class="nav-icon fa fa-circle fa-sm"></i> Kumpulan Pengguna</a>
                        </li>
                        <?php endif;?>
                        <?php if($this->auth->access_view($this->curuser,array(2009))):?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo set_active_menu(uri_segment(1),array('user'))?>" href="/user">
                                <i class="nav-icon fa fa-circle fa-sm"></i> Pengguna</a>
                        </li>
                        <?php endif;?>
                        <?php if($this->auth->access_view($this->curuser,array(2013))):?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo set_active_menu(uri_segment(1),array('audit_trail'))?>" href="/audit_trail">
                                <i class="nav-icon fa fa-circle fa-sm"></i> Audit Trail</a>
                        </li>
                        <?php endif;?>
                    </ul>
                </li>
                <?php endif;?>

                <?php if($this->auth->access_main_view($this->curuser,array(3000))):?>
                <li class="nav-item nav-dropdown <?php echo set_active_dropdown(uri_segment(1),array('asset'))?>">
                    <a class="nav-link nav-dropdown-toggle <?php echo set_active_menu(uri_segment(1),array('asset'))?>" href="#2">
                        <i class="nav-icon icon-settings"></i> Kod Fail</a>
                    <ul class="nav-dropdown-items">
                        <?php if($this->auth->access_view($this->curuser,array(3001))):?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo set_active_menu(uri_segment(2),array('asset_type','add_asset_type','edit_asset_type'))?>" href="/asset/asset_type">
                                <i class="nav-icon fa fa-circle fa-sm"></i> Jenis Harta</a>
                        </li>
                        <?php endif;?>
                        <?php if($this->auth->access_view($this->curuser,array(3005))):?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo set_active_menu(uri_segment(2),array('category','add_category','edit_category'))?>" href="/asset/category">
                                <i class="nav-icon fa fa-circle fa-sm"></i> Kod Kategori</a>
                        </li>
                        <?php endif;?>
                        <?php if($this->auth->access_view($this->curuser,array(3009))):?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo set_active_menu(uri_segment(2),array('asset_unit','add_asset_unit','edit_asset_unit'))?>" href="/asset/asset_unit">
                                <i class="nav-icon fa fa-circle fa-sm"></i> Kod Harta</a>
                        </li>
                        <?php endif;?>
                        <?php if($this->auth->access_view($this->curuser,array(3013))):?>
                        <li class="nav-item">
                            <!-- <a class="nav-link <?php echo set_active_menu(uri_segment(2),array('asset_location','add_asset_location','edit_asset_location'))?>" href="/asset/asset_location">
                                <i class="nav-icon icon-action-redo"></i> Kawasan</a> -->
                            <a class="nav-link <?php echo set_active_menu(uri_segment(2),array('rental_use','add_asset_rental_use','edit_asset_rental_use'))?>" href="/asset/rental_use">
                                <i class="nav-icon fa fa-circle fa-sm"></i> Kegunaan Sewaan</a>
                        </li>
                        <?php endif;?>
                        <?php if($this->auth->access_view($this->curuser,array(3017))):?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo set_active_menu(uri_segment(2),array('tenant_type','add_asset_tenant_type','edit_asset_tenant_type'))?>" href="/asset/tenant_type">
                                <i class="nav-icon fa fa-circle fa-sm"></i> Jenis Penyewa</a>
<!--                            <a class="nav-link" href="/asset/tenant_type">-->
<!--                                <i class="nav-icon icon-puzzle"></i> Kod Dokumen</a>-->
<!--                            <a class="nav-link" href="/code_utility">-->
<!--                                <i class="nav-icon icon-puzzle"></i> Kod Utiliti</a>-->
                        </li>
                        <?php endif;?>
                    </ul>
                </li>
                <?php endif;?>

                <?php if($this->auth->access_main_view($this->curuser,array(4000))):?>
                <li class="nav-item nav-dropdown <?php echo set_active_dropdown(uri_segment(1),array('rental_application','interview'))?>">
                    <a class="nav-link nav-dropdown-toggle <?php echo set_active_menu(uri_segment(1),array('rental_application','interview'))?>" href="#3">
                        <i class="nav-icon icon-login"></i> Permohonan</a>
                    <ul class="nav-dropdown-items">
                        <?php if($this->auth->access_view($this->curuser,array(4001))):?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo set_active_menu(uri_segment(2),array('application_type','form'))?>" href="/rental_application/application_type">
                                <i class="nav-icon fa fa-circle fa-sm"></i> Borang Permohonan</a>
                        </li>
                        <?php endif;?>
                        <?php if($this->auth->access_view($this->curuser,array(4002))):?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo set_active_menu(uri_segment(2),array('application','application_process'))?>" href="/rental_application/application">
                                <i class="nav-icon fa fa-circle fa-sm"></i> Senarai Permohonan</a>
                        </li>
                        <?php endif;?>
                        <?php if($this->auth->access_view($this->curuser,array(4005))):?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo set_active_menu(uri_segment(1),array('interview'))?>" href="/interview/interview_list">
                                <i class="nav-icon fa fa-circle fa-sm"></i> Senarai Temuduga</a>
                        </li>
                        <?php endif;?>
                        <?php if($this->auth->access_view($this->curuser,array(4009))):?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo set_active_menu(uri_segment(2),array('application_approval'))?>" href="/rental_application/application_approval">
                                <i class="nav-icon fa fa-circle fa-sm"></i> Kelulusan</a>
                        </li>
                        <?php endif;?>
                        <?php if($this->auth->access_view($this->curuser,array(4010))):?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo set_active_menu(uri_segment(2),array('application_agree'))?>" href="/rental_application/application_agree">
                                <i class="nav-icon fa fa-circle fa-sm"></i> Setuju Terima</a>
                        </li>
                        <?php endif;?>
                    </ul>
                </li>
                <?php endif;?>

                <?php if($this->auth->access_main_view($this->curuser,array(5000))):?>
                <li class="nav-item nav-dropdown <?php echo set_active_dropdown(uri_segment(1),array('account'))?>">
                    <a class="nav-link nav-dropdown-toggle <?php echo set_active_menu(uri_segment(1),array('account'))?>" href="#4">
                        <i class="nav-icon icon-people"></i> Akaun</a>
                    <ul class="nav-dropdown-items">
                        <?php if($this->auth->access_view($this->curuser,array(5002))):?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo set_active_menu(uri_segment(2),array('account_create_list','create_acc'))?>" href="/account/account_create_list">
                                <i class="nav-icon fa fa-circle fa-sm"></i> Pendaftaran Akaun</a>
                        </li>
                        <?php endif;?>
                        <?php if($this->auth->access_view($this->curuser,array(5003))):?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo set_active_menu(uri_segment(2),array('account_list'))?>" href="/account/account_list">
                                <i class="nav-icon fa fa-circle fa-sm"></i> Senarai Akaun</a>
                        </li>
                        <?php endif;?>
                        <?php if($this->auth->access_view($this->curuser,array(5005))):?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo set_active_menu(uri_segment(2),array('time_extension'))?>" href="/account/time_extension">
                                <i class="nav-icon fa fa-circle fa-sm"></i> Lanjutan Sewaan</a>
                        </li>
                        <?php endif;?>
<!--                        --><?php //if($this->auth->access_view($this->curuser,array(5005))):?>
                            <li class="nav-item">
                                <a class="nav-link <?php echo set_active_menu(uri_segment(2),array('kuarters_list','kuarters_add','kuarters_detais'))?>" href="/account/kuarters_list">
                                    <i class="nav-icon fa fa-circle fa-sm"></i> Laporan Kerosakan Kuarters</a>
                            </li>
<!--                        --><?php //endif;?>
                    </ul>
                </li>
                <?php endif;?>

                <?php if($this->auth->access_main_view($this->curuser,array(6000))):?>
                <li class="nav-item nav-dropdown <?php echo set_active_dropdown(uri_segment(1),array('bill'))?>">
                    <a class="nav-link nav-dropdown-toggle <?php echo set_active_menu(uri_segment(1),array('bill'))?>" href="#5">
                        <i class="nav-icon icon-layers"></i> Bil Sewaan</a>
                    <ul class="nav-dropdown-items">
                        <?php if($this->auth->access_view($this->curuser,array(6001))):?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo set_active_menu(uri_segment(2),array('account_list','generate_current_bill','current_bill'))?>" href="/bill/account_list">
                                <i class="nav-icon fa fa-circle fa-sm"></i> Akaun</a>
                        </li>
                        <?php endif;?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo set_active_menu(uri_segment(2),array('notice_list'))?>" href="/bill/notice_list">
                                <i class="nav-icon fa fa-circle fa-sm"></i> Tunggakan</a>
                        </li>
                    </ul>
                </li>
                <?php endif;?>

                <?php if($this->auth->access_main_view($this->curuser,array(7000))):?>
                <li class="nav-item nav-dropdown <?php echo set_active_dropdown(uri_segment(1),array('journal'))?>">
                    <a class="nav-link nav-dropdown-toggle <?php echo set_active_menu(uri_segment(1),array('journal'))?>" href="#6">
                        <i class="nav-icon icon-speedometer"></i> Pelarasan</a>
                    <ul class="nav-dropdown-items">
                        <?php if($this->auth->access_view($this->curuser,array(7001))):?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo set_active_menu(uri_segment(2),array('account_list','generate_current_bill','current_bill'))?>" href="/journal/search">
                                <i class="nav-icon fa fa-circle fa-sm"></i>Senarai Pelarasan</a>
                        </li>
                        <?php endif;?>
                        <?php if($this->auth->access_view($this->curuser,array(7002))):?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo set_active_menu(uri_segment(2),array('account_list','generate_current_bill','current_bill'))?>" href="/journal/entry">
                                <i class="nav-icon fa fa-circle fa-sm"></i>Senarai Kemasukan</a>
                        </li>
                        <?php endif;?>
                        <?php if($this->auth->access_view($this->curuser,array(7003))):?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo set_active_menu(uri_segment(2),array('account_list','generate_current_bill','current_bill'))?>" href="/journal/index">
                                <i class="nav-icon fa fa-circle fa-sm"></i>Senarai Kelulusan</a>
                        </li>
                        <?php endif;?>
                    </ul>
                    </a>
                </li>
                <?php endif;?>


<!--                <li class="nav-item nav-dropdown --><?php //echo set_active_dropdown(uri_segment(1),array('journal'))?><!--">-->
<!--                    <a class="nav-link nav-dropdown-toggle --><?php //echo set_active_menu(uri_segment(1),array('journal'))?><!--" href="/journal/insert">-->
<!--                        <i class="nav-icon icon-layers"></i> Penyelarasan</a>-->
<!--                    <ul class="nav-dropdown-items">-->
<!--                        <li class="nav-item">-->
<!--                            <a class="nav-link --><?php //echo set_active_menu(uri_segment(2),array('account_list','generate_current_bill','current_bill'))?>
                                    <!-- " href="/bill/account_list"> -->
<!--                                <i class="nav-icon fa fa-circle fa-sm"></i> Akaun</a>-->
<!--                        </li>-->
<!--                        <li class="nav-item">-->
<!--                            <a class="nav-link --><?php //echo set_active_menu(uri_segment(2),array('notice_list'))?><!--" href="/bill/notice_list">-->
<!--                                <i class="nav-icon fa fa-circle fa-sm"></i> Tunggakan</a>-->
<!--                        </li>-->
<!--                    </ul>-->
<!--                </li>-->

                <?php if($this->auth->access_main_view($this->curuser,array(8000))):?>
                <li class="nav-item nav-dropdown <?php echo set_active_dropdown(uri_segment(1),array('report'))?>">
                    <a class="nav-link nav-dropdown-toggle <?php echo set_active_menu(uri_segment(1),array('report'))?>" href="#5">
                        <i class="nav-icon icon-chart"></i> Laporan</a>
                    <ul class="nav-dropdown-items">
                        <!-- <li class="nav-item">
                            <a class="nav-link <?php echo set_active_menu(uri_segment(2),array('rent_summary'))?>" href="/report/rent_summary">
                                <i class="nav-icon fa fa-circle fa-sm"></i> Ringkasan Sewaan</a>
                        </li> -->
<!--                        <li class="nav-item">-->
<!--                            <a class="nav-link --><?php //echo set_active_menu(uri_segment(2),array('gl_summary'))?><!--" href="/report/gl_summary">-->
<!--                                <i class="nav-icon fa fa-circle fa-sm"></i> Hasil Kod GL</a>-->
<!--                        </li>-->

<!--                        <li class="nav-item">-->
<!--                            <a class="nav-link --><?php //echo set_active_menu(uri_segment(2),array('category_adjustment'))?><!--" href="/report/category_adjustment">-->
<!--                                <i class="nav-icon fa fa-circle fa-sm"></i> Penyata Penyesuaian</a>-->
<!--                        </li>-->


<!--                        <li class="nav-item">-->
<!--                            <a class="nav-link --><?php //echo set_active_menu(uri_segment(2),array('category_aging'))?><!--" href="/report/category_aging">-->
<!--                                <i class="nav-icon fa fa-circle fa-sm"></i> Aging Sewaan</a>-->
<!--                        </li>-->
                        <?php if($this->auth->access_view($this->curuser,array(8001))):?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo set_active_menu(uri_segment(2),array('category_aging_details'))?>" href="/report/category_aging_details">
                                <i class="nav-icon fa fa-circle fa-sm"></i> Aging Sewaan Terperinci</a>
                        </li>
                        <?php endif;?>
                        <?php if($this->auth->access_view($this->curuser,array(8002))):?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo set_active_menu(uri_segment(2),array('category_aging'))?>" href="/report/category_aging">
                                <i class="nav-icon fa fa-circle fa-sm"></i> Aging Sewaan Ringkasan</a>
                        </li>
                        <?php endif;?>
<!--                        <li class="nav-item">-->
<!--                            <a class="nav-link --><?php //echo set_active_menu(uri_segment(2),array('account_outstanding'))?><!--" href="/report/account_outstanding">-->
<!--                                <i class="nav-icon fa fa-circle fa-sm"></i> Tunggakan Tertinggi</a>-->
<!--                        </li>-->
                        <!-- <li class="nav-item">
                            <a class="nav-link <?php echo set_active_menu(uri_segment(2),array('account_adjustment'))?>" href="/report/account_adjustment">
                                <i class="nav-icon fa fa-RFcircle fa-sm"></i> Penyesuaian Akaun</a>
                        </li> -->
                        <?php if($this->auth->access_view($this->curuser,array(8003))):?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo set_active_menu(uri_segment(2),array('gst_rental'))?>" href="/report/gst_rental">
                                <i class="nav-icon fa fa-circle fa-sm"></i> GST Terperinci</a>
                        </li>
                        <?php endif;?>
                        <?php if($this->auth->access_view($this->curuser,array(8004))):?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo set_active_menu(uri_segment(2),array('gst_rental_simple'))?>" href="/report/gst_rental_simple">
                                <i class="nav-icon fa fa-circle fa-sm"></i> GST Ringkasan</a>
                        </li>
                        <?php endif;?>
                        <?php if($this->auth->access_view($this->curuser,array(8005))):?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo set_active_menu(uri_segment(2),array('code_gl'))?>" href="/report/code_gl">
                                <i class="nav-icon fa fa-circle fa-sm"></i> Kod Transaksi</a>
                        </li>
                        <?php endif;?>
                        <?php if($this->auth->access_view($this->curuser,array(8006))):?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo set_active_menu(uri_segment(2),array('highest_overdue '))?>" href="/report/highest_overdue">
                                <i class="nav-icon fa fa-circle fa-sm"></i> Tunggakan Tertinggi</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo set_active_menu(uri_segment(2),array('highest_overdue '))?>" href="/report/highest_overdue/two">
                                <i class="nav-icon fa fa-circle fa-sm"></i> Tunggakan</a>
                        </li>
                        <?php endif;?>
                        <?php if($this->auth->access_view($this->curuser,array(8007))):?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo set_active_menu(uri_segment(2),array('record_transaction'))?>" href="/report/record_transaction">
                                <i class="nav-icon fa fa-circle fa-sm"></i> Rekod Transaksi</a>
                        </li>
                        <?php endif;?>
                        <?php if($this->auth->access_view($this->curuser,array(8008))):?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo set_active_menu(uri_segment(2),array('adjustment_statement'))?>" href="/report/adjustment_statement">
                                <i class="nav-icon fa fa-circle fa-sm"></i> Penyata Penyesuaian Terperinci</a>
                        </li>
                        <?php endif;?>
                        <?php if($this->auth->access_view($this->curuser,array(8009))):?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo set_active_menu(uri_segment(2),array('adjustment_statement_ringkasan'))?>" href="/report/adjustment_statement_ringkasan">
                                <i class="nav-icon fa fa-circle fa-sm"></i> Penyata Penyesuaian Ringkasan</a>
                        </li>
                        <?php endif;?>
                        <?php if($this->auth->access_view($this->curuser,array(8010))):?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo set_active_menu(uri_segment(2),array('payment'))?>" href="/report/payment">
                                <i class="nav-icon fa fa-circle fa-sm"></i> Laporan Pembayaran Penyewa</a>
                        </li>
                        <?php endif;?>
                        <?php if($this->auth->access_view($this->curuser,array(8011))):?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo set_active_menu(uri_segment(2),array('journal'))?>" href="/report/journal">
                                <i class="nav-icon fa fa-circle fa-sm"></i> Laporan Jurnal Sewaan</a>
                        </li>
                        <?php endif;?>
                    </ul>
                </li>
                <?php endif;?>


<!--                <li class="nav-title">Kod Fail</li>-->
<!--                <li class="nav-item">-->
<!--                    <a class="nav-link" href="/department">-->
<!--                        <i class="nav-icon icon-drop"></i> Jabatan/Bahagian</a>-->
<!--                </li>-->
<!--                <li class="nav-item">-->
<!--                    <a class="nav-link" href="/user_group">-->
<!--                        <i class="nav-icon icon-puzzle"></i> Kumpulan Pengguna</a>-->
<!--                </li>-->
<!--                <li class="nav-item">-->
<!--                    <a class="nav-link" href="/user">-->
<!--                        <i class="nav-icon icon-puzzle"></i> Pengguna</a>-->
<!--                </li>-->
<!--                <li class="nav-item">-->
<!--                    <a class="nav-link" href="/audit_trail">-->
<!--                        <i class="nav-icon icon-puzzle"></i> Audit Trail</a>-->
<!--                </li>-->
<!--                <li class="nav-item nav-dropdown">-->
<!--                    <a class="nav-link nav-dropdown-toggle" href="typography.html"><i class="nav-icon icon-pencil"></i> User</a>-->
<!--                    <ul class="nav-dropdown-items">-->
<!--                        <li class="nav-item">-->
<!--                            <a class="nav-link" href="/user/user_groups/">-->
<!--                                <i class="nav-icon icon-puzzle"></i> User Group</a>-->
<!--                            <a class="nav-link" href="base/breadcrumb.html">-->
<!--                                <i class="nav-icon icon-puzzle"></i> Position                                                  </a>-->
<!--                            <a class="nav-link" href="base/breadcrumb.html">-->
<!--                                <i class="nav-icon icon-puzzle"></i> User Account</a>-->
<!--                        </li>-->
<!--                    </ul>-->
<!--                </li>-->
<!--                <li class="nav-title">Components</li>-->
<!--                <li class="nav-item nav-dropdown">-->
<!--                    <a class="nav-link nav-dropdown-toggle" href="#">-->
<!--                        <i class="nav-icon icon-puzzle"></i> Base</a>-->
<!--                    <ul class="nav-dropdown-items">-->
<!--                        <li class="nav-item">-->
<!--                            <a class="nav-link" href="base/breadcrumb.html">-->
<!--                                <i class="nav-icon icon-puzzle"></i> Breadcrumb</a>-->
<!--                        </li>-->
<!--                        <li class="nav-item">-->
<!--                            <a class="nav-link" href="base/cards.html">-->
<!--                                <i class="nav-icon icon-puzzle"></i> Cards</a>-->
<!--                        </li>-->
<!--                        <li class="nav-item">-->
<!--                            <a class="nav-link" href="base/carousel.html">-->
<!--                                <i class="nav-icon icon-puzzle"></i> Carousel</a>-->
<!--                        </li>-->
<!--                        <li class="nav-item">-->
<!--                            <a class="nav-link" href="base/collapse.html">-->
<!--                                <i class="nav-icon icon-puzzle"></i> Collapse</a>-->
<!--                        </li>-->
<!--                        <li class="nav-item">-->
<!--                            <a class="nav-link" href="base/forms.html">-->
<!--                                <i class="nav-icon icon-puzzle"></i> Forms</a>-->
<!--                        </li>-->
<!--                        <li class="nav-item">-->
<!--                            <a class="nav-link" href="base/jumbotron.html">-->
<!--                                <i class="nav-icon icon-puzzle"></i> Jumbotron</a>-->
<!--                        </li>-->
<!--                        <li class="nav-item">-->
<!--                            <a class="nav-link" href="base/list-group.html">-->
<!--                                <i class="nav-icon icon-puzzle"></i> List group</a>-->
<!--                        </li>-->
<!--                        <li class="nav-item">-->
<!--                            <a class="nav-link" href="base/navs.html">-->
<!--                                <i class="nav-icon icon-puzzle"></i> Navs</a>-->
<!--                        </li>-->
<!--                        <li class="nav-item">-->
<!--                            <a class="nav-link" href="base/pagination.html">-->
<!--                                <i class="nav-icon icon-puzzle"></i> Pagination</a>-->
<!--                        </li>-->
<!--                        <li class="nav-item">-->
<!--                            <a class="nav-link" href="base/popovers.html">-->
<!--                                <i class="nav-icon icon-puzzle"></i> Popovers</a>-->
<!--                        </li>-->
<!--                        <li class="nav-item">-->
<!--                            <a class="nav-link" href="base/progress.html">-->
<!--                                <i class="nav-icon icon-puzzle"></i> Progress</a>-->
<!--                        </li>-->
<!--                        <li class="nav-item">-->
<!--                            <a class="nav-link" href="base/scrollspy.html">-->
<!--                                <i class="nav-icon icon-puzzle"></i> Scrollspy</a>-->
<!--                        </li>-->
<!--                        <li class="nav-item">-->
<!--                            <a class="nav-link" href="base/switches.html">-->
<!--                                <i class="nav-icon icon-puzzle"></i> Switches</a>-->
<!--                        </li>-->
<!--                        <li class="nav-item">-->
<!--                            <a class="nav-link" href="base/tables.html">-->
<!--                                <i class="nav-icon icon-puzzle"></i> Tables</a>-->
<!--                        </li>-->
<!--                        <li class="nav-item">-->
<!--                            <a class="nav-link" href="base/tabs.html">-->
<!--                                <i class="nav-icon icon-puzzle"></i> Tabs</a>-->
<!--                        </li>-->
<!--                        <li class="nav-item">-->
<!--                            <a class="nav-link" href="base/tooltips.html">-->
<!--                                <i class="nav-icon icon-puzzle"></i> Tooltips</a>-->
<!--                        </li>-->
<!--                    </ul>-->
<!--                </li>-->
<!--                <li class="nav-item nav-dropdown">-->
<!--                    <a class="nav-link nav-dropdown-toggle" href="#">-->
<!--                        <i class="nav-icon icon-cursor"></i> Buttons</a>-->
<!--                    <ul class="nav-dropdown-items">-->
<!--                        <li class="nav-item">-->
<!--                            <a class="nav-link" href="buttons/buttons.html">-->
<!--                                <i class="nav-icon icon-cursor"></i> Buttons</a>-->
<!--                        </li>-->
<!--                        <li class="nav-item">-->
<!--                            <a class="nav-link" href="buttons/button-group.html">-->
<!--                                <i class="nav-icon icon-cursor"></i> Buttons Group</a>-->
<!--                        </li>-->
<!--                        <li class="nav-item">-->
<!--                            <a class="nav-link" href="buttons/dropdowns.html">-->
<!--                                <i class="nav-icon icon-cursor"></i> Dropdowns</a>-->
<!--                        </li>-->
<!--                        <li class="nav-item">-->
<!--                            <a class="nav-link" href="buttons/brand-buttons.html">-->
<!--                                <i class="nav-icon icon-cursor"></i> Brand Buttons</a>-->
<!--                        </li>-->
<!--                    </ul>-->
<!--                </li>-->
<!--                <li class="nav-item">-->
<!--                    <a class="nav-link" href="charts.html">-->
<!--                        <i class="nav-icon icon-pie-chart"></i> Charts</a>-->
<!--                </li>-->
<!--                <li class="nav-item nav-dropdown">-->
<!--                    <a class="nav-link nav-dropdown-toggle" href="#">-->
<!--                        <i class="nav-icon icon-star"></i> Icons</a>-->
<!--                    <ul class="nav-dropdown-items">-->
<!--                        <li class="nav-item">-->
<!--                            <a class="nav-link" href="icons/coreui-icons.html">-->
<!--                                <i class="nav-icon icon-star"></i> CoreUI Icons-->
<!--                                <span class="badge badge-success">NEW</span>-->
<!--                            </a>-->
<!--                            <a class="nav-link" href="icons/flags.html">-->
<!--                                <i class="nav-icon icon-star"></i> Flags</a>-->
<!--                        </li>-->
<!--                        <li class="nav-item">-->
<!--                            <a class="nav-link" href="icons/font-awesome.html">-->
<!--                                <i class="nav-icon icon-star"></i> Font Awesome-->
<!--                                <span class="badge badge-secondary">4.7</span>-->
<!--                            </a>-->
<!--                        </li>-->
<!--                        <li class="nav-item">-->
<!--                            <a class="nav-link" href="icons/simple-line-icons.html">-->
<!--                                <i class="nav-icon icon-star"></i> Simple Line Icons</a>-->
<!--                        </li>-->
<!--                    </ul>-->
<!--                </li>-->
<!--                <li class="nav-item nav-dropdown">-->
<!--                    <a class="nav-link nav-dropdown-toggle" href="#">-->
<!--                        <i class="nav-icon icon-bell"></i> Notifications</a>-->
<!--                    <ul class="nav-dropdown-items">-->
<!--                        <li class="nav-item">-->
<!--                            <a class="nav-link" href="notifications/alerts.html">-->
<!--                                <i class="nav-icon icon-bell"></i> Alerts</a>-->
<!--                        </li>-->
<!--                        <li class="nav-item">-->
<!--                            <a class="nav-link" href="notifications/badge.html">-->
<!--                                <i class="nav-icon icon-bell"></i> Badge</a>-->
<!--                        </li>-->
<!--                        <li class="nav-item">-->
<!--                            <a class="nav-link" href="notifications/modals.html">-->
<!--                                <i class="nav-icon icon-bell"></i> Modals</a>-->
<!--                        </li>-->
<!--                    </ul>-->
<!--                </li>-->
<!--                <li class="nav-item">-->
<!--                    <a class="nav-link" href="widgets.html">-->
<!--                        <i class="nav-icon icon-calculator"></i> Widgets-->
<!--                        <span class="badge badge-primary">NEW</span>-->
<!--                    </a>-->
<!--                </li>-->
<!--                <li class="divider"></li>-->
<!--                <li class="nav-title">Extras</li>-->
<!--                <li class="nav-item nav-dropdown">-->
<!--                    <a class="nav-link nav-dropdown-toggle" href="#">-->
<!--                        <i class="nav-icon icon-star"></i> Pages</a>-->
<!--                    <ul class="nav-dropdown-items">-->
<!--                        <li class="nav-item">-->
<!--                            <a class="nav-link" href="login.html" target="_top">-->
<!--                                <i class="nav-icon icon-star"></i> Login</a>-->
<!--                        </li>-->
<!--                        <li class="nav-item">-->
<!--                            <a class="nav-link" href="register.html" target="_top">-->
<!--                                <i class="nav-icon icon-star"></i> Register</a>-->
<!--                        </li>-->
<!--                        <li class="nav-item">-->
<!--                            <a class="nav-link" href="404.html" target="_top">-->
<!--                                <i class="nav-icon icon-star"></i> Error 404</a>-->
<!--                        </li>-->
<!--                        <li class="nav-item">-->
<!--                            <a class="nav-link" href="500.html" target="_top">-->
<!--                                <i class="nav-icon icon-star"></i> Error 500</a>-->
<!--                        </li>-->
<!--                    </ul>-->
<!--                </li>-->
<!--                <li class="nav-item mt-auto">-->
<!--                    <a class="nav-link nav-link-success" href="https://coreui.io" target="_top">-->
<!--                        <i class="nav-icon icon-cloud-download"></i> Download CoreUI</a>-->
<!--                </li>-->
<!--                <li class="nav-item">-->
<!--                    <a class="nav-link nav-link-danger" href="https://coreui.io/pro/" target="_top">-->
<!--                        <i class="nav-icon icon-layers"></i> Try CoreUI-->
<!--                        <strong>PRO</strong>-->
<!--                    </a>-->
<!--                </li>-->
            </ul>
        </nav>
        <button class="sidebar-minimizer brand-minimizer" type="button"></button>
    </div>
    <main class="main">
        <!-- Breadcrumb-->
        <!-- Breadcrumb-->
        <?php if(uri_segment(1)!='dashboard'):?>
        <ol class="breadcrumb">
            <li style="margin-right: auto;margin-left: 0%" class="breadcrumb-menu d-md-down-none">
                <h1><?php echo $pagetitle?></h1>
            </li>

            <li style="margin-left: auto" class="breadcrumb-item"><a href="/">Dashboard</a></li>
            <?php if($link_1):?>
            <li class="breadcrumb-item"><?php echo $link_1?></li>
            <?php endif;?>
            <?php if($link_2):?>
            <li class="breadcrumb-item"><?php echo $link_2?></li>
            <?php endif;?>
            <?php if($link_3):?>
            <li class="breadcrumb-item"><?php echo $link_3?></li>
            <?php endif;?>
            <!-- Breadcrumb Menu-->
        </ol>
        <?php endif;?>
        <div class="container-fluid">
            <div class="animated fadeIn">
