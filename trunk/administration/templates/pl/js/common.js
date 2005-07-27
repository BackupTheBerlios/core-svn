function E(id) {
    return document.getElementById(id)
}

function form_it() {
    forms = document.getElementsByTagName('form')
    if (forms.length > 0) {
        f = forms[0]
        var lp=0
        //for (i in f) {alert(i.tagName); if (lp++ > 4) break}
        for (i=0; i<f.length; i++)
        {
            t = f[i].type
            n = f[i].tagName
            if ( ( n == 'input' && ( t == 'text' || t == 'password' || t == 'checkbox' || t == 'radio')) || n == 'select') {
                f[i].focus()
                break
            }
        }
    }
}

