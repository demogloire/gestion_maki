
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="au theme template">
    <meta name="author" content="Hau Nguyen">
    <meta name="keywords" content="au theme template">

    <!-- Title Page-->
    <title><?php if($title !=null){echo $title; }else{echo "Ets MAKI |Gestion"; }?></title>

    <?php echo $this->Html->css('font-face'); ?>
    <?php echo $this->Html->css('vendor/font-awesome-4.7/css/font-awesome.min'); ?>
    <?php echo $this->Html->css('vendor/font-awesome-5/css/fontawesome-all.min'); ?>
    <?php echo $this->Html->css('vendor/mdi-font/css/material-design-iconic-font.min'); ?>

    
    <?php echo $this->Html->css('vendor/bootstrap-4.1/bootstrap.min'); ?>

    <?php echo $this->Html->css('vendor/animsition/animsition.min'); ?>
    <?php echo $this->Html->css('vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min'); ?>
    <?php echo $this->Html->css('vendor/wow/animate'); ?>
    <?php echo $this->Html->css('vendor/css-hamburgers/hamburgers.min'); ?>
    <?php echo $this->Html->css('vendor/slick/slick'); ?>
    <?php echo $this->Html->css('vendor/perfect-scrollbar/perfect-scrollbar'); ?>
    <?php echo $this->Html->css('theme'); ?>
    <?php echo $this->Html->css('style'); ?>   
    <?php echo $this->Html->css('select2/select2'); ?>
    <?php echo $this->Html->css('datepicker'); ?>
    <?php echo $this->Html->script('tinymce/jquery.tinymce.min'); ?>
    <?php echo $this->Html->script('tinymce/tinymce.min'); ?>
    <?php echo $this->Html->script('JsBarcode.all.min'); ?>
    <script>tinymce.init({selector:'textarea',height: 400});</script>
    <?php echo $this->fetch('script'); ?>

    
   

</head>

