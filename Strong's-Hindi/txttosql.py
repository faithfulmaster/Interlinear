# -*- coding: utf-8 -*-
import os
import sys
import re
import glob
import errno

files = glob.glob('hindi_nt.txt')

# For hi_bib_id_tb_texts_noembed
for name in files:
	print name
	infile = open(name, "r")
	outfile = open("sqlinput.txt", "w")
	lines = infile.readlines()
	outline = ""
	for line in lines:
		findv = re.search(r"(\d+)\s(.*)", line)
		outline += "(" + findv.group(1).strip() + ",'" + findv.group(2).strip() + "'),"
	outfile.write(outline)
	outfile.close()
	print "Done !"

# For hi_pbtb_word
for name in files:
	print name
	infile = open(name, "r")
	outfile = open("sqlinput1.txt", "w")
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
				outline += "(" + findv.group(1).strip() + "," + str(n) + ",'" + word + "'),"
	outfile.write(outline)
	outfile.close()
	print "Done !"