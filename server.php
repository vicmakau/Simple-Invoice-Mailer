<?php

//connects to the database
$databaseAccess = databaseAccess::initialise('localhost', 'root', '','swiftcoins');


//instantiate customer class
$custObj = new customer();


//instantiate product class
$productObj = new product();


//instantiate invoice class
$invoiceObj = new invoice();

//instantiate curder class
$orderObj = new order();

//instantiate DateTime class
$date = new DateTime();

//php mailer

require 'vendor/autoload.php';

require 'vendor/phpmailer/phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;

use PHPMailer\PHPMailer\Exception;




//receive AJAX data from index.php
if(isset($_GET['name']) && isset($_GET['email']))

{

	//stores GET values in variables
	$custId = $_GET['id'];

	$invoiceto = $_GET['name'];

	$email = $_GET['email'];

	$product = $_GET['product'];

	$cost = $_GET['cost'];

	//make this dynamic in index.php
	$PayMethod = "M-Pesa Pay";

	//today
	$dateSend = date("l F jS, Y - g:ia", time());

    $dateDue  = date("l F jS, Y - g:ia", time() + 5 *24 * 60 * 60);


	//call fetch_product() to get product details
	$productObj->fetch_product($product);

	//get productId
	$productId = $productObj ->get_pr_id($product);

	
	//make an order
	// $orderObj->create_order($productId, $custId);


	//generate unique invoice Number
	$invoiceNo = $invoiceObj ->generate_number();

	require('fpdf.php');


	$pdf = new FPDF(); 


	$pdf->AddPage();


	$pdf->SetFont('Arial','B',10);


	$pdf->SetFillColor(1,255,255);

	$pdf->Cell(60,10,'Invoice for:'.$invoiceto,0,1,'L',true,'https://www.plus2net.com');

	$pdf->Cell(60,10,'Invoice Number: '.$invoiceNo,0,1,'L');

	$pdf->Cell(60,10,'Date:'.$dateSend,0,1,'L');

	$pdf->Cell(60,10,'Order:'.$product,0,1,'L');

	$pdf->Cell(60,10,'Amount Due:'.$cost,0,1,'L');

	$pdf->Cell(60,10,'Due date:'.$dateDue,0,1,'L');

	$pdf->Output($invoiceto.'_'.$invoiceNo.'.pdf','F');


	//create the invoice
	$invoiceObj ->create_invoice($invoiceto, $dateSend, $PayMethod, $invoiceNo, $cost, $dateDue);

	//send the invoice
	$send = $invoiceObj ->send_invoice($email);


	//feedback

	echo "
    
            <div class = 'col-lg-2'>
            
            </div>
            
            
            <div class = 'alert alert-success'>
            
            
                An invoice has been sent to ".$email."
                
                
            </div>
            
        ";


        //end AJAX call

}




class databaseAccess{



    private static $instance = false;


    public $connection;



    private function __construct($dbhost, $dbuser, $dbpass, $dbname){

        $this->connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

    }



    public static function initialise($dbhost, $dbuser, $dbpass, $dbname){

        if( !self::$instance ) self::$instance = new databaseAccess( $dbhost, $dbuser, $dbpass, $dbname );

        return self::$instance;

    }

}




class customer{

	public $fname;

	public $lname;

	public $id;

	public $residence;


	//get customer details from database;
	function fetch_customer($email)

		{


			GLOBAL $databaseAccess;
			

			$fetchCust = "SELECT * FROM customers_tbl WHERE customer_email = '$email'";

			$runFetchCust = $databaseAccess ->connection ->query($fetchCust);

			if(!$runFetchCust)

				die($databaseAccess->connection ->error);

			else

				{

					$resultArray = $runFetchCust ->fetch_array(MYSQLI_ASSOC);



					$this->fname = $resultArray['customer_fname'];

					$this->lname = $resultArray['customer_lname'];

					$this->id = $resultArray['customer_id'];

					$this->residence = $resultArray['customer_residence'];


				}


		}


	function addCust($fname, $lname, $email, $password){

		GLOBAL $databaseAccess;




		$insertCust = "INSERT INTO 

                    customers_tbl(customer_fname, customer_lname, customer_email,password)

                    VALUES
                            
                	('$fname', '$lname', '$email', '$password')";


        $run = $databaseAccess ->connection ->query($insertCust);



        if(!$run) {

        	die($databaseAccess ->connection->error);

        }

        else

        	return true;

	}




	function custLogin($email, $password)

