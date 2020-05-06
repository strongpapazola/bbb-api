import sys
import json
import requests
from os import system

def curl(arg = ''):
	url = "https://<url_main>/bbb-api/examples/"+arg
	payload = {}
	files = []
	headers = {
	  'X-API-KEY': ''
	}
	response = requests.request("GET", url, headers=headers, data = payload, files = files).text
	return response

def getMeetings():
	res = json.loads(curl('getMeetings.php'))
	#res = json.dumps(parsed_json, indent=4, sort_keys=Tru	e)
	ktiga = len(res) - 3
	if ktiga == 0:
		print('{}')
	for i in range(0, ktiga):
		i = res[str(i)]
		result = '{"id": "%s", "name": "%s", "moderatorPw": "%s", "attendeePw": "%s"}' % (i['meetingId']['0'],i['meetingName']['0'],i['moderatorPw']['0'],i['attendeePw']['0'],)
		print(result)

def createMeeting():
	print('Create Meeting !')
	print('Usage : "{"id": "idroom", "name": "nameroom", "pass": "passroom"}"')
	print('Example : "{"id": "123", "name": "123", "pass": "123"}"')
	a = str(input('$ '))
	a = json.loads(a)
	res = json.loads(curl('createMeeting.php?meetingId=%s&meetingName=%s&moderatorPw=%s' % (a['id'], a['name'], a['pass'],)))
	print(res['returncode']['0'])
	try:
		print(res['message']['0'])
	except:
		a = res['message']

def endMeeting():
	print('pengembangan')

def getJoinAsModerator():
	print('Get Join As Moderator !')
	print('Usage : "{"id": "idroom", "name": "username", "pass": "passroom"}"')
	print('Example : "{"id": "123", "name": "123", "pass": "123"}"')
	a = str(input('$ '))
	a = json.loads(a)
	res = curl('getJoinMeetingUrlModerator.php?meetingId=%s&username=%s&password=%s' % (a['id'], a['name'], a['pass'],))
	print(res)

def getMeetingInfo():
	print('Get Meeting Info !')
	print('Usage : "{"id": "idroom", "pass": "passroom"}"')
	print('Example : "{"id": "123", "pass": "123"}"')
	a = str(input('$ '))
	a = json.loads(a)
	res = curl('getMeetingInfo.php?meetingId=%s&password=%s' % (a['id'], a['pass'],))
#	res = json.dumps(res, indent=4, sort_keys=True)
	print(res.json())

def banner():
	print('=== Meeting Master ===')
	print('1. Get Meetings')
	print('2. Create Meeting')
	print('3. End Meeting')
	print('4. Get Join As Moderator')
	print('5. Get Meeting Info')
	print('6. Exit')
	print('')
	print('Usage : python3 %s <number>' % (sys.argv[0],))

def show_menu(a = ''):
	if a == '1':
		getMeetings()
	elif a == '2':
		createMeeting()
	elif a == '3':
		endMeeting()
	elif a == '4':
		getJoinAsModerator()
	elif a == '5':
		getMeetingInfo()
	elif a == '6':
		exit()



if "__main__" == __name__:
	try:
		show_menu(str(sys.argv[1]))
	except:
		banner()

#print(res['0'])