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
    <title>Login</title>

    <?php echo $this->Html->css('css/font-face'); ?>
    <?php echo $this->Html->css('vendor/font-awesome-4.7/css/font-awesome.min'); ?>
    <?php echo $this->Html->css('vendor/font-awesome-5/css/fontawesome-all.min'); ?>
    <?php echo $this->Html->css('vendor/bootstrap-4.1/bootstrap.min'); ?>
    <?php echo $this->Html->css('vendor/mdi-font/css/material-design-iconic-font.min'); ?>
    <?php echo $this->Html->css('vendor/animsition/animsition.min'); ?>
    <?php echo $this->Html->css('vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min'); ?>
    <?php echo $this->Html->css('vendor/wow/animate'); ?>
    <?php echo $this->Html->css('vendor/css-hamburgers/hamburgers.min'); ?>
    <?php echo $this->Html->css('vendor/slick/slick'); ?>
    <?php echo $this->Html->css('vendor/select2/select2.min'); ?>
    <?php echo $this->Html->css('vendor/perfect-scrollbar/perfect-scrollbar'); ?>
    <?php echo $this->Html->css('theme'); ?>
    <?php echo $this->fetch('css'); ?>

</head>

<body class="animsition">
    <div class="page-wrapper">
        <div class="page-content--bge5">
            <div class="container">
                <div class="login-wrap" style="margin-top:12px;">
                    <div class="login-content">
                        <div class="login-logo">
                            <a href="#">
                                <img src="images/icon/logo.png" alt="CoolAdmin">
                            </a>
                        </div>
                        <div class="login-form">

                        <?php echo $this->Form->create('User'); ?>
                        <?php echo $this->Session->flash(); ?>

                <?php
                  echo $this->Form->input('username',array('class'=>'au-input au-input--full', 'label'=>false,'placeholder'=>'nom d\'utilisateur', 'id'=>'inputEmail'));
                  ?>
                    <br>
                  <?php
                  echo $this->Form->input('password',array('class'=>'au-input au-input--full', 'label'=>false,'placeholder'=>'Password', 'id'=>'inputPassword'));
                  ?>
                    <br>
                    <button class="btn btn-lg btn-primary btn-block btn-signin"  id="submit" name="submit" type="submit">Connexion</button>
                    </form>
 
                        </div>
                    </div>
                </div>
            </div>
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
    <?php echo $this->Html->script('vendor/select2/select2.min'); ?>
    <?php echo $this->Html->script('main'); ?>
    <?php echo $this->fetch('script'); ?>

</body>

</html>
<!-- end document-->