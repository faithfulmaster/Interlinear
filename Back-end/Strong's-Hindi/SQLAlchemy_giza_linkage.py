# -*- coding: utf-8 -*-
from sqlalchemy import *
import codecs

db = create_engine('mysql://root@127.0.0.1:3306/interlinear_fulldb?charset=utf8')
db.echo = False  # Try changing this to True and see what happens
metadata = MetaData(db)

giza_output_compare = []
count = 0
prev_id = 0

bib_id_hist_word = Table('bib_id_hist_word', metadata, autoload=True)
hist_word = bib_id_hist_word.select(bib_id_hist_word).execute()

wh_word = Table('wh_word', metadata, autoload=True)

giza_linkage_grk2ayt = Table('giza_linkage_grk2ayt', metadata, autoload=True)
infile = giza_linkage_grk2ayt.insert()

for row in hist_word:
	wh_word_records = wh_word.select((wh_word.c.ayat == row.id) & (wh_word.c.strong == row.strong)).execute()
	countrecords = select([func.count(wh_word.c.pos)]).where((wh_word.c.ayat == row.id) & (wh_word.c.strong == row.strong)).as_scalar().execute().fetchall()
	if row.id != prev_id:
		giza_output_compare = []
	prev_id = row.id
	for i in countrecords:
		count = i.count_1
	if count == 0:
		infile.execute(ayat = r.ayat, net = row.pos, kata = row.original, wh = 255, tipe = 0, stage = 0)
	for r in wh_word_records:
		if (r.ayat, row.pos, row.original, r.strong, r.pos) not in giza_output_compare:
			if count == 1:
				if row.original == '':
					infile.execute(ayat = r.ayat, net = 255, wh = r.pos, strong = r.strong, tipe = 0, stage = 0)
					giza_output_compare.append((r.ayat, row.pos, row.original, r.strong, r.pos))
					break;
				else:
					infile.execute(ayat = r.ayat, net = row.pos, kata = row.original, wh = r.pos, strong = r.strong, tipe = 1, stage = 1)
					giza_output_compare.append((r.ayat, row.pos, row.original, r.strong, r.pos))
					break;
			else:
				ismember = [x for x in giza_output_compare if x[0] == r.ayat and x[2] == row.original and x[3] == r.strong and x[4] == r.pos]
				if not ismember:
					if row.original == '':
						infile.execute(ayat = r.ayat, net = 255, wh = r.pos, strong = r.strong, tipe = 0, stage = 0)
						giza_output_compare.append((r.ayat, row.pos, row.original, r.strong, r.pos))
						break;
					else:
						infile.execute(ayat = r.ayat, net = row.pos, kata = row.original, wh = r.pos, strong = r.strong, tipe = 1, stage = 1)
						giza_output_compare.append((r.ayat, row.pos, row.original, r.strong, r.pos))
						break;
print "Done !"