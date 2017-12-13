import openpyxl
import re
import sys
import glob
import errno
import os
 
files = glob.glob('Archive/NT.xlsx')
sorted_list = []
for name in files:
    print name
    filename = name.split('/')[1]
    print filename
    wb = openpyxl.load_workbook(name)

    print "Conversion in progress !"
    prev_book = ""
    prev_chapter = ""
    prev_verse = ""
    num = []
    sheet = wb.get_sheet_by_name("Sheet1")
    i = 0
    for row in range(2, sheet.max_row + 1):
        if sheet['A' + str(row)].value != prev_book or sheet['B' + str(row)].value != prev_chapter or sheet['C' + str(row)].value != prev_verse:
            sorted_list.append((str(sheet['A' + str(row)].value), int(sheet['B' + str(row)].value), int(sheet['C' + str(row)].value), 0))
            sorted_list[i-1] = ((prev_book, prev_chapter, prev_verse, num))
            num = []
        num.append(int(sheet['D' + str(row)].value))
        prev_book = str(sheet['A' + str(row)].value)
        prev_chapter = int(sheet['B' + str(row)].value)
        prev_verse = int(sheet['C' + str(row)].value)
    sorted_list[i-1] = ((prev_book, prev_chapter, prev_verse, num))

    sorted_list.sort()
    len1 = len(sorted_list)
    print sorted_list
    outfile = open("strongs_en.txt", "w")
    for i in range(0, len1):
        outfile.write(sorted_list[i][0] + " " + str(sorted_list[i][1]) + " " + str(sorted_list[i][2]))
        len2 = len(sorted_list[i][3])
        for j in range(0, len2):
            outfile.write(" " + str(sorted_list[i][3][j]))
        outfile.write("\n")
    outfile.close();

    print "Done !!"