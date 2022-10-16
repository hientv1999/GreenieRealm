import smtplib
import sys
from googletrans import Translator
# init the Google API translator
translator = Translator()
#1st = username
#2nd = email
#3rd = hash
#4th = language
#5th = link http
#below to setup email login
server=smtplib.SMTP('smtp.gmail.com', 587)
server.starttls()
username = 'greenierealm@gmail.com'
password = 'idxlhdbfgegzyosx'
server.login(username,password)

#below to prepare customer info
link = str(sys.argv[5])
language = str(sys.argv[4])
hash_num = str(sys.argv[3])
sendto = str(sys.argv[2])
customerName = str(sys.argv[1])
#gender = 'Mr. '
#if str(sys.argv[2]) == 'Female':
#    gender = 'Mrs. '
# customerName = sendto[0:sendto.index('@')].capitalize()


#below to compose the email in customized language
subject = translator.translate('GreenieRealm - Password Change', dest=language).text
salutation = translator.translate('Hello ' + customerName + ',', dest=language).text 
body = translator.translate('Please follow this link to change your account password: ', dest=language).text + '\n\n' + link + '/change_password.php?username=' + customerName + '&hash=' + hash_num + '\n\n' + translator.translate('Best regards, ', dest=language).text + '\n\nGreenie Realm'
msg = 'From: ' +username+ '\nTo: ' + sendto + '\n'
msg = msg + 'Subject:' + subject + '\n' + salutation + '\n\n' + body
server.sendmail(username,sendto,msg.encode('utf-8'))
server.quit()