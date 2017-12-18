# Interlinear
Scripts for Hindi Interlinear (Python, SQL and PHP)

This repo contains the scripts for developing an Interlinear

## Prerequisites

### MGIZA Word Alignment Tool 
-- From http://www.statmt.org/moses/?n=Moses.ExternalTools#ntoc3

	git clone https://github.com/moses-smt/mgiza.git
	cd mgiza/mgizapp
	sudo apt install cmake

Install boost: 
	
	sudo apt-get install libboost-all-dev
	cmake .
	make
	make install

Now, check your installation using : 
	
	cd bin 
	./mgiza

-- Now, referring to, https://fabioticconi.wordpress.com/2011/01/17/how-to-do-a-word-alignment-with-giza-or-mgiza-from-parallel-corpus/

Download tools and extract it: 

	http://www.statmt.org/europarl/v7/tools.tgz

Download any parallel corpus for a language pair, and extract it from: 

	http://www.statmt.org/europarl/

Now tokenize,

	tokenizer.perl -l src < raw_corp.src > corp.tok.src
	tokenizer.perl -l trg < raw_corp.trg > corp.tok.trg

where: row_corp is the filename of the parallel corpus that we downloaded 
    src is the source language name
    trg is the target language name

Now, lowercase every word,
	
	tr '[:upper:]' '[:lower:]' < corp.tok.src > corp.tok.low.src
	tr '[:upper:]' '[:lower:]' < corp.tok.trg > corp.tok.low.trg

Install gcc:
	
	add-apt-repository ppa:eugenesan/ppa
	apt-get update
	apt-get install gcc-4.9 -y

Make classes (Note: Please avoid any space after -p and -V),
	
	mkcls -n10 -pcorp.tok.low.src -Vcorp.tok.low.src.vcb.classes
	mkcls -n10 -pcorp.tok.low.trg -Vcorp.tok.low.trg.vcb.classes

Note: mkcls executable is present in the bin folder of the mgizapp, so you can directly run it in the present folder by providing the location of the mkcls in the bin folder followed by the above command.

Translate the corpora into GIZA format,
	
	plain2snt corp.tok.low.src corp.tok.low.trg

Create concurrence
	
	snt2cooc corp.tok.low.src_corp.tok.low.trg.cooc corp.tok.low.src.vcb corp.tok.low.trg.vcb corp.tok.low.src_corp.tok.low.trg.snt

Finally aligning,
Download this configuration file: 
	
	http://pastebin.com/b1ksHtUy

Update the above file: 
	
	ncpus 4 (if number of cores = 4)

Run it: 
	
	mgiza configfile	

Finally you'll get as many output files as "ncpus"
	Concatenate those files, and you have your word alignment

### Python 2.7
-- From https://tecadmin.net/install-python-2-7-on-ubuntu-and-linuxmint/

Install python pre-requisites

	sudo apt-get update
	sudo apt-get install build-essential checkinstall	
	sudo apt-get install libreadline-gplv2-dev libncursesw5-dev libssl-dev libsqlite3-dev tk-dev libgdbm-dev libc6-dev libbz2-dev

Download and extract python package
	
	cd /usr/src
	sudo wget https://www.python.org/ftp/python/2.7.14/Python-2.7.14.tgz
	sudo tar xzf Python-2.7.14.tgz

Compile python package
	
	cd Python-2.7.14
	sudo ./configure
	sudo make altinstall

Check python version
	
	python2.7 -V

### MYSQL
-- From https://www.digitalocean.com/community/tutorials/how-to-install-mysql-on-ubuntu-16-04

Installation
	
	sudo apt-get update
	sudo apt-get install mysql-server

Configuration
	
	mysql_secure_installation

Testing
	systemctl status mysql.service

### phpMyAdmin (optional)
-- From https://www.digitalocean.com/community/tutorials/how-to-install-and-secure-phpmyadmin-on-ubuntu-16-04

Installation
	
	sudo apt-get update
	sudo apt-get install phpmyadmin php-mbstring php-gettext

This will ask you a few questions in order to configure your installation correctly.

Warning: When the first prompt appears, apache2 is highlighted, but not selected. If you do not hit Space to select Apache, the installer will not move the necessary files during installation. Hit Space, Tab, and then Enter to select Apache.

    For the server selection, choose apache2.
    Select yes when asked whether to use dbconfig-common to set up the database
    You will be prompted for your database administrator's password
    You will then be asked to choose and confirm a password for the phpMyAdmin application itself

Enable PHP scripts explicitly
    
    sudo phpenmod mcrypt   
    sudo phpenmod mbstring

Restart Apache server
	
	sudo systemctl restart apache2

Access phpmyAdmin interface through web browser : https://domain_name_or_IP/phpmyadmin