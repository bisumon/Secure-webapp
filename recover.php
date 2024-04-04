<?php  require_once('includes/header.php') ?>

    <!--Navigation Bar-->
    <?php require_once('includes/nav.php') ?>

    <!--Main Page-->        
    <div class="container">
        <div class="row">
            <div class="col-lg-6  m-auto">
                <div class="card bg-dark bg-opacity-50 mt-5 p-2">
                    <div class="card-title">
                        <h2 class="text-center text-light mt-2"> Recover Password </h2>
                        <hr>
                        <?php 
                            recover_password();
                            display_message();
                        ?>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="email" name="UEmail" placeholder="User Email" class="form-control py-2 mb-2">
                            <input type="hidden" name="token" value="<?php echo Token_Generator(); ?>">
                        
                    </div>
                    <div class="card-footer">
                            <button class="btn btn-dark float-left"> Reset Password </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php require_once('includes/footer.php') ?>
