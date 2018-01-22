# -*- coding: utf-8 -*-
from sqlalchemy import *
import codecs
import numpy as np

infile = codecs.open("stemmed_bible.txt", mode="r", encoding="utf-8")
inline = infile.read() #lines()
infile.close()

word_list = []

for line in inline:
	words = line.split("\t")
	if words[3] != None:
		word_list.append(words[3].encode('utf-8'))

np_array = np.array(word_list)
uniquewords = np.unique(np_array)
print uniquewords
# uniquewords = set(word.sort())
# print uniquewords