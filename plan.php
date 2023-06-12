<?php include_once('header.php') ?>
<div class="col-md-1"></div>
<div class="col-md-10 well">
	<h3 class="text-primary">Plan for the week</h3>
	<hr style="border-top:1px dotted #ccc;" />
	<div class="col-md-12">
		<form method="POST" action="plan_add.php">
			<div class="form-group">
				<select class="form-control" name="weekName" required="required" onchange="weekChange(this.value);">
					<option value="">Select</option>
					<?php
					$xml = simplexml_load_file('week.xml');
					foreach ($xml->week as $week) {
						echo '<option value="';
						echo $week->weekid . '^^^' . $week->fromdate . '^^^' . $week->todate;
						echo '"';
						if (($_REQUEST['week']) == ($week->weekid)) {
							echo "selected";
						}
						echo '>';
						echo $week->weekname;
						echo '</option>';
					}
					?>
				</select>
			</div>
			<?php
			if (isset($_REQUEST['week']) && $_REQUEST['week'] != 'null') {
				$weekId = $_REQUEST['week'];
				$date2 = new DateTime($_REQUEST['to']);
				$date2->modify('+1 day');
				$period = new DatePeriod(
					new DateTime($_REQUEST['from']),
					new DateInterval('P1D'),
					$date2
				);
				$xmlTeam = simplexml_load_file('team.xml');
				$xmlEmployee = simplexml_load_file('employee.xml');
				$xmlPlan = simplexml_load_file('plan.xml');
				$teamArr = [];
				foreach ($xmlTeam->team as $team) {
					$teamId = (string) $team->teamid;
					$teamName = (string) $team->teamname;
					$empArr = [];
					$matchingEmployees = $xmlEmployee->xpath("//employee[teamid='$teamId']");
					if (!empty($matchingEmployees)) {
						foreach ($matchingEmployees as $matchingEmployee) {
							$empId = (string) $matchingEmployee->employeeid;
							$empName = (string) $matchingEmployee->employeename;
							$weekArr = [];
							foreach ($period as $key => $value) {
								$weekDate = $value->format('Y-m-d');
								$matchingPlans = $xmlPlan->xpath("//plan[weekid='$weekId'][weekdate='$weekDate'][empid='$empId'][teamid='$teamId']");
								if (!empty($matchingPlans)) {
									$weekArr[$weekDate] = (string) $matchingPlans[0]->empdata;
								} else {
									$weekArr[$weekDate] = '';
								}
							}
							array_push($empArr, array('id' => $empId, 'name' => $empName, 'week' => $weekArr));
						}
					}
					array_push($teamArr, array('id' => $teamId, 'name' => $teamName, 'employee' => $empArr));
				}
				// echo '<pre>', print_r($teamArr);
			?>
				<table class="table table-bordered" style="background-color: #fff;">
					<thead>
						<tr>
							<th>#</th>
							<?php
							foreach ($period as $key => $value) {
								echo '<th>';
								echo $value->format('Y-m-d');
								echo '</th>';
							}
							?>
							<th>Total</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$i = $k = $m = 0;
						foreach ($teamArr as $teamValue) {
							$i++;
							echo '<tr>';
							echo '<td class="warning">';
							echo $teamValue['name'];
							echo '</td>';
							$j = 0;
							foreach ($period as $key => $value) {
								$j++;
								$teamRowId = $teamValue['id'] . $j;
								echo '<td class="warning">';
								echo '<input type="text" name="team_data[]" id="team_col_total' . $teamRowId . '"  class="form-control" value="" readonly />';
								echo '</td>';
							}
							echo '<td class="warning">';
							echo '<input type="text" name="team_total[]" id="team_row_total' . $i . '" class="form-control" value="" readonly />';
							echo '</td>';
							echo '</tr>';
							foreach ($teamValue['employee'] as $employeeValue) {
								$k++;
								echo '<tr>';
								echo '<td>';
								echo $employeeValue['name'];
								echo '</td>';
								$l = 0;
								foreach ($employeeValue['week'] as $key => $value) {
									$l++;
									$teamColId = $teamValue['id'] . $l;
									echo '<td>';
									echo '<input type="time" name="empData[]" id="emp_data' . $k . '" row-id=' . $k . ' col-id=' . $l . ' team-row-id=' . $i . ' team-col-id=' . $teamColId . ' class="form-control emp_data emp_row' .  $k . ' emp_col' . $l . ' team_row' .  $i . ' team_col' .  $teamColId . '" value="' . $value . '" required />';
									echo '<input type="hidden" name="weekDate[]" value="' . $key . '" />';
									echo '<input type="hidden" name="empName[]" value="' . $employeeValue['name'] . '" />';
									echo '<input type="hidden" name="empId[]" value="' . $employeeValue['id'] . '" />';
									echo '<input type="hidden" name="teamId[]" value="' . $teamValue['id'] . '" />';
									echo '<input type="hidden" name="teamName[]" value="' . $teamValue['name'] . '" />';

									echo '</td>';
								}
								echo '<td>';
								echo '<input type="text" name="emp_row_total[]" id="emp_row_total' . $k . '"  class="form-control" value="" readonly />';
								echo '</td>';
								echo '</tr>';
							}
						}
						?>
						<tr>
							<td class="warning"><b>Total</b></td>
							<?php
							foreach ($period as $key => $value) {
								$m++;
								echo '<td class="warning">';
								echo '<input type="text" name="emp_col_total[]" id="emp_col_total' . $m . '"  class="form-control" value="" readonly />';
								echo '</td>';
							}
							?>
							<td class="warning">
								<input type="hidden" name="weekId" value="<?php echo $_REQUEST['week']; ?>" />
								<input type="text" name="total" id="total" class="form-control" value="" readonly />
							</td>
						</tr>
					</tbody>
				</table>
				<div align="right"><button class="btn btn-success" name="add">Add</button></div>
			<?php
			}
			?>
		</form>
	</div>
