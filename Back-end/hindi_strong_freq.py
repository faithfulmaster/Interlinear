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

bib_id_hist_word = Table('bib_id_hist_word', metadata, autoload=True)

f = io.open("freq_table.txt", mode = "r", encoding = "utf-8")
fc =f.readlines()
freq_table = []
final_table = []
intermediate_array = []
outfile = io.open("hindi_strong_freqtable.txt", mode = "w", encoding = "utf-8")

for line in fc:
	words = line.strip().split("\t")
	freq_table.append((words[1], int(words[2]), int(words[0]))) 

freq_table.sort(reverse=True)

prev_word = 0
for i in freq_table:
	if i[0] == prev_word:
		intermediate_array.append(i[2])
	else:
		final_table.append((prev_word, intermediate_array))
		# outfile.write(str(prev_word) + " " + str(intermediate_array) + "\n")
		intermediate_array = []
		intermediate_array.append(i[2])

	prev_word = i[0]

final_table.append((prev_word, intermediate_array))
# outfile.write(str(prev_word) + " " + str(intermediate_array) + "\n")

# print (final_table)
infile = io.open("stemmed_bible.txt", mode = "r", encoding= "utf-8")
insert_content = bib_id_hist_word.insert()

st_bible = infile.readlines()

infile.close()
bib_dict = {}
for i, d in final_table:
	bib_dict[i] = d

for items in st_bible:
	item = items.split("\t")
	st_word = item[-1].strip("\n")
	if st_word != "":
		try:
			word = bib_dict[st_word]
			if word[0] != 3588:
				item.append(word[0])
				insert_content.execute(id=item[0], pos=item[1], original=item[2], stem=item[3], strong=item[4])
				print(item)
			else:
				item.append(word[1])
				insert_content.execute(id=item[0], pos=item[1], original=item[2], stem=item[3], strong=item[4])
				print(item)
		except:
			insert_content.execute(id=item[0], pos=item[1], original=item[2], stem=item[3])
			print (item)
	else:
		insert_content.execute(id=item[0], pos=item[1], original=item[2])
		print (item)

print ("Table populated !")