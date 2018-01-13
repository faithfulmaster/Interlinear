# Strongs - Hindi

### Archive Folder 
Contains NT and OT Strongs's based bible content in xlsx files

### Mgiza Files
Contains the source as well as target of Mgiza Word Aligner Tool with Strongs NT as the source language and Hindli ULB NT as the target language

### SQL Files
Contains the SQL files containing the dump of the Strongs-Hindi Interlinear

### Scripts
Before running these scripts, you may need to get the text in a aligned structure using the scripts given under : [Source Scripts](../Interlinear/Back-end/Source)

1.	__tokenizer.py__

	Converts the text file into tokenized format (separated by spaces)

2.	__strongsverse.py__ (You do not need to run this script, as Strong's text is already available on this repo)

	Parses the Strong's number from the [xlsx files](https://github.com/faithfulmaster/Interlinear/tree/master/Strong's-Hindi/Archive) and converts them into text format based on book id, chapter and verse

3.	__comparestrong_ulbhindi.py__
	
	Compares the Strongs text file with the Hindi ULB text file, lne by line based on Book Id, Chapter and Verse, and raises an exception incase of a mismatch; and thus helps aligning the text into a format which can serve as input to Mgiza Word Aligner.

4.	__removeref.py__

	Removes the book id, chapter and verse number and replaces them by a unique number.

5.	__txttosql.py__

	Generates the formatted text from Hindi ULB which can serve as input to SQL tables bib_id_tb_texts_noembed and pbayt_word

6. 	__parsemgiza.py__

	*	Takes as input the multiple output files generated from Mgiza Word Aligner. Generates as output a single concatenated file which is sorted in ascending order.

	*	Parses the concatenated Mgiza file and generates a tagged file as follows: 

	1 अब्राहम <11> की <1078> सन्तान <5207> , दाऊद <1138> की <1078> सन्तान <5207> , यीशु <2424> मसीह <5547> की <1078> वंशावली . NULL <976>  

	*	Removes all duplicates tags in the tagged file and removes unnecessary spaces between puntuation marks, and generates a final internliear text, alongwith its SQL dump file to generate the bib_id_tbst_texts table.

7.	__txttosql_tbst_phrase.py__
	
	*	Takes as input the interlinear text file generated in the above script, and gives as output an SQL dump file to generate the bib_id_tbst_phrase table.

Please Note: Before running the script, open it and update the source/target folder names and add/remove any regex as per your requirement.

## Instructions: 

1. Download the entire repo
2. Place your source usfm files in the [Source Folder](../Interlinear/Back-end/Source).
3. Get a properly aligned bilingual text from the source usfm files using the following scripts: 
	a) For renaming files [renameFiles_byID.py](../Interlinear/Back-end/Source/renameFiles_byID.py)
	b) For converting usfm files to a single file [usfm_to_text_with_bcv.py](../Interlinear/Back-end/Source/usfm_to_text_with_bcv.py)
	c) For cleanup of text [text_cleanup.py](../Interlinear/Back-end/Source/text_cleanup.py)
4. Run the tokenizer script [tokenizer.py](../Interlinear/Back-end/Strong's-Hindi/tokenizer.py) on both the source and target languages
5. Compare the source language text with the strongs text [comparestrong_ulbhindi.py](../Interlinear/Back-end/Strong's-Hindi/comparestrong_ulbhindi.py)
6. Remove the references from the source language text [removeref.py](../Interlinear/Back-end/Strong's-Hindi/removeref.py)
7. Create a utf-8 compatible database using create db sql file [createdb.sql](../Interlinear/Back-end/Strong's-Hindi/SQL%20Files/createdb.sql)
8. Create first two tables (hi_pbayt_word.sql and hi_bib_id_tb_texts_noembed.sql) for the back-end by running this script [txttosql.py](../Interlinear/Back-end/Strong's-Hindi/txttosql.py) Load these tables to the database as follows:
	mysql -u username --max_allowed_packet=1073741824 --default-character-set=utf8 database_name < Your_location\hi_pbayt_word.sql
You might also need to update the sql config file by updating the following values:
	
	[client]
	default-character-set=utf8

	[mysqldump]
	quick
	max_allowed_packet = 100M

	[mysqld]
	port = 3306
	character-set-server=utf8
	collation-server=utf8_bin
	default-storage-engine=INNODB
	max_allowed_packet=256M
	innodb_log_file_size=2GB
	transaction-isolation=READ-COMMITTED
	binlog_format=row

9. Load the greek-hebrew SQL dump file to the db [interlinear_fulldb.sql](../Interlinear/Back-end/Strong's-Hindi/SQL%20Files/interlinear_fulldb.sql)
10. Use the MGIZA word aligner tool to align the texts obtained in point 6 (Source language text and Strongs text). Instructions to run MGIZA tool can be found [here](../Interlinear/Back-end/README.md)
11. Now, run the [parsemgiza.py](../Interlinear/Back-end/Strong's-Hindi/parsemgiza.py) script to generate a single interlinear text file, and also generate the bib_id_tbst_texts.sql file. Also run the [txttosql_tbst_phrase.py](../Interlinear/Back-end/Strong's-Hindi/txttosql_tbst_phrase.py) to generate the txttosql_tbst_phrase.sql file. Load these tables to the database.
12. Now, load the [Link_hi_bib_id_tbst_phrase_with_greek.sql](../Interlinear/Back-end/Strong's-Hindi/SQL%20Files/Link_hi_bib_id_tbst_phrase_with_greek.sql) file to the db.
13. Run the [txttosql_bib_id_hist_word.py](../Interlinear/Back-end/Strong's-Hindi/txttosql_bib_id_hist_word.py) script to generate the hi_bib_id_hist_word.sql file. Load the sql file to the db. This is a temporary table which can be removed after the 15th step.
14. Create the giza_linkage_grk2ayt table by the following two SQL commands:

	DROP TABLE IF EXISTS giza_linkage_grk2ayt;
	CREATE TABLE IF NOT EXISTS giza_linkage_grk2ayt (ayat smallint(5) UNSIGNED DEFAULT NULL, net tinyint(3) UNSIGNED DEFAULT NULL, kata char(30) COLLATE utf8_unicode_ci DEFAULT NULL, wh tinyint(3) UNSIGNED DEFAULT NULL, strong smallint(5) UNSIGNED DEFAULT NULL, tipe tinyint(3) UNSIGNED NOT NULL DEFAULT '0', stage tinyint(3) UNSIGNED NOT NULL DEFAULT '0', KEY ayat (ayat), KEY net (net), KEY wh (wh), KEY strong (strong), KEY stage (stage) ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

15. Finally, run the [SQLAlchemy_giza_linkage.py](../Interlinear/Back-end/Strong's-Hindi/SQLAlchemy_giza_linkage.py) script to populate the giza_linkage_grk2ayt table in the db.

This completes the Back-end for the Interlinear Project !