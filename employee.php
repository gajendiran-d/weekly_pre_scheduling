<?php include_once('header.php') ?>
<div class="col-md-3"></div>
<div class="col-md-6 well">
	<h3 class="text-primary">Employee</h3>
	<hr style="border-top:1px dotted #ccc;" />
	<div class="col-md-4">
		<form method="POST" action="employee_add.php">
			<div class="form-group">
				<label>Employee Name</label>
				<input type="text" class="form-control" name="employeeName" required="required" />
			</div>
			<div class="form-group">
				<label>Team</label>
				<select class="form-control" name="teamName" required="required">
					<option value="">Select</option>
					<?php
					$xml = simplexml_load_file('team.xml');
					foreach ($xml->team as $team) {
						echo '<option value=' . $team->teamid . '^^' . urlencode($team->teamname) . '>' . $team->teamname . '</option>';
					}
					?>
				</select>
			</div>
			<div align="right"><button class="btn btn-success" name="add">Add</button></div>
		</form>
	</div>
	<div class="col-md-8">
		<table class="table table-bordered">
			<thead class="alert-info">
				<tr>
					<th>Employee Name</th>
					<th>Team Name</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$xml = simplexml_load_file('employee.xml');
				foreach ($xml->employee as $employee) {
					echo '<tr><td>' . $employee->employeename . '</td><td>' . $employee->teamname . '</td></tr>';
				}
				?>
			</tbody>
		</table>
	</div>
</div>
<?php include_once('footer.php') ?>