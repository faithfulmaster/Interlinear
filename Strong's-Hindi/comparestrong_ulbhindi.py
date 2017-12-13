import os
import sys
import re
import glob
import errno

files = glob.glob('nt_ulb_hi.hi')
strfile = open('nt_strongs_en.txt', 'r')
strfilelines = strfile.readlines()
strfilelen = len(strfilelines)

for name in files:

	print name
	infile = open(name, "r")
	outfile = open("strongs_nt.txt", "w")
	lines = infile.readlines()
	i = 0

	for line in lines:
		findv = re.search(r"([0-9A-Z]{3})\t(\d+)\t(\d+(\-\d+)?)\t(.*)", line)
		if i < strfilelen:
			findstr = re.search(r"\d+\_([0-9A-Z]{3})\s(\d+)\s(\d+(\-\d+)?)\s(.*)", strfilelines[i])
		if findv.group(1) == findstr.group(1) and findv.group(2) == findstr.group(2) and findv.group(3) == findstr.group(3):
			outfile.write(findstr.group(1) + " " + findstr.group(2) + " " + findstr.group(3) + " " + findstr.group(5) + "\n")
		else:
			outfile.write("\n")
			i += 1
		i += 1

	outfile.close()
	print "Done !"