prev = '';
prevdir = '';
prevpos = 0;
touched = false;
tipe = 1;

function showText() {
    if (dd.elements.av.visible)
    {
    		document.getElementById("KJVNASB").classList.remove("down");
        dd.elements.av.hide();
    }
    else
    {		document.getElementById("KJVNASB").classList.add("down");
        dd.elements.av.show();
    }
}


function showPDF()
{
	window.open('pdf.php','_blank');
}

function changeBook(chap)
{
	var sbook = document.getElementById("sbook");
	//alert(sbook.value.substr(sbook.value.search(":")+1));
	var schap = document.getElementById("schapter");
	schap.innerHTML='';
	var chap_count = sbook.value.substr(sbook.value.search(":")+1);
	for(var i=1;i<=chap_count;i++)
	{	
		var option = document.createElement("option");
		option.text=i;
		option.value=i;
		if(i==chap)
		option.selected=true;
		
		try{
			schap.add(option,schap.option[null]);
		}
		catch(e)
		{
			schap.add(option,null);
		}
	}
}

function changeChapter(verse)
{
	var sbook = document.getElementById("sbook");
	var schap = document.getElementById("schapter");
	var sverse = document.getElementById("sverse");
	sverse.innerHTML='';
	var book = sbook.value.substr(0,sbook.value.search(":"));
	var chap = schap.value;
	$.ajax({
		url:"books.php",
		data:{act:'change',book:book,chap:chap}
	}).done(function(verse_count){
		//alert(verse_count);
		for(var i=1;i<=verse_count;i++)
		{	
			var option = document.createElement("option");
			option.text=i;
			option.value=i;
			if(i==verse)
			option.selected=true;
			try{
				sverse.add(option,sverse.option[null]);
			}
			catch(e)
			{
				sverse.add(option,null);
			}
		}
	
	});
}

function goto()
{
	var sbook = document.getElementById("sbook");
	var schap = document.getElementById("schapter");
	var sverse = document.getElementById("sverse");
	
	var book = sbook.value.substr(0,sbook.value.search(":"));
	var chap = schap.value;
	var verse = sverse.value;
	
	//create hidden to passing book, chapter, and verse
	/*var body = document.body;
	var hbook = document.createElement("hidden");
	hbook.id="hbook";
	hbook.value=book;
	var hchap = document.createElement("hidden");
	hchap.id="hchap";
	hchap.value=chap;
	var hverse = document.createElement("hidden");
	hverse.id="hverse";
	hverse.value=verse;
	document.body.appendChild(hbook);
	document.body.appendChild(hchap);
	document.body.appendChild(hverse);*/
	
	$.ajax({
		url:"books.php",
		data:{act:"goto",book:book,chap:chap,verse:verse}
	}).done(function(id){
		//alert(id);
		var uri_old = document.getElementById('uri_old').value;
		var uri_new = document.getElementById('uri_new').value;
		
		if(id<23146)
			window.location.href =uri_old+"?id="+id;
		else
			window.location.href =uri_new+"?id="+id;
	});
	
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
