<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
	<title>Mysara Student Management System</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="description" content="Mysara College (Pvt) Ltd is a Sri Lankan private ICT institution affiliated to MJ international vocational training service provider in the UK. This Institute is currently conducting Higher Diploma in Computing which is equivalent to 1st and 2nd years of an academic degree programme">
	<meta name="author" content="Mysara College (Pvt) Ltd">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="robots" content="index,follow">
	<meta name="keywords" content="Mysara, College, ICT institution, MJ international, Higher Diploma in Computing, degree programme" />
	<!-- Styles -->
	<link rel="stylesheet" href="assets/css/style.css">
	<link rel='stylesheet' type='text/css' href='assets/css/plugins/prettycheckable/prettyCheckable.css'>
	<link rel='stylesheet' type='text/css' href='assets/css/plugins/daterangepicker/daterangepicker.css'>
	<!-- JS Libs -->
	<script src="assets/js/libs/jquery.js" type="text/javascript"></script>
	<script src="assets/js/libs/modernizr.js" type="text/javascript"></script>
	<script src="assets/js/libs/placeholder.js" type="text/javascript"></script>
	<script src="assets/js/libs/alertify.min.js" type="text/javascript"></script>
	<script src="assets/js/libs/jquery.leanModal.min.js" type="text/javascript"></script>

	<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
	    <!--[if lt IE 9]>
	       <script src="assets/js/libs/html5.js"></script>
	<![endif]-->
	</head>

<!-- Full height wrapper -->
<body>
<div class="loader"><span></span><span></span><span></span><span></span></div>
		<div id="wrapper">

		<header id="header" class="container">
		<!-- Main navigation -->
				<nav class="main-navigation navbar navbar-default navbar-fixed-top" role="navigation">

				<div class="navbar-inner">
       				 <div class="container">

					<!-- Collapse navigation for mobile -->
					<div class="navbar-header">
						<button type="button" class="my-mobile-nav-bar navbar-toggle" data-toggle="collapse" data-target=".main-navigation-collapse">
							
							<a href="home"><img src="assets/img/main_logo.png" alt="main-logo"></a>

							<section class="pull-right mobile-menu-icon">
								<span class="icon-bar"></span>
				           		 <span class="icon-bar"></span>
				            	<span class="icon-bar"></span>
							</section>
							

						</button>
					</div>
					<!-- /Collapse navigation for mobile -->
					<!-- Navigation -->
					<div class="main-navigation-collapse collapse navbar-collapse">

						<a class="brand logomain" href="home">mysara student management system</a>

						<!-- Navigation items -->
						<ul class="nav navbar-nav pull-right">

							<?php $user = new User(); if( $user->coordinator() || $user->lecturer() ){ ?>

							<!-- Dropdown navigation items for student-->
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="elusive icon-adult"></span>Students<b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li><a href="student?action=add"><span class="elusive icon-plus"></span> Add Student</a></li>
									<li><a href="student?action=edit"><span class="elusive icon-edit"></span> Edit Student</a></li>
								</ul>
							</li>

							<!-- Dropdown navigation items for students-->
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="elusive icon-adult"></span>Lecturers<b class="caret"></b></a>
								<ul class="dropdown-menu">

									<li><a href="lecturer?action=add"><span class="elusive icon-plus"></span> Add Lecturer</a></li>
									<li><a href="lecturer?action=all"><span class="elusive icon-circle-arrow-right"></span> Get All Lecturers</a></li>
								</ul>
							</li>

							<!-- Dropdown navigation items for batches-->
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="elusive icon-group"></span>Batches<b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li><a href="batch?action=add"><span class="elusive icon-plus"></span> Add Batch</a></li>
									<li><a href="batch?action=all"><span class="elusive icon-group"></span> Get All Batches</a></li>
								</ul>
							</li>

							<?php } if( $user->student()){ ?>

							<li><a href="student?action=editMe"><span class="elusive icon-edit"></span> Edit Me</a></li>

							<?php } ?>

							<!-- /Dropdown navigation items for logged in users-->
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="elusive icon-user"></span><?php echo $user->data()->username; ?><b class="caret"></b></a>
								<ul class="dropdown-menu">
								<li><a href="member?action=settings"><span class="elusive icon-cog"></span> Settings</a></li>
								<li><a href="member?action=logout"><span class="elusive icon-eject"></span> Logout</a></li>
								</ul>
							</li>
						</ul>
						<!-- /Navigation items -->

					</div>
					<!-- /Navigation -->

					</div>
					</div>
				</nav>
				<!-- /Main navigation -->
</header>
	<!-- /Main page header -->

<div class="container my-maincontainer" role="main">