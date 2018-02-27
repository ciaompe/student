<?php include 'header.php'; ?>

<script src="assets/js/plugins/password/jquery-pass.js" type="text/javascript"></script>

<div class="row">
					
					<!-- Form validation states -->
			<form action="#" method="POST" id="addStu_form" novalidate>
					<article class="col-sm-6">
						<div class="data-block shadow-block">
							<header>
								<h2>Student Details</h2>
							</header>
							<section>

									<div class="form-group">
										<label class="control-label">Student REG Number:</label>
										<input type="text" class="form-control custom-disabled-input" value="<?php if(empty($data['regid'])) {echo '1';}foreach ($data['regid'] as $row) { echo $row->studentId+1; } ?>" readonly>
									</div>
								
									<div class="form-group">
										<label class="control-label">Student Name:</label>
										<input type="text" name="name" class="form-control" placeholder="John Doe" required>
									</div>
									<div class="form-group">
										<label class="control-label">Student NIC:</label>
										<input type="text" name="studentNic" class="form-control" placeholder="9133256389V" required data-validation-regex-regex="[0-9]{9}[vVxX]">
									</div>
									<div class="form-group">
										<label class="control-label">Student Address:</label>
										<textarea id="inputTextarea" name="address" class="form-control" rows="3" required></textarea>
									</div>
									<div class="form-group">
										<div class="radio styled-radio">
												<label>
												<input type="radio" name="gender" id="optionsRadios1" value="M" data-label="Male" checked required>
												</label>

												<label>
												<input type="radio" name="gender" id="optionsRadios2" value="F" data-label="FEMALE" required>
												</label>
										</div>
										
									</div>
									<div class="form-group">
										<label class="control-label">Student Date of Birth:</label>
										<div class="input-group">
										<span class="input-group-addon"><i class="elusive icon-calendar"></i></span>
										<input type="text" name="dob" class="datepicker form-control" placeholder="24/10/2013"required>
										</div>
									</div>

									<div class="form-group">
										<label class="control-label">Select Batch:</label>
										<select name="batch" id="select" class="form-control" required>
													<option></option>
													<?php 
														foreach ($data['batch'] as $row) {
															echo '<option value="'.$row->batch_id.'">Batch '.$row->batch_number.'</option>';
														}
													?>
										</select>
									</div>

							</section>
						</div>
					</article>
					<!-- /Data block -->

					<!-- Data block -->
					<article class="col-sm-6">
						<div class="data-block shadow-block">
							<header>
								<h2>Student Contact Information</h2>
							</header>
							<section>
									<div class="form-group">
										<label class="control-label">Student Email:</label>
										<input type="email" name="email" class="form-control" placeholder="mpe2010manoj@gmail.com" required>
										
									</div>
									<div class="form-group">
										<label class="control-label">Student Telephone Number:</label>
										<input type="text" name="telephone" class="form-control" placeholder="0112746982" required maxlength="10" minlength="10">
									</div>
									<div class="form-group">
										<label class="control-label">Student Mobile Number:</label>
										<input type="text" name="mobile" class="form-control" placeholder="0754886845" required maxlength="10" minlength="10">
									</div>

							</section>
						</div>

						<div class="data-block shadow-block">
							<header>
								<h2>Student Login Details</h2>
							</header>
							<section>
									<div class="form-group">
										<label class="control-label">Student username:</label>
										<input type="text" name="username" class="form-control" placeholder="mpe2010" required>
										
									</div>
									<div class="form-group">
										<label class="control-label">Student password:</label>
										<div class="input-group">
										<input type="text" class="form-control" name="password" id="password" required>
										<span class="input-group-btn">
											<button id="generate" class="btn btn-default" type="button">Generate</button>
										</span>
									</div>
									</div>

							</section>
						</div>

						<div id="errors_bg_two" class="pull-left"></div>
						<button id="submit" type="submit" class="pull-right btn btn-primary" style="margin-left:10px">Save Student</button>
						<button id="clear" type="submit" class="pull-right btn btn-danger">Clear</button>
					</article>
					<!-- /Data block -->

					<div class="col-sm-12">
						
					</div>

					

				</form>

					
	</div>

<script type="text/javascript">

  $(function() {

    $("input,select,textarea").jqBootstrapValidation({

      preventSubmit: true,
      submitSuccess: function($form, e) {
          e.preventDefault();
          $("#errors_bg_two").hide().html("<p>Please wait</p>").fadeIn('fast');

            $.ajax({
                type:"post",
                    url:'student?action=add',
                    data:$("#addStu_form").serialize(),
                    success:function(data){
                      $("#errors_bg_two").hide().html(data).fadeIn('fast');
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                      jQuery("#errors_bg_two").hide().html("<p>Server Error Occured</p>").fadeIn('fast');
                }
            });
      }

    });

  });

  $('#clear').click(function(){
    $('#addStu_form')[0].reset();
 });

  $('#generate').click(function() {
     $('#password').passy( 'generate', 5);
  });
                              
</script>



<?php include 'footer.php'; ?>