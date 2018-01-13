prev = '';
prevdir = '';
prevpos = 0;
touched = false;
tipe = 1;

var chaptercount = new Array(67);
for (var n = 0; n < chaptercount.length; n++ ) {
    chaptercount[n] = new Array(2);
}

chaptercount[0]['max']=0; chaptercount[0]['count']=0;					chaptercount[34]['max']=3; chaptercount[34]['count']=900;  											
chaptercount[1]['max']=50; chaptercount[1]['count']=0;        chaptercount[35]['max']=3; chaptercount[35]['count']=903;  
chaptercount[2]['max']=40; chaptercount[2]['count']=50;       chaptercount[36]['max']=3; chaptercount[36]['count']=906;  
chaptercount[3]['max']=27; chaptercount[3]['count']=90;       chaptercount[37]['max']=2; chaptercount[37]['count']=909;  
chaptercount[4]['max']=36; chaptercount[4]['count']=117;      chaptercount[38]['max']=14; chaptercount[38]['count']=911; 
chaptercount[5]['max']=34; chaptercount[5]['count']=153;      chaptercount[39]['max']=4; chaptercount[39]['count']=925;  
chaptercount[6]['max']=24; chaptercount[6]['count']=187;      chaptercount[40]['max']=28; chaptercount[40]['count']=929; 
chaptercount[7]['max']=21; chaptercount[7]['count']=211;      chaptercount[41]['max']=16; chaptercount[41]['count']=957; 
chaptercount[8]['max']=4; chaptercount [8]['count']=232;      chaptercount[42]['max']=24; chaptercount[42]['count']=973; 
chaptercount[9]['max']=31; chaptercount[9]['count']=236;      chaptercount[43]['max']=21; chaptercount[43]['count']=997; 
chaptercount[10]['max']=24; chaptercount[10]['count']=267;    chaptercount[44]['max']=28; chaptercount[44]['count']=1018;
chaptercount[11]['max']=22; chaptercount[11]['count']=291;    chaptercount[45]['max']=16; chaptercount[45]['count']=1046;
chaptercount[12]['max']=25; chaptercount[12]['count']=313;    chaptercount[46]['max']=16; chaptercount[46]['count']=1062;
chaptercount[13]['max']=29; chaptercount[13]['count']=338;    chaptercount[47]['max']=13; chaptercount[47]['count']=1078;
chaptercount[14]['max']=36; chaptercount[14]['count']=367;    chaptercount[48]['max']=6; chaptercount[48]['count']=1091; 
chaptercount[15]['max']=10; chaptercount[15]['count']=403;    chaptercount[49]['max']=6; chaptercount[49]['count']=1097; 
chaptercount[16]['max']=13; chaptercount[16]['count']=413;    chaptercount[50]['max']=4; chaptercount[50]['count']=1103; 
chaptercount[17]['max']=10; chaptercount[17]['count']=426;    chaptercount[51]['max']=4; chaptercount[51]['count']=1107; 
chaptercount[18]['max']=42; chaptercount[18]['count']=436;    chaptercount[52]['max']=5; chaptercount[52]['count']=1111; 
chaptercount[19]['max']=150; chaptercount[19]['count']=478;   chaptercount[53]['max']=3; chaptercount[53]['count']=1116; 
chaptercount[20]['max']=31; chaptercount[20]['count']=628;    chaptercount[54]['max']=6; chaptercount[54]['count']=1119; 
chaptercount[21]['max']=12; chaptercount[21]['count']=659;    chaptercount[55]['max']=4; chaptercount[55]['count']=1125; 
chaptercount[22]['max']=8; chaptercount[22]['count']=671;     chaptercount[56]['max']=3; chaptercount[56]['count']=1129; 
chaptercount[23]['max']=66; chaptercount[23]['count']=679;    chaptercount[57]['max']=1; chaptercount[57]['count']=1132; 
chaptercount[24]['max']=52; chaptercount[24]['count']=745;    chaptercount[58]['max']=13; chaptercount[58]['count']=1133;
chaptercount[25]['max']=5; chaptercount[25]['count']=797;     chaptercount[59]['max']=5; chaptercount[59]['count']=1146; 
chaptercount[26]['max']=48; chaptercount[26]['count']=802;    chaptercount[60]['max']=5; chaptercount[60]['count']=1151; 
chaptercount[27]['max']=12; chaptercount[27]['count']=850;    chaptercount[61]['max']=3; chaptercount[61]['count']=1156; 
chaptercount[28]['max']=14; chaptercount[28]['count']=862;    chaptercount[62]['max']=5; chaptercount[62]['count']=1159; 
chaptercount[29]['max']=3; chaptercount[29]['count']=876;     chaptercount[63]['max']=1; chaptercount[63]['count']=1164; 
chaptercount[30]['max']=9; chaptercount[30]['count']=879;     chaptercount[64]['max']=1; chaptercount[64]['count']=1165; 
chaptercount[31]['max']=1; chaptercount[31]['count']=888;     chaptercount[65]['max']=1; chaptercount[65]['count']=1166; 
chaptercount[32]['max']=4; chaptercount[32]['count']=889;     chaptercount[66]['max']=22; chaptercount[66]['count']=1167;
chaptercount[33]['max']=7; chaptercount[33]['count']=893;

