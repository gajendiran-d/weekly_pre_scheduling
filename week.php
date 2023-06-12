<?php include_once('header.php') ?>
<div class="col-md-3"></div>
<div class="col-md-6 well">
	<h3 class="text-primary">Weeks</h3>
	<hr style="border-top:1px dotted #ccc;" />
	<div class="col-md-4">
		<form method="POST" action="week_add.php">
			<div class="form-group">
				<label>Week Name</label>
				<input type="text" class="form-control" name="weekName" required="required" />
			</div>
			<div class="form-group">
				<label>From Date</label>
				<input type="date" class="form-control" name="fromDate" required="required" />
			</div>
			<div class="form-group">
				<label>To Date</label>
				<input type="date" class="form-control" name="toDate" required="required" />
			</div>
			<div align="right"><button class="btn btn-success" name="add">Add</button></div>
		</form>
	</div>
	<div class="col-md-8">
		<table class="table table-bordered">
			<thead class="alert-info">
				<tr>
					<th>Week Name</th>
					<th>From Date</th>
					<th>To Date</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$xml = simplexml_load_file('week.xml');
				foreach ($xml->week as $week) {
					echo '<tr><td>' . $week->weekname . '</td><td>' . $week->fromdate . '</td><td>' . $week->todate . '</td></tr>';
				}
				?>
			</tbody>
		</table>
	</div>
</div>
<?php include_once('footer.php') ?>