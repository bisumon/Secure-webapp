<?php  require_once('includes/header.php') ?>

    <!--Navigation Bar-->
    <?php require_once('includes/nav.php') ?>

    <!--Main Page--> 
    <div class="container">
        <div class="row">
            <div class="col-lg-6  m-auto">
                <div class="card bg-dark bg-opacity-50 mt-5 py-2">
                    
                    <div class="card-title">
                        <h2 class="text-center text-light mt-2"> Request Evaluation Form </h2>
                        <hr>
                    </div>
                    <div class="card-body">
                        <?php  request_evaluation();
                               display_message(); 
                        ?>
                        <form class="text-light" method="post" enctype="multipart/form-data">
                            <div>Comments</div>
                            <textarea class="form-control" style ="max-height: 200px" name ="comments" cols="35" rows="6" id="comment_entered" required></textarea>
                            <div>Preferred Contact</div>
                            <select name="contact" class="py-2 mb-2">
                                <option value="phone">phone</option>
                                <option value="email">email</option>
                            </select>   
                            <div>Select image to upload: (allowed format: JPG,JPEG,PNG)</div>
                            <input name="photo" type="file" name="fileToUpload" id="fileToUpload" class="py-2 mb-2" required>
                            <input type="hidden" name="token" value="<?php echo Token_Generator(); ?>">
                            <div><button name = "submit" class="btn btn-dark float-right">Submit</button></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>       

<?php require_once('includes/footer.php') ?>