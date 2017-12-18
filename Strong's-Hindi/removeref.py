import os
import sys
import re
import glob
import errno

files = glob.glob('nt_ulb_hi.hi')
for name in files:
	print name
	infile = open(name, "r")
	outfile = open("hindi_nt.txt", "w")
	lines = infile.readlines()
	n = 0

	for line in lines:
		n += 1
		print line
		findv = re.search(r"[0-9A-Z]{3}\t\d+\t\d+(\-\d+)?\t(.*)", line)
		outfile.write(str(n) + " " + findv.group(2) + "\n")
	outfile.close()
	print "Done !"