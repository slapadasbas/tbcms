<?php
	session_start();
	include 'class3.php';
	$class=new mydesign;
	$class->database_connect();

	date_default_timezone_set('Asia/Manila');
	$sessiondate=date('mdY');

	$limit=12;
	$page=$_SESSION['session_ppage'];
	$type=$_SESSION['session_ptype'];

	if(isset($_POST['pageno'])) {
		$page=str_replace("'", '', $_REQUEST['pageno']);
		$page=str_replace('"', '', $page);
		$page=str_replace("<", '', $page);
		$page=str_replace('>', '', $page);

		$_SESSION['session_ppage']=$page;
		$page=$_SESSION['session_ppage'];
		echo '<script>window.location.assign("https://tbcmerchantservices.com/shopping/");</script>';
	}

	if($page==""){$page=1;}

	if(isset($_POST['shoptype'])) {
		$type=str_replace("'", '', $_REQUEST['shoptype']);
		$type=str_replace('"', '', $type);
		$type=str_replace("<", '', $type);
		$type=str_replace('>', '', $type);
		$_SESSION['session_ppage']=1;
		$_SESSION['session_ptype']=$type;
		$type=$_SESSION['session_ptype'];
		echo '<script>window.location.assign("https://tbcmerchantservices.com/shopping/");</script>';
	}

	if($type==""){$type='%%';}

	if(!isset($_SESSION['session_tbcmerchant_ctr'.$sessiondate])){
		$class->doc_type();
		$class->html_start('');
			$class->head_start();
			echo '<link rel="shortcut icon" type="image/x-icon" href="https://tbcmerchantservices.com/images/tbslogo.png" />';
			$class->title_page('TBCMS');
				$class->script('https://tbcmerchantservices.com/js/jquery-3.1.1.js');
				$class->script('https://tbcmerchantservices.com/js/bootstrap.js');
				$class->link('https://tbcmerchantservices.com/css/bootstrap.css');
				$class->script('https://tbcmerchantservices.com/js/jquery1.3.js');
			$class->head_end();

			$class->body_start('');
				$class->page_home_header_start();
					$class->page_shopping_header_content1();
				$class->page_home_header_end();
				$class->page_shopping_navbar_content1();
?>
                               <div class="container" align="center">
                                       <h4><b>FOR YOUR INFORMATION ( FYI )</b></h4>
                                       <h4>TBCMS (TBC Merchant Services) is an online advertising, online store and an online exchange for all verified and paid TBC holders. Thus, the products in the shopping center are not owned by TBCMS. They are owned by the verified and qualified TBCMS Merchants. Contact them if you have any question pertaining to their products and services.</h4>
                               </div>
<?php

				$query="select * from xtbl_product where Type like '$type'";
				$rs=mysql_query($query);
				$rows=mysql_num_rows($rs);
				$p=ceil($rows/$limit);
				$start=($page-1)*$limit;
				$query="select * from xtbl_product where Type like '$type' order by Ctr DESC LIMIT ".$limit."  OFFSET ".$start."";
				$rs=mysql_query($query);
				if($type=="%%"){echo '<br><div class="container"><h2>All Categories</h4></div>';}
				else{echo '<br><div class="container"><h2>All '.$type.'</h4></div>';}
				
				echo '<br><div class="container" align="center">';
				while($row=mysql_fetch_assoc($rs)) {
			?>		<div class="col-md-3" style="height: 450px;padding-bottom: 10px; border-right: 1px solid #f2f2f2;border-bottom: 1px solid #f2f2f2">
						<div style="height: 35px;">
							<h4><b><?php echo $row['Product_Name'];?></b></h4>
						</div>
						<div style="height: 330px;">
							<?php
								if(file_exists('products/'.$row['Image'])) {
				            ?>
				            	<img width="250" <?php echo 'src="https://tbcmerchantservices.com/products/'.$row['Image'].'"';?> >
				            <?php
				            	}
				            	else{
				            ?>
				            	<img width="250" <?php echo 'src="https://tbcmerchantservices.com/products/0000.jpg"';?> >
				            <?php
				            	}
							?>
							
						</div>
						
						<div style="height: 20px;"><h4 style="color: red;"><b><?php echo '&#8369;'.number_format($row['Product_Price'],2);?></b></h4></div>
						
						<div style="height: 20px;">
							<a <?php echo 'href="https://tbcmerchantservices.com/item/?product='.$row['Ctr'].'"';?> class="btn btn-info btn-block"
								style="font-size: 20px; border-radius: 0px">VIEW INFO</a>
						</div>
						
					</div>
			<?php
				}
				echo '</div><br><br>';
				echo '<div class="container" align="center"><br><br>';
				if($page>1) {
			?>
				<form method="POST" hidden>
					<input name="pageno" <?php echo 'value="'.($page-1).'"';?> />
					<input id="prev_page" type="submit" />
				</form>
				<a href="javascript:void(0)" onclick="$('#prev_page').click();" class="btn btn-danger "
					style="font-size: 20px; border-radius: 0px">
					<span class="glyphicon glyphicon-chevron-left"></span>
					PREVIOUS PAGE</a>
			<?php
				}
				if($page<$p) {
			?>
				<form method="POST" hidden>
					<input name="pageno" <?php echo 'value="'.($page+1).'"';?> />
					<input id="next_page" type="submit" />
				</form>
				<a href="javascript:void(0)" onclick="$('#next_page').click();" class="btn btn-danger "
					style="font-size: 20px; border-radius: 0px">NEXT PAGE
					<span class="glyphicon glyphicon-chevron-right"></span></a>
			<?php
				}
				echo '</div><br><br><br>';
			$class->page_welcome_header_content_start_footer();
                        $class->chatscript();
			$class->body_end();	
		$class->html_end();


	}
	else{
		$ctr=$_SESSION['session_tbcmerchant_ctr'.$sessiondate];

		$query="select * from xtbl_account_info WHERE Main_Ctr='$ctr'";
		$rs=mysql_query($query);
		$row=mysql_fetch_assoc($rs);
		$email_status=$row['Email_Status'];
		$account_type=$row['Account_Type'];
		$account_status=$row['Account_Status'];
		$card_status=$row['Card_Status'];
		$username=$row['Username'];
		$account_addressyou=$row['Crypt_Id'];
		$activation_amount=0;

		$query="select * from xtbl_main_info WHERE Ctr='$ctr'";
		$rs=mysql_query($query);
		$row=mysql_fetch_assoc($rs);
		$current_email=$row['Email'];

		if($email_status=='INACTIVE' || $account_status=='INACTIVE' || $card_status=='INACTIVE'){
			header("location: https://tbcmerchantservices.com/home/");
		}
		else {
			$class->doc_type();
			$class->html_start('');
				$class->head_start();
					echo '<link rel="shortcut icon" type="image/x-icon" href="https://tbcmerchantservices.com/images/tbslogo.png" />';
					$class->title_page('TBCMS-'.$username);
					$class->script('https://tbcmerchantservices.com/js/jquery-3.1.1.js');
					$class->script('https://tbcmerchantservices.com/js/bootstrap.js');
					$class->link('https://tbcmerchantservices.com/css/bootstrap.css');
					$class->script('https://tbcmerchantservices.com/js/jquery1.3.js');
				$class->head_end();
				$class->body_start('');
				if($account_type=='MERCHANT') {
					$class->page_home_header_start();
						$class->page_home2_header_content();
					$class->page_home_header_end();
				}
				else { //if buyer
					$class->page_home_header_start();
						$class->page_home3_header_content();
					$class->page_home_header_end();
				}
				echo '<div class="container"><h3>Welcome back,  <b>'.$current_email.'</b></h3></div>';
				$query="select * from xtbl_product_request where From_Ctr='$ctr' order by Ctr DESC LIMIT 20";
				$rs=mysql_query($query);
				?>
					<div class="container" >
						<h2>Latest Orders</h2>
						<table class="table table-bordered">
							<tr>
								<td>Date</td>
								<td>Merchant/Product</td>
								<td>Quantity/Amount</td>
								<td>Transact ID</td>
								<td>Status</td>
							</tr>
					<?php
						while($row=mysql_fetch_assoc($rs)) {
					?>
							<tr>
								<td><?php echo '<b>'.$row['Datetime'].'</b>';?></td>
								<td>
								<?php 

									$mer_query="select * from xtbl_main_info Where Ctr='".$row['To_Ctr']."'";
									$mer_rd=mysql_query($mer_query);
									$mer_row=mysql_fetch_assoc($mer_rd);
									echo '<b>'.$mer_row['Business_Name'].'</b><br>';

									$prod_query="select * from xtbl_product Where Ctr='".$row['Product_Ctr']."'";
									$prod_rd=mysql_query($prod_query);
									$prod_row=mysql_fetch_assoc($prod_rd);
									echo $prod_row['Product_Name'];
								?>
								</td>
								<td>
								<?php 
									echo '<b>Qnty: '.$row['Quantity'].'</b><br>';
									echo 'PHP '.number_format($row['Amount'],2);
								?>
								</td>

								<td><?php echo '<b>'.$row['Transact_Id'].'</b>';?></td>
								<td><?php echo '<b>'.$row['Status'].'</b>';?></td>
							</tr>	
					<?php
						}
					?>
						</table>
					</div>
				<?php

					$class->page_shopping_navbar_content1();
?>
                               <div class="container" align="center">
                                       <h4><b>FOR YOUR INFORMATION ( FYI )</b></h4>
                                       <h4>TBCMS (TBC Merchant Services) is an online advertising, online store and an online exchange for all verified and paid TBC holders. Thus, the products in the shopping center are not owned by TBCMS. They are owned by the verified and qualified TBCMS Merchants. Contact them if you have any question pertaining to their products and services.</h4>
                               </div>
<?php

					$query="select * from xtbl_product where Type like '$type'";
					$rs=mysql_query($query);
					$rows=mysql_num_rows($rs);
					$p=ceil($rows/$limit);
					$start=($page-1)*$limit;
					$query="select * from xtbl_product where Type like '$type' order by Ctr DESC LIMIT ".$limit."  OFFSET ".$start."";
					$rs=mysql_query($query);
					if($type=="%%"){echo '<br><div class="container"><h2>All Categories</h2></div>';}
					else{echo '<br><div class="container"><h2>All '.$type.'</h4></div>';}
					
					echo '<br><div class="container" align="center">';
					while($row=mysql_fetch_assoc($rs)) {
				?>		<div class="col-md-3" style="height: 450px;padding-bottom: 10px; border-right: 1px solid #f2f2f2;border-bottom: 1px solid #f2f2f2">
							<div style="height: 35px;">
								<h4><b><?php echo $row['Product_Name'];?></b></h4>
							</div>
							<div style="height: 330px;">
								<?php
									if(file_exists('products/'.$row['Image'])) {
					            ?>
					            	<img width="250" <?php echo 'src="https://tbcmerchantservices.com/products/'.$row['Image'].'"';?> >
					            <?php
					            	}
					            	else{
					            ?>
					            	<img width="250" <?php echo 'src="https://tbcmerchantservices.com/products/0000.jpg"';?> >
					            <?php
					            	}
								?>
							</div>
							
							<div style="height: 20px;"><h4 style="color: red;"><b><?php echo '&#8369;'.number_format($row['Product_Price'],2);?></b></h4></div>
							
							<div style="height: 20px;">
								<a <?php echo 'href="https://tbcmerchantservices.com/item/?product='.$row['Ctr'].'"';?> class="btn btn-info btn-block"
									style="font-size: 20px; border-radius: 0px">VIEW INFO</a>
							</div>
							
						</div>
				<?php
					}
					echo '</div><br><br>';
					echo '<div class="container" align="center"><br><br>';
					if($page>1) {
				?>
					<form method="POST" hidden>
						<input name="pageno" <?php echo 'value="'.($page-1).'"';?> />
						<input id="prev_page" type="submit" />
					</form>
					<a href="javascript:void(0)" onclick="$('#prev_page').click();" class="btn btn-danger "
						style="font-size: 20px; border-radius: 0px">
						<span class="glyphicon glyphicon-chevron-left"></span>
						PREVIOUS PAGE</a>
				<?php
					}
					if($page<$p) {
				?>
					<form method="POST" hidden>
						<input name="pageno" <?php echo 'value="'.($page+1).'"';?> />
						<input id="next_page" type="submit" />
					</form>
					<a href="javascript:void(0)" onclick="$('#next_page').click();" class="btn btn-danger "
						style="font-size: 20px; border-radius: 0px">NEXT PAGE
						<span class="glyphicon glyphicon-chevron-right"></span></a>
				<?php
					}
					echo '</div><br><br><br>';

					$class->page_welcome_header_content_start_footer();
                                        $class->chatscript();
				$class->body_end();	
			$class->html_end();
		}
	}

?>