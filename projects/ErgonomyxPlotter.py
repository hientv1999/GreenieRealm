import imaplib, email, time, sys, json
from datetime import date, datetime
from dateutil.relativedelta import relativedelta
from email.header import decode_header

# account credentials
username_hash = ['\x1b', '\x00', '\x11', '\x1d', '\x00', '\x17', '_', ']', 'T', 'V', '6', '\x02', '\x1e', '\x08', '\x1d', '\x1f', 'Z', '\x02', '\x01', '\t']
password_hash = ['\x00', '\x11', '\x03', '\x12', '\x15', '\x04', '\x02', '\t', '\x05', '\r', '\x0e', '\x13', '\x07', '\x06', '\x01', '\x16']
username = ""
password = ""
key = sys.argv[1]
for i in range(len(username_hash)):
    username += chr(ord(username_hash[i])^ord(key[i%len(key)]))
for i in range(len(password_hash)):
    password += chr(ord(password_hash[i])^ord(key[i%len(key)]))

# create an IMAP4 class with SSL 
imap = imaplib.IMAP4_SSL("imap.gmail.com")
# authenticate
imap.login(username, password)
imap.list()
# get email from Megan Hand since 20-Jan-2022
imap.select("Inbox")
# just return value before this start time
time_range = sys.argv[2]
if time_range == "Month":
    start_time = date.today().replace(day=1)
elif time_range == "Quarter":
    start_time = date.today().replace(day=1, month = date.today().month - date.today().month % 3 + 1)
elif time_range == "Year":
    start_time = date.today() - relativedelta(day = 1, month = 1)
if start_time < datetime.strptime("20-01-2022", "%d-%m-%Y").date():
    start_time = datetime.strptime("20-01-2022", "%d-%m-%Y").date()
# start searching for emails
result, data = imap.search(None, '(FROM "Megan Hands" SINCE {0})'.format( start_time.strftime("%d-%b-%Y")))
ids = data[0] # data is a list.
id_list = ids.split() # ids is a space separated string
# limit emails
desk_sold_amount, bike_sold_amount, timestamp = [], [], []

for i in range(len(id_list)-1, -1, -1):
    # fetch the email message by ID
    res, msg = imap.fetch(id_list[i], "(RFC822)")
    for response in msg:
        if isinstance(response, tuple):
            # parse a bytes email into a message object
            msg = email.message_from_bytes(response[1])
            for part in msg.walk():
                # extract content type of email
                content_type = part.get_content_type()
                if content_type == "text/plain":
                    # get the email body
                    body = part.get_payload(decode=True).decode()
                    # print text/plain emails
                    desk_index = body.find("desk")
                    bike_index = body.find("bike")
                    #find location of desk sold amount
                    i = desk_index - 2
                    while (i>0):
                        if (body[i] == " "):
                            break
                        else: 
                            i-=1
                    desk_numeric_filter = filter(str.isdigit, body[i+1:desk_index-1])
                    #find location of bike sold amount
                    i = bike_index - 2
                    while (i>0):
                        if (body[i] == " "):
                            break
                        else: 
                            i-=1
                    bike_numeric_filter = filter(str.isdigit, body[i+1:bike_index-1])
                    #save desk/bike sold amount
                    try:
                        desks = int("".join(desk_numeric_filter))
                        bikes = int("".join(bike_numeric_filter))
                        desk_sold_amount.append(desks)
                        bike_sold_amount.append(bikes)
                        timestamp.append(email.utils.parsedate_to_datetime(msg['Date']).replace(tzinfo=None))
                    except:
                        None                                
# close the connection and logout
imap.close()
imap.logout()

print(json.dumps([{"desk": str(desk_sold), "bike": str(bike_sold), "timestamp": str(date)} for desk_sold, bike_sold, date in zip(desk_sold_amount, bike_sold_amount, timestamp)]))
