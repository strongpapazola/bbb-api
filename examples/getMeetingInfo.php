<?php

require_once('../includes/bbb-api.php');


$bbb = new BigBlueButton();

$infoParams = array(
	'meetingId' => '1234', 		// REQUIRED - We have to know which meeting.
	'password' => 'mp',			// REQUIRED - Must match moderator pass for meeting.

);

$itsAllGood = true;
try {
	$result = $bbb->getMeetingInfoWithXmlResponseArray($infoParams);
} catch (Exception $e) {
	echo 'Caught exception: ', $e->getMessage(), "\n";
	$itsAllGood = false;
}

if ($itsAllGood == true) {
	if ($result == null) {
		echo "Failed to get any response. Maybe we can't contact the BBB server.";
	} else {
		echo json_encode($result);
		if (!isset($result['messageKey'])) {
//			echo "<p>Meeting info was found on the server.</p>";
		} else {
//			echo "<p>Failed to get meeting info.</p>";
		}
	}
}
