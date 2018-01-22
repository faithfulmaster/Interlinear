import os
import sys
import re
import glob
import errno

files = glob.glob('output.txt')
for name in files:
	print name
	infile = open(name, "r")
	outfile = open("hindi_nt_final.txt", "w")
	lines = infile.readlines()
	n = 23145

	for line in lines:
		n += 1
		print line
		findv = re.search(r"[0-9A-Z]{3}\s\d+\s\d+(\-\d+)?\s(.*)", line)
		outfile.write(str(n) + " " + findv.group(2) + "\n")
	outfile.close()
	print "Done !"