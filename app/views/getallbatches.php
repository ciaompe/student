<?php include 'header.php'; ?>

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

<div class="row">

		<br>
		<div class="data-block">
			<header>
				<h2>All Registerd Batches</h2>
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

					<?php if (empty($data)) {?>
						<td>No Data</td><td>No Data</td><td>No Data</td>
					<?php } ?>

					<?php foreach ($data as $row) { ?>
					
					<tr>
						<td>Batch <?php echo $row->batch_number; ?></td>
						<td><?php echo $row->batch_reg_date; ?></td>
						<td class="toolbar">
							<div class="btn-group">
								<a href="batch?action=open&id=<?php echo $row->batch_id; ?>" class="btn btn-primary btn-sm" style="margin-right:5px;"><span class="elusive icon-group" style="margin-right:5px;"></span>Show</a>
								<?php if ($this->_user->coordinator()) {?>
								<a href="batch?action=edit&id=<?php echo $row->batch_id; ?>" class="btn btn-primary btn-sm" style="margin-right:5px;"><span class="elusive icon-edit" style="margin-right:5px;"></span>Edit</a>
								<a href="batch?action=delete&id=<?php echo $row->batch_id; ?>" class="btn btn-primary btn-sm" onclick="return confirm('Are you sure delete this batch ?')"><span class="elusive icon-trash" style="margin-right:5px;"></span>Delete</a>
								<?php }?>
							</div>
						</td>
							
					</tr>

					<?php } ?>

				</tbody>
			</table>
		</div>
		

</div>

<?php include 'footer.php'; ?>