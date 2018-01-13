# -*- coding: utf-8 -*-
import os
import sys
import re
import glob
import errno

files = glob.glob('hindi_nt.txt')

# For hi_bib_id_tb_texts_noembed.sql
for name in files:
	print name
	infile = open(name, "r")
	outfile = open("hi_bib_id_tb_texts_noembed.sql", "w")
	outfile.write("DROP TABLE IF EXISTS bib_id_tb_texts_noembed;\n\n")
	outfile.write("CREATE TABLE bib_id_tb_texts_noembed (id smallint(5) unsigned NOT NULL DEFAULT '0', text text COLLATE utf8_unicode_ci, PRIMARY KEY(id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;\n\n")
	outfile.write("INSERT INTO bib_id_tb_texts_noembed (id, text) VALUES ",)
	lines = infile.readlines()
	outline = ""
	for line in lines:
		findv = re.search(r"(\d+)\s(.*)", line)
		outline += "(" + str(23145 + int(findv.group(1).strip())) + ",'" + findv.group(2).strip() + "'),"
	outfile.write(outline[:-1] + ";")
	outfile.close()
	print "Done !"

# For hi_pbayt_word.sql
for name in files:
	print name
	infile = open(name, "r")
	outfile = open("hi_pbayt_word.sql", "w")
	outfile.write("DROP TABLE IF EXISTS pbayt_word;\n\n")
	outfile.write("CREATE TABLE pbayt_word (id smallint(5) unsigned DEFAULT NULL, pos tinyint(3) unsigned DEFAULT NULL, word char(30) DEFAULT NULL COLLATE utf8_unicode_ci, KEY ayat (id), KEY pos (pos), KEY kata (word)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;\n\n")
	outfile.write("INSERT INTO pbayt_word (id, pos, word) VALUES ",)
	lines = infile.readlines()
	outline = ""
	for line in lines:
		findv = re.search(r"(\d+)\s(.*)", line)
		n = 0
		process1 = findv.group(2).strip().replace("”", "")
		process2 = process1.replace("“", "")
		process3 = process2.replace("‘", "")
		process4 = process3.replace("’", "")
		tokens = filter(None, re.split("[, !?:;.]+", process4))

		for word in tokens:
			if word != "":
				n += 1
				print word
				outline += "(" + str(23145 + int(findv.group(1).strip())) + "," + str(n) + ",'" + word + "'),"
	outfile.write(outline[:-1] + ";")
	outfile.close()
	print "Done !"