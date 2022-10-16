import time
from datetime import datetime
ts = time.time()
utc_offset = (datetime.fromtimestamp(ts) - datetime.utcfromtimestamp(ts)).total_seconds()
print(int(utc_offset), end='') 