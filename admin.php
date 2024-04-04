<?php require_once('includes/header.php') ?>

    <!--Navigation Bar-->
    <?php require_once('includes/nav.php') ?>

    <!--Admin Main Page-->
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="bg-transparent mt-5 py-2">
                    <h3 class="text-center"> 
                     <?php 

                        if(logged_in())
                        {
                            echo '<div class="alert alert-success"> You Have Successfully Logged in </div>';
                        }
                        else
                        {
                            redirect('login.php');
                        }
                     
                     ?>       
                    </h3>
                </div>
            </div>
        </div>
    </div>

<?php require_once('includes/footer.php') ?>