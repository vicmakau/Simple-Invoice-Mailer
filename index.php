<?php


require_once("header.php");


require_once("server.php");




?>

<div class="container">

	<div class="row">

		<div class="col-lg-12" id="content">

		<div class="col-lg-12" id="orderSummary">

			<div class="col-lg-2">
			</div>

			<div class="col-lg-8">

				<h5 class="text-center">Order Summary</h5>

				<table class="table table-stripped">

					<tr>

						<th>SN</th>

						<th>Name of Product</th>

						<th>Quantity</th>

						<th>Unit Cost</th>

						<th>Total Cost</th>

					</tr>

					<?php

					//call fetch_product of product class in server.php 
					$product = $productObj ->fetch_product("premium");

					//store product details in vars
					$name = $productObj->product_name;

					$price = $productObj->product_price;

					?>

					<tr>

						<!-- display product details -->

						<td>1.</td>

						<td id="prName"><?php echo $name;?></td>

						<td id="prQuantity">1</td>

						<td id="prUnitPrice">Kshs. <?php echo $price;?></td>

						<td id="prTotPrice">Kshs. <?php echo $price;?></td>


					</tr>

					<tr>

						<td>TOTAL:</td>

						<td colspan="8" id="orderCost">Kshs.<?php echo $price;?></td>

					</tr>

				</table>


				<div class="col-lg-12" id="cust">


					<div class="col-lg-10" id="custDetails">

						<h4>Customer Details</h4>

							<?php

								if(isset($_SESSION["customer_email"]))  //is user logged in??

									{


										$custEmail = $_SESSION["customer_email"];

										$custObj ->fetch_customer($custEmail);

										$custName = $custObj ->get_name();

										$custId = $custObj ->get_id();


										echo '



											<p>Name: <span id="name">'.$custName.'</span></p>

											<p>Email Address: <span id="email">'. $custEmail.'</span></p>

											<p style="visibility: hidden;" id="id">'.$custId.'</p>

											';

									}


									else

									{
										echo 'please <a href = "login.php">Log in</a> to continue.';
									}

							?>

					</div>


				

					<div class="col-lg-10" id="payment">

						<h4 class="text-centre">Choose a payment Method</h4>

						<!-- not dynamic. Needs to accept user choice -->

						<label class = "radio-inline">

							<input type="radio" name="" checked="checked">M-Pesa Pay

						</label>

						<label class = "radio-inline">

							<input type="radio" name="">CBA Bank

						</label>

					</div>

				</div>


				<?php 

					if(isset($_SESSION["customer_email"]))

							{

								echo '

									<button class="btn btn-primary pull-right" type="submit" onclick ="makeOrder()" >Confirm and Proceed</button>

								';

							}

						else

							{

								echo '

									<a type = "button" class="btn btn-danger pull-right" type="submit" href= "login.php" >Login to Proceed</a>

								';
							}

					?>
				

			</div>

		</div>

	</div>

</div>

</div>



<script>

	//this function is fired when the user clicks the 'confirm and proceed' button

	//function stores the details of the products ordered in vars

	//opens an AJAX xmlhttp call and sends the details to server

	function makeOrder()

		{

			var id =$("#id").text();

			var name = $("#name").text();

			var email = $("#email").text();

			var product = $("#prName").text();

			var cost = $("#orderCost").text();
			


			if (window.XMLHttpRequest) {

	                // code for IE7+, Firefox, Chrome, Opera, Safari

	                    xmlhttp = new XMLHttpRequest();


	                } else {

	                    // code for IE6, IE5
	                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");


	                }

	                xmlhttp.onreadystatechange = function() {


	                    if (this.readyState == 4 && this.status == 200) {

	                    	//once receive is received from the server, 

	                    	//change the markup of #content div

	                    	//to the received text

	                    	$("#content").html(this.responseText);



	                    }


	                };

	                
	                //send the request to server.php

	                xmlhttp.open("GET","server.php?id="+id+"&name="+name+"&email="+email+"&product="+product+"&cost="+cost);


	                xmlhttp.send();

				



			}
		

</script>




<footer>

</footer>










