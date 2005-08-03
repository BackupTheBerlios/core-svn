//shortcut
function E(id) {
    return document.getElementById(id)
}

//przelacza wlasciwosc 'checked' wybranych pol
function switchChecked(v) {
    
    a=document.getElementsByName(v)
    for(i=0; i<a.length; i++)
        a[i].checked = !a[i].checked
}

//sprawdza czy z pol podanych w parametrze ktores jest zaznaczone
//jesli tak, to wyskakuje okienko 'confirm()' z pytaniem podanym w parametrze
//zwraca wtedy wartosc jaka wybral user - true (OK) lub false (CANCEL)
//jesli zadne z pol nie jest zaznaczone, to zwraca false
function askChecked(q, v)
{
    a = document.getElementsByName(v)
    for (i=0; i<a.length; i++) {
        if (a[i].checked) {
            return confirm(q)
        }
    }
    return false
}

//przelacza wlasciwosc 'disabled' danego pola
function toggleDisable(id) {
    
    e = E(id)
    if (e) e.disabled = !e.disabled
}

//shortcut
function dw(s) {
    document.write(s + '<br />')
}



window.onload = function(){
    forms = document.getElementsByTagName('form')

    if (forms.length > 0) {
        for (var k=0; k<forms.length; k++)
        {
            f = forms[k]
            for (var i=0; i<f.elements.length; i++)
            {
                e = f[i]
                n = ('tagName' in e) ? e.tagName.toLowerCase() : ''
                t = ('type' in e) ? e.type.toLowerCase() : ''
                d = ('disabled' in e) ? e.disabled : false
                r = ('readOnly' in e) ? e.readOnly : false
                if ( ( ( n == 'input' && ( t == 'text' || t == 'password' || t == 'checkbox' || t == 'radio') ) 
                        || n == 'select' || n == 'textarea') && !d && !r) {
                    e.focus()
                    k=forms.length + 1 //ucieczka z pierwszej petli
                    break
                }
            }
        }
    }
    
    edCanvas = E('canvas')
}