var versecount = new Array(0, 31, 25, 24, 26, 32, 22, 24, 22, 29, 32, 32, 20, 18, 24, 21, 16, 27, 33, 38, 18, 34, 24, 20, 67, 34, 35, 46, 22, 35, 43, 55, 32, 20, 31, 29, 43, 36, 30, 23, 23, 57, 38, 34, 34, 28, 34, 31, 22, 33, 26, 22, 25, 22, 31, 23, 30, 25, 32, 35, 29, 10, 51, 22, 31, 27, 36, 16, 27, 25, 26, 36, 31, 33, 18, 40, 37, 21, 43, 46, 38, 18, 35, 23, 35, 35, 38, 29, 31, 43, 38, 17, 16, 17, 35, 19, 30, 38, 36, 24, 20, 47, 8, 59, 57, 33, 34, 16, 30, 37, 27, 24, 33, 44, 23, 55, 46, 34, 54, 34, 51, 49, 31, 27, 89, 26, 23, 36, 35, 16, 33, 45, 41, 50, 13, 32, 22, 29, 35, 41, 30, 25, 18, 65, 23, 31, 40, 16, 54, 42, 56, 29, 34, 13, 46, 37, 29, 49, 33, 25, 26, 20, 29, 22, 32, 32, 18, 29, 23, 22, 20, 22, 21, 20, 23, 30, 25, 22, 19, 19, 26, 68, 29, 20, 30, 52, 29, 12, 18, 24, 17, 24, 15, 27, 26, 35, 27, 43, 23, 24, 33, 15, 63, 10, 18, 28, 51, 9, 45, 34, 16, 33, 36, 23, 31, 24, 31, 40, 25, 35, 57, 18, 40, 15, 25, 20, 20, 31, 13, 31, 30, 48, 25, 22, 23, 18, 22, 28, 36, 21, 22, 12, 21, 17, 22, 27, 27, 15, 25, 23, 52, 35, 23, 58, 30, 24, 42, 15, 23, 29, 22, 44, 25, 12, 25, 11, 31, 13, 27, 32, 39, 12, 25, 23, 29, 18, 13, 19, 27, 31, 39, 33, 37, 23, 29, 33, 43, 26, 22, 51, 39, 25, 53, 46, 28, 34, 18, 38, 51, 66, 28, 29, 43, 33, 34, 31, 34, 34, 24, 46, 21, 43, 29, 53, 18, 25, 27, 44, 27, 33, 20, 29, 37, 36, 21, 21, 25, 29, 38, 20, 41, 37, 37, 21, 26, 20, 37, 20, 30, 54, 55, 24, 43, 26, 81, 40, 40, 44, 14, 47, 40, 14, 17, 29, 43, 27, 17, 19, 8, 30, 19, 32, 31, 31, 32, 34, 21, 30, 17, 18, 17, 22, 14, 42, 22, 18, 31, 19, 23, 16, 22, 15, 19, 14, 19, 34, 11, 37, 20, 12, 21, 27, 28, 23, 9, 27, 36, 27, 21, 33, 25, 33, 27, 23, 11, 70, 13, 24, 17, 22, 28, 36, 15, 44, 11, 20, 32, 23, 19, 19, 73, 18, 38, 39, 36, 47, 31, 22, 23, 15, 17, 14, 14, 10, 17, 32, 3, 22, 13, 26, 21, 27, 30, 21, 22, 35, 22, 20, 25, 28, 22, 35, 22, 16, 21, 29, 29, 34, 30, 17, 25, 6, 14, 23, 28, 25, 31, 40, 22, 33, 37, 16, 33, 24, 41, 30, 24, 34, 17, 6, 12, 8, 8, 12, 10, 17, 9, 20, 18, 7, 8, 6, 7, 5, 11, 15, 50, 14, 9, 13, 31, 6, 10, 22, 12, 14, 9, 11, 12, 24, 11, 22, 22, 28, 12, 40, 22, 13, 17, 13, 11, 5, 26, 17, 11, 9, 14, 20, 23, 19, 9, 6, 7, 23, 13, 11, 11, 17, 12, 8, 12, 11, 10, 13, 20, 7, 35, 36, 5, 24, 20, 28, 23, 10, 12, 20, 72, 13, 19, 16, 8, 18, 12, 13, 17, 7, 18, 52, 17, 16, 15, 5, 23, 11, 13, 12, 9, 9, 5, 8, 28, 22, 35, 45, 48, 43, 13, 31, 7, 10, 10, 9, 8, 18, 19, 2, 29, 176, 7, 8, 9, 4, 8, 5, 6, 5, 6, 8, 8, 3, 18, 3, 3, 21, 26, 9, 8, 24, 13, 10, 7, 12, 15, 21, 10, 20, 14, 9, 6, 33, 22, 35, 27, 23, 35, 27, 36, 18, 32, 31, 28, 25, 35, 33, 33, 28, 24, 29, 30, 31, 29, 35, 34, 28, 28, 27, 28, 27, 33, 31, 18, 26, 22, 16, 20, 12, 29, 17, 18, 20, 10, 14, 17, 17, 11, 16, 16, 13, 13, 14, 31, 22, 26, 6, 30, 13, 25, 22, 21, 34, 16, 6, 22, 32, 9, 14, 14, 7, 25, 6, 17, 25, 18, 23, 12, 21, 13, 29, 24, 33, 9, 20, 24, 17, 10, 22, 38, 22, 8, 31, 29, 25, 28, 28, 25, 13, 15, 22, 26, 11, 23, 15, 12, 17, 13, 12, 21, 14, 21, 22, 11, 12, 19, 12, 25, 24, 19, 37, 25, 31, 31, 30, 34, 22, 26, 25, 23, 17, 27, 22, 21, 21, 27, 23, 15, 18, 14, 30, 40, 10, 38, 24, 22, 17, 32, 24, 40, 44, 26, 22, 19, 32, 21, 28, 18, 16, 18, 22, 13, 30, 5, 28, 7, 47, 39, 46, 64, 34, 22, 22, 66, 22, 22, 28, 10, 27, 17, 17, 14, 27, 18, 11, 22, 25, 28, 23, 23, 8, 63, 24, 32, 14, 49, 32, 31, 49, 27, 17, 21, 36, 26, 21, 26, 18, 32, 33, 31, 15, 38, 28, 23, 29, 49, 26, 20, 27, 31, 25, 24, 23, 35, 21, 49, 30, 37, 31, 28, 28, 27, 27, 21, 45, 13, 11, 23, 5, 19, 15, 11, 16, 14, 17, 15, 12, 14, 16, 9, 20, 32, 21, 15, 16, 15, 13, 27, 14, 17, 14, 15, 21, 17, 10, 10, 11, 16, 13, 12, 13, 15, 16, 20, 15, 13, 19, 17, 20, 19, 18, 15, 20, 15, 23, 21, 13, 10, 14, 11, 15, 14, 23, 17, 12, 17, 14, 9, 21, 14, 17, 18, 6, 25, 23, 17, 25, 48, 34, 29, 34, 38, 42, 30, 50, 58, 36, 39, 28, 27, 35, 30, 34, 46, 46, 39, 51, 46, 75, 66, 20, 45, 28, 35, 41, 43, 56, 37, 38, 50, 52, 33, 44, 37, 72, 47, 20, 80, 52, 38, 44, 39, 49, 50, 56, 62, 42, 54, 59, 35, 35, 32, 31, 37, 43, 48, 47, 38, 71, 56, 53, 51, 25, 36, 54, 47, 71, 53, 59, 41, 42, 57, 50, 38, 31, 27, 33, 26, 40, 42, 31, 25, 26, 47, 26, 37, 42, 15, 60, 40, 43, 48, 30, 25, 52, 28, 41, 40, 34, 28, 41, 38, 40, 30, 35, 27, 27, 32, 44, 31, 32, 29, 31, 25, 21, 23, 25, 39, 33, 21, 36, 21, 14, 23, 33, 27, 31, 16, 23, 21, 13, 20, 40, 13, 27, 33, 34, 31, 13, 40, 58, 24, 24, 17, 18, 18, 21, 18, 16, 24, 15, 18, 33, 21, 14, 24, 21, 29, 31, 26, 18, 23, 22, 21, 32, 33, 24, 30, 30, 21, 23, 29, 23, 25, 18, 10, 20, 13, 18, 28, 12, 17, 18, 20, 15, 16, 16, 25, 21, 18, 26, 17, 22, 16, 15, 15, 25, 14, 18, 19, 16, 14, 20, 28, 13, 28, 39, 40, 29, 25, 27, 26, 18, 17, 20, 25, 25, 22, 19, 14, 21, 22, 18, 10, 29, 24, 21, 21, 13, 14, 25, 20, 29, 22, 11, 14, 17, 17, 13, 21, 11, 19, 17, 18, 20, 8, 21, 18, 24, 21, 15, 27, 21)

