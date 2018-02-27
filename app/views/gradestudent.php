<?php include 'header.php'; include 'student_settings_nav.php';?>
<br>

	<div class="row">

		<article class="col-sm-6">

				<div class="data-block shadow-block">
					<header>
						<h2 class="pull-left">Student Subjects</h2>
						<span class="pull-right sub-year-count">First Year</span>
					</header>
					<section class="lecture-details">
						<ul>
							<?php foreach ($data as $row) { if ($row->sub_year == 1) {?>

							<li><a id="<?php echo $row->sub_id; ?>" class="grade" rel="leanModal" href="#grade"><?php echo $row->sub_name; ?><i class="elusive icon-edit pull-right"></i></a></li>

							<?php }	} ?>
						</ul>
					</section>
				</div>
		</article>

		<article class="col-sm-6">
				<div class="data-block shadow-block">
					<header>
						<h2 class="pull-left">Student Subjects</h2>
						<span class="pull-right sub-year-count">Second Year</span>
					</header>
					<section class="lecture-details">
						<ul>
							<?php foreach ($data as $row) { if ($row->sub_year == 2) {?>

							<li><a id="<?php echo $row->sub_id; ?>" class="grade" rel="leanModal" href="#grade"><?php echo $row->sub_name; ?><i class="elusive icon-edit pull-right"></i></a></li>

							<?php }	} ?>
						</ul>
					</section>
				</div>

		</article>

	</div>
	<br><br>


	<div id="grade">

		<form action="#" method="POST" id="grade_form">
			<header>
				<h2>Choose Grade</h2>
				<i class="modelcolse elusive icon-remove pull-right"></i>
			</header>
			<section>
				<ul id="gradings">
					
				</ul>
			</section>
			<div id="errors_bg_five"></div>

			<div id="buttons"></div>
		</form>

	</div>

	<script type="text/javascript">

		$(function() {

			function getUrlVars() {
			    var vars = {};
			    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
			        vars[key] = value;
			    });
			    return vars;
			}

			function updatebut() {
				$("#buttons").empty();
				$("#buttons").append('<button id="update" type="submit">Update</button>');
			}

			function update(subid, grade_id) {
				$('#update').click(function(e){
					e.preventDefault();
					$("#errors_bg_five").hide().html("<p>Please wait</p>").fadeIn('fast');
					return $.ajax({
		  				type: 'post',
						url: 'student?action=updateGrading&gid='+grade_id+'&subid='+subid+'&sid='+getUrlVars()['sid'],
						 data:$("#grade_form").serialize(),
						success:function(data){
							$("#errors_bg_five").hide().html(data).fadeIn('fast');
						},
						error: function(XMLHttpRequest, textStatus, errorThrown) {
							$("#errors_bg_five").hide().html("<p>Server Error Occured</p>").fadeIn('fast');
						}
					});
				});
			}

			var subid, grade_id;

			$('.grade').click(function(){
				   $("#errors_bg_five").empty();
				   subid = $(this).attr('id');
				   $.ajax({
				        type:"post",
				        url:'student?action=loadGrading&sid='+getUrlVars()['sid']+'&subid='+subid,
				        success:function(data){
				        	$("#gradings").empty();
				        	data =  $.parseJSON(data);

				        	if (Object.keys(data).length == 0) {
				        		$('#gradings').append('<li><div class="radio styled-radio"><label><input type="radio" name="grade" id="optionsRadios1" value="D" data-label="DISTINCTION"></label></div></li><li><div class="radio styled-radio"><label><input type="radio" name="grade" id="optionsRadios2" value="M" data-label="MERIT"></label></div></li><li><div class="radio styled-radio"><label><input type="radio" name="grade" id="optionsRadios3" value="P" data-label="PASS"></label></div></li><li><div class="radio styled-radio"><label><input type="radio" name="grade" id="optionsRadios4" value="R" data-label="RE SUBMIT"></label></div></li>');
				        		$("#buttons").empty();
				        		$("#buttons").append('<button id="submit" type="submit">Submit</button>');

				        		$('#submit').click(function(e){
									 e.preventDefault();

									 $("#errors_bg_five").hide().html("<p>Please wait</p>").fadeIn('fast');

									  $.ajax({
								          type:"post",
								          url:'student?action=submitGrading&sid='+getUrlVars()['sid']+'&subid='+subid,
								          data:$("#grade_form").serialize(),
								          success:function(data){
								            $("#errors_bg_five").hide().html(data).fadeIn('fast');
								          },
								          error: function(XMLHttpRequest, textStatus, errorThrown) {
								            $("#errors_bg_five").hide().html("<p>Server Error Occured</p>").fadeIn('fast');
								         }
								      });

								});
				        	}
				        	 else {
				        		if(data[0].grade == "D"){
				        			$('#gradings').append('<li><div class="radio styled-radio"><label><input checked type="radio" name="grade" id="optionsRadios1" value="D" data-label="DISTINCTION"></label></div></li><li><div class="radio styled-radio"><label><input type="radio" name="grade" id="optionsRadios2" value="M" data-label="MERIT"></label></div></li><li><div class="radio styled-radio"><label><input type="radio" name="grade" id="optionsRadios3" value="P" data-label="PASS"></label></div></li><li><div class="radio styled-radio"><label><input type="radio" name="grade" id="optionsRadios4" value="R" data-label="RE SUBMIT"></label></div></li>');
				        			grade_id = data[0].grade_id;
				        			updatebut();
				        			update(subid, grade_id);

					        	} else if(data[0].grade == "M") {
					        		$('#gradings').append('<li><div class="radio styled-radio"><label><input type="radio" name="grade" id="optionsRadios1" value="D" data-label="DISTINCTION"></label></div></li><li><div class="radio styled-radio"><label><input checked type="radio" name="grade" id="optionsRadios2" value="M" data-label="MERIT"></label></div></li><li><div class="radio styled-radio"><label><input type="radio" name="grade" id="optionsRadios3" value="P" data-label="PASS"></label></div></li><li><div class="radio styled-radio"><label><input type="radio" name="grade" id="optionsRadios4" value="R" data-label="RE SUBMIT"></label></div></li>');
					        		grade_id = data[0].grade_id;
					        		updatebut();
									update(subid, grade_id);
								
					        	} else if (data[0].grade == "P") {
					        		$('#gradings').append('<li><div class="radio styled-radio"><label><input type="radio" name="grade" id="optionsRadios1" value="D" data-label="DISTINCTION"></label></div></li><li><div class="radio styled-radio"><label><input type="radio" name="grade" id="optionsRadios2" value="M" data-label="MERIT"></label></div></li><li><div class="radio styled-radio"><label><input checked type="radio" name="grade" id="optionsRadios3" value="P" data-label="PASS"></label></div></li><li><div class="radio styled-radio"><label><input type="radio" name="grade" id="optionsRadios4" value="R" data-label="RE SUBMIT"></label></div></li>');	
					        		grade_id = data[0].grade_id;
					        		updatebut();
					        		update(subid, grade_id);
					        	} else if (data[0].grade == "R") {
					        		$('#gradings').append('<li><div class="radio styled-radio"><label><input type="radio" name="grade" id="optionsRadios1" value="D" data-label="DISTINCTION"></label></div></li><li><div class="radio styled-radio"><label><input type="radio" name="grade" id="optionsRadios2" value="M" data-label="MERIT"></label></div></li><li><div class="radio styled-radio"><label><input type="radio" name="grade" id="optionsRadios3" value="P" data-label="PASS"></label></div></li><li><div class="radio styled-radio"><label><input checked type="radio" name="grade" id="optionsRadios4" value="R" data-label="RE SUBMIT"></label></div></li>');	
					        		grade_id = data[0].grade_id;
					        		updatebut();
					        		update(subid, grade_id);
					        	}
				        	}

				        	$('.styled-checkbox input, .styled-radio input').prettyCheckable();
				        },
				        error: function(XMLHttpRequest, textStatus, errorThrown) {
				        	$("#errors_bg_five").hide().html("<p>Server Error Occured</p>").fadeIn('fast');
				     	}
				  });
			});
		});


		$("a[rel*=leanModal]").leanModal({ closeButton: ".modelcolse" });

	</script>

<?php include 'footer.php'; ?>