		{

				GLOBAL $databaseAccess;


	        $err = "";


	        $login="SELECT customer_email,password FROM customers_tbl WHERE customer_email='$email' AND password='$password'";


	        $run_login = $databaseAccess ->connection ->query($login);



	        $check_login = $run_login ->num_rows;



	            if($check_login == 0){


	                    $err = "Invalid credentials.Try again";


	            }


	            elseif($check_login == 1){


	                $_SESSION["customer_email"] = $email;


	                header("location:index.php");
	                
	                        
	            }

	            elseif($check_login > 1 ){

	                $err = "Sorry. There is a problem with your account. Please contact support.";

	            }

	        

	             return $err;

	    }






	


	function get_name()

		{


			return $this->fname." ".$this->lname;
		}



	function get_email()

		{

			return $this->email;
		}


	function get_residence()

		{
			return $this->residence;
		}

	function get_id()

		{
			return $this->id;
		}


}


class invoice{

	public $subject;

	public $content;

	public $toMail;

	public $toName;

	public $invoice_number;



		function create_invoice($toName, $date, $method, $number, $bill, $due)

		{

			$number = $this->invoice_number;

			$this->toName = $toName;

  

		    $this->content = "Hello ".$this->toName.", find your invoice attached";

      

			$this ->subject = "Invoice for ".$this->toName;




		}


	function send_invoice($email)

		{

			$this->toMail = $email;


				try {
						    //Server settings

							$mailerObj = new PHPMailer(true);


							if (!isset($mailerObj)) 
							    $mailerObj = new stdClass();


						    $mailerObj->SMTPDebug = 0;                                       // Enable verbose debug output
						    $mailerObj->isSMTP();                                            // Set mailer to use SMTP
						    $mailerObj->Host       = 'smtp.gmail.com';  // Specify main and backup SMTP servers
						    $mailerObj->SMTPAuth   = true;                                   // Enable SMTP authentication
						    $mailerObj->Username   = 'maildev458@gmail.com';                     // SMTP username
						    $mailerObj->Password   = 'alxypmfnrhsclmme';                               // SMTP password
						    $mailerObj->SMTPSecure = 'ssl';                                  // Enable TLS encryption, `ssl` also accepted
						    $mailerObj->Port       = 465;                                    // TCP port to connect to

						    //Recipients
						    $mailerObj->setFrom('maildev458@gmail.com');
						    $mailerObj->addAddress($this->toMail);     // Add a recipient
						    /*$mailerObj->addAddress('ellen@example.com');               // Name is optional
						    $mailerObj->addReplyTo('info@example.com', 'Information');
						    $mailerObj->addCC('cc@example.com');
						    $mailerObj->addBCC('bcc@example.com');*/

						    // Attachments
						    $mailerObj->addAttachment($this->toName.'_'.$this->invoice_number.'.pdf');         // Add attachments
						    /*$mailerObj->addAttachment('/tmp/image.jpg', 'new.jpg');*/   // Optional name

						    // Content
						    $mailerObj->isHTML(true);                                  // Set email format to HTML
						    $mailerObj->Subject = $this->subject;
						    $mailerObj->Body    = $this->content;
						    $mailerObj->AltBody = 'Sorry.Html email is not enabled in your account';

						    $mailerObj->send();
						    
						    echo 'success';
						    
						} catch (Exception $e) {
						    
						    echo "Message could not be sent. Mailer Error: {$mailerObj->ErrorInfo}";
						    
						}

		}


	function generate_number()

		{

			$this->invoice_number = rand(1000,100000);

			return $this->invoice_number;
		}


	function notify_send()

		{
			$notification = "An invoice has been sent to ".$this->toMail;

			return $notification;
		}

	
}



class order{

	function create_order($custId, $prdctId)

		{

			GLOBAL $databaseAccess;


			$create = "INSERT INTO orders_tbl(customer_id,product_id, order_status) VALUES ('$custId', '$prdctId', 'unpaid')";

			$execute = $databaseAccess ->connection ->query($create);

			if(!$execute)

					{
						die($databaseAccess ->connection ->error);
					}

			else

				return true;

		}



}


class product{

	public $product_name;

	public $product_price;

	public $product_id;


	//get product details from database
	function fetch_product($name)

		{

			GLOBAL $databaseAccess;
			

			$fetchPrdct = "SELECT * FROM products_tbl WHERE product_name LIKE '%$name%'";

			$runFetchPrdct = $databaseAccess ->connection ->query($fetchPrdct);

			if(!$runFetchPrdct)

				die($databaseAccess->connection ->error);

			else

				{

					$resultArray = $runFetchPrdct ->fetch_array(MYSQLI_ASSOC);



					$this->product_name = $resultArray['product_name'];

					$this->product_price = $resultArray['product_price'];

					$this->product_id = $resultArray['product_id'];



				}


		}



	function get_pr_name()


		{
			return $this->product_name;
		}


	function get_pr_price()


		{
			return $this->product_price;
		}


	function get_pr_id()


		{
			return $this->product_id;
		}
}

	