function showText() {
    if (dd.elements.av.visible)
    {
    		document.getElementById("KJVNASB1").classList.remove("down");
    		document.getElementById("KJVNASB2").classList.remove("down");
        dd.elements.av.hide();
    }
    else
    {		
    		document.getElementById("KJVNASB1").classList.add("down");
    		document.getElementById("KJVNASB2").classList.add("down");
        dd.elements.av.show();
    }
}

function isCanvasSupported(){
  var elem = document.createElement('canvas');
  return !!(elem.getContext && elem.getContext('2d'));
}

function checkBrowser(id)
{
	var url = window.location.pathname;
	var filename = url.substring(url.lastIndexOf('/')+1);
	url = document.URL.substring(0,document.URL.lastIndexOf('/'));
	var nocanvas = document.URL.substring(document.URL.lastIndexOf('nocanvas'));
	
	if(!isCanvasSupported() && nocanvas!="nocanvas=true")
	{	
		window.location.href='detail.php?id='+id+'&nocanvas=true';
	}
}

function showPDF(id,version,img)
{
	window.open('../pdf.php?id='+id+'&version='+version+'&img='+img,'_blank');
	//var doc = new jsPDF();
	
	//doc.text(20, 20, 'Hello world!');
	//var interlinear = document.getElementById("myCanvas");
	//doc.text(20,20,"<p>Lorem ipsum dolor sit amet. consectetuer adipiscing elit</p>");
	//doc.fromHTML(interlinear.innerHTML);	
	
	//var a = doc.output('dataurlstring');
	//window.open(a);
}

