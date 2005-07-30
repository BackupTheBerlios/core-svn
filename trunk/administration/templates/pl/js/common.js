function E(id) {
    return document.getElementById(id)
}
function switchChecked(v) {
    
    a=document.getElementsByName(v)
    for(i=0; i<a.length; i++) {
        
        if (a[i].checked) {a[i].checked = ''}
        else {a[i].checked = 'true' }
    }
}
function askChecked(q, v)
{
    a = document.getElementsByName(v)
    c = false
    for (i=0; i<a.length; i++) {
        if (a[i].checked) {
            c = true
            break
        }
    }
    return c ? confirm(q) : false
}
function toggleDisable(id) {
    e = E(id)
    if (e) e.disabled = e.disabled ? '' : 'true'
}

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
                        || n == 'select' || n == 'textarea') && d == false && r == false){
                    e.focus()
                    k=forms.length + 1
                    break
                }
            }
        }
    }
    
    edCanvas = E('canvas')
}
