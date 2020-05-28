a='''createMeeting.php
createRecordedMeeting.php
deleteRecordings.php
endMeeting.php
getJoinMeetingUrlAttendee.php
getJoinMeetingUrlModerator.php
getJoinMeetingUrlModeratorRecord.php
getMeetings.php
isMeetingRunning.php
publishRecordings.php
'''

a = a.splitlines()
from os import system
for i in a:
 system('sed -i "s/print_r/echo json_encode/g" %s' % (i,))
