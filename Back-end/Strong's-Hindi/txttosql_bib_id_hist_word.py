# -*- coding: utf-8 -*-
import re
import sys
import glob
import errno
import os

# Generate sql file: hi_bib_id_hist_word.sql
infile = open("hindi_interlinear_final.txt", "r")
inline = infile.readlines()
sqlfile = open("hi_bib_id_hist_word.sql", "w")
sqlfile.write("DROP TABLE IF EXISTS bib_id_hist_word;\n\n")
sqlfile.write("CREATE TABLE bib_id_hist_word (id int(11) NOT NULL, pos int(11) NOT NULL, original varchar(255) COLLATE utf8_unicode_ci NOT NULL, strong varchar(255) CHARACTER SET utf8 NOT NULL, word varchar(255) CHARACTER SET utf8 NOT NULL, KEY id (id), KEY original (original)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;\n\n")
sqlfile.write("INSERT INTO bib_id_hist_word (id, pos, original, strong, word) VALUES ",)
outline = ""
linenum = 1 + 23145
for line in inline:
	pos = 1
	onlyline = re.search("\d+\s(.*)", line)
	process1 = onlyline.group(1).strip().replace("”", "")
	process2 = process1.replace("“", "")
	process3 = process2.replace("‘", "")
	process4 = process3.replace("’", "")
	tokens = re.sub(r"([,!?:;.]+)", r"", process4)
	actualline = tokens.strip().split(" NULL ")
	for j in range(0, len(actualline)):
		if j == 0:
			words = actualline[j].strip().split(">")
			for i in words:
				if i != "":
					strongsnum = re.search(r"<(.*)", i)
					if strongsnum != None:
						multiword = i.strip().split(" ")
						for k in multiword:
							if not re.search(r"<", k):
								outline += "(" + str(linenum) + "," + str(pos) + ",'" + k.strip() + "','" + strongsnum.group(1) + "',''),"
								pos += 1
					else:
						multiword = i.strip().split(" ")
						for k in multiword:
							outline += "(" + str(linenum) + "," + str(pos) + ",'" + k.strip() + "','',''),"
							pos += 1
		else:
			strongsnum = re.search(r"<(.*)>", actualline[j])
			outline += "(" + str(linenum) + ",255,''," + strongsnum.group(1) + ",''),"
		
	linenum += 1

sqlfile.write(outline[:-1] + ";")
infile.close()
print "Sql file generated !"