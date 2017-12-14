# Source Files 

All usfm source files to be placed here

Scripts available here:

1.	__renameFiles_byID.py__ (Optional)

	Can be used to rename all the SFM/ USFM files in the folder, based on their book id, so that you can get all the files in ascending order 

2.	__usfm_to_text_with_bcv.py__

	Convert all usfm files in a folder to a single text file

3.	__textcleanup.py__

	Reads two text files simultaneously line by line comparing each other on the basis of book id, chapter and verse.
	Throws an exception if they do not match. You also might need to update the scripts with some regex for better cleanup, if necessary. 
	This script helps in cleaning up the two text files, which can then be served as input to the Mgiza Word Aligner Tool.

Please Note: Before running the script, open it and update the source/target folder names as per your requirement. 