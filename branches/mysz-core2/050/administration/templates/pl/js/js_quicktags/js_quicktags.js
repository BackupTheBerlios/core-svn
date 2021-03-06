// JS QuickTags version 1.1
//
// Copyright (c) 2002-2004 Alex King
// http://www.alexking.org/
//
// Licensed under the LGPL license
// http://www.gnu.org/copyleft/lesser.html
//
// **********************************************************************
// This program is distributed in the hope that it will be useful, but
// WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
// **********************************************************************
//
// This JavaScript will insert the tags below at the cursor position in IE and 
// Gecko-based browsers (Mozilla, Camino, Firefox, Netscape). For browsers that 
// do not support inserting at the cursor position (Safari, OmniWeb) it appends
// the tags to the end of the content.
//
// The variable 'edCanvas' must be defined as the <textarea> element you want 
// to be editing in. See the accompanying 'index.html' page for an example.

help_ed_bold = "Tekst wzmocniony: <strong>tekst</strong>";
help_ed_italic = "Tekst kursyw�: <em>tekst</em>";
help_ed_under = "Tekst podkre�lony: <u>tekst</u>";
help_ed_ul = "Lista nieuporz�dkowana: <ul />";
help_ed_ol = "Lista uporz�dkowana: <ol />";
help_ed_li = "Element listy: <li>tekst</li>";
help_ed_link = "Odno�nik: <a href=\"adresURI\">tekst</a>";
help_ed_img = "Wstaw obrazek: <img src=\"nazwa_obrazka\" alt=\"tekst obja�niaj�cy\" />";
help_ed_abbr = "Definicja skr�tu: <abbr title=\"definicja\">skr�t</abbr>";
help_ed_more = "Podziel tekst: tekst [podziel] dalsza cz��";
help_ed_block = "Cytuj: <blockquote>cytat</blockquote>";
help_ed_pre = "Wstaw preformatowany tekst: <pre>tekst</pre>";
help_ed_close = "Zamknij wszystkie otwarte tagi HTMLCode";

function helpline2(help) {
    if ( (e = E('helpline')) ) e.value = eval("help_" + help);
}

var edButtons = new Array();
var edLinks = new Array();
var edOpenTags = new Array();

function edButton(id, display, tagStart, tagEnd, open) {
	this.id = id;				// used to name the toolbar button
	this.display = display;		// label on button
	this.tagStart = tagStart; 	// open tag
	this.tagEnd = tagEnd;		// close tag
	this.open = open;			// set to -1 if tag does not need to be closed
}

edButtons[edButtons.length] = new edButton('ed_bold'
                                          ,'b'
                                          ,'<strong>'
                                          ,'</strong>'
                                          );

edButtons[edButtons.length] = new edButton('ed_italic'
                                          ,'i'
                                          ,'<em>'
                                          ,'</em>'
                                          );

edButtons[edButtons.length] = new edButton('ed_under'
                                          ,'u'
                                          ,'<u>'
                                          ,'</u>'
                                          );

edButtons[edButtons.length] = new edButton('ed_link'
                                          ,'link'
                                          ,''
                                          ,'</a>'
                                          ); // special case

edButtons[edButtons.length] = new edButton('ed_img'
                                          ,'img'
                                          ,''
                                          ,''
                                          ,-1
                                          ); // special case

edButtons[edButtons.length] = new edButton('ed_ul'
                                          ,'ul'
                                          ,'<ul>\n'
                                          ,'</ul>\n\n'
                                          );

edButtons[edButtons.length] = new edButton('ed_ol'
                                          ,'ol'
                                          ,'<ol>\n'
                                          ,'</ol>\n\n'
                                          );

edButtons[edButtons.length] = new edButton('ed_li'
                                          ,'li'
                                          ,'\t<li>'
                                          ,'</li>\n'
                                          );

edButtons[edButtons.length] = new edButton('ed_block'
                                          ,'b-quote'
                                          ,'<blockquote>'
                                          ,'</blockquote>'
                                          );

edButtons[edButtons.length] = new edButton('ed_pre'
                                          ,'pre'
                                          ,'<pre>'
                                          ,'</pre>'
                                          );
edButtons[edButtons.length] = new edButton('ed_abbr'
                                          ,'abbr'
                                          ,''
                                          ,'</abbr>'
                                          );
edButtons[edButtons.length] = new edButton('ed_more'
                                          ,'podziel'
                                          ,'[podziel]'
                                          ,''
                                          ,-1
                                          );

