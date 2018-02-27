<?php $user =  new User(); include 'header.php'; if( $user->coordinator() || $user->lecturer() ){ ?>

<div class="row">

					<?php 
						if (Session:: exists('sucess')) {
							echo '<div class="alert alert-success alert-dismissable fade in">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true" title="Close alert">&times;</button>
					<strong>Success!</strong> '.Session::display('sucess').'</div>';
							echo'<br>';
						}

						if (Session:: exists('error')) {
						  echo '<div class="alert alert-danger alert-dismissable fade in">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true" title="Close alert">&times;</button>
					<strong>Error!</strong> '.Session::display('error').'</div>';
							echo'<br>';
						}
					 ?>

					<!-- Widget block -->
					<div class="col-sm-2 col-xs-6">
						<a class="data-block widget-block new-round cordi" href="student?action=add">
							<span class="widget-icon elusive icon-adult"></span>
							<strong>Add Student</strong>
						</a>
					</div>
					<!-- /Widget block -->

					<!-- Widget block -->
					<div class="col-sm-2 col-xs-6">
						<a class="data-block widget-block new-round cordi" href="student?action=edit">
							<span class="widget-icon elusive icon-adult"></span>
							<strong>Edit Student</strong>
						</a>
					</div>
					<!-- /Widget block -->

					<!-- Widget block -->
					<div class="col-sm-2 col-xs-6">
						<a class="data-block widget-block new-round" href="student?action=find">
							<span class="widget-icon elusive icon-adult"></span>
							<strong>Find Student</strong>
						</a>
					</div>
					<!-- /Widget block -->

					<!-- Widget block -->
					<div class="col-sm-2 col-xs-6">
						<a class="data-block widget-block new-round cordi" href="lecturer?action=add">
							<span class="widget-icon elusive icon-torso"></span>
							<strong>Add Lecture</strong>
						</a>
					</div>
					<!-- /Widget block -->

					<!-- Widget block -->
					<div class="col-sm-2 col-xs-6">
						<a class="data-block widget-block new-round cordi" href="batch?action=add">
							<span class="widget-icon elusive icon-group"></span>
							<strong>Add Batch</strong>
						</a>
					</div>
					<!-- /Widget block -->

					<!-- Widget block -->
					<div class="col-sm-2 col-xs-6">
						<a class="data-block widget-block new-round cordi" href="lecturer?action=all">
							<span class="widget-icon elusive icon-myspace"></span>
							<strong>Lectures</strong>
						</a>
					</div>
					<!-- /Widget block -->

					<!-- Widget block -->
					
					<!-- /Widget block -->

</div>

<div class="row">

		<br>
		<div class="data-block">
			<header>
				<h2>Latest Registerd Batches</h2>
			</header>
		</div>

		<div class="table-responsive table-top-margin">
			<table class="table table-hover">
				<thead>
					<tr>
						<th style="border-left:none">Batch Number</th>
						<th>Registerd Date</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>

					<?php if (empty($data)){?>
						<td>No Data</td><td>No Data</td><td>No Data</td>
					<?php } ?>

					<?php foreach ($data as $row) { ?>
					
					<tr>
						<td>Batch <?php echo $row->batch_number; ?></td>
						<td><?php echo $row->batch_reg_date; ?></td>
						<td class="toolbar">
							<div class="btn-group">
								<a href="batch?action=open&id=<?php echo $row->batch_id; ?>" class="btn btn-primary btn-sm" style="margin-right:5px;"><span class="elusive icon-group" style="margin-right:5px;"></span>Open</a>
							</div>
						</td>
							
					</tr>

					<?php } ?>

				</tbody>
			</table>
		</div>
		

</div>


<?php 

	if (!$user->coordinator()) {
		echo "<script type=\"text/javascript\">$('.cordi').addClass('disabled'); $('.cordi').attr('disabled','disabled');</script>";
	} 

} else { ?>
	
	<!-- Student View -->
	<div class="row">

			<?php 
				if (Session:: exists('sucess')) {
							echo '<div class="alert alert-success alert-dismissable fade in">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true" title="Close alert">&times;</button>
					<strong>Success!</strong> '.Session::display('sucess').'</div>';
							echo'<br>';
				}
				if (Session:: exists('error')) {
						  echo '<div class="alert alert-danger alert-dismissable fade in">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true" title="Close alert">&times;</button>
					<strong>Error!</strong> '.Session::display('error').'</div>';
							echo'<br>';
				}

					
			?>
	</div>

	<div class="row">

		<div class="data-block">
			<header>
				<h2>All My Subjects Result</h2>
			</header>
		</div>

		<div class="table-responsive table-top-margin">
			<table class="table table-hover">
				<thead>
					<tr>
						<th style="border-left:none">Subject</th>
						<th>Grade</th>
					</tr>
				</thead>

				<tbody>

				<?php
					if (count($data['grade']) == 0) {
						echo "<tr><td>No Data</td><td>No Data</td></tr>";
					}
					foreach ($data['subjects'] as $subject) {
						foreach ($data['grade'] as $grade) {

							echo '<tr>';
							if ($subject->sub_id == $grade->sub_id) {

								echo '<td>'.$subject->sub_name.'</td>';
								if ($grade->grade == "D") {
									echo '<td>DISTINCTION</td>';
								} else if($grade->grade == "M") {
									echo '<td>MERIT</td>';
								} else if($grade->grade == "P") {
									echo '<td>PASS</td>';
								} else if($grade->grade == "R") {
									echo '<td>RE SUBMIT</td>';
								}
							}
							echo '</tr>';
						}
					}
				?>
				</tbody>
			</table>
		</div>

		<a href="cretaePDF" class="btn btn-default">Download As PDF</a>
</div>



<?php } include 'footer.php';  ?>