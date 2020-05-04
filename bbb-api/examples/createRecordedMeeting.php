<?php
require_once('../includes/bbb-api.php');


$bbb = new BigBlueButton();

$creationParams = array(
	'meetingId' => '12345', 				// REQUIRED
	'meetingName' => 'Test Recorded Meeting Name', 	// REQUIRED
	'attendeePw' => 'ap',
	'moderatorPw' => 'mp',
	'welcomeMsg' => '',
	'dialNumber' => '',
	'voiceBridge' => '',
	'webVoice' => '',
	'logoutUrl' => '',
	'maxParticipants' => '-1',
	'record' => 'true',
	'duration' => '5',
);

$itsAllGood = true;
try {
	$result = $bbb->createMeetingWithXmlResponseArray($creationParams);
} catch (Exception $e) {
	echo 'Caught exception: ', $e->getMessage(), "\n";
	$itsAllGood = false;
}

if ($itsAllGood == true) {
	if ($result == null) {
		echo "Failed to get any response. Maybe we can't contact the BBB server.";
	} else {
		echo json_encode($result);
		if ($result['returncode'] == 'SUCCESS') {
			echo "<p>Meeting succesfullly created.</p>";
		} else {
			echo "<p>Meeting creation failed.</p>";
		}
	}
}
