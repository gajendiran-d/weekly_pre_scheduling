<?php
	if(ISSET($_POST['add'])){
		if(file_exists("week.xml")){
			$weeks = simplexml_load_file('week.xml');
			$randId = rand(100000,999999).uniqid();
			$week = $weeks->addChild('week');
			$week->addChild('weekid', $randId);
			$week->addChild('weekname', $_POST['weekName']);
			$week->addChild('fromdate', $_POST['fromDate']);
			$week->addChild('todate', $_POST['toDate']);
			file_put_contents('week.xml', $weeks->asXML());
			header('location:week.php');
		}	
	}
