<?php include 'header.php'; $user = new User();?>

	<div class="row">

		<form action="#" method="POST" id="changeemail_form" novalidate>

			<article class="col-sm-6">

				<div class="data-block shadow-block">
					<header>
						<h2>Change Email</h2>
					</header>

					<section>
						<div class="form-group">
							<label class="control-label">Email:</label>
							<input type="email" name="email" class="form-control" value="<?php echo $user->data()->email; ?>" required>
						</div>

						<div class="form-group">
							<button id="cemail" type="submit" class="btn btn-primary">Change</button>
							<div id="errors_bg_two" class="pull-right"></div>
						</div>

					</section>
				</div>

				

			</article>

		</form>

		<form action="#" method="POST" id="changepassword_form" novalidate>

			<article class="col-sm-6">

				<div class="data-block shadow-block">
					<header>
						<h2>Change Password</h2>
					</header>

					<section>
						<div class="form-group">
							<label class="control-label">Current Password:</label>
							<input type="password" name="Current-Password" class="form-control" required>
						</div>
						<div class="form-group">
							<label class="control-label">New Password:</label>
							<input type="password" name="New-Password" class="form-control" required>
						</div>
						<div class="form-group">
							<label class="control-label">Confirm Password:</label>
							<input type="password" name="Confirm-Password" class="form-control" data-validation-match-match="New-Password" required>
						</div>

						<div class="form-group">
							<button id="cpassword" type="submit" class="btn btn-primary">Change</button>
							<div id="errors_bg_three" class="pull-right"></div>
						</div>
					</section>
				</div>

			</article>

		</form>

	</div>

	<script type="text/javascript">
		$(function() {

		    $("#changeemail_form input,select,textarea").jqBootstrapValidation({

		      preventSubmit: true,
		      submitSuccess: function($form, e) {
		          e.preventDefault();
		          $("#errors_bg_two").hide().html("<p>Please wait</p>").fadeIn('fast');

		            $.ajax({
		                type:"post",
		                url:'member?action=settings&change=email',
		                data:$("#changeemail_form").serialize(),
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

		$(function() {

		    $("#changepassword_form input,select,textarea").jqBootstrapValidation({

		      preventSubmit: true,
		      submitSuccess: function($form, e) {
		          e.preventDefault();
		          $("#errors_bg_three").hide().html("<p>Please wait</p>").fadeIn('fast');

		            $.ajax({
		                type:"post",
		                url:'member?action=settings&change=password',
		                data:$("#changepassword_form").serialize(),
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