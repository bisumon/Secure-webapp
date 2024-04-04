<?php  require_once('includes/header.php') ?>

<!--Navigation Bar-->
<?php require_once('includes/nav.php') ?>
<?php
// print_r($_POST); //testing
if(isset($_POST['submit'])) {
    $file = $_FILES['photo'];

    $file_name = $file['name'];
    $file_type = $file['type'];
    $file_tmp_name = $file['tmp_name'];
    $file_size = $file['size'];
    $file_error = $file['error'];

    $file_ext = explode('.', $file_name);
    $file_actual_ext = strtolower(end($file_ext));
    
    $allowed = array('jpg', 'jpeg', 'png');

    if(in_array($file_actual_ext, $allowed)) {
        if($file_error === 0) {
            if($file_size < 1000000) {
                $file_name_new = uniqid('', true).".".$file_actual_ext;
                // echo $file_name_new; //testing
                $file_dest = 'images/'.$file_name_new;
                move_uploaded_file($file_tmp_name, $file_dest);
                echo "submission successful!";
            } else {
                echo "File size too big";
            }
        } else {
            echo "There was an error uploading your file";
        }
    } else {
        echo "File type not allowed";
    }

} else {
   echo "not submitted"; //testing
}

// echo "test"; //testing

?>
<?php require_once('includes/footer.php') ?>