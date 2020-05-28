<?php

require_once('../includes/bbb-api.php');

$bbb = new BigBlueButton();

$recordingsParams = array(
	'meetingId' => '', 			// OPTIONAL - comma separate if multiples

);

$itsAllGood = true;
try {
	$result = $bbb->getRecordingsWithXmlResponseArray($recordingsParams);
} catch (Exception $e) {
	echo 'Caught exception: ', $e->getMessage(), "\n";
	$itsAllGood = false;
}

if ($itsAllGood == true) {
	if ($result == null) {
		echo "Failed to get any response. Maybe we can't contact the BBB server.";
	} else {
		var_dump($result);
		if ($result['returncode'] == 'SUCCESS') {
			echo "<p>Meeting info was found on the server.</p>";
		} else {
			echo "<p>Failed to get meeting info.</p>";
		}
	}
}
