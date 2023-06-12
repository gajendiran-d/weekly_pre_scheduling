<?php
	if(ISSET($_POST['add'])){
		if(file_exists("team.xml")){
			$teams = simplexml_load_file('team.xml');
			$randId = rand(100000,999999).uniqid();
			$team = $teams->addChild('team');
			$team->addChild('teamid', $randId);
			$team->addChild('teamname', $_POST['teamName']);
			file_put_contents('team.xml', $teams->asXML());
			header('location:team.php');
		}	
	}
