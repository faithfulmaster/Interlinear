import pdb
from collections import Counter

f = open("strong_hindi.csv", mode='r', encoding='utf-8')
fc = f.readlines()
f.close()

ft = []

for line in fc:
    sn, wrd = line.split("\t")
    sn  = sn.strip()
    wrd = wrd.strip()
    if sn == "":
        sn = -1
    if wrd == "":
        wrd = "###"
    ft.append((sn, wrd))

o = open("freq_table.txt", mode='w', encoding='utf-8')

# ftc = Counter(ft)
# ftc
# o.write(ftc)
uft = list(set(ft)) # unique frequency item count list
uft_size = len(uft)
uft_slize = round(uft_size/100,0)*10
print(str(uft_size), str(uft_slize))

ind = 0
for item in uft:
    print(".", end="")
    if(uft_slize % 100 == 0):
        print(ind)
    o.write(str(item[0]) + "\t" + str(item[1]) + "\t" + str(ft.count(item)) + "\n")
    ind += 1
o.close()