function showTip(content,title,width,height)
{
	if(title==null)
		title="";	
	if(width==null)
		width=300;
	if(height==null)
		height=200;
	Tip(content, TITLE,title,WIDTH,width,HEIGHT,height,DELAY,1000,FADEIN,100,CLOSEBTN,true,STICKY,true,CENTERMOUSE,true);
}

function selectBookChapAll(b, c, v, s, t) {
	s.options.length = 0;
	t.options.length = 0;
	for (i = 0; i < chaptercount[b]['max']; i++) {
		o = new Option(i + 1, i + 1);
		s.options[i] = o;
	}
	chapterno = chaptercount[b]['count']+c;
	
	for (i = 0; i < versecount[chapterno]; i++) {
		o = new Option(i + 1, i + 1);
		t.options[i] = o;
	}

	if (s.options[c-1])
	    s.options[c-1].selected = true;
	else
	    s.options[0].selected = true;
	t.options[v].selected = true;
}


function selectChapterAll(b, c, v, s) {
    s.options.length = 0;
    chapterno = chaptercount[b]['count']+c;
    
	
	for (i = 0; i < versecount[chapterno]; i++) {
		o = new Option(i + 1, i + 1);
		s.options[i] = o;
		
	}

	if (s.options[v])
	    s.options[v].selected = true;
	else
	    s.options[0].selected = true;
}

