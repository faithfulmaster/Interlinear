# Strongs - Hindi

### Archive Folder 
Contains NT and OT Strongs's based bible content in xlsx files

### Mgiza Files
Contains the source as well as target of Mgiza Word Aligner Tool with Strongs NT as the source language and Hindli ULB NT as the target language

### SQL Files
Contains the SQL files containing the dump of the Strongs-Hindi Interlinear

### Scripts
Before running these scripts, you may need to get the text in a aligned structure using the scripts given under : [Source Scripts](https://github.com/faithfulmaster/Interlinear/tree/master/Source)

1.	__tokenizer.py__

	Converts the text file into tokenized format (separated by spaces)

2.	__strongsverse.py__

	Parses the Strong's number from the [xlsx files](https://github.com/faithfulmaster/Interlinear/tree/master/Strong's-Hindi/Archive) and converts them into text format based on book id, chapter and verse

3.	__comparestrong_ulbhindi.py__
	
	Compares the Strongs text file with the Hindi ULB text file, lne by line based on Book Id, Chapter and Verse, and raises an exception incase of a mismatch; and thus helps aligning the text into a format which can serve as input to Mgiza Word Aligner.

4.	__removeref.py__

	Removes the book id, chapter and verse number and replaces them by a unique number.

5.	__txttosql.py__

	Generates the formatted text from Hindi ULB which can serve as input to SQL tables bib_id_tb_texts_noembed and pbtb_word

6. 	__parsemgiza.py__

	*	Takes as input the multiple output files generated from Mgiza Word Aligner. Generates as output a single concatenated file which is sorted in ascending order.

	*	Parses the concatenated Mgiza file and generates a tagged file as follows: 

	1 अब्राहम <11> की <1078> सन्तान <5207> , दाऊद <1138> की <1078> सन्तान <5207> , यीशु <2424> मसीह <5547> की <1078> वंशावली . NULL <976>  

	*	Removes all duplicates tags in the tagged file and removes unnecessary spaces between puntuation marks, and generates a final internliear text, alongwith its SQL dump file to generate the bib_id_tbst_texts table.

7.	__txttosql_tbst_phrase.py__
	
	*	Takes as input the interlinear text file generated in the above script, and gives as output an SQL dump file to generate the bib_id_tbst_phrase table.

Please Note: Before running the script, open it and update the source/target folder names and add/remove any regex as per your requirement. 