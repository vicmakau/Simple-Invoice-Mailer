<?php

session_start();




?>
<!DOCTYPE html>
<html>

<head>


    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Make purchase</title>

    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    
    <link rel="stylesheet" href="style.css">
    
    <link href='https://fonts.googleapis.com/css?family=PT Sans' rel='stylesheet'>

    <link href='https://fonts.googleapis.com/css?family=Courgette' rel='stylesheet'>

    <link href='https://fonts.googleapis.com/css?family=Dosis' rel='stylesheet'>

    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>


    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>



    <script>

    function makeOrder()

        {

            var id;
            id = 1;

            if (window.XMLHttpRequest) {

                    // code for IE7+, Firefox, Chrome, Opera, Safari

                        xmlhttp = new XMLHttpRequest();


                    } else {

                        // code for IE6, IE5
                        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");


                    }

                    xmlhttp.onreadystatechange = function() {


                        if (this.readyState == 4 && this.status == 200) {





                        }


                    };

                    

                    xmlhttp.open("GET","server.php?id="+id);


                    xmlhttp.send();

                



            }
        

</script>



</head>

<body>

    <div class="page-header">


        <div class="navbar navbar-default navbar-fixed-top">


            <div class="container-fluid">


                <div class="navbar-header">


                    <a class="navbar-brand" href="index.php">Company Name</a>


                </div>



                <ul class="nav navbar-nav navbar-right">



                    <li><a href="login.php">Login</a></li>

                    <li><a href="signup.php">Signup</a></li>

                    <li><a href="logout.php">Logout</a></li>



                    
                </ul>


               
            </div>


        </div>


    </div>

