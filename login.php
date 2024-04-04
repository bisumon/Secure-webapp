<?php  require_once('includes/header.php') ?>

    <!--Navigation Bar-->
    <?php require_once('includes/nav.php') ?>

    <!--Main Page-->  
    <?php 
        define("site_key","6LfIQ5EpAAAAAPb8pVg3G4vC6T_gk3ZUdwyuwOg8");
    ?>      
    <div class="container">
        <div class="row">
            <div class="col-lg-4  m-auto px-5">
                <div class="card bg-dark bg-opacity-50 mt-5 p-2">
                    <div class="card-title">
                        <?php 
                            display_message();
                            login_validation();
                        ?>
                        <h2 class="text-center text-light mt-2"> Login Form </h2>
                        <hr>
                    </div>
                    <div class="card-body text-center">
                       <form id="login-form" method="POST">
                            <div class="row py-2 mb-2 px-4">
                                <input type="email" name="UEmail" placeholder="User Email" class="form-control py-2 mb-2" value="<?php if(isset($_POST['UEmail'])){echo $_POST['UEmail'];} ?>">
                            </div>
                            <div class="row py-2 mb-2 px-4">
                            <input type="password" name="UPass" placeholder=" Password " class="form-control py-2 mb-2">
                            </div> 
                            <!-- <div class="g-recaptcha" data-sitekey="6LehTB8pAAAAANtM2VywtXCS-2MQkOBlFKwHLyFB"></div>
                            <br/> -->
                            <button class="btn btn-dark float-right mt-2 g-recaptcha" data-sitekey="<?php echo site_key; ?>" data-callback='onSubmit' data-action='submit'> Login </button>
                       
                    </div>
                    <div class="card-footer">
                        <!-- <input type="checkbox" name="remember"> <span> Remember Me </span>  -->
                        <a href="recover.php" class="float-right text-light"> Forgot Password </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function onSubmit(token) {
            document.getElementById("login-form").submit();
        }
    </script>
<?php require_once('includes/footer.php') ?>
