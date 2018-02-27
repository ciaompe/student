<?php include 'header.php'; ?>

<div class="row">

	<form action="#" method="POST" id="updateStu_form" novalidate>
		<article class="col-sm-6">
			<div class="data-block shadow-block">
				<header>
					<h2>Student Details</h2>
				</header>
				<section>
					<div class="form-group">
						<label class="control-label">Student Name:</label>
						<input type="text" name="name" class="form-control" value="<?php echo $data->studentName; ?>" required>
					</div>
					<div class="form-group">
						<label class="control-label">Student Address:</label>
						<textarea id="inputTextarea" name="address" class="form-control" rows="3" required><?php echo $data->studentAddress; ?></textarea>
					</div>
					<div class="form-group">

						<?php if ($data->studentGender == "M") { ?>

						<div id="radio_buttons" class="radio styled-radio">
							<label><input type="radio" name="gender" id="optionsRadios2" value="F" data-label="Female" required></label>
							<label><input type="radio" name="gender" id="optionsRadios1" value="M" data-label="Male"  checked required></label>
						</div>

						<?php } if($data->studentGender == "F") {?>

						<div id="radio_buttons" class="radio styled-radio">
							<label><input type="radio" name="gender" id="optionsRadios2" value="F" data-label="Female" checked required></label>
							<label><input type="radio" name="gender" id="optionsRadios1" value="M" data-label="Male" required></label>
						</div>

						<?php } ?>

					</div>
					<div class="form-group">
						<label class="control-label">Student Date of Birth:</label>
						<div class="input-group">
							<span class="input-group-addon"><i class="elusive icon-calendar"></i></span>
							<input type="text" name="dob" class="datepicker form-control" value="<?php echo $data->studentDob; ?>" required>
						</div>
					</div>

				</section>
			</div>

		</article>

		<article class="col-sm-6">
			<div class="data-block shadow-block">
				<header>
					<h2>Student Contact Information</h2>
				</header>

				<section>

					<div class="form-group">
						<label class="control-label">Student Telephone Number:</label>
						<input type="number" name="telephone" class="form-control" value="<?php echo $data->studentTelhome; ?>" required maxlength="10" minlength="10">
					</div>

					<div class="form-group">
						<label class="control-label">Student Mobile Number:</label>
						<input type="number" name="mobile" class="form-control" value="<?php echo $data->studentTelmobile; ?>" required maxlength="10" minlength="10">
					</div>

				</section>

			</div>

			<div id="errors_bg_three" class="pull-left"></div>
			<button id="submit" type="submit" class="pull-right btn btn-primary" disabled="true">Update</button>

	</form>

</div>


<script type="text/javascript">
	
	$(function() {

		$("input,select,textarea").change( function() {
			$('#submit').attr('disabled', false);
		});

	    $("input,select,textarea").jqBootstrapValidation({
	      preventSubmit: true,
	      submitSuccess: function($form, e) {
	          e.preventDefault();
	          $("#errors_bg_three").hide().html("<p>Please wait</p>").fadeIn('fast');

	            $.ajax({
	                type:"post",
	                url:'student?action=updateMe',
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

  });

</script>

<?php include 'footer.php'; ?>