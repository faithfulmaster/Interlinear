# -*- coding: utf-8 -*-
import re
import sys
import glob
import errno
import os

# Generate sql file: hi_bib_id_tbst_phrase.sql
infile = open("hindi_interlinear_final.txt", "r")
inline = infile.readlines()
sqlfile = open("hi_bib_id_tbst_phrase.sql", "w")

sqlfile.write("DROP TABLE IF EXISTS bib_id_tbst_phrase;\n\n")

sqlfile.write("CREATE TABLE bib_id_tbst_phrase (id int(11) NOT NULL, pos int(11) NOT NULL, original varchar(255) COLLATE utf8_unicode_ci NOT NULL, strong varchar(255) CHARACTER SET utf8 NOT NULL, word varchar(255) CHARACTER SET utf8 NOT NULL, KEY id (id), KEY original (original)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;\n\n")

sqlfile.write("INSERT INTO bib_id_tbst_phrase (id, pos, original, strong, word) VALUES (",)

linenum = 1
for line in inline:
	pos = 1
	onlyline = re.search("\d+\s(.*)", line)
	words = onlyline.group(1).strip().split(">")
	for i in words:
		if i != "":
			strongsnum = re.search(r"<(.*)", i)
			if strongsnum != None:
				sqlfile.write(",(" + str(linenum) + "," + str(pos) + ",'" + i + ">','" + strongsnum.group(1) + "','')" )
			else:
				sqlfile.write(",(" + str(linenum) + "," + str(pos) + ",'" + i + "','','')" )
			pos += 1
	linenum += 1

sqlfile.write(";")
infile.close()
print "Sql file generated !"