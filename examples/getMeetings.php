<?php

require_once('../includes/bbb-api.php');

$bbb = new BigBlueButton();

$itsAllGood = true;
try {
	$result = $bbb->getMeetingsWithXmlResponseArray();
} catch (Exception $e) {
	echo 'Caught exception: ', $e->getMessage(), "\n";
	$itsAllGood = false;
}

if ($itsAllGood == true) {
	if ($result == null) {
		echo "Failed to get any response. Maybe we can't contact the BBB server.";
	} else {
		if ($result['returncode'] == 'SUCCESS') {
//			echo "<p>We got some meeting info from BBB:</p>";
//			echo json_encode($result);
			echo json_encode($result);
		} else {
			print("Yang didapat: " . $result["returncode"]);
		}
	}
}