function goto(no)
{
	var formbible;
	var sbook;
	var schap;
	var sverse;
	
	if(no==1)
	{
		formbible = document.getElementById("formBible1");
		sbook = document.getElementById("sbook1");
		schap = document.getElementById("schapter1");
		sverse = document.getElementById("sverse1");
		
	}
	else if(no==2)
	{
		formbible = document.getElementById("formBible2");
		sbook = document.getElementById("sbook2");
		schap = document.getElementById("schapter2");
		sverse = document.getElementById("sverse2");
	}
	
		var book = sbook.value;
		var chap = schap.value;
		var verse = sverse.value;
		
		var ccount=0;
		var vcount = 0;
		
		//checkpoint count pada kitab sebelumnya
		ccount = chaptercount[book]['count'] + parseInt(chap);
		
		//menghitung id sampai chapter terakhir di kitab ini saja
		for(var i=0; i < ccount; i++)
		{
			vcount += versecount[i];
		}
		vcount = vcount + parseInt(verse);
		
		
		var uri_old = document.getElementById('uri_old').value;
		var uri_new = document.getElementById('uri_new').value;
		
		if(vcount<23146)
			formbible.action = uri_old+"?id="+vcount;
		else
			formbible.action = uri_new+"?id="+vcount;
		
}
	
function autoCheck(self, ac) {
    if ((self.options[self.selectedIndex].value != 255) && (self.options[self.selectedIndex].value != 253))
        if (self.options[self.selectedIndex].value == 0)
            ac.checked = '';
        else
            ac.checked = 'on';
}

function autoCheck2(self, ac) {
    ac.checked = 'on';
}

function autoSelect(as) {
    if (as.selectedIndex == 0)
        as.selectedIndex = 1;
    else
        as.selectedIndex = 0;
}

function autoSelect2(as, ac) {
    if (as.selectedIndex == 0) {
        as.selectedIndex = 1;
        ac.checked = 'on';
    }
    else {
        as.selectedIndex = 0;
        ac.checked = '';
    }
}

function displayLinkage() {
  jg.clear();
  for (i = 0; i < linkage.length; i++) {
    jg.setColor(warna[linkage[i][2]]);
    jg.setStroke(linkage[i][3]);
    if ((linkage[i][0] != 0) && (linkage[i][1] != 0)) {
      jg.drawLine(x1, (linkage[i][0]-1)*linc+y1, x2, (linkage[i][1]-1)*rinc+y2);
    }
    else if ((linkage[i][0] != 0) && (linkage[i][1] == 0)) {
      y = (linkage[i][0]-1)*linc+y1;
      jg.drawLine(x1, y, x1+10, y);
      jg.fillEllipse(x1+8, y-2, 5, 5);
    }
    else if ((linkage[i][0] == 0) && (linkage[i][1] != 0)) {
      y = (linkage[i][1]-1)*rinc+y2;
      jg.drawLine(x2-10, y, x2, y);
      jg.fillEllipse(x2-12, y-2, 5, 5);
    }
  }
  jg.paint();
}

function resetCanvas() {
  if (touched) {
    if (confirm('reset changes?')) {
      linkage.splice(0, linkage.length);
      initLinkage();
      displayLinkage();
      reset.style.color = '';
      reset.style.backgroundColor = '';
      save.style.color = '';
      save.style.backgroundColor = '';
      done.style.color = '';
      done.style.backgroundColor = '';
      touched = false;
    }
  }
}

function saveCanvas(page, id, strong, check) {
  if (touched || (check && !checked)) {
//  if (confirm('simpan perubahan?')) {
      var save = page + '?id=' + id + '&strong=' + strong;
      for (i = 0; i < linkage.length; i++) {
        if (check)
          save += '&l[' + i + ']=' + linkage[i][0] + '&r[' + i + ']=' + linkage[i][1] + '&t[' + i + ']=' + linkage[i][2] + '&s[' + i + ']=2';
        else
          save += '&l[' + i + ']=' + linkage[i][0] + '&r[' + i + ']=' + linkage[i][1] + '&t[' + i + ']=' + linkage[i][2] + '&s[' + i + ']=' + linkage[i][3];
      }
      window.location = save;
//  }
  }
}

