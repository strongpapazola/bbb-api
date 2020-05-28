<?php

require_once('../includes/bbb-api.php');

$bbb = new BigBlueButton();

$endParams = array(
	'meetingId' => '1234', 			// REQUIRED - We have to know which meeting to end.
	'password' => 'mp',				// REQUIRED - Must match moderator pass for meeting.

);

// Get the URL to end a meeting:
$itsAllGood = true;
try {
	$result = $bbb->endMeetingWithXmlResponseArray($endParams);
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
			echo "<p>Meeting succesfullly ended.</p>";
		} else {
			echo "<p>Failed to end meeting.</p>";
		}
	}
}
