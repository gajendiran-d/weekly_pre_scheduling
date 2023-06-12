<?php
if (isset($_POST['add'])) {
	if (file_exists("employee.xml")) {
		$employees = simplexml_load_file('employee.xml');
		$randId = rand(100000, 999999) . uniqid();
		$teamDetail = explode("^^", $_POST['teamName']);
		$employee = $employees->addChild('employee');
		$employee->addChild('employeeid', $randId);
		$employee->addChild('employeename', $_POST['employeeName']);
		$employee->addChild('teamid', $teamDetail[0]);
		$employee->addChild('teamname', urldecode($teamDetail[1]));
		file_put_contents('employee.xml', $employees->asXML());
		header('location:employee.php');
	}
}
