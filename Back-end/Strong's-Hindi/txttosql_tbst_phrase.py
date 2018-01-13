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
sqlfile.write("INSERT INTO bib_id_tbst_phrase (id, pos, original, strong, word) VALUES ",)
outline = ""
linenum = 1
for line in inline:
	pos = 1
	onlyline = re.search("\d+\s(.*)", line)
	actualline = onlyline.group(1).strip().split(" NULL ")
	for j in range(0, len(actualline)):
		if j == 0:
			words = actualline[j].strip().split(">")
			for i in words:
				if i != "":
					strongsnum = re.search(r"<(.*)", i)
					if strongsnum != None:
						outline += "(" + str(linenum) + "," + str(pos) + ",'" + i.strip() + ">','" + strongsnum.group(1) + "',''),"
					else:
						outline += "(" + str(linenum) + "," + str(pos) + ",'" + i.strip() + "','',''),"
					pos += 1
		else:
			strongsnum = re.search(r"<(.*)>", actualline[j])
			outline += "(" + str(linenum) + "," + str(pos) + ",''," + strongsnum.group(1) + ",''),"
			pos += 1

		
	linenum += 1

sqlfile.write(outline[:-1] + ";")
infile.close()
print "Sql file generated !"