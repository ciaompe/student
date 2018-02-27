<?php 

include 'header.php';  
Session::delete('batchid');
Session::put('batchid', Input::get('id'));
?>

<div class="row">
		<br>
		
		<div class="data-block">
			<header>
				<h2>Batch <?php foreach ($data['batch'] as $row) {
					echo $row->batch_number;
				} ?> Students</h2>
			</header>
		</div>

		<div class="table-responsive table-top-margin">
			<table class="table table-hover">
				<thead>
					<tr>
						<th style="border-left:none">REG No</th>
						<th>Name</th>
						<th>NIC No</th>
						<th>Telephone</th>
						<th>Mobile</th>
						<th>REG Date</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					
				<?php if (count($data['stuents']) > 0) {foreach ($data['stuents'] as $row) { ?>
					<tr>
						<td><?php echo $row->studentId; ?></td>
						<td><?php echo $row->studentName; ?></td>
						<td><?php echo $row->studentNic; ?></td>
						<td><?php echo $row->studentTelhome; ?></td>
						<td><?php echo $row->studentTelmobile; ?></td>
						<td><?php echo $row->studentReg; ?></td>
						<td class="toolbar">
							<div class="btn-group">
								<a href="student?action=show&sid=<?php echo $row->studentId; ?>" class="btn btn-primary btn-sm" style="margin-right:5px;"><span class="elusive icon-user" style="margin-right:5px;"></span>Show</a>
								<a href="student?action=result&sid=<?php echo $row->studentId; ?>" class="btn btn-primary btn-sm" style="margin-right:5px;"><span class="elusive icon-flag" style="margin-right:5px;"></span>Results</a>
								<?php if ($user->coordinator()) { ?>
								<a href="student?action=liveEdit&sid=<?php echo $row->studentId; ?>" class="btn btn-primary btn-sm" style="margin-right:5px;"><span class="elusive icon-edit" style="margin-right:5px;"></span>Edit</a>
								<a href="student?action=delete&sid=<?php echo $row->studentId; ?>&batchid=<?php echo $row->batchId; ?>" class="btn btn-primary btn-sm" style="margin-right:5px;" onclick="return confirm('Are you sure delete this student ?')"><span class="elusive icon-trash" style="margin-right:5px;"></span>Delete</a>
								<?php } ?>
								<a href="student?action=grade&sid=<?php echo $row->studentId; ?>" class="btn btn-primary btn-sm"><span class="elusive icon-flag-alt" style="margin-right:5px;"></span>Grade</a>
							</div>
						</td>
					</tr>
				<?php } } else {?>
					<tr>
						<td>NO Data</td>
						<td>NO Data</td>
						<td>NO Data</td>
						<td>NO Data</td>
						<td>NO Data</td>
						<td>NO Data</td>
						<td>NO Data</td>
					</tr>
				<?php } ?>

				</tbody>
			</table>
		</div>
</div>


<?php include 'footer.php'; ?>