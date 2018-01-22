# -*- coding: utf-8 -*-
from sqlalchemy import *
import os
import io
import sys
import re
import glob
import codecs

db = create_engine('mysql://root@127.0.0.1:3306/interlinear_fulldb?charset=utf8')
db.echo = False  # Try changing this to True and see what happens
metadata = MetaData(db)

# wh_word = Table('wh_word', metadata, autoload=True)
# wh_word_rows = wh_word.select(wh_word).execute()

f = io.open("freq_table.txt", mode = "r", encoding = "utf-8")
fc =f.readlines()
freq_table = []
final_table = []
intermediate_array = []
outfile = io.open("strong_hindi_freqtable.txt",mode = "w", encoding = "utf-8")

for line in fc:
	words = line.strip().split("\t")
	freq_table.append((int(words[0]), int(words[2]), words[1])) 

freq_table.sort(reverse=True)

prev_strong = 0
for i in freq_table:
	if i[0] == prev_strong:
		intermediate_array.append(i[2])
	else:
		final_table.append((prev_strong, intermediate_array))
		# outfile.write(str(prev_strong) + " " + str(intermediate_array) + "\n")
		intermediate_array = []
		intermediate_array.append(i[2])

	prev_strong = i[0]

final_table.append((prev_strong, intermediate_array))
# outfile.write(str(prev_strong) + " " + str(intermediate_array) + "\n")

# print (final_table)
infile = io.open("wh_word_new.csv", mode = "r", encoding= "utf-8")

wh_word_hi = Table('wh_word_hi', metadata, autoload=True)
insert_content = wh_word_hi.insert()

st_bible = infile.readlines()

infile.close()
bib_dict = {}
for i, d in final_table:
	bib_dict[i] = d

# print bib_dict[11][0]

for items in st_bible:
	item = items.split(",")
	st_word = item[3].strip("\n")
	try:
		word = bib_dict[int(st_word)]
		item.append(word[0])
		insert_content.execute(ayat=item[0], pos=item[1], kata=item[2], strong=item[3], hindi=word[0])
		print(item)
	except Exception as e:
		insert_content.execute(ayat=item[0], pos=item[1], kata=item[2], strong=item[3])
		print(e.message) 
		print (item)


print ("Table populated !")