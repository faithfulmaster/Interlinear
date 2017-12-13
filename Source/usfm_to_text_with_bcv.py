import csv
import re
import sys
import glob
import errno
import os

files = glob.glob('Hii-Hindi usfm/*.usfm')
hi_sorted_list = []
for i in files:
    filename = i.split('/')[1]
    fn = filename.split('.')[0]
    hi_sorted_list.append((fn, i))
    hi_sorted_list.sort()
# print hi_sorted_list
len1 = len(hi_sorted_list)
hi_file = 'hi.txt'
outfile1 = open(hi_file, 'w')

for i in range(0, len1):
    print hi_sorted_list[i][0]
    # open usfm file for reading
    f = open (hi_sorted_list[i][1],'r')

    # Writing to txt file
    prev_book = ""
    prev_chapter = ""
    chapter = ""
    book = ""
    verse = ""
    addline = ""

    d = f.readlines()
    f.seek(0)
    for line in d:
        if line == "\n":
            if addline:
                print prev_book + "\t" + prev_chapter + "\t" + verse + "\t" + addline
                outfile1.write(prev_book + "\t" + prev_chapter + "\t" + verse + "\t" + addline + "\n")
                addline = ""
            continue
        elif re.search(r"\\c\s?(\d+)", line):
            if addline:
                print prev_book + "\t" + prev_chapter + "\t" + verse + "\t" + addline
                outfile1.write(prev_book + "\t" + prev_chapter + "\t" + verse + "\t" + addline + "\n")
                addline = ""
            findc = re.search(r"\\c\s?(\d+)", line)
            chapter = findc.group(1).strip()
            if chapter == prev_chapter:
                continue
            else:
                prev_chapter = chapter
        elif re.search(r"\\id\s?([1-9A-Z]{3})", line):
            if addline:
                print prev_book + "\t" + prev_chapter + "\t" + verse + "\t" + addline
                outfile1.write(prev_book + "\t" + prev_chapter + "\t" + verse + "\t" + addline + "\n")
                addline = ""
            findid = re.search(r"\\id\s?([1-9A-Z]{3})", line)
            book = findid.group(1).strip()
            if book == prev_book:
                continue
            else:
                prev_book = book
        elif re.search(r"\\v\s(\d+(\-)?(\d+)?)\s(.*)", line):
            if addline:
                print prev_book + "\t" + prev_chapter + "\t" + verse + "\t" + addline
                outfile1.write(prev_book + "\t" + prev_chapter + "\t" + verse + "\t" + addline + "\n")
                addline = ""
            findv = re.search(r"\\v\s(\d+(\-)?(\d+)?)\s(.*)", line)
            verse = findv.group(1)
            addline = findv.group(4)
        elif re.search(r"(\\q(\d+)?)\s?(.*)", line):
            findq = re.search(r"(\\[qm](\d+)?)\s?(.*)", line)
            if findq.group(3):
                addline = addline.strip() + " " + findq.group(3).strip()
        elif re.search(r"\\m\s(.*)", line):
            findm = re.search(r"\\m\s(.*)", line)
            if findm.group(1):
                addline = addline.strip() + " " + findm.group(1).strip()
        elif not line[0:1] == '\\':
            if line[3:] != " ":
                addline = addline.strip() + " " + line.strip()


        if (line == d[-1]):
            print prev_book + "\t" + prev_chapter + "\t" + verse + "\t" + addline
            outfile1.write(prev_book + "\t" + prev_chapter + "\t" + verse + "\t" + addline + "\n")

        prev_book = book
        prev_chapter = chapter


    print "USFM to TXT conversion done!"

    # close the usfm and csv file
    f.close()
outfile1.close()