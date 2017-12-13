# -*- coding: utf-8 -*-
import re
import sys
import glob
import errno
import os

# Concatenation of mgiza files 
files = glob.glob('st-hi/st_hi.dict.A3.final*')
fileindex = []
totallen = 0
m = 0
i = -1
linenum = 0
outfile = open("combined_output.txt", "w")

for name in files:
	fopen = open(name, 'r')
	infile = fopen.readlines()
	m = 0
	findorder = ""
	findtar = ""

	for i in range(0, len(infile)):
		if m % 3 == 0:
			forder = re.search(r"\((\d+)\)", infile[i])
			findorder = forder.group(1)
		elif m % 3 == 1:
			findtar = infile[i]
		elif m % 3 == 2:
			findsource = infile[i]
			fileindex.append((int(findorder), findsource.strip(), findtar.strip()))
		m += 1
	fopen.close()

fileindex.sort()
for i in fileindex:
	outfile.write("Sentence pair (" + str(i[0]) + ")\n")
	outfile.write(i[1] + "\n")
	outfile.write(i[2] + "\n")

outfile.close()
print "Concatenation Done!"

# Parse mgiza file
infile = open("combined_output.txt", "r")
inline = infile.readlines()
outfile = open("hindi_interlinear.txt", "w")
m = 0
occurences = []

for i in range(0, len(inline)):

	if m % 3 == 0:
		print inline[i].strip()
		forder = re.search(r"\((\d+)\)", inline[i])
		findorder = forder.group(1)

	elif m % 3 == 1:
		flag = 0
		word = ""
		pos = ""
		skip = 0
		for j in inline[i].strip():
			if skip < 8:
				if j == "{" or j == "(" or j == ")" or j == "}":
					skip += 1
			elif skip == 8:
				if j != " " and flag == 0 and j != "(":
					word += str(j)
				elif flag == 0 and j == "(":
					flag = 1
				elif flag == 1 and j == "{":
					flag = 2
				elif flag == 2 and j == "}":
					flag = 1
				elif flag == 2 and j != "}":
					pos += str(j)
				elif flag == 1 and j == ")":
					occurences.append((word.strip(), pos.strip().split(" ")))
					word = ""
					pos = ""
					flag = 0
	elif m % 3 == 2:
		hiwords = inline[i].strip().split(" ")
		hiwithstr = []
		nullvalues = ""
		for num in range(0, len(hiwords)):
			hiwithstr.append(0)
		values = []
		nonnullvalues = []

		for k in occurences:
			values.append(k[0])

		for k in occurences:
			for l in k[1]:
				if l != '':
					hiwithstr[int(l) - 1] = (hiwords[int(l) - 1] + " <" + k[0] + ">")
					nonnullvalues.append(k[0])

		nullvalues = set(values) - set(nonnullvalues)
		for i in nullvalues:
			hiwithstr.append("NULL <" + str(i) + ">")
					
		hipos = 0
		for index in hiwithstr:
			if index == 0:
				outfile.write(hiwords[hipos] + " ")
			else:
				outfile.write(index + " ")
			hipos += 1
		outfile.write("\n")
		occurences = []
	m += 1

infile.close()
outfile.close()
print "Tagging Completed"

# Remove duplicate tags and generate sql file
infile = open("hindi_interlinear.txt", "r")
inline = infile.readlines()
outfile = open("hindi_interlinear_final.txt", "w")
sqlfile = open("hi_bib_id_tbst_texts.sql", "w")

sqlfile.write("DROP TABLE IF EXISTS bib_id_tbst_texts;\n\n")

sqlfile.write("CREATE TABLE bib_id_tbst_texts (id smallint(5) unsigned NOT NULL DEFAULT '0', text text COLLATE utf8_unicode_ci, PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;\n\n")

sqlfile.write("INSERT INTO bib_id_tbst_texts (id, text) VALUES (",)
for line in inline:
	words = line.split(" ")
	outline = ""

	for i in range(2, len(words)):
		if words[i] == words[i-2] and words[i] != 'NULL':
			words[i-2] = ""
	for j in words:
		if j != "":
			outline += j + " "
	
	removenewline = outline.strip()
	removespace1 = re.sub(r" ,", ",", removenewline)
	removespace2 = re.sub(r" \?", "?", removespace1)
	removespace3 = re.sub(r" ;", ";", removespace2)
	removespace4 = re.sub(r" :", ":", removespace3)
	removespace5 = re.sub(r"“ ", "“", removespace4)
	removespace6 = re.sub(r" ”", "”", removespace5)
	removespace7 = re.sub(r" !", "!", removespace6)
	removespace8 = re.sub(r" \.", ".", removespace7)
	removespace9 = re.sub(r"‘ ", "‘", removespace8)
	removespace10 = re.sub(r" ’", "’", removespace9)
	outfile.write(removespace10 + "\n")

	sqlsearch = re.search(r"(\d+)\s(.*)", removespace10)
	if sqlsearch != None:
		sqlfile.write("),(" + sqlsearch.group(1) + "," + "'" + sqlsearch.group(2) + "'")
	else:
		print removespace10
sqlfile.write(");")
outfile.close()
infile.close()
print "Cleanup completed and sql file generated !"