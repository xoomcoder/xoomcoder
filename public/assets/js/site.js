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
    fetch('api', {
        method: 'POST',
        body: fd
    })
    // process the response in json format
    .then(function(response) {
        response.json()
        .then(function(json){
            console.log(json);

            // show the feedback to user
            if ('feedback' in json) {
                var feedback = event.target.querySelector('.feedback');
                if (feedback) feedback.innerHTML = json.feedback;
            }
        });
    })
}

// https://www.w3schools.com/js/js_cookies.asp
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
      var c = ca[i];
      while (c.charAt(0) == ' ') {
        c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
      }
    }
    return "";
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
        
        var videos = document.querySelectorAll('pre code.language-youtube');
        for(var s=0; s<videos.length; s++)
        {
            var current = videos[s];
            var parsedUrl = new URL(current.innerHTML);
            const youtubeId = parsedUrl.searchParams.get('v');
            // https://developer.mozilla.org/fr/docs/Web/API/Element/insertAdjacentHTML
            current.parentNode.insertAdjacentHTML('beforebegin', 
            //`<iframe title="youtube" width="100%" height="315" src="https://www.youtube.com/embed/${youtubeId}"></iframe>`
            //`<iframe width="560" height="315" src="https://www.youtube.com/embed/xm4gcoVmTJs" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`
            `<iframe title="tutoriels youtube" width="560" height="315" src="https://www.youtube.com/embed/${youtubeId}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen=""></iframe>`            );
        }   
    }, 4000);

    // install ajax
    addAction("form[action=api]", "submit", cbAjax);

    // keep screen resolution in cookies
    let scook = {
        w: screen.width,
        h: screen.height,
    };
    setCookie("screen", JSON.stringify(scook), 1);

}

run();
