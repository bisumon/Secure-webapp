<?php  require_once('includes/header.php') ?>

    <!--Navigation Bar-->
    <?php require_once('includes/nav.php') ?>

    <!--Main Page-->        
    <div class="container">
        <div class="row">
            <div class="col-lg-6  m-auto">
                <div class="card bg-dark bg-opacity-50 mt-5 p-2">
                    <div class="card-title">
                        <h2 class="text-center text-light mt-2"> Please Check Your Email For Login Code </h2>
                        <hr>
                        <?php login_code(); ?>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="text" name="login-code" placeholder="Please enter your log in code" class="form-control py-2 mb-2"> 
                        
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-dark float-left"> Login </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php require_once('includes/footer.php') ?>
