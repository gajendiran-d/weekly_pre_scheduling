<?php
if (isset($_POST['add'])) {
	if (file_exists("plan.xml")) {
		$weekId = $_POST['weekId'];
		$plans = simplexml_load_file('plan.xml');
		$deletePlans = $plans->xpath("//plan[weekid='$weekId']");
		if (!empty($deletePlans)) {
			foreach ($deletePlans as $element) {
				unset($element[0]);
			}
			$plans->asXML('plan.xml');
		}
		for ($i = 0; $i < count($_POST['empData']); $i++) {
			$randId = rand(100000, 999999) . uniqid();
			$weekDate = $_POST['weekDate'];
			$empId = $_POST['empId'];
			$empName = $_POST['empName'];
			$teamId = $_POST['teamId'];
			$teamName = $_POST['teamName'];
			$empData = $_POST['empData'];
			$plan = $plans->addChild('plan');
			$plan->addChild('planid', $randId);
			$plan->addChild('weekid', $weekId);
			$plan->addChild('weekdate', $weekDate[$i]);
			$plan->addChild('empid', $empId[$i]);
			$plan->addChild('empname', $empName[$i]);
			$plan->addChild('teamid', $teamId[$i]);
			$plan->addChild('teamname', $teamName[$i]);
			$plan->addChild('empdata', $empData[$i]);
		}
		file_put_contents('plan.xml', $plans->asXML());
		header('location:plan.php?week=null&from=null&to=null');
	}
}
