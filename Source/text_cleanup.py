import csv
import re
import sys
import glob
import errno
import os

eng_f = open ('en.txt','r')
engline = eng_f.readlines()
eng_f.seek(0)
i = 0;

hi_f = open ('hi.txt','r') 
hiline = hi_f.readlines()
hi_f.seek(0)
j = 0;

# outfile = open('en_new.txt', 'w')
# prev_line = ""

print len(engline)
print len(hiline)

for i in range(0, len(engline)):
	findeng = re.search(r"([1-9A-Z]{3})\t(\d+)\t(\d+(\-\d+)?)", engline[i])
	findhi = re.search(r"([1-9A-Z]{3})\t(\d+)\t(\d+(\-\d+)?)", hiline[i])
	try:
		# if not (findeng.group(1) + "\t" + findeng.group(2) + "\t" + findeng.group(3) == prev_line):
		# 	outfile.write(engline[i])
		# prev_line = findeng.group(1) + "\t" + findeng.group(2) + "\t" + findeng.group(3)
		if not findeng.group(1) == findhi.group(1):
			print "Books do not match\n" + findeng.group(0) + "\n" + findhi.group(0)
			break;
		elif not findeng.group(2) == findhi.group(2):
			print "Chapters do not match\n" + findeng.group(0) + "\n" + findhi.group(0)
			break;
		elif not findeng.group(3) == findhi.group(3):
			print "Verses do not match\n" + findeng.group(0) + "\n" + findhi.group(0)
			break;
		elif i == len(engline):
			print "Aligned !!!"
	except:
		# print engline[i]
		print engline[i] + "\n" + hiline[i]

		break;