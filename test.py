from datetime import datetime, timedelta, timezone
d = datetime.now(timezone.utc).astimezone()
utc_offset = int(d.utcoffset().total_seconds())
print(utc_offset)