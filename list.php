<?php  require_once('includes/header.php') ?>

    <!--Navigation Bar-->
    <?php require_once('includes/nav.php') ?>
    <?php
        if($_SESSION['UType']=='customer')
        {
    ?>
    <div>You don't have access to this page!</div>
    <?php
        } else 
        {
    ?>
    <!--Main Page-->      
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="card bg-dark bg-opacity-50 my-5 py-3 px-5">
                    <h3 class="text-center text-light"> List of Requests </h3>
                    <?php  display_requests();?> 
                </div>
            </div>
        </div>
    </div>
    <?php
    }
    ?>
<?php require_once('includes/footer.php') ?>