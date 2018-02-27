<?php include 'header.php'; ?>

	<div class="row">

		<form action="#" method="POST" id="findStubynic_form" novalidate>
			<article class="col-sm-6">
				<div class="data-block shadow-block">
					<header>
						<h2>Find Student By Nic</h2>
					</header>
					<section>
						<div class="form-group">
							<input type="text" name="studentNic" class="form-control" placeholder="913323858V" required data-validation-regex-regex="[0-9]{9}[vVxX]">
						</div>
						<div class="form-group">
							<button id="byNIC" type="submit" class="btn btn-primary">Find Student</button>
							<div id="errors_bg_two" class="pull-right"></div>
						</div>	
					</section>
				</div>
			</article>
		</form>

		<form action="#" method="POST" id="findStubyregnum_form" novalidate>
			<article class="col-sm-6">
				<div class="data-block shadow-block">
					<header>
						<h2>Find Student By REG Number</h2>
					</header>
					<section>
						<div class="form-group">
							<input type="number" name="studentregnum" class="form-control" placeholder="01" required>
						</div>
						<div class="form-group">
							<button id="byNIC" type="submit" class="btn btn-primary">Find Student</button>
							<div id="errors_bg_for" class="pull-right"></div>
						</div>	
					</section>
				</div>
			</article>
		</form>


	</div>

	<div id="udpated_form"></div>

