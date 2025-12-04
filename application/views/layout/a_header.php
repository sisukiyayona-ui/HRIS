<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="refresh" content="1800; url=<?php echo base_url()?>Auth/keluar" />

  <title>HRIS | </title>
   <style>
      blink {
        animation: blinker 0.9s linear infinite;
        color: #ff0000;
       }
      @keyframes blinker {  
        50% { opacity: 0; }
       }
       .blink-one {
         animation: blinker-one 1s linear infinite;
       }
       @keyframes blinker-one {  
         0% { opacity: 0; }
       }
       .blink-two {
         animation: blinker-two 1.4s linear infinite;
       }
       @keyframes blinker-two {  
         100% { opacity: 0; }
       }

      
    .warna-dup {
      background-color:yellow;
      color:black
    }

       .customBtn {
      padding: 0.5rem 1.5rem;
      margin: 1rem;
      border-radius: 2rem;;
      transition: all 250ms;
      &:after {
          content:">";
          padding-left: .5rem;
      }
      &:hover {
          transform: scale(1.05);
          box-shadow: 4px 4px 8px rgba(#000, 0.3);
        
      }
  }
    </style>
  <!-- jQuery -->
  <script src="<?php echo base_url()?>assets/vendors/jquery/dist/jquery.min.js"></script>
  
  <!-- Bootstrap -->
  <link href="<?php echo base_url()?>assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
 
  <!-- Font Awesome -->
  <link href="<?php echo base_url()?>assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <!-- NProgress -->
  <link href="<?php echo base_url()?>assets/vendors/nprogress/nprogress.css" rel="stylesheet">
  <!-- Animate.css -->
  <link href="<?php echo base_url()?>assets/vendors/animate.css/animate.min.css" rel="stylesheet">
  <!-- bootstrap-daterangepicker -->
  <link href="<?php echo base_url()?>assets/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
  <!-- bootstrap-datetimepicker -->
  <link href="<?php echo base_url()?>assets/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
  <!-- iCheck -->
  <link href="<?php echo base_url()?>assets/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
  <!-- Custom Theme Style -->
  <link href="<?php echo base_url()?>assets/build/css/custom.min.css" rel="stylesheet">
  <!-- Multi Select -->
  <link href="<?php echo base_url()?>assets/vendors/multi-select/css/multi-select.css" media="screen" rel="stylesheet" type="text/css">
  <!-- Bootstrap Select -->
  <link href="<?php echo base_url()?>assets/vendors/bootstrap-select/dist/css/bootstrap-select.min.css" media="screen" rel="stylesheet" type="text/css">
  <!-- Datatables -->
  <link href="<?php echo base_url()?>assets/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
 

  <link href="<?php echo base_url()?>assets/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo base_url()?>assets/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo base_url()?>assets/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo base_url()?>assets/vendors/datatables.net-bs/css/10/fixedColumns.bootstrap.min.css" rel="stylesheet">
  
</head>