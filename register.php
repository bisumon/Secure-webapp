<?php  require_once('includes/header.php') ?>

    <!--Navigation Bar-->
    <?php require_once('includes/nav.php') ?>
    <!--Registration Form-->
    <?php 
        define("site_key","6LfIQ5EpAAAAAPb8pVg3G4vC6T_gk3ZUdwyuwOg8");
    ?>
    <div class="container">
        <div class="row">
            <div class="col-lg-4  m-auto">
                <div class="card bg-dark bg-opacity-50 mt-5 py-2">   
                    <div class="card-title">
                        <h2 class="text-center text-light mt-2"> Registration Form </h2>
                        <hr>
                    </div>
                    <div class="card-body px-4 text-center">
                        <?php user_validation(); ?>
                        <form id="registration-form" method="post">
                            <div class="row py-2 mb-2 px-4"> 
                                <input type="text" name="FirstName" placeholder=" First Name " class="form-control col me-2" value="<?php if(isset($_POST['FirstName'])){echo $_POST['FirstName'];} ?>" required>
                                <input type="text" name="LastName" placeholder=" Last Name " class="form-control col ms-2" value="<?php if(isset($_POST['LastName'])){echo $_POST['LastName'];} ?>" required>
                            </div>
                            <div class="row py-2 mb-2 px-4">
                                <input type="text" name="UserName" placeholder=" User Name " class="form-control col" value="<?php if(isset($_POST['UserName'])){echo $_POST['UserName'];} ?>" required>
                            </div> 
                            <div class="row py-2 mb-2 px-4">
                                <input type="email" name="Email" placeholder=" Email " class="form-control col" value="<?php if(isset($_POST['Email'])){echo $_POST['Email'];} ?>" required>
                            </div>
                            <div class="row py-2 mb-2 px-4">
                                <input type="tel" name="Phone" placeholder=" Phone " class="form-control col" value="<?php if(isset($_POST['Phone'])){echo $_POST['Phone'];} ?>" required>
                            </div>
                            <div class="row py-2 mb-2 px-4">
                                <input type="password" name="pass" placeholder=" Password " class="form-control col" required>
                            </div>
                            <div class="row py-2 mb-2 px-4">
                                <input type="password" name="cpass" placeholder=" Confirm Password " class="form-control col" required>
                            </div>
                            <!-- <div class="g-recaptcha" data-sitekey="6LehTB8pAAAAANtM2VywtXCS-2MQkOBlFKwHLyFB"></div> -->
                            <button class="btn btn-dark float-right mt-2 g-recaptcha" data-sitekey="<?php echo site_key; ?>" data-callback='onSubmit' data-action='submit'> Register Now </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function onSubmit(token) {
            document.getElementById("registration-form").submit();
        }
    </script>
<?php require_once('includes/footer.php') ?>