function setType(self) {
	tipe = self.value;
}

function clickWord(self, dir, pos) {
  isZero = false;
  for (i = 0; i < linkage.length; i++) {
    if ((dir == 'l') && (pos == linkage[i][0]) && (linkage[i][1] == 0)) {
      isZero = true;
      break;
    }
    if ((dir == 'r') && (pos == linkage[i][1]) && (linkage[i][0] == 0)) {
      isZero = true;
      break;
    }
  }
  if (!isZero) {
    if ((prevdir == '') || (prevdir == dir)) {
      if (prev != '') {
        prev.style.border = '';
      }
      if (prev == self) {
        prev = '';
        prevdir = '';
        prevpos = 0;
      }
      else {
        self.style.border = 'thin dashed red';
        prev = self;
        prevdir = dir;
        prevpos = pos;
      }
    }
    else {
      self.style.border = 'thin dashed red';
      if (prevdir == 'l') {
        left = prevpos;
        right = pos;
      }
      else {
        left = pos;
        right = prevpos;
      }
      isExist = false;
      isChanged = false;
      for (i = 0; i < linkage.length; i++) {
        if ((linkage[i][0] == left) && (linkage[i][1] == right)) {
//        if (confirm('hapus link ini?')) {
            linkage.splice(i, 1);
	    isChanged = true;
//        }
          isExist = true;
          break;
        }
      }
      if (!isExist) {
        linkage[linkage.length] = new Array(left, right, tipe, Stroke.DOTTED);
        isChanged = true;
      }
      if (isChanged) {
      	touched = true;
        displayLinkage();
        reset.style.color = '#FFFFFF';
        reset.style.backgroundColor = '#FF0000';
        save.style.color = '#FFFFFF';
        save.style.backgroundColor = '#FF0000';
        done.style.color = '#FFFFFF';
        done.style.backgroundColor = '#FF0000';
      }
      self.style.border = '';
      prev.style.border = '';
      prev = '';
      prevdir = '';
      prevpos = 0;
    }
  }
//  event.cancelBubble = true;
}

function dblClickWord(self, dir, pos) {
  isZero = false;
  isChanged = false;
  for (i = 0; i < linkage.length; i++) {
    if ((dir == 'l') && (pos == linkage[i][0])) {
      if (linkage[i][1] == 0) {
//      if (confirm('hapus link ini?')) {
          linkage.splice(i, 1);
	  isChanged = true;
//      }
      }
      isZero = true;
      break;
    }
    if ((dir == 'r') && (pos == linkage[i][1])) {
      if (linkage[i][0] == 0) {
//      if (confirm('hapus link ini?')) {
          linkage.splice(i, 1);
	  isChanged = true;
//      }
      }
      isZero = true;
      break;
    }
  }
  if (!isZero) {
    if (dir == 'l') {
      linkage[linkage.length] = new Array(pos, 0, tipe, Stroke.DOTTED);
    }
    else {
      linkage[linkage.length] = new Array(0, pos, tipe, Stroke.DOTTED);
    }
    isChanged = true;
  }
  if (isChanged) {
    touched = true;
    displayLinkage();
    reset.style.color = '#FFFFFF';
    reset.style.backgroundColor = '#FF0000';
    save.style.color = '#FFFFFF';
    save.style.backgroundColor = '#FF0000';
    done.style.color = '#FFFFFF';
    done.style.backgroundColor = '#FF0000';
  }
  if (prev != '') {
    prev.style.border = '';
    prev = '';
    prevdir = '';
    prevpos = 0;
  }
//  document.selection.empty();
//  event.cancelBubble = true;
}

function clearBox() {
  if (prev != '') {
    prev.style.border = '';
    prev = '';
    prevdir = '';
    prevpos = 0;
  }
}

function changeType(self, id) {
//    if ((self.className == 'undefined') || (self.className == '')) {
    if (self.className == 'd0') {
        self.className = 'd2';
        id.value = 'd2';
    }
    else {
        self.className = 'd0';
        id.value = 'd0';
    }
}

function rotateType(self, id) {
//    if ((self.className != 'undefined') && (self.className != '')) {
    if (self.className != 'd0') {
        newClass = next_class[self.className];
        self.className = newClass;
        id.value = newClass;
    }
}