function edLink(display, URL, newWin) {
	this.display = display;
	this.URL = URL;
	if (!newWin) {
		newWin = 0;
	}
	this.newWin = newWin;
}


edLinks[edLinks.length] = new edLink('alexking.org'
                                    ,'http://www.alexking.org/'
                                    );

edLinks[edLinks.length] = new edLink('CORE Cms'
                                    ,'http://core.no1-else.com/'
                                    );


function edShowButton(button, i) {
	if (button.id == 'ed_img') {
		document.write('<input type="button" id="' + button.id + '" class="ed_button" onclick="edInsertImage(edCanvas);" value="' + button.display + '" onmouseover="helpline2(\'' + button.id  + '\')" />');
	}
	else if (button.id == 'ed_link') {
		document.write('<input type="button" id="' + button.id + '" class="ed_button" onclick="edInsertLink(edCanvas, ' + i + ');" value="' + button.display + '" onmouseover="helpline2(\'' + button.id  + '\')" />');
	}
	else if (button.id == 'ed_abbr') {
		document.write('<input type="button" id="' + button.id + '" class="ed_button" onclick="edInsertAbbr(edCanvas, ' + i + ');" value="' + button.display + '" onmouseover="helpline2(\'' + button.id  + '\')" />');
	}
	else {
		document.write('<input type="button" id="' + button.id + '" class="ed_button" onclick="edInsertTag(edCanvas, ' + i + ');" value="' + button.display + '" onmouseover="helpline2(\'' + button.id  + '\')" />');
	}
}

function edShowLinks() {
	var tempStr = '<select onchange="edQuickLink(this.options[this.selectedIndex].value, this);"><option value="-1" selected>(Quick Links)</option>';
	for (i = 0; i < edLinks.length; i++) {
		tempStr += '<option value="' + i + '">' + edLinks[i].display + '</option>';
	}
	tempStr += '</select>';
	document.write(tempStr);
}

function edAddTag(button) {
	if (edButtons[button].tagEnd != '') {
		edOpenTags[edOpenTags.length] = button;
		document.getElementById(edButtons[button].id).value = '*' + document.getElementById(edButtons[button].id).value;
	}
}

function edRemoveTag(button) {
	for (i = 0; i < edOpenTags.length; i++) {
		if (edOpenTags[i] == button) {
			edOpenTags.splice(i, 1);
			document.getElementById(edButtons[button].id).value = document.getElementById(edButtons[button].id).value.replace('*', '');
		}
	}
}

function edCheckOpenTags(button) {
	var tag = 0;
	for (i = 0; i < edOpenTags.length; i++) {
		if (edOpenTags[i] == button) {
			tag++;
		}
	}
	if (tag > 0) {
		return true; // tag found
	}
	else {
		return false; // tag not found
	}
}	

function edCloseAllTags() {
	var count = edOpenTags.length;
	for (o = 0; o < count; o++) {
		edInsertTag(edCanvas, edOpenTags[edOpenTags.length - 1]);
	}
}

function edQuickLink(i, thisSelect) {
	if (i > -1) {
		var newWin = '';
		if (edLinks[i].newWin == 1) {
			newWin = ' target="_blank"';
		}
		var tempStr = '<a href="' + edLinks[i].URL + '"' + newWin + '>' 
		            + edLinks[i].display
		            + '</a>';
		thisSelect.selectedIndex = 0;
		edInsertContent(edCanvas, tempStr);
	}
	else {
		thisSelect.selectedIndex = 0;
	}
}

function edSpell(myField) {
	var word = '';
	if (document.selection) {
		myField.focus();
	    var sel = document.selection.createRange();
		if (sel.text.length > 0) {
			word = sel.text;
		}
	}
	else if (myField.selectionStart || myField.selectionStart == '0') {
		var startPos = myField.selectionStart;
		var endPos = myField.selectionEnd;
		if (startPos != endPos) {
			word = myField.value.substring(startPos, endPos);
		}
	}
	if (word == '') {
		word = prompt('Wpisz poszukiwane s�owo:', '');
	}
	if (word != '') {
		window.open('http://dictionary.reference.com/search?q=' + escape(word));
	}
}

function edToolbar() {
	document.write('<div id="ed_toolbar">');
	for (i = 0; i < edButtons.length; i++) {
		edShowButton(edButtons[i], i);
	}
	document.write('<input type="button" id="ed_close" class="ed_button" onclick="edCloseAllTags();" value="Domknij tagi" onmouseover="helpline2(\'ed_close\')" />');
	//document.write('<input type="button" id="ed_spell" class="ed_button" onclick="edSpell(edCanvas);" value="Dict" />');
//	edShowLinks();
	document.write('</div>');
}

