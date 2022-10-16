with open('../../../../../../etc/wpa_supplicant/wpa_supplicant.conf', 'r') as file:
    data = file.readlines()
    i = 0
    while data[i].split('=')[0] != "\tssid":
        i +=1
    ssid = data[i].split('"')[1]
    psk = data[i+1].split('"')[1]
    print(ssid)
    print(psk)
