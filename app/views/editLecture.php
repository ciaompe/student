<?php include 'header.php'; ?>

<div class="row">

	<form action="#" method="POST" id="editLECT_form" novalidate>

		<article class="col-sm-6">
			<div class="data-block shadow-block">
				<header>
					<h2>Lecture Subjects</h2>
				</header>

				<section class="custom-block-section">

					<?php foreach ($data['subjects'] as $subject) {	
					?>
					<div class="form-group">
						<div class="checkbox styled-checkbox">
							<label>
								<input id="sub-<?php echo $subject->sub_id; ?>" type="checkbox" name="subjects[]" value="<?php echo $subject->sub_id; ?>" data-label="<?php echo $subject->sub_name; ?>" required>
							</label>
						</div>
					</div>

					<?php foreach ($data['lectureSub'] as $lect_sub) {
							
							if ($subject->sub_id == $lect_sub->sub_id) {
								echo "<script>$('#sub-".$subject->sub_id."').attr('checked', 'checked');</script>";
							}
					} }?>

				</section>
			</div>
		</article>

		<article class="col-sm-6">
			<div class="data-block shadow-block">
				<header>
					<h2>Lecture Details</h2>
				</header>

				<section>
					<div class="form-group">
						<label class="control-label">Lecture Name:</label>
						<input type="text" name="lectureName" class="form-control" value="<?php echo $data['lecture'][0]->lecturerName; ?>" required>
					</div>
					<div class="form-group">
						<label class="control-label">Lecture Email:</label>
						<input type="email" name="lectureEmail" class="form-control" value="<?php echo $data['lecture'][0]->lecturerEmail; ?>"required>			
					</div>
				</section>
			</div>

			<div id="errors_bg_two" class="pull-left"></div>
			<button id="submit" type="submit" class="pull-right btn btn-primary" style="margin-left:10px" disabled="true">Update Lecture</button>
		</article>

	</form>


</div>

<br><br>

<script type="text/javascript">

	$(function() {

		$("input,select,textarea").change( function() {
			$('#submit').attr('disabled', false);
		});

		function getUrlVars() {
		    var vars = {};
		    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
		        vars[key] = value;
		    });
		    return vars;
		}

	   $("input,select,textarea").jqBootstrapValidation({

	      preventSubmit: true,
	      submitSuccess: function($form, e) {
	          e.preventDefault();
	          $("#errors_bg_two").hide().html("<p>Please wait</p>").fadeIn('fast');

	            $.ajax({
	                type:"post",
	                    url:'lecturer?action=update&lid='+getUrlVars()["lid"]+'&email=<?php echo $data['lecture'][0]->lecturerEmail; ?>&uid=<?php echo $data['lecture'][0]->user_id; ?>',
	                    data:$("#editLECT_form").serialize(),
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


</script>


<?php include 'footer.php'; ?>