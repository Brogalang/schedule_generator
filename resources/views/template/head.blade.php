<meta charset="UTF-8">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
<title>Schedule Maker</title>

<!-- General CSS Files -->
<link rel="stylesheet" href="{{asset('assets/modules/bootstrap/css/bootstrap.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/modules/fontawesome/css/all.min.css')}}">

<!-- CSS Libraries -->
<link rel="stylesheet" href="{{asset('assets/modules/bootstrap-social/bootstrap-social.css')}}">
<!-- Template CSS -->
<link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
<link rel="stylesheet" href="{{asset('assets/css/components.css')}}">

<!-- Select2 -->
<link rel="stylesheet" href="{{asset('assets/modules/select2/dist/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/modules/select2/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">

<!-- CSS Libraries -->
<link rel="stylesheet" href="{{asset('assets/modules/bootstrap-daterangepicker/daterangepicker.css')}}">
<link rel="stylesheet" href="{{asset('assets/modules/jquery-selectric/selectric.css')}}">
<link rel="stylesheet" href="{{asset('assets/modules/bootstrap-timepicker/css/bootstrap-timepicker.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}">

<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.3.0/css/flag-icon.min.css"> -->
<!-- Start GA -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script>

<!-- CSS Libraries -->
<link rel="stylesheet" href="{{asset('assets/modules/datatables/datatables.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css')}}">

<!-- include the style -->
<link rel="stylesheet" href="{{asset('alertifyjs/css/alertify.min.css')}}" />
<!-- include a theme -->
<link rel="stylesheet" href="{{asset('alertifyjs/css/themes/default.min.css')}}" />


<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-94034622-3');
</script>

<style type="text/css">
 
.preloader {
 
  position: fixed;
 
  top: 0;
 
  left: 0;
 
  width: 100%;
 
  height: 100%;
 
  z-index: 9999;
 
  background-color: #fff;
 
}
 
.preloader .loading {
 
  position: absolute;
 
  left: 50%;
 
  top: 50%;
 
  transform: translate(-50%,-50%);
 
  font: 14px arial;
 
}
 
</style>

<style type=text/css>
        .freezetbl {
            position: relative;
            max-height: 350px;
        }
        .freezetbl thead {
        position: -webkit-sticky;
        position: sticky;
        top: 0;
        z-index: 2;
        }
    </style>