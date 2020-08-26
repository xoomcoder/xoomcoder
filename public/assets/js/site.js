function addAction(selector, eventname, callback)
{
    var list = document.querySelectorAll(selector);
    for(var l=0; l < list.length; l++) {
        var el = list[l];
        el.addEventListener(eventname, callback);
    }    
}

function cbAjax (event)
{
    event.preventDefault();

    // fill the form data
    var fd = new FormData(event.target);

    // send the form data
    fetch("api", {
        method:"POST",
        body: fd
    })
    .then(function(response) {
        response.json()
        .then(function(json){
            console.log(json);
        });
    })
}

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

    // install ajax
    addAction("form[action=api]", "submit", cbAjax);
}

run();
