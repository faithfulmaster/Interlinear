#! /usr/bin/env python3.5
''' Lightweight Hindi stemmer
Copyright © 2010 Luís Gomes <luismsgomes@gmail.com>.

Implementation of algorithm described in

    A Lightweight Stemmer for Hindi
    Ananthakrishnan Ramanathan and Durgesh D Rao
    http://computing.open.ac.uk/Sites/EACLSouthAsia/Papers/p6-Ramanathan.pdf

    @conference{ramanathan2003lightweight,
      title={{A lightweight stemmer for Hindi}},
      author={Ramanathan, A. and Rao, D.},
      booktitle={Workshop on Computational Linguistics for South-Asian Languages, EACL},
      year={2003}
    }

Ported from HindiStemmer.java, part of of Lucene.
'''
import string
import re
suffixes = {
    1: ["ो", "े", "ू", "ु", "ी", "ि", "ा"],
    2: ["कर", "ाओ", "िए", "ाई", "ाए", "ने", "नी", "ना", "ते", "ीं", "ती", "ता", "ाँ", "ां", "ों", "ें"],
    3: ["ाकर", "ाइए", "ाईं", "ाया", "ेगी", "ेगा", "ोगी", "ोगे", "ाने", "ाना", "ाते", "ाती", "ाता", "तीं", "ाओं", "ाएं", "ुओं", "ुएं", "ुआं"],
    4: ["ाएगी", "ाएगा", "ाओगी", "ाओगे", "एंगी", "ेंगी", "एंगे", "ेंगे", "ूंगी", "ूंगा", "ातीं", "नाओं", "नाएं", "ताओं", "ताएं", "ियाँ", "ियों", "ियां"],
    5: ["ाएंगी", "ाएंगे", "ाऊंगी", "ाऊंगा", "ाइयाँ", "ाइयों", "ाइयां"],
}

def hi_stem(word):
    for L in 5, 4, 3, 2, 1:
        if len(word) > L + 1:
            for suf in suffixes[L]:
                if word.endswith(suf):
                    return word[:-L]
    return word
f = open("input.txt", mode = 'r', encoding = 'utf-8')
fc = f.read()
stop_words =  ["के", "का", "की", "है", "यह", "हैं", "को", "इस", "कि", "जो", "ने", "हो", "था", "वाले", "बाद", "ये", "इसके", "थे", "होने", "वह", "वे", "होती", "थी", "हुई", "जा", "इसे", "जब", "होते", "कोई", "हुए", "व", "न", "अभी", "जैसे", "सभी", "तरह", "उस", "आदि", "कुल", "एस", "रहा", "रहे", "इसी", "रखें", "पे", "उसके"]

for stop_word in stop_words:
    stop_word = r'(?<=\s)' + stop_word + r'(?=\s)'
    fc = re.sub(stop_word, "", fc)
    # fc = fc.replace(stop_word, "")

fc = fc.split("\n")

o = open("output.txt", mode='w', encoding='utf-8')
for line in fc:
    m = []
    for l in line.split():
        l = l.strip("!\"#$%&\'()*+,./:;<=>?@[\\]^_`{|}~“.”‘’")
        m.append(hi_stem(l))

    o.write(" ".join(m) + "\n")
o.close()

# if __name__ == '__main__':
#     import sys
#     if len(sys.argv) != 1:
#         sys.exit('{} takes no arguments'.format(sys.argv[0]))
#     for line in sys.stdin:
#         print(*[hi_stem(word) for word in line.split()])