// insertion code

function edInsertTag(myField, i) {
	//IE support
	if (document.selection) {
		myField.focus();
	    sel = document.selection.createRange();
		if (sel.text.length > 0) {
			sel.text = edButtons[i].tagStart + sel.text + edButtons[i].tagEnd;
		}
		else {
			if (!edCheckOpenTags(i) || edButtons[i].tagEnd == '') {
				sel.text = edButtons[i].tagStart;
				edAddTag(i);
			}
			else {
				sel.text = edButtons[i].tagEnd;
				edRemoveTag(i);
			}
		}
		myField.focus();
	}
	//MOZILLA/NETSCAPE support
	else if (myField.selectionStart || myField.selectionStart == '0') {
		var startPos = myField.selectionStart;
		var endPos = myField.selectionEnd;
		var cursorPos = endPos;
		var scrollTop = myField.scrollTop;

		if (startPos != endPos) {
			myField.value = myField.value.substring(0, startPos)
			              + edButtons[i].tagStart
			              + myField.value.substring(startPos, endPos) 
			              + edButtons[i].tagEnd
			              + myField.value.substring(endPos, myField.value.length);
			cursorPos += edButtons[i].tagStart.length + edButtons[i].tagEnd.length;
		}
		else {
			if (!edCheckOpenTags(i) || edButtons[i].tagEnd == '') {
				myField.value = myField.value.substring(0, startPos) 
				              + edButtons[i].tagStart
				              + myField.value.substring(endPos, myField.value.length);
				edAddTag(i);
				cursorPos = startPos + edButtons[i].tagStart.length;
			}
			else {
				myField.value = myField.value.substring(0, startPos) 
				              + edButtons[i].tagEnd
				              + myField.value.substring(endPos, myField.value.length);
				edRemoveTag(i);
				cursorPos = startPos + edButtons[i].tagEnd.length;
			}
		}
		myField.focus();
		myField.selectionStart = cursorPos;
		myField.selectionEnd = cursorPos;
		myField.scrollTop = scrollTop;
	}
	else {
		if (!edCheckOpenTags(i) || edButtons[i].tagEnd == '') {
			myField.value += edButtons[i].tagStart;
			edAddTag(i);
		}
		else {
			myField.value += edButtons[i].tagEnd;
			edRemoveTag(i);
		}
		myField.focus();
	}
}

function edInsertContent(myField, myValue) {
	//IE support
	if (document.selection) {
		myField.focus();
		sel = document.selection.createRange();
		sel.text = myValue;
		myField.focus();
	}
	//MOZILLA/NETSCAPE support
	else if (myField.selectionStart || myField.selectionStart == '0') {
		var startPos = myField.selectionStart;
		var endPos = myField.selectionEnd;
		myField.value = myField.value.substring(0, startPos)
		              + myValue 
                      + myField.value.substring(endPos, myField.value.length);
		myField.focus();
		myField.selectionStart = startPos + myValue.length;
		myField.selectionEnd = startPos + myValue.length;
	} else {
		myField.value += myValue;
		myField.focus();
	}
}

function edInsertLink(myField, i, defaultValue) {
	if (!defaultValue) {
		defaultValue = 'http://';
	}
	if (!edCheckOpenTags(i)) {
		var URL = prompt('Wpisz adres docelowy:' ,defaultValue);
		if (URL) {
			edButtons[i].tagStart = '<a href="' + URL + '">';
			edInsertTag(myField, i);
		}
	}
	else {
		edInsertTag(myField, i);
	}
}

function edInsertAbbr(myField, i, defaultValue) {
	if (!defaultValue) {
		defaultValue = '';
	}
	if (!edCheckOpenTags(i)) {
		var TITLE = prompt('Wpisz opis:' ,defaultValue);
		if (TITLE) {
			edButtons[i].tagStart = '<abbr title="' + TITLE + '">';
			edInsertTag(myField, i);
		}
	}
	else {
		edInsertTag(myField, i);
	}
}

function edInsertImage(myField) {
	var myValue = prompt('Wpisz adres do obrazka:', 'http://');
	if (myValue) {
		myValue = '<img src="' 
				+ myValue 
				+ '" alt="' + prompt('Podaj opis obrazka:', '') 
				+ '" />';
		edInsertContent(myField, myValue);
	}
}
