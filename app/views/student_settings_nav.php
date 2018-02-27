<ul class="breadcrumb">
	<li style="padding:0 10px 0 0"><a href="batch?action=open&id=<?php echo Session::get('batchid'); ?>">BATCH</a></li>
	<li><a href="student?action=show&sid=<?php echo $_GET['sid'];?>"><span class="elusive icon-chevron-right" style="margin-right:3px;"></span> Show</a></li>
	<li><a href="student?action=result&sid=<?php echo $_GET['sid'];?>"><span class="elusive icon-chevron-right" style="margin-right:3px;"></span> Results</a></li>
	<?php $user = new user(); if ($user->coordinator()) {?>
	<li><a href="student?action=liveEdit&sid=<?php echo $_GET['sid'];?>"><span class="elusive icon-chevron-right" style="margin-right:3px;"></span> Edit</a></li>
	<?php } ?>
	<li><a href="student?action=grade&sid=<?php echo $_GET['sid'];?>"><span class="elusive icon-chevron-right" style="margin-right:3px;"></span> Grade</a></li>
</ul>