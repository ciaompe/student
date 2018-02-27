<?php include 'header.php'; include 'student_settings_nav.php';?>
<br>
<div class="row">

	<article class="col-sm-6">
		<div class="data-block shadow-block">

				<header>
					<h2>Student Informations</h2>
				</header>

				<section class="student-data-list">
					<ul class="my-custom-list">

						<li>
							<label>Registration ID : </label>
							<p><?php echo $data[0]->studentId; ?></p>
						</li>

						<li>
							<label>Name : </label>
							<p><?php echo $data[0]->studentName; ?></p>
						</li>

						<li>
							<label>NIC Number : </label>
							<p><?php echo $data[0]->studentNic; ?></p>
						</li>

						<li>
							<label>Address : </label>
							<p><?php echo $data[0]->studentAddress; ?></p>
						</li>

						<?php 

							if ($data[0]->studentGender == "M") {
								echo '<li>
										<label>Gender : </label>
										<p>Male</p>
									</li>';
							} else if ($data[0]->studentGender == "F") {
								echo '<li>
										<label>Gender : </label>
										<p>Female</p>
									</li>';
							}

						 ?>

						<li>
							<label>Date of Birth : </label>
							<p><?php echo $data[0]->studentDob; ?></p>
						</li>

						<li>
							<label>Registration Date : </label>
							<p><?php echo $data[0]->studentReg; ?></p>
						</li>

						<li>
							<label>Batch Number : </label>
							<p>Batch <?php echo $data[0]->batch_number; ?></p>
						</li>

					</ul>
				</section>
		</div>
	</article>

	<article class="col-sm-6">
		<div class="data-block shadow-block">

				<header>
					<h2>Student Contact Informations</h2>
				</header>

				<section class="student-data-list">
					<ul class="my-custom-list">
						<li>
							<label>Email : </label>
							<p><?php echo $data[0]->studentEmail; ?></p>
						</li>

						<li>
							<label>Telephone Numbr : </label>
							<p><?php echo $data[0]->studentTelhome; ?></p>
						</li>

						<li>
							<label>Mobile Numbr : </label>
							<p><?php echo $data[0]->studentTelmobile; ?></p>
						</li>

					</ul>
				</section>
		</div>
	</article>

</div>

<?php include 'footer.php'; ?>