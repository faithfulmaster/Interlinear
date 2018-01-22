# -*- coding: utf-8 -*-
import re
import sys
import glob
import errno
import os
import openpyxl

bookwrite = openpyxl.Workbook()
sheetwrite = bookwrite.active
dest_filename = "strong_hindi.xlsx"
row2 = 1

infile = open("hindi_interlinear_final.txt", "r")
inline = infile.readlines()
outline = ""
linenum = 23145
for line in inline:
	linenum += 1
	onlyline = re.search(str(linenum) + " (.*)", line)
	if onlyline:
		actualline = onlyline.group(1).strip()
		words = actualline.strip().split(">")
		for i in words:
			if i != "":
				multiword = i.strip().split(" ")
				for k in multiword:
					if not re.search(r"<", k):
						sheetwrite['B' + str(row2)] = k.strip()
						row2 += 1
					else:
						row2 -= 1
						sheetwrite['A' + str(row2)] = k[1:].strip()
						row2 += 1
		
infile.close()
	
bookwrite.save(filename = dest_filename)
print "\nConversion done !"