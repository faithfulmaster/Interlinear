# -*- coding: utf-8 -*-
import re
import sys
import glob
import errno
import os
import openpyxl

bookwrite = openpyxl.Workbook()
sheetwrite = bookwrite.active
dest_filename = "hindi_strong.xlsx"
row2 = 1

infile = open("hindi_interlinear_final.txt", "r")
inline = infile.readlines()
outline = ""
num = 23145
for line in inline:
	num += 1
	onlyline = re.search(str(num) + " (.*)", line)
	if onlyline:
		actualline = onlyline.group(1).strip()
		words = actualline.strip().split(">")
		for i in words:
			if i != "":
				strongsnum = re.search(r"(.*) <", i)
				if strongsnum != None:
					multistrongword = i.strip().split(" ")
					for k in multistrongword:
						if not re.search(r"<", k):
							sheetwrite['A' + str(row2)] = k.strip()
							row2 += 1
						else:
							row2 -= 1
							sheetwrite['B' + str(row2)] = k[1:].strip()
							row2 += 1

	
bookwrite.save(filename = dest_filename)
print "\nConversion done !"