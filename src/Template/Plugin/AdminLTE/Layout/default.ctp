<?php

use App\View\AjaxView;
use Cake\Core\Configure;

/** @var AjaxView $this */
/** @var object $appUser */
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>
        <?php echo Configure::read('Theme.title'); ?> | <?= isset($title) ? $title : $this->fetch('title'); ?>
    </title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <?php echo $this->Html->css('AdminLTE./bower_components/bootstrap/dist/css/bootstrap.min'); ?>
    <!-- Font Awesome -->
    <?php //echo $this->Html->css('AdminLTE./bower_components/font-awesome/css/font-awesome.min'); ?>
    <!-- Ionicons -->
    <?php echo $this->Html->css('AdminLTE./bower_components/Ionicons/css/ionicons.min'); ?>
    <!-- Theme style -->
    <?php echo $this->Html->css('AdminLTE.AdminLTE.min'); ?>
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <?php echo $this->Html->css('AdminLTE.skins/skin-'.Configure::read('Theme.skin').'.min'); ?>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>


    <![endif]-->
    <?php echo $this->Html->script('moment/min/moment.min'); ?>
    <?php echo $this->Html->script('sweetalert2/dist/sweetalert2.all.min'); ?>
    <?php echo $this->Html->css('sweetalert2/dist/sweetalert2.min'); ?>
    <?php echo $this->Html->css('overrides'); ?>

    <!-- Google Font -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    <?php echo $this->fetch('css'); ?>

    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <?php echo $this->Html->script('vue_components/general'); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.0/axios.min.js"
            integrity="sha256-S1J4GVHHDMiirir9qsXWc8ZWw74PHHafpsHp5PXtjTs=" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/vue-select@latest"></script>
    <link rel="stylesheet" href="https://unpkg.com/vue-select@latest/dist/vue-select.css">

    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
          integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <?php echo $this->Html->script('main'); ?>
    <script>
        const USER_ID = '<?=  $appUser->_id ?>';
        const USER_NAME = '<?=  $appUser->full_name ?>';
    </script>
</head>
<body class="hold-transition skin-<?php echo Configure::read('Theme.skin'); ?> sidebar-mini">
<div class="wrapper">

    <header class="main-header">
        <!-- Logo -->
        <a href="<?php echo $this->Url->build('/'); ?>" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><?php echo Configure::read('Theme.logo.mini'); ?></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><?php echo Configure::read('Theme.logo.large'); ?></span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <?php echo $this->element('nav-top'); ?>
    </header>

    <?php echo $this->element('aside-main-sidebar'); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <section class="content-header">
            <?php if (isset($title)) { ?>
                <h1>
                    <?= $title ?>
                    <?php if (isset($subTitle)) { ?>
                        <small><?= $subTitle ?></small>
                    <?php } ?>
                </h1>
            <?php } ?>
            <!--            <ol class="breadcrumb">-->
            <!--                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>-->
            <!--                <li><a href="#">Examples</a></li>-->
            <!--                <li class="active">Blank page</li>-->
            <!--            </ol>-->
        </section>

        <?php echo $this->Flash->render(); ?>
        <?php echo $this->Flash->render('auth'); ?>
        <?php echo $this->fetch('content'); ?>

    </div>
    <!-- /.content-wrapper -->

    <?php echo $this->element('footer'); ?>

    <!-- Control Sidebar -->
    <?php echo $this->element('aside-control-sidebar'); ?>
    <!-- /.control-sidebar -->

    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<?php echo $this->Html->script('AdminLTE./bower_components/jquery/dist/jquery.min'); ?>
<?php echo $this->Html->script('notify.min'); ?>
<!-- Bootstrap 3.3.7 -->
<?php echo $this->Html->script('AdminLTE./bower_components/bootstrap/dist/js/bootstrap.min'); ?>
<!-- AdminLTE App -->
<?php echo $this->Html->script('AdminLTE.adminlte.min'); ?>
<!-- Slimscroll -->
<?php echo $this->Html->script('AdminLTE./bower_components/jquery-slimscroll/jquery.slimscroll.min'); ?>
<!-- FastClick -->
<?php echo $this->Html->script('AdminLTE./bower_components/fastclick/lib/fastclick'); ?>

<?php echo $this->fetch('script'); ?>

<?php echo $this->fetch('scriptBottom'); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $(".navbar .menu").slimscroll({
            height: "200px",
            alwaysVisible: false,
            size: "3px"
        }).css("width", "100%");

        var a = $('a[href="<?php echo $this->Url->build() ?>"]');
        if (!a.parent().hasClass('treeview') && !a.parent().parent().hasClass('pagination')) {
            a.parent().addClass('active').parents('.treeview').addClass('active');
        }

    });
</script>

</body>
</html>
