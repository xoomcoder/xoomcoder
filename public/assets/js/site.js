// https://plainjs.com/javascript/events/running-code-when-the-document-is-ready-15/
function run() {
    // do something... 2000ms later

    setTimeout(() => {
        var scripts = document.querySelectorAll('script[type="text/xoomcoder"]');
        for(var s=0; s<scripts.length; s++)
        {
            var current = scripts[s];
            // https://developer.mozilla.org/fr/docs/Web/API/Element/insertAdjacentHTML
            current.insertAdjacentHTML('afterend', current.innerHTML);
        }        
    }, 2000);

}

run();
