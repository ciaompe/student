<?php include 'header.php'; ?>

<script src="assets/js/plugins/password/jquery-pass.js" type="text/javascript"></script>

<div class="row">

	<form action="#" method="POST" id="addLECT_form" novalidate>

		<article class="col-sm-6">
			<div class="data-block shadow-block">
				<header>
					<h2>Lecturer Subjects</h2>
				</header>

				<section class="custom-block-section">
					<div class="form-group">

						<?php foreach ($data as $row) { ?>
						<div class="checkbox styled-checkbox">
							<label>
								<input type="checkbox" name="subjects[]" value="<?php echo $row->sub_id ?>" data-label="<?php echo $row->sub_name; ?>" required>
							</label>
						</div>

						<?php } ?>

					</div>
				</section>
			</div>
		</article>

		<article class="col-sm-6">
			<div class="data-block shadow-block">
				<header>
					<h2>Lecturer Details</h2>
				</header>

				<section>
					<div class="form-group">
						<label class="control-label">Lecturer Name:</label>
						<input type="text" name="lecturerName" class="form-control" placeholder="John Doe" required>
					</div>
					<div class="form-group">
						<label class="control-label">Lecturer Email:</label>
						<input type="email" name="email" class="form-control" placeholder="mpe2010manoj@gmail.com" required>			
					</div>
				</section>
			</div>

			<div class="data-block shadow-block">
					<header>
					<h2>Lecturer Login Details</h2>
					</header>
				<section>
					<div class="form-group">
						<label class="control-label">Lecturer username:</label>
						<input type="text" name="username" class="form-control" placeholder="mpe2010" required>
										
					</div>
					<div class="form-group">
							<label class="control-label">Lecturer password:</label>
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
			<button id="submit" type="submit" class="pull-right btn btn-primary" style="margin-left:10px">Save Lecturer</button>
			<button id="clear" type="submit" class="pull-right btn btn-danger">Clear</button>

		</article>

	</form>


</div>

<br><br>

<script type="text/javascript">

  $(function() {

    $("input,select,textarea").jqBootstrapValidation({

      preventSubmit: true,
      submitSuccess: function($form, e) {
          e.preventDefault();
          $("#errors_bg_two").hide().html("<p>Please wait</p>").fadeIn('fast');

            $.ajax({
                type:"post",
                    url:'lecturer?action=add',
                    data:$("#addLECT_form").serialize(),
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
    $('#addLECT_form')[0].reset();
 });

   $('#generate').click(function() {
     $('#password').passy( 'generate', 5);
  });
                              
</script>


<?php include 'footer.php'; ?>