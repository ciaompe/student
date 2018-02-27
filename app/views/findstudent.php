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

	<div id="studentDetails"></div>


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

	function drawData(mydata) {
		 $('#studentDetails').html('<hr class="table_devider"><div class="row"><article class="col-sm-6"><div class="data-block shadow-block"><header><h2>Student Details</h2></header><section><div class="form-group"><label class="control-label">Student REG Number:</label><input type="text" class="form-control custom-disabled-input" value="'+mydata.students[0].studentId+'" readonly></div><div class="form-group"><label class="control-label">Student Name:</label><input type="text" class="form-control" value="'+mydata.students[0].studentName+'" readonly></div><div class="form-group"><label class="control-label">Student NIC:</label><input type="text" class="form-control custom-disabled-input" value="'+mydata.students[0].studentNic+'" readonly></div><div class="form-group"><label class="control-label">Student Address:</label><textarea class="form-control" rows="3" readonly>'+mydata.students[0].studentAddress+'</textarea></div><div class="form-group"><label class="control-label">Student Date of Birth:</label><input type="text" class="form-control" value="'+mydata.students[0].studentDob+'" readonly></div><div class="form-group"><label class="control-label">Student Batch Number:</label><input type="text" class="form-control custom-disabled-input" value="Batch '+mydata.students[0].batch_number+'" readonly></div><div class="form-group"><label class="control-label">Student Registration Date:</label><input type="text" class="form-control" value="'+mydata.students[0].studentReg+'" readonly></div></section></div></article><article class="col-sm-6"><div class="data-block shadow-block"><header><h2>Student Contact Information</h2></header><section><div class="form-group"><label class="control-label">Student Email Address:</label><input type="text" class="form-control" value="'+mydata.students[0].studentEmail+'" readonly></div><div class="form-group"><label class="control-label">Student Telephone Number:</label><input type="text" class="form-control" value="'+mydata.students[0].studentTelhome+'" readonly></div><div class="form-group"><label class="control-label">Student Mobile Number:</label><input type="text" class="form-control" value="'+mydata.students[0].studentTelmobile+'" readonly></div></section></div></article><article class="col-sm-6"><div class="data-block shadow-block"><header><h2>Student Login Information</h2></header><section><div class="form-group"><label class="control-label">Student Username:</label><input type="text" class="form-control custom-disabled-input" value="'+mydata.students[0].username+'" readonly></div></section></div></article></div><br><br>');
	}

    $("#findStubynic_form input,select,textarea").jqBootstrapValidation({

      preventSubmit: true,
      submitSuccess: function($form, e) {
          e.preventDefault();
          $("#errors_bg_two").hide().html("<p>Please wait</p>").fadeIn('fast');

            $.ajax({
                type:"post",
                    url:'student?action=find&method=nic',
                    data:$("#findStubynic_form").serialize(),
                    success:function(data){

                  		if(IsJsonString(data) ) {

                  			$(".loader").css('display', 'block');
                  			$("#errors_bg_two p").remove();
                  			var mydata = $.parseJSON(data);

                  			setTimeout(function(){

	                  			$(".loader").css('display', 'none');
	                  			drawData(mydata);

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
                    url:'student?action=find&method=regnum',
                    data:$("#findStubyregnum_form").serialize(),
                    success:function(data){

                  		if(IsJsonString(data) ) {

                  			$(".loader").css('display', 'block');
                  			$("#errors_bg_for p").remove();
                  			var mydata = $.parseJSON(data);

                  			setTimeout(function(){
                  				$(".loader").css('display', 'none');
	                  			drawData(mydata);

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