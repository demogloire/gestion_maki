
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
    <?php echo $this->Html->script('html2pdf.bundle.min'); ?>
    <title><?php if($title !=null){echo $title; }else{echo "Ets MAKI |Gestion"; }?></title>
    <script>tinymce.init({selector:'textarea',height: 400});</script>
    <script src="{{url_for('static', filename='html2pdf.bundle.min.js')}}"></script>
    <script>
        function generatePDF() {

            const element = document.getElementById("rapport");
            html2pdf()
                .set({ html2canvas: { scale: 4 }, jsPDF:{ orientation: 'landscape' }})
                .from(element)
                .save();
                
        }
    </script>
    <?php echo $this->fetch('script'); ?>
   

</head>

<body class="animsition">

        <!-- PAGE CONTAINER-->


                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                    <?php echo $this->Session->flash(); ?>
                    <?php echo $this->fetch('content'); ?>
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
