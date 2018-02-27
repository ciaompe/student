<?php include 'header.php'; include 'student_settings_nav.php';?>
<br>
<div class="row">

		<div class="data-block">
			<header>
				<h2>All Subjects Result</h2>
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
</div>

<?php include 'footer.php'; ?>