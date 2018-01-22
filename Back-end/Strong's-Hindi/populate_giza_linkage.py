# -*- coding: utf-8 -*-
from sqlalchemy import *
import numpy as np
import pdb
import codecs

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

giza_linkage_grk2ayt = Table('giza_linkage_grk2ayt', metadata, autoload=True)
insert_content = giza_linkage_grk2ayt.insert()

for lid in range(23146, 31103):
	hindi_verse = hindi_table.select(hindi_table).where(hindi_table.c.id==lid).execute()
	greek_verse = greek_table.select(greek_table).where(greek_table.c.ayat==lid).execute()

	greek_data_table = np.asarray(convertTable2List(greek_verse))
	hindi_data_table = np.asarray(convertTable2List(hindi_verse))

	confidence_pair1 = []
	final_array = []

	output = []
	for j, hindi_row in enumerate(hindi_data_table):
		flag = True
		for i, greek_row in enumerate(greek_data_table):
			# index = np.where(greek_data_table[...,3] == hindi_row[4])[0]
			if hindi_row[4] == greek_row[3]:
				if hindi_row[3] == greek_row[4]:
					output.append([lid, hindi_row[1], hindi_row[2], greek_row[1], greek_row[3], 2, 1, hindi_row[3]])
					print "hurray"
					insert_content.execute(ayat=lid, net=hindi_row[1], kata=hindi_row[2], wh=greek_row[1], strong=greek_row[3], tipe=2, stage=1, stem=hindi_row[3])
					greek_data_table = np.delete(greek_data_table, i, axis=0)
					flag = False
					break
				else:
					output.append([lid, hindi_row[1], hindi_row[2], greek_row[1], greek_row[3], 1, 1, hindi_row[3]])
					insert_content.execute(ayat=lid, net=hindi_row[1], kata=hindi_row[2].encode('utf-8'), wh=greek_row[1], strong=greek_row[3], tipe=1, stage=1, stem=hindi_row[3].encode('utf-8'))
					greek_data_table = np.delete(greek_data_table, i, axis=0)
					flag = False
					break
			else:
				try:
					if hindi_row[3].encode('utf-8') == greek_row[4].encode('utf-8'):
						output.append([lid, hindi_row[1], hindi_row[2], greek_row[1], greek_row[3], 1, 1, hindi_row[3]])
						insert_content.execute(ayat=lid, net=hindi_row[1], kata=hindi_row[2], wh=greek_row[1], strong=greek_row[3], tipe=1, stage=1, stem=hindi_row[3])
						greek_data_table = np.delete(greek_data_table, i, axis=0)
						flag = False
						break

				except:
					if hindi_row[3] == None:
						output.append([lid, hindi_row[1], hindi_row[2], 255, "", 0, 0, None])
						insert_content.execute(ayat=lid, net=hindi_row[1], kata=hindi_row[2], wh=255, tipe=0, stage=0)
						flag = False
						break
					elif greek_row[4] == None:
						output.append([lid, 255, "", greek_row[1], greek_row[3], 0, 0, None])
						insert_content.execute(ayat=lid, net=255, wh=greek_row[1], strong=greek_row[3], tipe=0, stage=0)
						greek_data_table = np.delete(greek_data_table, i, axis=0)
						flag = False
						break
		if flag:
			if hindi_row[3]:
				output.append([lid, hindi_row[1], hindi_row[2], 255, "", 0, 0, hindi_row[3]])
				insert_content.execute(ayat=lid, net=hindi_row[1], kata=hindi_row[2].encode('utf-8'), wh=255, tipe=0, stage=0, stem=hindi_row[3].encode('utf-8'))
			else:
				output.append([lid, hindi_row[1], hindi_row[2], 255, "", 0, 0, hindi_row[3]])
				insert_content.execute(ayat=lid, net=hindi_row[1], kata=hindi_row[2].encode('utf-8'), wh=255, tipe=0, stage=0)	

	for greek_row in greek_data_table:
		output.append([lid, 255, "", greek_row[1], greek_row[3], 0, 0, ""])
		insert_content.execute(ayat=lid, net=255, wh=greek_row[1], strong=greek_row[3], tipe=0, stage=0)

	

print("An output is generated!")
o.close()