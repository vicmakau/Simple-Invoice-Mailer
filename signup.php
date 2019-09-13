<?php


require_once("header.php");


require_once("server.php");


 function clean_data($data){

        $data=htmlspecialchars($data);

        $data=trim($data);

        $data=stripslashes($data);

        return $data;

    }


    //define validation error as empty 

    $error= $addMemberErr = "";


    //define vars

    $fname = $lname = $email =  $password="";



    if($_SERVER["REQUEST_METHOD"] == "POST") {


            $fname = $_POST['fname'];

            $lname = $_POST['lname'];

            $email = $_POST['email'];

            $password = $_POST['pw'];

            $passconf = $_POST['confirm_password'];


            




                //validate data
               
                    if(empty($fname)){

                        $error="Please enter your first name.";

                    }


                   elseif(empty($lname)){

                        $error="Please enter your last name.";

                    }

                    elseif(empty($email)){

                        $error="Please enter your email address.";

                    }

                  

                    elseif (empty($password)) {

                        $error="Sorry.Please choose a password";

                    }

                    elseif(strlen($password) < 5){

                        $error="Password must be at least five characters";

                    }

                    elseif(!filter_var($email,FILTER_VALIDATE_EMAIL)){

                        $error="Sorry. The email address you entered is invalid";

                    }

                    elseif (empty($passconf)) {

                        $error="Please confirm your password";

                    }

                    elseif($passconf != $password){

                        $error="Passwords do not match";

                    }
                  






                        //upload data if no errors found

                        if($error == ''){


                            $password=md5($password);
                            



                            $addMemberErr = $custObj ->addCust($fname, $lname, $email, $password);

                            //redirect to login page ,pass a var telling user to login on login page



                            $_SESSION["signup_to_login"] = "You have been signed up. Please Login";



                            header("location:login.php");

                          


                        }
                       
    
    }


?>

<div class="container-fluid" id="signupPage">


    <div class="row">


        <div class="col-lg-3">
        </div>



        <div class="col-lg-6" id="signup">



            <?php

                            //check for validation errors

                            if($error!=='' || $addMemberErr != ''){


                    ?>
                                <div class="alert alert-danger">

                                    <?php


                                    //display validation errors here

                                    echo "<strong>".$error."<strong>";

                                    echo "<strong>".$addMemberErr."<strong>";


                                    ?>

                                </div>
                    <?php

                            }

                    ?>



            <h4 class="text-center">Signup</h4>


            <h5 class="text-center">Required fields are marked <span style="color: red; font-weight: bold;">*</span></h5>

            <form action="signup.php" method="post" enctype="multipart/form-data" id="signupForm">

        
                <div class="form-group form-group-sm col-lg-6">

                    <label for="fname">First Name:</label><span style="color: red; font-weight: bold;">*</span>

                    <input type="text" name="fname" id="fname" class="form-control" value="<?php echo $fname;?>"> 

                 </div>


                <div class="form-group form-group-sm col-lg-6">

                    <label for="lname">Last Name:</label><span style="color: red; font-weight: bold;">*</span>

                    <input type="text" name="lname" id="lname" class="form-control" value="<?php echo $lname;?>">

                </div>


                
                 
                <div class="form-group form-group-sm col-lg-8">

                    <label for="mail">Email Address:</label><span style="color: red; font-weight: bold;">*</span>

                    <input type="text" name="email" id="mail" class="form-control" value="<?php echo $email;?>">

                </div>


            

                <div class="form-group col-lg-12">

                    <label for="pw">Password</label><span style="color: red; font-weight: bold;">*</span>

                    <input type="password" id="pw" class="form-control" name="pw" >

                </div>


                <div class="form-group col-lg-12">

                    <label for="pconf">Confirm Password</label><span style="color: red; font-weight: bold;">*</span>

                    <input type = "password" id = "pconf" class = "form-control" name = "confirm_password" >

                </div>  


                <div class="form-group col-lg-12">

                    <p class="text-center"><button type="submit" class="btn btn-default signup-btn">Sign Up</button></p>

                </div>


            </form>


            <div class="text-center col-lg-12"><h4>Already have an account? <a href="login.php">Login here</a></h4></div>

        </div>

    </div>
</div>
