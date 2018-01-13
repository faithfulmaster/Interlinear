# -*- coding: utf-8 -*-

import os
import sys
import re
import glob
import errno

files = glob.glob('hindi_nt.txt')
for name in files:
	print name
	infile = open(name, "r")
	outfile = open("tokens_hindi_nt.txt", "w")
	lines = infile.readlines()
	outline = ""
	for line in lines:
		findv = re.search(r"(\d+)\s(.*)", line)
		process1 = findv.group(2).strip().replace("”", " ”")
		process2 = process1.replace("“", "“ ")
		process3 = process2.replace("‘", " ‘ ")
		process4 = process3.replace("’", " ’ ")
		tokens = re.sub(r"([, !?:;.]+)", r" \1", process4)
		print tokens
		outfile.write(findv.group(1) + " " + tokens + "\n")

	outfile.close()
	print "Done !"