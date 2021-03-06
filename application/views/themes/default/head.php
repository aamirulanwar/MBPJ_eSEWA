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
    <title>Sistem Sewa Elektronik MBPJ</title>
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

    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"> -->
    <!-- <link href="/assets/node_modules/bootstrap/dist/css/bootstrap.css" > -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
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
    <ul class="nav navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="/profile" role="button" aria-haspopup="true"
               aria-expanded="false">
                <img class="img-avatar" src="/assets/images/user.jpg" alt="administrator"><?php echo $this->curuser['USER_NAME']?>&nbsp;&nbsp;
            </a>
            <div class="dropdown-menu dropdown-menu-right">                
                <a class="dropdown-item" href="/profile">
                    <i class="fa fa-user"></i> Profil</a>
                <a class="dropdown-item" href="/logout">
                    <i class="fa fa-lock"></i> Log keluar        
                </a>
            </div>
        </li>
    </ul>
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
                        <i class="nav-icon icon-settings"></i> Pendaftaran Aset</a>
                    <ul class="nav-dropdown-items">
                        <?php if($this->auth->access_view($this->curuser,array(3001))):?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo set_active_menu(uri_segment(2),array('asset_type','add_asset_type','edit_asset_type'))?>" href="/asset/asset_type">
                                <i class="nav-icon fa fa-circle fa-sm"></i> Jenis Aset</a>
                        </li>
                        <?php endif;?>
                        <?php if($this->auth->access_view($this->curuser,array(3005))):?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo set_active_menu(uri_segment(2),array('category','add_category','edit_category'))?>" href="/asset/category">
                                <i class="nav-icon fa fa-circle fa-sm"></i> Seksyen Aset</a>
                        </li>
                        <?php endif;?>
                        <?php if($this->auth->access_view($this->curuser,array(3009))):?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo set_active_menu(uri_segment(2),array('asset_unit','add_asset_unit','edit_asset_unit'))?>" href="/asset/asset_unit">
                                <i class="nav-icon fa fa-circle fa-sm"></i> Kawasan Aset</a>
                        </li>
                        <?php endif;?>
                        <?php if($this->auth->access_view($this->curuser,array(3013))):?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo set_active_menu(uri_segment(2),array('rental_use','add_asset_rental_use','edit_asset_rental_use'))?>" href="/asset/rental_use">
                                <i class="nav-icon fa fa-circle fa-sm"></i> Kegunaan Sewaan</a>
                        </li>
                        <?php endif;?>
                        <?php if($this->auth->access_view($this->curuser,array(3017))):?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo set_active_menu(uri_segment(2),array('tenant_type','add_asset_tenant_type','edit_asset_tenant_type'))?>" href="/asset/tenant_type">
                                <i class="nav-icon fa fa-circle fa-sm"></i> Jenis Penyewa</a>
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
                            <a class="nav-link <?php echo set_active_menu(uri_segment(1)."/".uri_segment(2),'account/account_list')?>" href="/account/account_list">
                                <i class="nav-icon fa fa-circle fa-sm"></i> Senarai Akaun</a>
                        </li>
                        <?php endif;?>
                        <?php if($this->auth->access_view($this->curuser,array(5005))):?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo set_active_menu(uri_segment(2),array('time_extension'))?>" href="/account/time_extension">
                                <i class="nav-icon fa fa-circle fa-sm"></i> Lanjutan Sewaan</a>
                        </li>
                        <?php endif;?>
                        <?php if($this->auth->access_view($this->curuser,array(5008))):?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo set_active_menu(uri_segment(2),array('kuarters_list','kuarters_add','kuarters_detais'))?>" href="/account/kuarters_list">
                                <i class="nav-icon fa fa-circle fa-sm"></i> Laporan Kerosakan Kuarters</a>
                            </li>
                        <?php endif;?>
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
                            <a class="nav-link <?php echo set_active_menu(uri_segment(1)."/".uri_segment(2),array('bill/account_list','bill/generate_current_bill','bill/current_bill'))?>" href="/bill/account_list">
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
                            <a class="nav-link <?php echo set_active_menu(uri_segment(2),array('search'))?>" href="/journal/search">
                                <i class="nav-icon fa fa-circle fa-sm"></i>Senarai Pelarasan</a>
                        </li>
                        <?php endif;?>
                        <?php if($this->auth->access_view($this->curuser,array(7002))):?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo set_active_menu(uri_segment(2),array('entry'))?>" href="/journal/entry">
                                <i class="nav-icon fa fa-circle fa-sm"></i>Senarai Kemasukan</a>
                        </li>
                        <?php endif;?>
                        <?php if($this->auth->access_view($this->curuser,array(7003))):?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo set_active_menu(uri_segment(2),array('index'))?>" href="/journal/index">
                                <i class="nav-icon fa fa-circle fa-sm"></i>Senarai Kelulusan</a>
                        </li>
                        <?php endif;?>
                    </ul>
                    </a>
                </li>
                <?php endif;?>

                <?php if($this->auth->access_main_view($this->curuser,array(8000))):?>
                <li class="nav-item nav-dropdown <?php echo set_active_dropdown(uri_segment(1),array('report'))?>">
                    <a class="nav-link nav-dropdown-toggle <?php echo set_active_menu(uri_segment(1),array('report'))?>" href="#5">
                        <i class="nav-icon icon-chart"></i> Laporan</a>
                    <ul class="nav-dropdown-items">
                        
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
                            <a class="nav-link <?php echo set_active_menu(uri_segment(1).'/'.uri_segment(2).'/'.uri_segment(3),'report/highest_overdue')?>" href="/report/highest_overdue">
                                <i class="nav-icon fa fa-circle fa-sm"></i> Tunggakan Tertinggi</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo set_active_menu(uri_segment(1).'/'.uri_segment(2).'/'.uri_segment(3),'report/highest_overdue/two')?>" href="/report/highest_overdue/two">
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
                        <?php if($this->auth->access_view($this->curuser,array(8012))):?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo set_active_menu(uri_segment(2),array('hartanah'))?>" href="/report/hartanah">
                                <i class="nav-icon fa fa-circle fa-sm"></i> Laporan Perjanjian Sewaan Hartanah</a>
                        </li>
                        <?php endif;?>
                        <?php if($this->auth->access_view($this->curuser,array(8013))):?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo set_active_menu(uri_segment(2),array('papaniklan'))?>" href="/report/papaniklan">
                                <i class="nav-icon fa fa-circle fa-sm"></i> Laporan Perjanjian Sewa Papaniklan</a>
                        </li>
                        <?php endif;?>
                        <?php if($this->auth->access_view($this->curuser,array(8014))):?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo set_active_menu(uri_segment(2),array('perjanjian_kutipan'))?>" href="/report/perjanjian_kutipan">
                                <i class="nav-icon fa fa-circle fa-sm"></i> Laporan Perbandingan Jumlah Perjanjian & Kutipan</a>
                        </li>
                        <?php endif;?>
                        <?php if($this->auth->access_view($this->curuser,array(8015))):?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo set_active_menu(uri_segment(2),array('hasil'))?>" href="/report/hasil">
                                <i class="nav-icon fa fa-circle fa-sm"></i> Laporan Hasil Perjanjian Sewa</a>
                        </li>
                        <?php endif;?>
                        <?php if($this->auth->access_view($this->curuser,array(8016))):?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo set_active_menu(uri_segment(2),array('laporan_iso'))?>" href="/report/laporan_iso">
                                <i class="nav-icon fa fa-circle fa-sm"></i> Laporan Pencapaian Objektif Kualiti</a>
                        </li>
                        <?php endif;?>
                        <?php if($this->auth->access_view($this->curuser,array(8017))):?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo set_active_menu(uri_segment(2),array('kutipan_tunggakan'))?>" href="/report/kutipan_tunggakan">
                                <i class="nav-icon fa fa-circle fa-sm"></i> Laporan Kutipan Tunggakan Undang-Undang</a>
                        </li>
                        <?php endif;?>
                    </ul>
                </li>
                <?php endif;?>

                <?php if($this->auth->access_main_view($this->curuser,array(9000))):?>
                <li class="nav-item nav-dropdown <?php echo set_active_dropdown(uri_segment(1),array('integration'))?>">
                    <a class="nav-link nav-dropdown-toggle <?php echo set_active_menu(uri_segment(1),array('integration'))?>" href="#5">
                        <i class="nav-icon icon-chart"></i> Integrasi </a>
                    <ul class="nav-dropdown-items">
                        <?php if($this->auth->access_view($this->curuser,array(9001))):?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo set_active_menu(uri_segment(2),array('test_sistem_kewangan'))?>" href="/integration/test_sistem_kewangan">
                                <i class="nav-icon fa fa-circle fa-sm"></i> Sistem Kewangan 
                            </a>
                        </li>
                        <?php endif;?>
                    </ul>
                </li>
                <?php endif;?>
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
