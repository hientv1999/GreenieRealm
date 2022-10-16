char_uuid = "beb5483e-36e1-4688-b7f5-ea07361b26a8"
char_uuid2 = "42eab6ec-829e-4d99-b48f-81481d077948"
#order: SEND (OTP.address) sensorName location
#find SSID and Pass
import subprocess
def getWiFi():
    with open('../../../../../../etc/wpa_supplicant/wpa_supplicant.conf', 'r') as file:
        data = file.readlines()
        i = 0
        while data[i].split('=')[0] != "\tssid":
            i +=1
        ssid = data[i].split('"')[1]
        psk = data[i+1].split('"')[1]
        return ssid, psk
#find local IP of host
import netifaces as ni
def getIP():   
    ni.ifaddresses('wlan0')
    ip = ni.ifaddresses('wlan0')[2][0]['addr']
    return ip
#find local timezone
import time
from datetime import datetime
def getOffset():
    ts = time.time()
    utc_offset = (datetime.fromtimestamp(ts) - datetime.utcfromtimestamp(ts)).total_seconds()
    return str(utc_offset)
#find address of Agrismart by Bleak
from bleak import BleakScanner, BleakClient
import asyncio
import sys, random

async def main():
    start = time.time()
    #SPREAD OTP MODE
    if (sys.argv[1] == "OTP"):
        #find address of all devices named "Unnamed Agrismart". allow 60 secs
        addresses = []
        devices = await BleakScanner.discover()
        for d in devices:
            if (d.name == "Unnamed Agrismart"):
                addresses.append(d.address) 
        #throw duplicated set
        addresses = list(set(addresses))
        #send OTP to all devices. each device gets different OTP. allow 2 minutes max
        OTP = random.sample(range(0, 9999), len(addresses))
        fail = 0
        return_string = ""
        for i in range(len(addresses)):
            client = BleakClient(addresses[i])
            start = time.time()
            while time.time() - start < float(60):
                try:
                    await client.connect()
                    await client.write_gatt_char(char_uuid,bytearray(str(OTP[i]),"utf-8"),response=True)
                    await client.disconnect()
                except Exception:
                    continue
                else:
                    print (str(OTP[i]) + "." + addresses[i])
                    break
            if time.time() - start >= float(60):
                fail += 1
        if (fail == len(addresses)):
            sys.exit()
    #SEND DATA MODE
    elif (sys.argv[1] == "SEND" and sys.argv[2] != ""):
        start = time.time()
        OTP = sys.argv[2].split(".")[0]
        address = sys.argv[2].split(".")[1]
        client = BleakClient(address)
        start = time.time()
        return_msg = ""
        while  time.time() - start < float(60):
            try:
                await client.connect()
                await client.write_gatt_char(char_uuid,bytearray("validate","utf-8"),response=True)
                await asyncio.sleep(1)
                validate_OTP = await client.read_gatt_char(char_uuid2)
                validate_OTP = validate_OTP.decode()
                if validate_OTP == OTP:
                    ssid, psk = getWiFi()
                    #ssid
                    await client.write_gatt_char(char_uuid,bytearray(ssid,"utf-8"),response=True)
                    await asyncio.sleep(0.1)
                    #psk
                    await client.write_gatt_char(char_uuid,bytearray(psk,"utf-8"),response=True)
                    await asyncio.sleep(0.1)
                    #location
                    location = sys.argv[4]
                    await client.write_gatt_char(char_uuid,bytearray(location,"utf-8"),response=True)
                    await asyncio.sleep(0.1)
                    #sensor name
                    sensorName = sys.argv[3]
                    await client.write_gatt_char(char_uuid,bytearray(sensorName,"utf-8"),response=True)
                    await asyncio.sleep(0.1)
                    #IP
                    ip = getIP()
                    await client.write_gatt_char(char_uuid,bytearray(ip,"utf-8"),response=True)
                    await asyncio.sleep(0.1)
                    #timezone
                    tz = getOffset()
                    await client.write_gatt_char(char_uuid,bytearray(tz,"utf-8"),response=True)
                    await asyncio.sleep(0.1)
                    #get return message
                    while (1>0):
                        try:
                            return_msg = await client.read_gatt_char(char_uuid2)
                            if (return_msg != b'\x00'):
                                return_msg = return_msg.decode()
                                break
                        except Exception:
                            None
                        else:
                            continue
                else:
                    print("Unauthorized device")
                    await client.disconnect()
                    sys.exit()
            except Exception:
                None
            else:
                break
        if time.time() - start >= float(60):
            print("Cannot connect to Agrismart")
        else:
            if (return_msg != "Setup success"):
                print(return_msg)
asyncio.run(main())




