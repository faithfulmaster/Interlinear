#!/usr/bin/python
# -*- coding: utf-8 -*-
from sqlalchemy import *
import numpy as np

def convertTable2List(t):
	tmp_array = []
	for row in t:
		tmp_array.append(list(row))
	return(tmp_array)

db = create_engine('mysql://root@127.0.0.1:3306/interlinear_fulldb?charset=utf8')
db.echo = False  # Try changing this to True and see what happens
metadata = MetaData(db)

hindi_table = Table('bib_id_hist_word', metadata, autoload=True) # Hindi first table
greek_table = Table('wh_word_hi', metadata, autoload=True) # Greek, second table



for lid in range(23146, 23147):
	hindi_verse = hindi_table.select(hindi_table).where(hindi_table.c.id==lid).execute()
	greek_verse = greek_table.select(greek_table).where(greek_table.c.ayat==lid).execute()

	greek_data_table = np.asarray(convertTable2List(greek_verse))
	hindi_data_table = np.asarray(convertTable2List(hindi_verse))

	confidence_pair1 = []
	final_array = []

# To delete the first row, do this:

# x = numpy.delete(x, (0), axis=0)
# To delete the third column, do this:

# x = numpy.delete(x,(2), axis=1)

	for row in hindi_data_table:
		index = np.where(greek_data_table[...,3] == row[4])[0]
		if np.size(index) != 0:
		 	confidence_pair1.append([lid, int(row[1]), row[2], row[3], index[0]+1, row[4], 1, 1])
		 	del greek_data_table[index]
		else:
			if row[3] != None:
				confidence_pair1.append([lid, int(row[1]), row[2], row[3], 255, "", 0, 0])
			else:
				confidence_pair1.append([lid, int(row[1]), row[2], '', 255, "", 0, 0])
	print(confidence_pair1)
	confidence_pair2 = []
	for row in greek_data_table:
		y = [(x[2].encode('utf-8').strip(), x[3].encode('utf-8').strip(), x[1]) for x in hindi_data_table.tolist() if x[3]]
		flag = 0
		for items in y:
			if row[4].encode('utf-8') == items[1]:
				flag = 1
				confidence_pair2.append([lid, int(items[2]), items[0], items[1], int(row[1]), row[3], 1, 1])
			else:
				confidence_pair2.append([lid, 255, '', '', int(row[1]), row[3], 0, 0])

	confidence_pair2 = set(tuple(i) for i in confidence_pair2)
	confidence_pair2 = list(list(i) for i in confidence_pair2)

	

	output = []
	for hindi_rec in confidence_pair1:
		for i, greek_rec in enumerate(confidence_pair2):
			if(hindi_rec[1] == greek_rec[1] and hindi_rec[4] == greek_rec[4]):
				hindi_rec[6] = 2
				output.append(hindi_rec)
				del confidence_pair2[i]
	print("\n\nConfidence2\n")
	print(confidence_pair2)
	print("=========")
			# elif(hindi_rec[1] == greek_rec[1] and hindi_rec[4] != greek_rec[4]):

				# confidence_pair2.pop(i)
		# 	else:
		# 		output.append(hindi_rec)
		# output.append(greek_rec)
	# output_set = set(tuple(i) for i in output)
	# print(len(confidence_pair2))
	print("\n\n")
	print(confidence_pair1)
	# if(confidence_pair1[0][1] == confidence_pair2[0][1] && confidence_pair1[0][4] == confidence_pair2[0][4])
	# print confidence_pair2
	# for i in list(confidence_pair1):
	# 	# print i
	# 	for j in confidence_pair2:
	# 		# print j
	# 		if i == j:
	# 			print j
	# 			final_array.append([i[0], i[1], i[2], i[3], i[4], 2, i[6]])
	# 		else:
	# 			final_array.append(i)

	# print set(tuple(i) for i in final_array)



	# print set(confidence_pair2)
				# 	temp[5] = 2
	# 			else:
	# 				final_array.append([lid, int(items[2]), items[0], items[1], int(row[1])-1, 1, 1])

	# final_array.append([lid, ])




# (lid, hi_pos, hi_word, hi_stem, str_pos, stro_num, tipe, stage)