<?php
   use PHPMailer\PHPMailer\PHPMailer;
   use PHPMailer\PHPMailer\Exception;
   require 'vendor/autoload.php';

   $domain = "localhost/Lovejoy"; 
   $mailbox = "mailbox";
   $mail_pw ="password"; 
   $secret = "secret";

   // Clean String Values
   function clean ($string)
   {
       return htmlentities($string);
   }

   // Redirection
   function redirect($location)
   {
       return header("location:{$location}");
   }

   // Set Session Message
   function set_message($msg)
   {
       if(!empty($msg))
       {
           $_SESSION['Message'] = $msg;
       }
       else
       {
           $msg="";
       }
   }

   // Display Message Function
   function display_message()
   {
       if(isset($_SESSION['Message']))
       {
            echo $_SESSION['Message'];
            unset($_SESSION['Message']);
       }
   }


   // Generate Token
   function Token_Generator()
   {
       $token = $_SESSION['token']=md5(uniqid(mt_rand(),true));
       return $token;
   }

   // Send Email Function
   function send_email($email,$sub,$msg,$header)
   {
    global $mailbox, $mail_pw;
    try{
        $mail = new PHPMailer();
        $mail->SMTPDebug = false;                   // Enable verbose debug output
        $mail->isSMTP();                        // Set mailer to use SMTP
        $mail->Host       = 'smtp.gmail.com;';    // Specify main SMTP server
        $mail->SMTPAuth   = true;               // Enable SMTP authentication
        $mail->Username   = $mailbox;     // SMTP username
        $mail->Password   = $mail_pw;         // SMTP password
        $mail->SMTPSecure = 'tls';              // Enable TLS encryption
        $mail->Port       = 587;                // TCP port to connect to
        $mail->setFrom($mailbox, 'Lovejoy');           // Set sender of the mail
        $mail->addAddress($email);           // Add a recipient
        $mail->isHTML(true);                                  
        $mail->Subject = $sub;
        $mail->Body    = $msg;
    //    return mail($email,$sub,$msg,$header);
        if($mail->send()){
            return true;
        } else {
            return false;
        }
        
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
       
       
   }

   //***********User Validation Functions********** */

   // Errors Function
   function Error_validation($Error)
   {
       return '<div class="alert alert-danger">'.$Error.'</div>';
   }


   // User Validation Function
   function user_validation()
   {
       if($_SERVER['REQUEST_METHOD']=='POST')
       {
           $FirstName = clean($_POST['FirstName']);
           $LastName = clean($_POST['LastName']);
           $UserName = clean($_POST['UserName']);
           $Email = clean($_POST['Email']);
           $Phone = clean($_POST['Phone']);
           $Pass = clean($_POST['pass']);
           $CPass = clean($_POST['cpass']);

           $Errors = [];
           $Max = 20;
           $Min = 02;

           //recaptcha
           global $secret;
           $response = $_POST['g-recaptcha-response'];
           $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response";

           $response_data= file_get_contents($url);
           $row = json_decode($response_data);
           if(!$row->success)
           {   
               $Errors[] = "Recaptcha verification failed";
           }else if($row->score<0.5) {
                $Errors[] = "Recaptcha verification score too low";
           }

           // Check the First Name Characters
           if(strlen($FirstName)<$Min)
           {
               $Errors[]= " First Name Cannot Be Less Than {$Min} Characters ";
           }

           // Check the First Name Characters
           if(strlen($FirstName)>$Max)
           {
                $Errors[]= " First Name Cannot Be More Than {$Max} Characters ";
           }

           // Check the Last Name Characters
           if(strlen($LastName)<$Min)
           {
               $Errors[]= " Last Name Cannot Be Less Than {$Min} Characters ";
           }

           // Check the Last Name Characters
           if(strlen($LastName)>$Max)
           {
                $Errors[]= " Last Name Cannot Be More Than {$Max} Characters ";
           }

           // Check the Username Format
           if(!preg_match("/^[a-zA-Z,0-9]*$/",$UserName))
           {
                $Errors[]= " User Name Includes Invalid Characters ";
           }

           // Check the Email Exists
           if(Email_Exists($Email))
           {
                $Errors[]= " Email Already Registered ! ";
           }

           // Check the User Name Exists
           if(User_Exists($UserName))
           {
                $Errors[]= " User Name Already Registered ! ";
           }

           // Checks Phone Format
           if(!preg_match('/[0-9]{7,20}/', $Phone))
           {
                $Errors[]= " Phone should be at least 7 digits and only contain numbers ! ";
           }

            // Check Phone Number Exists
            if(Phone_Exists($Phone))
            {
                $Errors[]= " Phone Number Already Registered ! ";
            }

           // Password & Confirm Password
           if($Pass!=$CPass)
           {
                $Errors[]= " Password Not Matched ! ";
           }
           // Password Strength
           if(strlen($Pass)<8) {
                $Errors[] = "Password should contain at least 8 digits";
           }
           if(preg_match('/\s/', $Pass))
           {
                $Errors[]= " Password should not contain spaces! ";
           }

           if(!preg_match('/[A-Z]/', $Pass))
           {
                $Errors[]= " Password should contain at least one upper case! ";
           }

           if(!preg_match('/[a-z]/', $Pass))
           {
                $Errors[]= " Password should contain at least one lower case! ";
           }

           if(!preg_match('/[0-9]/', $Pass))
           {
                $Errors[]= " Password should contain at least one number! ";
           }

           if(!preg_match('/[_.\/?!*@#$%^&\'"]/', $Pass))
           {
                $Errors[]= " Password should contain at least one symbol! ";
           }


           if(!empty($Errors))
           {
               foreach($Errors as $Error)
               {
                    echo Error_validation($Error);
               }
           }
           else
           {
                if(user_registration($FirstName,$LastName,$UserName,$Email,$Phone,$Pass))
                {
                    set_message('<p class="bg-success-subtle border border-success-subtle text-center rounded-3 lead">You have successfully registered, please check you email for the activation link</p>');
                    redirect("index.php");

                }
                else
                {
                    set_message('<p class="bg-danger-subtle border border-danger-subtle text-center rounded-3 lead"> Registration unsuccessful, please try again </p>');
                    redirect("index.php");
                }
           }

        }
   }

   // Email Exists Function
   function Email_Exists($email)
   {
        $sql = " select * from Users where Email='$email'";
        $result = Query($sql);
        if(fetch_data($result))
        {
            return true;
        }
        else
        {
            return false;
        }
   }

   // User Exists Function
   function User_Exists($user)
   {
        $sql = " select * from Users where UserName='$user'";
        $result = Query($sql);
        if(fetch_data($result))
        {
            return true;
        }
        else
        {
            return false;
        }
   }

      // User Exists Function
      function Phone_Exists($phone)
      {
           $sql = " select * from Users where Phone='$phone'";
           $result = Query($sql);
           if(fetch_data($result))
           {
               return true;
           }
           else
           {
               return false;
           }
      }

   // User Registration Function
   function user_registration($FName,$LName,$UName,$Email,$Phone,$Pass)
   {    
        global $domain;
        $FirstName = escape($FName);
        $LastName = escape($LName);
        $UserName = escape($UName);
        $Email = escape($Email);
        $Phone = escape($Phone);
        $Pass = escape($Pass);

        if(Email_Exists($Email))
        {
            return true;
        }
        else if(User_Exists($UserName))
        {
            return true;
        }
        else if(Phone_Exists($Phone))
        {
            return true;
        }
        else
        {   
            try {
                $Salt = mt_rand();
                $Password = md5($Pass.$Salt);
                $Validation_code = md5($UserName.microtime());
                $date_created = date("Y-m-d H:i:s");
                $sql = "insert into Users (FirstName,LastName,UserName,Email,Phone,Password,Salt,Validation_Code,Active, Date_created) values ('$FirstName','$LastName','$UserName','$Email','$Phone','$Password','$Salt','$Validation_code','0', '$date_created')";
                $result = Query($sql);
                confirm($result);


                $subject = " Active Your Account ";
                $msg = " Please Go to this Link to Activate Your Account: $domain/activate.php?Email=$Email&Code=$Validation_code";
                $header = "From No-Reply Lovejoy";
                send_email($Email,$subject,$msg,$header);

                return true;
            } catch (Exception $e) {
                return false;
            }
        }
   }

   //Email Activation Function
   function activation()
   {
       if($_SERVER['REQUEST_METHOD']=="GET")
       {
           $Email = $_GET['Email'];
           $Code = escape($_GET['Code']);

           $sql = " select * from Users where Email='$Email' AND Validation_Code='$Code'";
           $result = Query($sql);
           confirm($result);

            if(fetch_data($result))
            {
                $sqlquery = " update Users set Active='1', Validation_Code='0' where Email='$Email' AND Validation_Code='$Code'";
                $result2 = Query($sqlquery);
                confirm($result2);
                set_message('<p class="bg-success-subtle border border-success-subtle text-center text-success-emphasis rounded-3 mx-auto px-5 my-auto lead"> Your Account Has Been Successfully Activated </p>');
                redirect('login.php');
            }
            else
            {
                echo '<p class="bg-danger-subtle border border-danger-subtle text-center text-danger-emphasis rounded-3 mx-auto px-5 my-auto lead"> Your Account is Not Activated </p>';
            }

       }
   }


   ///User Login Validation Function
   function login_validation()
   {
       $Errors = [];

       if($_SERVER['REQUEST_METHOD'] == 'POST')
       {    
            global $secret;
            $response = $_POST['g-recaptcha-response'];
            $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response";

            $response_data= file_get_contents($url);
            $row = json_decode($response_data);
            // if(!$row->success)
            // {   
            //     $Errors[] = "Recaptcha verification failed";
            // }
            $UserEmail = clean($_POST['UEmail']);
            $UserPass = clean($_POST['UPass']);
            $Remember = isset($_POST['remember']);


            if(empty($UserEmail))
            {
                $Errors[] = " Please Enter Your Email ";
            }

            if(empty($UserPass))
            {
                $Errors[] = " Please Enter Your Password ";
            }

            if(!empty($Errors))
            {
                foreach ($Errors as $Error)
                {
                    echo Error_validation($Error);
                }
            }
            else
            {   
                // if(!user_login($UserEmail,$UserPass,$Remember)) 
                // {   
                //     echo Error_validation(" Please Enter Correct Email or Password");
                // }
                user_login($UserEmail,$UserPass,$Remember);

            }
       }
   }

   

   // User Login Function
   function user_login($UEmail,$UPass,$Remember)
   {    
        $query = "select * from Users where Email='$UEmail' and Active='1'";
        $result = Query($query);
         //if email exists, add 1 to number of login attempts, if attempts reaches limit, lock account
        $query = "select Num_Attempts, Lock_Period from Users where Email='$UEmail' and Active='1'";

        if($row=fetch_data($result))
        {   
            //todo: check if user login attempts reached limit
            $num_attempts = $row['Num_Attempts'];
            $lock_period = $row['Lock_Period'];
            if($lock_period>date("Y-m-d H:i:s", time())) {
                echo Error_validation(" Your account is currently locked for security reasons, please try again later ");
                return false;
            }

            $db_pass = $row['Password'];
            $salt = $row['Salt'];
            if(md5($UPass.$salt)==$db_pass)
            {
                if($row['Type']==='customer'){
                    $_SESSION['Email']=$UEmail;
                    $_SESSION['Uid']=$row['ID'];
                    $_SESSION['UType']=$row['Type'];
                    // redirect("admin.php");
                    $code = mt_rand();
                    $sql = "update Users set Validation_Code='$code', Num_Attempts=0, Lock_Period=NULL where Email='$UEmail'";
                    Query($sql);
                    
                    $ID = $row['ID'];
                    $subject = " Log in Code ";
                    $msg = " Your validation code is $code";
                    $header = "From No-Reply Lovejoy";
                    send_email($UEmail,$subject,$msg,$header);
                    redirect("logincode.php?Email=$UEmail&ID=$ID");
                } else {
                    $sql = "update Users set Validation_Code='$code', Num_Attempts=0, Lock_Period=NULL where Email='$UEmail'";
                    Query($sql);
                    $_SESSION['Email']=$UEmail;
                    $_SESSION['Uid']=$row['ID'];
                    $_SESSION['UType']=$row['Type'];
                    redirect("admin.php");
                }

            }
            else
            {   
                //add 1 to login attempts and lock account if attempt limit is reached
                $num_attempts+=1;
                if($num_attempts >= 5) {
                    $lock_period = date("Y-m-d H:i:s", strtotime("+30 minutes")); 
                    $sql = "update Users set Num_Attempts='$num_attempts', Lock_Period='$lock_period' where Email='$UEmail'";
                    Query($sql);
                    echo Error_validation(" Your account has been temporarily locked due to multiple failed login attempts, please try again in 30 minutes ");
                } else {
                    $sql = "update Users set Num_Attempts='$num_attempts' where Email='$UEmail'";
                    Query($sql);
                    echo Error_validation(" Please Enter Correct Email or Password ");
                }
                return false;
            }
        } else {
            echo Error_validation(" Please Enter Correct Email or Password ");
            return false;
        }

   }

   //Logged in Function
   function logged_in()
   {
       if(isset($_SESSION['Email']) || isset($_COOKIE['email']))
       {
           return true;
       }
       else
       {
           return false;
       }
   }

   /////////////Recover Function///////////////////
   function recover_password()
   {
        global $domain;
        if($_SERVER['REQUEST_METHOD'] == "POST")
        {
            if(isset($_SESSION['token']) && $_POST['token'] == $_SESSION['token'])
            {
               $Email = $_POST['UEmail'];

               if(Email_Exists($Email))
               {
                    $code = md5($Email.microtime());
                    setcookie('temp_code',$code,time()+300);
                    $sql = "update Users set Validation_Code='$code' where Email='$Email'";
                    Query($sql);

                    $Subject = " Please Reset the Password ";
                    $Message = "Your code is: <b>{$code}</b>.  Please go to this link to reset your password $domain/code.php?Email='$Email'&Code='$code'";
                    $header = "Lovejoy";

                    if(send_email($Email,$Subject,$Message,$header))
                    {
                        echo '<div class="alert alert-success"> Please Check Your Email :) </div>';
                    }
                    else
                    {
                        echo Error_validation(" We Coudn't Send an Email ");
                    }

               }
               else
               {
                   echo Error_validation(" Email Not Found....");
               }

            }
            else
            {
                echo Error_validation("Unauthorized action");
            }
        }
   }


   /// Validation Code Function
   function validation_code()
   {
       if(isset($_COOKIE['temp_code']))
       {    
            if(!isset($_GET['Email']) && !isset($_GET['Code']))
            {
                redirect("index.php");
            }
            else if(empty($_GET['Email']) && empty($_GET['Code']))
            {
                redirect("index.php");
            }
            else
            {
                if(isset($_POST['recover-code']))
                {   
                    $Code = escape($_POST['recover-code']);
                    $Email = $_GET['Email'];

                    $query = "select * from Users where Validation_Code='$Code' and Email=$Email";
                    $result = Query($query);
                    if(fetch_data($result))
                    {
                        setcookie('temp_code',$Code, time()+300);
                        redirect("reset.php?Email=$Email&Code=$Code");
                    }
                    else
                    {
                        echo Error_validation(" Your Code is Wrong :) ");
                    }
                }
            }
       }
       else
       {
           set_message('<div class="alert alert-danger"> Your Code Has Expired </div>');
           redirect("recover.php");
       }
   }


   ///////////////Reset Password Function//////////////////////

   function reset_password()
   {
       if(isset($_COOKIE['temp_code']))
       {
            if(isset($_GET['Email']) && isset($_GET['Code']))
            {
                if(isset($_SESSION['token']) && isset($_POST['token']))
                {
                    if($_SESSION['token'] == $_POST['token'])
                    {
                         if($_POST['reset-pass'] === $_POST['reset-c-pass'])
                         {
                                $Errors = [];
                                $Password = $_POST['reset-pass'];
                                // Password Strength
                                if(strlen($Password)<8) {
                                    $Errors[] = "Password should contain at least 8 digits";
                                }
                                if(preg_match('/\s/', $Password))
                                {
                                    $Errors[]= " Password should not contain spaces! ";
                                }

                                if(!preg_match('/[A-Z]/', $Password))
                                {
                                     $Errors[]= " Password should contain at least one upper case! ";
                                }

                                if(!preg_match('/[a-z]/', $Password))
                                {
                                    $Errors[]= " Password should contain at least one lower case! ";
                                }

                                if(!preg_match('/[0-9]/', $Password))
                                {
                                    $Errors[]= " Password should contain at least one number! ";
                                }

                                if(!preg_match('/[_.\/?!*@#$%^&\'"]/', $Password))
                                {
                                     $Errors[]= " Password should contain at least one symbol! ";
                                }
                     

                                if(!empty($Errors)) 
                                {
                                    foreach($Errors as $Error)
                                    {
                                        echo Error_validation($Error);
                                    }
                                } else {
                                    $Salt = mt_rand();
                                    $Password = md5($Password.$Salt);
                                    $query2 = "update Users set Password='".$Password."', Validation_Code=0, Salt='".$Salt."' where Email=".$_GET['Email'];
                                    $result = Query($query2);

                                    if($result)
                                    {
                                        set_message('<div class="alert alert-success"> Your password has been successfully updated! </div>');
                                        redirect("login.php");

                                    }
                                    else
                                    {
                                        set_message('<div class="alert alert-danger"> Something went wrong, please try again! </div>');
                                    }

                                }
                                
                         }
                         else
                         {
                            set_message('<div class="alert alert-danger"> Passwords does not match! </div>');
                         }
                    }

                }

            }
            else
            {
                set_message('<div class="alert alert-danger> Your code or your email does not match! </div>');
            }
       }
       else
       {
           set_message('<div class="alert alert-danger> Your time period has expired, please try again! </div>');
       }
   }

   function request_evaluation()
   {

       if($_SERVER['REQUEST_METHOD'] == 'POST')
       {
        if((isset($_SESSION['token']) && isset($_POST['token']))&&$_POST['token'] === $_SESSION['token'])
        {
            if(isset($_POST['submit'])) {
                $file = $_FILES['photo'];
            
                $file_name = $file['name'];
                $file_type = $file['type'];
                $file_tmp_name = $file['tmp_name'];
                $file_size = $file['size'];
                $file_error = $file['error'];
                $file_dest = "";
                $file_ext = explode('.', $file_name);
                $file_actual_ext = strtolower(end($file_ext));
                
                $allowed = array('jpg', 'jpeg', 'png');
            
                if(in_array($file_actual_ext, $allowed)) {
                    if($file_error === 0) {
                        if($file_size < 1000000) {
                            $file_name_new = uniqid().".".$file_actual_ext;
                            $file_dest = 'images/items/'.$file_name_new;
                            move_uploaded_file($file_tmp_name, $file_dest);
                        } else {
                            $Errors[]= "File size too big";
                        }
                    } else {
                        $Errors[]= "There was an error uploading your file";
                    }
                } else {
                    $Errors[]="File type not allowed";
                }
            
            } else {
                $Errors[]="Submission Unsucessful";
            }
            $Comments = clean($_POST['comments']);
            $Contact = clean($_POST['contact']);
            $Photo = $file_dest;

            if(empty($Comments))
            {
                $Errors[] = " Please Enter Your Comments";
            }


            if(!empty($Errors))
            {
                foreach ($Errors as $Error)
                {
                    echo Error_validation($Error);
                }
            }
            else
            {
                if(submit_request($Comments, $Contact, $Photo))
                {
                    echo '<div class="alert alert-success"> Submission Successful </div>';
                }
                else
                {
                    echo Error_validation("Submission failed, please try again");
                }
            }
        } else {
            echo Error_validation("Unauthorized action");
        }

       }
   }

   function submit_request($Comments, $Contact, $Photo) 
   {
    $UserID=$_SESSION['Uid'];
    $query = "insert into Requests (user_id, comment, contact, photo) values ('$UserID','$Comments','$Contact','$Photo')";
    $result = Query($query);
    confirm($result);
    
    return true;

    

   }

   function display_requests() {
    $query = "select * from Requests";
    $result = Query($query);
    $output = "";
    
    $total_results = row_count($result);
    $results_per_page = 6;
    $num_pages = ceil($total_results / $results_per_page);
    $page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page']<=$num_pages && $_GET['page']>0? $_GET['page'] : 1;

    $rows = fetch_all($result);
    $page_first_result = ($page-1) * $results_per_page;  

    for($i=$page_first_result; $i<($page_first_result+$results_per_page>$total_results-1? $total_results:$page_first_result+$results_per_page); $i++) {
        $row = $rows[$i];
        $user_id = $row['user_id'];
        $comment = $row['comment'];
        $contact = $row['contact'];
        $photo_path = $row['photo'];
        $date = $row['timestamp'];
        $output.= "<div class='text-light'>";
        $output.= "<h5>Request for evaluation by user with user ID ".$user_id."</h5>";
        $output.="<div>Comment: ".$comment."</div>";
        $output.="<div>Preferred contact: ".$contact."</div>";
        $output.="<div><img src='".$photo_path."' alt='photo' width='300' height='300'></div>";
        $output.="<div>Date: ".$date." </div>";
        $output.= "</div>";
        $output.="<hr>";
    }

    $output .= '<nav aria-label="Page navigation"><ul class="pagination">';
    for($page = 1; $page<= $num_pages; $page++) {  
        $output .= '<li class="page-item"><a class="page-link" href = "list.php?page=' . $page . '">' . $page . ' </a></li>';  
    } 
    $output .= '</ul></nav>';

    echo $output;
   }

   function login_code()
   {
    if(isset($_POST['login-code']))
        {   
            $Code = escape($_POST['login-code']);
            $Email = $_GET['Email'];
            $ID = $_GET['ID'];

            $query = "select * from Users where Validation_Code='$Code' and Email='$Email'";
            $result = Query($query);
            if(fetch_data($result))
            {   
                $_SESSION['Email']=$Email;
                $_SESSION['Uid']=$ID;
                $_SESSION['UType']='customer';
                redirect("admin.php");
 
            }
            else
            {
                echo Error_validation(" Your Code is Wrong");
            }
        }
   }
?>
