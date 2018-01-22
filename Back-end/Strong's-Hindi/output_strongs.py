# -*- coding: utf-8 -*-
from sqlalchemy import *
import codecs

db = create_engine('mysql://root@127.0.0.1:3306/interlinear_fulldb?charset=utf8')
db.echo = False  # Try changing this to True and see what happens
metadata = MetaData(db)

outfile = open("strongsnt.txt", "w")


wh_word = Table('wh_word', metadata, autoload=True)
wh_word = wh_word.select(wh_word).execute()
prev_ayat = ""
total_records = 0
rowid = 23145

for row in wh_word:
	total_records += 1
	if prev_ayat == row.ayat:
		outfile.write(" " + str(row.strong))
	else:
		rowid += 1
		if row.ayat != rowid:
			print rowid
			rowid += 1
		outfile.write("\n" + str(row.ayat) + " "  + str(row.strong))
	prev_ayat = row.ayat

print total_records
outfile.close()

print "Done !"

# 23537
# 23722
# 23739
# 23933
# 24480
# 24583
# 24585
# 24667
# 24855
# 25688
# 25953
# 26215
# 27214
# 27477
# 27777
# 27929
# 28361