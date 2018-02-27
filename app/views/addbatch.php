<?php include 'header.php'; ?>

<div class="row">

	<form action="#" method="POST" id="addBatch_form" novalidate>

		<article class="col-sm-6 col-sm-push-3">
			<div class="data-block shadow-block">
				<header>
					<h2>Batch Details</h2>
				</header>

				<section>
					<div class="form-group">
						<label class="control-label">Batch Number:</label>
						<input type="text" name="batch_number" class="form-control" placeholder="49" required>
					</div>
					<div class="form-group">
						<label class="control-label">Batch Registration Date:</label>
						<div class="input-group">
							<span class="input-group-addon"><i class="elusive icon-calendar"></i></span>
							<input type="text" name="regDate" class="datepicker form-control" placeholder="24/10/2013"required>
						</div>		
					</div>
				</section>
			</div>

			<button id="submit" type="submit" class="btn btn-primary" style="margin-right:10px">Save Batch</button>
			<a href="home" class="btn btn-danger">Cancel</a>

			<div id="errors_bg_two" class="pull-right"></div>
		</article>
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
	                url:'batch?action=add',
	                data:$("#addBatch_form").serialize(),
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