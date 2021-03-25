<?php
/************************  LOAN APPLICATION FORM***************************/
function show_form_quick_form( $atts )
{
	?>
	<script src="<?php echo  P2P_PLUGIN_URL;?>/js/jquery-1.9.1.min.js"></script>
  <script src="<?php echo  P2P_PLUGIN_URL;?>/js/jquery.validate.js"></script>
 	<script src="<?php echo  P2P_PLUGIN_URL;?>/js/jquery.steps.js" type="text/javascript"></script>
	<?php
 if ( is_user_logged_in() )
 {
	  ?>
		<div class="content">
			<script type="text/javascript">
			$(function(){
 			var year = new Date().getFullYear();
			var myyear = year-17;
 			for (var i = (year-100); i < (myyear); i++){
					jQuery("#SYear").append("<option value='"+i+"'>"+i+"</option>");
 			}
			for (var i = 1; i < 13; i++){
				if(i<10){
					$("#SMonth").append("<option value='0"+i+"'>0"+i+"</option>");
				 }else{
					 $("#SMonth").append("<option value='"+i+"'>"+i+"</option>");
				 }
			}

		});
		function YYYYDD(valuesy)
		{
				$("#_SYear").val($("#SYear").val());
				if( $("#_SMonth").val()!="" && $("#_SDay").val()!="")
				{
				var birthday = $("#SYear").val()+"-"+$("#SMonth").val()+"-"+$("#SDay").val();
				$('#birthday').val(birthday);
				calcBirthday2(birthday);
			  }

		}
		function MMDD(valuesm)
		{
			var  feb=28;
		   var MonHead = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
			 var n = MonHead[valuesm - 1];
 			 var year = $("#SYear").val();
 			 if(  year%4 == 0 && year%100 == 0)
			 {
				 feb=29;
			 }
 			 if (valuesm==2 && feb==29){
 		 		  n++;
		 	}
     writeDay(n);
 		}
		function writeDay(n)
		{
				jQuery("#SDay").empty();
			for (var i = 1; i <= n; i++){
				if(i<10){
					$("#SDay").append("<option value='0"+i+"'>0"+i+"</option>");
				 }
				 else{
					jQuery("#SDay").append("<option value='"+i+"'>"+i+"</option>");
				}
 			}
		}
		function changeBirthday(values){
  		$("#_SYear").val($("#SYear").val());
	  	$("#_SMonth").val($("#SMonth").val());
		  $("#_SDay").val(values);
			$('#birthday').val(birthday);
			var birthday = $("#SYear").val()+"-"+$("#SMonth").val()+"-"+$("#SDay").val();
				$('#birthday').val(birthday);
		  var age = calcBirthday2(birthday);

			}//function
			function calcBirthday2(birthday)
			{
				var today = new Date();
		    var birthDate = new Date(birthday);
		     var age = today.getFullYear() - birthDate.getFullYear();
		    var m = today.getMonth() - birthDate.getMonth();
		    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
				    age--;
		    }
						$('#birthday').val(birthday);
						$('#age').val(age);
 				if(age<18)
					{
							$("#birthday_error").html("您還未滿18周歲");
						alert('minimum 18 years');
					}

			}
			</script>
				<script>
				$(function ()
				{
					var form = $("#example-form");
				 	form.validate({
					errorPlacement: function errorPlacement(error, element) {
					 error.insertAfter(element.parent()); },
					rules: {
					loanamount: { required:function(element) { alert($("#loanamount").val());return false;} },
					loantype:{required: true,} ,
					deadline :{	required: true,},
					loanamount: {required: true,},
					chinese_name: { required:true,},
					english_surname: { required:true,},
					english_name: { required:true,},
        	city: {required: true,},
					address: {	required: true,},
					id_num1: {	   numbervalidate: true },
				},
				  messages: {
						loantype:{ required: 'Enter Loan Type'}
					}
    });
		$.validator.addMethod('numbervalidate', function (value, element, param) {
    //Your Validation Here
     return false; // return bool here if valid or not.
}, 'Your error message!');

						$("#wizard").steps({
								headerTag: "h2",
								bodyTag: "section",
								transitionEffect: "slideLeft",
		onStepChanging: function (event, currentIndex, newIndex){
			if(newIndex == 1){

			//	alert($("#loanamount").val());
			//	return false;
		   form.validate().settings.ignore = ":disabled,:hidden";
			 alert(form.valid());
			 return form.valid();
			}else if(newIndex == 2){
				form.validate().settings.ignore = ":disabled,:hidden";
				return form.valid();
			}
			return true;
										},
		onStepChanged: function (event, currentIndex, priorIndex) {
			console.log('cur-'+currentIndex);
			if(currentIndex == 1){
				$('.next-btn a').html('Next');
			}
			if(currentIndex == 0){
				$('.next-btn a').html('Proceed to provide information');
			}
		},
								onFinishing: function (event, currentIndex)
									 {
											var paymenttype = $(".payment_type:checked").val();
				//console.log(paymenttype);
				if(paymenttype == 'CC'){
					form.validate().settings.ignore = ":disabled,:hidden";
					return form.valid();
				}else{
					return true;
				}
									 },
									 onFinished: function (event, currentIndex){
				          //console.log('finished');
											 $("#example-form").submit();
									 }

						});
				});

				</script>
        <?php
				if($_SERVER['REQUEST_METHOD']=='POST')
				{
				//	print_R($_POST);
				global $wpdb,$user;
				global $current_user;
				$current_user = get_currentuserinfo();
				$user_id = $current_user->ID ;
					$loantype = $_POST['loantype'];
					$loanamount = $_POST['loanamount'];
					$deadline= $_POST['deadline'];
					$chinese_name = $_POST['chinese_name'];
					$english_surname = $_POST['english_surname'];
					$english_name = $_POST['english_name'];
					$sex= $_POST['sex'];
					$birthday= $_POST['birthday'];
					$age =$_POST['age'];
					$email = $_POST['email'];
					$number = $_POST['number'];
					$address = $_POST['address'];
					$city =$_POST['city'];
					$postcode =$_POST['postcode'];
					$wpdb->query("INSERT INTO `".$wpdb->prefix."p2p_application_record` ( `loan_amount`, `repayment_period`, `loan_type`, `user_id`, `application_date`, `additional`, `extra`, `application_status`, `status`) VALUES (  '$loanamount', '$deadline', '$loantype', '$user_id', NOW(), '', '', 'send', '1')");
					$app_id = mysql_insert_id();
          $wpdb->query("INSERT INTO `".$wpdb->prefix."p2p_user_details` (`user_id`, `loan_application_no`, `chinessname`, `englishname`, `english_surname`, `dob`, `sex`, `age`, `email`, `phonenumber`, `address`, `city`, `company_name`, `status`) VALUES ('$user_id', '$app_id', '$chinese_name', '$english_surname', '$english_name', '$birthday', '$sex', '$age', '$email', '$number', '$address', '$city', '$company', '1')");
          //send mail to

				}
				?>
				<form id="example-form" action="#" method="POST">
	        <div id="wizard" class="donate-form">
	          <h2><?php echo P2P_loan_application;?></h2>
	          <section>
	            <div class="col-md-8  donate-step-1">
								<div class="step-1-1"><?php echo P2P_Please_choose_your_loan_information;?></div>
                  <div class="leftlable"><label> * <?php echo P2P_loan_type;?>: </label></div>
									<div class="rightlable">
                    <select id="name" name="loantype" class="form-control required">
											<option value=""><?php echo P2P_loan_type;?></option>
											<option value="Cash Personal Loans">現金私人貸款</option>	<option value="SD card number / Balance Transfer">清卡數 / 結餘轉戶</option>
											<option value="Owners of private loans">業主私人貸款</option>
											<option value="Mortgage / Building Mortgage">按揭 / 樓宇抵押貸款</option>
										</select>
                  </div>
									<div class="clear"></div>
								  <div class="leftlable"><label> * <?php echo P2P_loan_amount;?>: </label></div>
										<div class="rightlable"><input id="loanamount" name="loanamount" type="text" class="form-control required"  placeholder="<?php echo P2P_loan_amount;?>">
                   </div>	<div class="clear"></div>
								<div class="leftlable">	<label> *  <?php echo P2P_repayment_period;?>:</label></div>
								<div class="rightlable"> <select class="select required" name="deadline" id="deadline">
													<option value="-1"> Please select</option>
													<option value="6">	6 months</option>
													<option value="12">12 months (1 year)	</option>
													<option value="18">18 months (1.5 years)</option>
													<option value="24">24 months (2 years)	</option>
													<option value="36">	36 months (3 years)</option>
													<option value="48">48 months (4 years)</option>
													<option value="60">	60 months (5 years)	</option>
													<option value="72">72 months (6 years)</option>
													<option value="84">	84 months (7 years)	</option>
													<option value="96">96 months (8 years)</option>
													</select>
                   </div>	<div class="clear"></div>
										<div class="step-1-2"><?php echo P2P_fill_personal_infomation;?></div>
 										<div class="leftlable">	<label> * <?php echo P2P_cinese_name;?>: </label>	</div>
										<div class="rightlable">	<input type="text" class="text" name="chinese_name" class="form-control required" id="chinese_name" value="">
										</div>	<div class="clear"></div>
										<div class="leftlable">		  <label> * <?php echo P2P_english_surname;?>: </label></div>
										<div class="rightlable"><input type="text" class="text" name="english_surname" class="form-control required" id="english_surname" value="">
										</div>	<div class="clear"></div>
										<div class="leftlable">	<label> * <?php echo P2P_english_name;?>: </label></div>
										<div class="rightlable"><input type="text" class="text" name="english_name" class="form-control required" id="english_name" value="">
										</div>	<div class="clear"></div>
										<div class="leftlable">		<label> * <?php echo P2P_sex;?>: </label></div>
										<div class="rightlable"><input type="radio" id="sex-man" value="1" name="sex" checked="checked" class="required"> <span>male</span>
 										<br/><input type="radio" id="sex-woman" value="2" name="sex">	 <span>Female</span> <br/>
										</div>	<div class="clear"></div>
                   	<div class="leftlable">		<label> * <?php echo P2P_dob;?>: </label></div>
										<div class="rightlable">
										<select name="SYear" id="SYear" onchange="YYYYDD(this.value)" style="border: 1px solid #ddd;border-radius: 5px;font-size: 12px;height: 26px;padding-left: 8px;width: 70px;">
														<option value=""><font><font>year</font></font></option></select>
 														<select name="SMonth" id="SMonth" onchange="MMDD(this.value)" style="border: 1px solid #ddd;border-radius: 5px;font-size: 12px;height: 26px;padding-left: 8px;width: 70px;">
														　　 <option value=""><font><font>month</font></font></option></select>
 														<select name="SDay" id="SDay" onchange="changeBirthday(this.value);" style="border: 1px solid #ddd;border-radius: 5px;font-size: 12px;height: 26px;padding-left: 8px;width: 70px;"><option value="">day</option></select>
														<input type="hidden" name="y" value="" id="_SYear">
														<input type="hidden" name="m" value="" id="_SMonth">
														<input type="hidden" name="d" value="" id="_SDay">
														<input type="hidden" name="birthday" id="birthday" value="">
														<input type="hidden" name="birthday1" id="birthday1" value="">
														<input type="hidden" name="age" id="age">
														<span class="notice" id="birthday_error"></span>
													</div>	<div class="clear"></div>
													<div class="leftlable">		<label> * <?php echo P2P_ip_number;?>: </label></div>
														<div class="rightlable"><input type="text" maxlength="7" name="id_num1" size="12" id="id_num1" value="C214583">
																	(&nbsp;<input type="text" name="id_num2" id="id_num2" maxlength="1" size="3" value="5">
																	&nbsp;)
																		<input type="hidden" class="text" name="id_num11" id="id_num11" value="">
																		<input type="hidden" class="text" name="id_num12" id="id_num12" value="">

														</div>	<div class="clear"></div>

	            </div>
	          </section>
	          <h2><?php echo P2P_loan_application;?></h2>
	          <section>
	              <div class="col-md-8  donate-step-2">
	               	<div class="form-group">
	                  <input id="name" name="fname" type="text" class="form-control required"  placeholder="First Name">
	                  <span class="error_label"></span>
	                </div>
	                <div class="form-group">
	                  <input id="surname" name="lname" type="text" class="form-control required" placeholder="Last Name">
	                  <span class="error_label"></span>
	                </div>
	                <div class="form-group">
	                  <input id="email" name="email" type="text" class="form-control required email"  placeholder="Email">
	                  <span class="error_label"></span>
	                </div>
	                <div class="form-group">
	                  <input id="phone" name="number" type="text" class="form-control required"  placeholder="Phone">
	                  <span class="error_label"></span>
	                </div>
	                <div class="form-group">
	                  <input id="address" name="address" type="text" class="form-control required"  placeholder="Address">
	                  <span class="error_label"></span>
	                </div>
	                <div class="form-group">
	                  <input id="city" name="city" type="text" class="form-control required"  placeholder="City">
	                  <span class="error_label"></span>
	                </div>
	                <div class="form-group">
	                  <input id="postcode" name="postcode" type="text" class="form-control required"  placeholder="Post code">
	                  <span class="error_label"></span>
	                </div>
	                <div class="form-group">
	                  <select name="countryName" id="countryName" class="form-control">
	                   <option value="">Please Select your country</option>
	                  <?php
					  asort($countryList);
					   foreach($countryList as $ccode => $cname){
						   if($ccode == 'US')
						   { $elete = 'selected=selected';}else  { $elete ='';}
						   echo '<option '.$elete.' value="'.$ccode.'">'.$cname.'</option>';
					   }

					   ?>
	                  </select>
	                  <span class="error_label"></span>
	                </div>
	              </div>

	          </section>
	          <h2>Payment Details</h2>
	          <section>
		          <div class="col-md-8  donate-step-3">

	              </div>
	          </section>
	        </div>

	      </form>



<?php
 }
 else {
 		 echo do_shortcode('[theme-my-login]');
  }
}
add_shortcode( 'wpq_quick_form', 'show_form_quick_form' );
?>