</div>
<script>
	$(document).ready(function() {
		$(".emp_data").change(function() {
			var id = $(this).attr("row-id");
			var colId = $(this).attr("col-id");
			var teamRowId = $(this).attr("team-row-id");
			var teamColId = $(this).attr("team-col-id");

			var empDataRow = $(".emp_row" + id).map((_, el) => el.value).get();
			var empDataRowCalculation = timeCalculation(empDataRow);
			$("#emp_row_total" + id).val(empDataRowCalculation);

			var empDataCol = $(".emp_col" + colId).map((_, el) => el.value).get();
			var empDataColCalculation = timeCalculation(empDataCol);
			$("#emp_col_total" + colId).val(empDataColCalculation);

			var teamDataRow = $(".team_row" + teamRowId).map((_, el) => el.value).get();
			var teamDataRowCalculation = timeCalculation(teamDataRow);
			$("#team_row_total" + teamRowId).val(teamDataRowCalculation);

			var teamDataCol = $(".team_col" + teamColId).map((_, el) => el.value).get();
			var teamDataColCalculation = timeCalculation(teamDataCol);
			$("#team_col_total" + teamColId).val(teamDataColCalculation);

			var empDataFull = $(".emp_data").map((_, el) => el.value).get();
			var empDataFullCalculation = timeCalculation(empDataFull);
			$("#total").val(empDataFullCalculation);
		});
		// Trigger the change event on page load
  		$(".emp_data").trigger("change");
	});

	function timeCalculation(times) {
		var totalHours = 0;
		var totalMinutes = 0;
		$.each(times, function(index, time) {
			if (time != '') {
				var [hours, minutes] = time.split(":");
				var hoursValue = parseInt(hours, 10);
				var minutesValue = parseInt(minutes, 10);
				totalHours += hoursValue;
				totalMinutes += minutesValue;
			}
		});
		if (totalMinutes >= 60) {
			var extraHours = Math.floor(totalMinutes / 60);
			totalHours += extraHours;
			totalMinutes %= 60;
		}
		return totalHours.toString().padStart(2, "0") + ":" + totalMinutes.toString().padStart(2, "0");
	}

	function weekChange(str) {
		if (str) {
			var week = str.split("^^^");
			var id = week[0];
			var from = week[1];
			var to = week[2];
			window.location = './plan.php?week=' + id + '&from=' + from + '&to=' + to;
		} else {
			window.location = './plan.php?week=null&from=null&to=null';
		}
	}
</script>
<?php include_once('footer.php') ?>