<body class="animsition">
    <div class="page-wrapper">
        <!-- HEADER MOBILE-->
        <header class="header-mobile d-block d-lg-none">
            <div class="header-mobile__bar">
                <div class="container-fluid">
                    <div class="header-mobile-inner">
                        <a class="logo" href="index.html">
                            <img src="images/icon/logo.png" alt="CoolAdmin" />
                        </a>
                        <button class="hamburger hamburger--slider" type="button">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            <nav class="navbar-mobile">
                <div class="container-fluid">
                    <ul class="navbar-mobile__list list-unstyled">
                        <li class="has-sub">
                            <a class="js-arrow" href="#">
                                <i class="fas fa-tachometer-alt"></i>Dashboard</a>
                            <ul class="navbar-mobile-sub__list list-unstyled js-sub-list">
                                <li>
                                    <a href="index.html">Dashboard 1</a>
                                </li>
                                <li>
                                    <a href="index2.html">Dashboard 2</a>
                                </li>
                                <li>
                                    <a href="index3.html">Dashboard 3</a>
                                </li>
                                <li>
                                    <a href="index4.html">Dashboard 4</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="chart.html">
                                <i class="fas fa-chart-bar"></i>Charts</a>
                        </li>
                        <li>
                            <a href="table.html">
                                <i class="fas fa-table"></i>Tables</a>
                        </li>
                        <li>
                            <a href="form.html">
                                <i class="far fa-check-square"></i>Forms</a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="fas fa-calendar-alt"></i>Calendar</a>
                        </li>
                        <li>
                            <a href="map.html">
                                <i class="fas fa-map-marker-alt"></i>Maps</a>
                        </li>
                        <li class="has-sub">
                            <a class="js-arrow" href="#">
                                <i class="fas fa-copy"></i>Pages</a>
                            <ul class="navbar-mobile-sub__list list-unstyled js-sub-list">
                                <li>
                                    <a href="login.html">Login</a>
                                </li>
                                <li>
                                    <a href="register.html">Register</a>
                                </li>
                                <li>
                                    <a href="forget-pass.html">Forget Password</a>
                                </li>
                            </ul>
                        </li>
                        <li class="has-sub">
                            <a class="js-arrow" href="#">
                                <i class="fas fa-desktop"></i>UI Elements</a>
                            <ul class="navbar-mobile-sub__list list-unstyled js-sub-list">
                                <li>
                                    <a href="button.html">Button</a>
                                </li>
                                <li>
                                    <a href="badge.html">Badges</a>
                                </li>
                                <li>
                                    <a href="tab.html">Tabs</a>
                                </li>
                                <li>
                                    <a href="card.html">Cards</a>
                                </li>
                                <li>
                                    <a href="alert.html">Alerts</a>
                                </li>
                                <li>
                                    <a href="progress-bar.html">Progress Bars</a>
                                </li>
                                <li>
                                    <a href="modal.html">Modals</a>
                                </li>
                                <li>
                                    <a href="switch.html">Switchs</a>
                                </li>
                                <li>
                                    <a href="grid.html">Grids</a>
                                </li>
                                <li>
                                    <a href="fontawesome.html">Fontawesome Icon</a>
                                </li>
                                <li>
                                    <a href="typo.html">Typography</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- END HEADER MOBILE-->

        <!-- MENU SIDEBAR-->
        <aside class="menu-sidebar d-none d-lg-block">
            <div class="logo">
                <a href="#">
                    <img src="images/icon/logo.png" alt="Cool Admin" />
                </a>
            </div>
            <div class="menu-sidebar__content js-scrollbar1">
                <nav class="navbar-sidebar">
                    <ul class="list-unstyled navbar__list">
                        <li>
                        <?php echo $this->Html->link($this->Html->tag('i', ' ', array('class' => 'fas fa-shopping-cart')).'   Vente', array('controller' => 'users', 'action' => 'logout'), array('escape' => false)); ?>
                        </li>
                        <li class="has-sub">
                            <a class="js-arrow" href="#">
                                <i class="fas fa-chart-bar"></i>Stock</a>
                            <ul class="list-unstyled navbar__sub-list js-sub-list">
                                <li><?php echo $this->Html->link('Disponible',array('controller' =>'warehouses','action'=>'index_depot')); ?>
                                </li>
                                <li><?php echo $this->Html->link('Transferer',array('controller' =>'warehouses','action'=>'transfert')); ?>
                                </li>
                                <li><?php echo $this->Html->link('Corriger',array('controller' =>'warehouses','action'=>'correction')); ?>
                                </li>
                                <li> <?php echo $this->Html->link('Perrisable',array('controller' =>'warehouses','action'=>'perrisable')); ?>
                                </li>
                            </ul>
                        </li>
                        <li>
                        <?php echo $this->Html->link($this->Html->tag('i', ' ', array('class' => 'fas fa-folder')).'Rapports', array('controller' => 'users', 'action' => 'logout'), array('escape' => false)); ?>
                        </li>
                        <li class="has-sub">
                            <a class="js-arrow" href="#">
                                <i class="fas fa-gears"></i>Configurations</a>
                            <ul class="list-unstyled navbar__sub-list js-sub-list">
                                <li><?php echo $this->Html->link('Utilisateur',array('controller' =>'users','action'=>'enregister_utilisateur')); ?>
                                </li>
                                <li><?php echo $this->Html->link('Catégorie',array('controller' =>'categories','action'=>'index')); ?>
                                </li>
                                <li><?php echo $this->Html->link('Produit',array('controller' =>'products','action'=>'index')); ?>
                                </li>
                            </ul>
                        </li>

                        
                    </ul>
                </nav>
            </div>
        </aside>
        <!-- END MENU SIDEBAR-->

        <!-- PAGE CONTAINER-->
        <div class="page-container">
            <!-- HEADER DESKTOP-->
            <header class="header-desktop">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="header-wrap">
                            <form class="form-header" action="" method="POST">
                                <input class="au-input au-input--xl" type="text" name="search" placeholder="Search for datas &amp; reports..." />
                                <button class="au-btn--submit" type="submit">
                                    <i class="zmdi zmdi-search"></i>
                                </button>
                            </form>
                            <div class="header-button">
                                <div class="noti-wrap">
                                                                       
                                    <div class="noti__item js-item-menu">
                                        <i class="zmdi zmdi-notifications"></i>
                                        <?php echo $this->element('notification');?>
                                        
                                    </div>
                                </div>
                                <div class="account-wrap">
                                    <div class="account-item clearfix js-item-menu">
                                        <div class="image">
                                            <?php
                                                echo $this->Html->image("de.png", array("alt" => "Brownies"));
                                            ?>
                                            
                                        </div>
                                        <div class="content">
                                            <a class="js-acc-btn" href="#"><?php   echo $this->Session->read('Auth.User.prenom'); ?>    </a>
                                        </div>
                                        <div class="account-dropdown js-dropdown">
                                            <div class="info clearfix">
                                                <div class="image">
                                                <?php echo $this->Html->image("de.png", array("alt" => "MAKI")); ?>
                                                </div>
                                                <div class="content">
                                                    <h5 class="name">
                                                        <a href="#"><?php   echo $this->Session->read('Auth.User.prenom').' '.$this->Session->read('Auth.User.nom') ?></a>
                                                    </h5>
                                                    <span class="email"><?php   echo $this->Session->read('Auth.User.username'); ?> </span>
                                                </div>
                                            </div>
                                            <div class="account-dropdown__footer">

                                            <?php echo $this->Html->link($this->Html->tag('i', '', array('class' => 'zmdi zmdi-power')).'Déconnexion', array('controller' => 'users', 'action' => 'logout'), array('escape' => false)); ?>

                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- HEADER DESKTOP-->

            <!-- MAIN CONTENT-->
            <div class="main-content">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                    <?php echo $this->Session->flash(); ?>
                    <?php echo $this->fetch('content'); ?>
                    </div>
                </div>
            </div>
            <!-- END MAIN CONTENT-->
            <!-- END PAGE CONTAINER-->
        </div>

    </div>

    <?php echo $this->Html->script('vendor/jquery-3.2.1.min'); ?>
    <?php echo $this->Html->script('vendor/bootstrap-4.1/popper.min'); ?>
    <?php echo $this->Html->script('vendor/bootstrap-4.1/bootstrap.min'); ?>
    <?php echo $this->Html->script('vendor/slick/slick.min'); ?>
    <?php echo $this->Html->script('vendor/wow/wow.min'); ?>
    <?php echo $this->Html->script('vendor/animsition/animsition.min'); ?>
    <?php echo $this->Html->script('vendor/bootstrap-progressbar/bootstrap-progressbar.min'); ?>
    <?php echo $this->Html->script('vendor/counter-up/jquery.waypoints.min'); ?>
    <?php echo $this->Html->script('vendor/counter-up/jquery.counterup.min'); ?>
    <?php echo $this->Html->script('vendor/circle-progress/circle-progress.min'); ?>
    <?php echo $this->Html->script('vendor/perfect-scrollbar/perfect-scrollbar'); ?>
    <?php echo $this->Html->script('vendor/chartjs/Chart.bundle.min'); ?>
    <?php echo $this->Html->script('main'); ?>
    <?php echo $this->Html->script('select2/select2'); ?>
    <?php echo $this->Html->script('datepicker'); ?>
    <?php echo $this->fetch('script'); ?>

    <?php echo $this->fetch('modale'); ?>

</body>

</html>
<!-- end document-->
