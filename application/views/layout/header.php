<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title>Covid19 Tracking</title>

  <!-- GOOGLE FONTS -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500|Poppins:400,500,600,700|Roboto:400,500" rel="stylesheet"/>
  <link href="https://cdn.materialdesignicons.com/3.0.39/css/materialdesignicons.min.css" rel="stylesheet" />

  <!-- PLUGINS CSS STYLE -->
  <link href="<?php echo base_url()?>assets/template/dist/assets/plugins/toaster/toastr.min.css" rel="stylesheet" />
  <link href="<?php echo base_url()?>assets/template/dist/assets/plugins/nprogress/nprogress.css" rel="stylesheet" />
  <link href="<?php echo base_url()?>assets/template/dist/assets/plugins/flag-icons/css/flag-icon.min.css" rel="stylesheet"/>
  <link href="<?php echo base_url()?>assets/template/dist/assets/plugins/jvectormap/jquery-jvectormap-2.0.3.css" rel="stylesheet" />
  <link href="<?php echo base_url()?>assets/template/dist/assets/plugins/ladda/ladda.min.css" rel="stylesheet" />
  <link href="<?php echo base_url()?>assets/template/dist/assets/plugins/select2/css/select2.min.css" rel="stylesheet" />
  <link href="<?php echo base_url()?>assets/template/dist/assets/plugins/daterangepicker/daterangepicker.css" rel="stylesheet" />
  <script type="text/javascript" src="<?php echo base_url()?>assets/user/js/date_time.js"></script>

  <!-- SLEEK CSS -->
  <link id="sleek-css" rel="stylesheet" href="<?php echo base_url()?>assets/template/dist/assets/css/sleek.css" />

  

  <!-- FAVICON -->
  <link href="<?php echo base_url()?>assets/template/src/assets/img/im-chivid.gif" rel="shortcut icon" />

  <!--
    HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries
  -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
<script src="<?php echo base_url()?>assets/template/dist/assets/plugins/jquery/jquery.min.js"></script>
  <script src="<?php echo base_url()?>assets/template/dist/assets/plugins/nprogress/nprogress.js"></script>
  <script type="text/javascript" src="<?php echo base_url()?>assets/template/dist/assets/js/date_time.js"></script>
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>

   <!-- Data TAble 
         ============================================ -->
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.min.css')?>" rel="stylesheet">
    <link href="<?php echo base_url()?>assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
    <link href="http://cdn.datatables.net/fixedcolumns/3.2.6/css/fixedColumns.dataTables.min.css" rel="stylesheet">

    <style type="text/css">

       #t_answer tbody td{
        font-size: 12px;
        color : #000000;
       }

       #t_question tbody td{
        font-size: 12px;
        color : #000000;
       }

       #t_filter tbody td{
        font-size: 12px;
        color : #000000;
       }

        #t_optiontbody td{
        font-size: 12px;
        color : #000000;
       }

       img {
        width: 52px;
      }

      .dash {
        font-weight: 800;
        margin-bottom: 0;
        font-size: 2rem;
        line-height: 1.2;
      }


    </style>
</head>
