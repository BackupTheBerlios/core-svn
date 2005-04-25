// JavaScript Document

// Funkcja odpowiadaj±ca za obs³ugê linków
function visit(url) {
	
	if ((url.indexOf('http://') != -1) || (url.indexOf('ftp') != -1)) {
		
		window.open(url);
	} else if (url.length != 0) {
		
		window.open('http://' + url);
	}
}


// Funkcja odpowiadaj±ca za obs³ugê adresów e-mail
function mail(user, domain, subject) {
	
	locationstring = "mailto:" + user + "@" + domain + "?Subject=" + subject;
	window.location = locationstring;
}


// Funkcja odpowiadaj±ca za otwieranie nowego okna
function foto(path, w, h) {
	
	window.open(path, "", "noresize=0, margin=0, width=" + w + ", height=" + h + "");
}


// Funkcja sprawdzajaca formularz
function checkform() {
	
	var email = document.newsletter_form.email.value; //podajemy nazwe formularza
	// Jak ja uwielbiam wszelkie wzorce i inne pojebane dopasowania ;-)
	pattern = new RegExp( '^([a-zA-Z0-9\-\.\_]+)(\@)([a-zA-Z0-9\-\.]+)([\.])([a-zA-Z]{2,4})$' );
	
	if ( !pattern.test(email) ) {
		
		alert("Podaj adres e-mail");
		document.newsletter_form.email.focus();
		return false;
	}
	return true;
}


var tgs = new Array('div','body','td');
var szs = new Array('10px','11px','12px','13px','14px');
var startSz = 1;

function text_resize(trgt, inc) {
	
	if (!document.getElementById) return
	var d = document,cEl = null,sz = startSz,i,j,cTags;
	
	sz += inc;
	if ( sz < 0 ) sz = 0;
	if ( sz > 4 ) sz = 4;
	startSz = sz;
		
	if ( !( cEl = d.getElementById( trgt ) ) ) cEl = d.getElementsByTagName( trgt )[ 0 ];

	cEl.style.fontSize = szs[ sz ];

	for ( i = 0 ; i < tgs.length ; i++ ) {
		
		cTags = cEl.getElementsByTagName( tgs[ i ] );
		for ( j = 0 ; j < cTags.length ; j++ ) cTags[ j ].style.fontSize = szs[ sz ];
	}
}

function s(e) {
	
	var elementId = document.getElementById(e);
	if (elementId == null) return;
	if (elementId.style.display == '') {
		
		elementId.style.display = 'none';
		var ImgSrc = document.getElementById("i" + e);
		ImgSrc.src = "layout/plus.gif";
	} else {
		
		elementId.style.display = '';
		var ImgSrc = document.getElementById("i" + e);
		ImgSrc.src = "layout/minus.gif";
	}
}

// Funckja obslugujaca dwa przyciski w jednym formularzu: zapisz | wypisz
function send_data(selection) {

	// Jesli formularz zatwierdzony bez bledów
	if (checkform()) {
		
		document.newsletter_form.method = "post";
		
		// Jesli nacisniety jest przycisk 'zapisz'
		if ( selection == "sign_in" ) {
			// Przekierowanie do odpowiedniego url'a
			document.newsletter_form.action = "index.php?p=newsletter&m=sign_in";
		}
		
		// Jesli nacisniety jest przycisk 'wypisz'
		if ( selection == "sign_out" ) {
			// Przekierowanie do odpowiedniego url'a
			document.newsletter_form.action = "index.php?p=newsletter&m=sign_out";
		}
		// Zatwierdzenie formularza
		document.newsletter_form.submit();	
	}
}

/* Jak uzyc?
Nadajemy formularzowi nazwe <form name="newsletter_form">
Tworzymy dwa przyciki <input> dajac przy jednym akcje:
name="sign_in" onclick="send_data(this.name)"
, a przy drugim:
name="sign_out" onclick="send_data(this.name)"

Zasadniczo to by bylo na tyle. RegExp'y sprawdzane, formularz obsluguje dwa zdarzenia, których liczba moze byc
mowiac w skrocie nieograniczona. Przekonuje sie do JavaScript ;-)
*/ 