<script type="text/javascript">

  $(function() {


  	function IsJsonString(str) {
	 try {
	     $.parseJSON(str);
	   } catch (e) {
	   return false;
	 }
   	 return true;
	}

	function drawHTML(mydata) {

		 $('#udpated_form').html('<hr class="table_devider"><div class="row"><form action="#" method="POST" id="updateStu_form" novalidate><article class="col-sm-6"><div class="data-block shadow-block"><header><h2>Student Details</h2></header><section><div class="form-group"><label class="control-label">Student Name:</label><input type="text" name="name" class="form-control" value="'+mydata.students[0].studentName+'" required></div><div class="form-group"><label class="control-label">Student NIC:</label><input type="text" name="studentNic" class="form-control" value="'+mydata.students[0].studentNic+'" required data-validation-regex-regex="[0-9]{9}[vVxX]"></div><div class="form-group"><label class="control-label">Student Address:</label><textarea id="inputTextarea" name="address" class="form-control" rows="3" required>'+mydata.students[0].studentAddress+'</textarea></div><div class="form-group"><div id="radio_buttons" class="radio styled-radio"></div></div><div class="form-group"><label class="control-label">Student Date of Birth:</label><div class="input-group"><span class="input-group-addon"><i class="elusive icon-calendar"></i></span><input type="text" name="dob" class="datepicker form-control" value="'+mydata.students[0].studentDob+'" required></div></div><div class="form-group"><label class="control-label">Select Batch:</label><select id="mybatches" name="batch" id="select" class="form-control" required></select></div></section></div></article><article class="col-sm-6"><div class="data-block shadow-block"><header><h2>Student Contact Information</h2></header><section><div class="form-group"><label class="control-label">Student Email:</label><input type="email" name="email" class="form-control" value="'+mydata.students[0].studentEmail+'" required></div><div class="form-group"><label class="control-label">Student Telephone Number:</label><input type="number" name="telephone" class="form-control" value="'+mydata.students[0].studentTelhome+'" required maxlength="10" minlength="10"></div><div class="form-group"><label class="control-label">Student Mobile Number:</label><input type="number" name="mobile" class="form-control" value="'+mydata.students[0].studentTelmobile+'" required maxlength="10" minlength="10"></div></section></div><div id="errors_bg_three" class="pull-left"></div><button id="submit" type="submit" class="pull-right btn btn-primary" disabled="true">Update</button></form></div>');

	     if(mydata.students[0].studentGender == "M") {
	        $('#radio_buttons').append('<label><input type="radio" name="gender" id="optionsRadios1" value="M" data-label="Male" checked required></label>');
	        $('#radio_buttons').append('<label><input type="radio" name="gender" id="optionsRadios2" value="F" data-label="Female" required></label>');
	     }

	     if(mydata.students[0].studentGender == "F") {
	         $('#radio_buttons').append('<label><input type="radio" name="gender" id="optionsRadios2" value="F" data-label="Female" checked required></label>');
	         $('#radio_buttons').append('<label><input type="radio" name="gender" id="optionsRadios1" value="M" data-label="Male" required></label>');
	      }

	         $('.styled-checkbox input, .styled-radio input').prettyCheckable();
	         $('.datepicker').datepicker({"autoclose": true});

	      for(p in mydata.batches){
	         if (mydata.batches[p].batch_id == mydata.students[0].batchId) {
	             $('#mybatches').append('<option value="'+mydata.batches[p].batch_id+'" selected>'+mydata.batches[p].batch_number+'</option>');
	         } else {
	             $('#mybatches').append('<option value="'+mydata.batches[p].batch_id+'">'+mydata.batches[p].batch_number+'</option>');
	         }
	      }

	      $("input,select,textarea").change( function() {
			$('#submit').attr('disabled', false);
		});
	}

    $("#findStubynic_form input,select,textarea").jqBootstrapValidation({

      preventSubmit: true,
      submitSuccess: function($form, e) {
          e.preventDefault();
          $("#errors_bg_two").hide().html("<p>Please wait</p>").fadeIn('fast');
         
            $.ajax({
                type:"post",
                    url:'student?action=edit&method=nic',
                    data:$("#findStubynic_form").serialize(),
                    success:function(data){

                  		if(IsJsonString(data) ) {
                  			$(".loader").css('display', 'block');
                  			$("#errors_bg_two p").remove();
                  			var mydata = $.parseJSON(data);

                  			setTimeout(function(){

                  				$(".loader").css('display', 'none');
	                  			drawHTML(mydata);

	                  			 $("#updateStu_form input,select,textarea").jqBootstrapValidation({
											preventSubmit: true,
								      		submitSuccess: function($form, e) {
								          	 e.preventDefault();
								          	   $("#errors_bg_three").hide().html("<p>Please wait</p>").fadeIn('fast');

									        $.ajax({
									            type:"post",
									            url:'student?action=update&stu='+mydata.students[0].studentId+'&user='+mydata.students[0].user_id+'&myemail='+mydata.students[0].studentEmail+'&mynic='+mydata.students[0].studentNic,
									            data:$("#updateStu_form").serialize(),
									            success:function(data){
									               $("#errors_bg_three").hide().html(data).fadeIn('fast');
									            },
									            error: function(XMLHttpRequest, textStatus, errorThrown) {
									               jQuery("#errors_bg_three").hide().html("<p>Server Error Occured</p>").fadeIn('fast');
									       		}		
									     });
								     }
								});


	                  		}, 1000);

                  		} else {
                  			$("#errors_bg_two").hide().html(data).fadeIn('fast');
                  		}
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                    jQuery("#errors_bg_two").hide().html("<p>Server Error Occured</p>").fadeIn('fast');
                }
            });
      }

    });

	$("#findStubyregnum_form input,select,textarea").jqBootstrapValidation({

      preventSubmit: true,
      submitSuccess: function($form, e) {
          e.preventDefault();
         
          $("#errors_bg_for").hide().html("<p>Please wait</p>").fadeIn('fast');

            $.ajax({
                type:"post",
                    url:'student?action=edit&method=regnum',
                    data:$("#findStubyregnum_form").serialize(),
                    success:function(data){

                  		if(IsJsonString(data) ) {
                  			$(".loader").css('display', 'block');
                  			$("#errors_bg_for p").remove();
                  			var mydata = $.parseJSON(data);

                  			setTimeout(function(){
                  				$(".loader").css('display', 'none');
	                  			 drawHTML(mydata);
	                  			 $("#updateStu_form input,select,textarea").jqBootstrapValidation({
											preventSubmit: true,
								      		submitSuccess: function($form, e) {
								          	 e.preventDefault();
								          	   $("#errors_bg_three").hide().html("<p>Please wait</p>").fadeIn('fast');
									        $.ajax({
									            type:"post",
									            url:'student?action=update&stu='+mydata.students[0].studentId+'&user='+mydata.students[0].user_id+'&myemail='+mydata.students[0].studentEmail+'&mynic='+mydata.students[0].studentNic,
									            data:$("#updateStu_form").serialize(),
									            success:function(data){
									               $("#errors_bg_three").hide().html(data).fadeIn('fast');
									            },
									            error: function(XMLHttpRequest, textStatus, errorThrown) {
									               jQuery("#errors_bg_three").hide().html("<p>Server Error Occured</p>").fadeIn('fast');
									       		}		
									     });
								     }
								});


	                  		}, 1000);

                  		} else {
                  			$("#errors_bg_for").hide().html(data).fadeIn('fast');
                  		}
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                    jQuery("#errors_bg_for").hide().html("<p>Server Error Occured</p>").fadeIn('fast');
                }
            });
      }

    });

  });
                              
</script>

<?php include 'footer.php'; ?>