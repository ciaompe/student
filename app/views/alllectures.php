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
				<h2>Lecturers Panel</h2>
			</header>
		</div>

		<div class="table-responsive table-top-margin">
			<table class="table table-hover">
				<thead>
					<tr>
						<th style="border-left:none">Lecturer Name</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>

					<?php if (empty($data)) {?>
						<td>No Data</td><td>No Data</td><td>No Data</td>
					<?php } ?>

					<?php foreach ($data as $row) { ?>
					
					<tr>
						<td><?php echo $row->lecturerName; ?></td>
						<td class="toolbar">
							<div class="btn-group">
								<a href="lecturer?action=edit&lid=<?php echo $row->lecturerId; ?>" class="btn btn-primary btn-sm" style="margin-right:5px;"><span class="elusive icon-edit" style="margin-right:5px;"></span>Edit</a>
								<a href="lecturer?action=delete&lid=<?php echo $row->lecturerId; ?>" class="btn btn-primary btn-sm" onclick="return confirm('Are you sure delete this lecturer ?')" style="margin-right:5px;"><span class="elusive icon-trash" style="margin-right:5px;"></span>Delete</a>
								<?php if ($this->_user->getUserDetails($row->user_id)->user_group != 4) { ?>
								<a href="lecturer?action=ban&lid=<?php echo $row->lecturerId; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure ban this lecturer ?')"><span class="elusive icon-trash" style="margin-right:5px;"></span>Ban</a>
								<?php }  if ($this->_user->getUserDetails($row->user_id)->user_group == 4) {?>
								<a href="lecturer?action=unban&lid=<?php echo $row->lecturerId; ?>" class="btn btn-warning btn-sm" onclick="return confirm('Are you sure unban this lecturer ?')"><span class="elusive icon-trash" style="margin-right:5px;"></span>Unban</a>
								<?php } ?>
							</div>
						</td>
							
					</tr>

					<?php } ?>

				</tbody>
			</table>
		</div>
		

</div>


<?php include 'footer.php'; ?>