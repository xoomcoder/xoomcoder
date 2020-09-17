<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, , maximum-scale=1.0, user-scalable=no, shrink-to-fit=no">
    <!-- IMPORTANT: NO INDEX -->
    <meta name="robots" content="noindex">
    <!-- favicon -->
    <link rel="icon" href="assets/img/xoomcoder.svg">

    <title>XoomCoder Studio</title>
    <style>
html, body {
    width:100%;
    padding:0;
    margin:0;
    font-size:12px;
    text-align: center;
}

* {
    box-sizing: border-box;
    width:100%;
    /* transition: all 0.5s ease-out; */ /* TOO BIG */
}
h2 {
    border: 1px solid #cccccc;
    background-color: #eeeeee;
    padding: 1rem;
}
header nav a {
    display: inline-block;
}
article:hover {
    background-color: #eeeeee;
    cursor:pointer;
}

.toolbar {
    position:fixed;
    top:2rem;
    right:2rem;
    width:4vmin;
    height:4vmin;
    z-index:9999;
    cursor:pointer;
}
.options {
    position:fixed;
    top:100%;
    left:0;
    width:100%;
    height:100%;
    z-index:999;
    transition: all 0.5s ease-out;
    background-color: rgba(200,200,200,0.9);
}
.options.active {
    top:0;
    overflow-y:auto;
}

form {
    padding:1rem;
}
form label {
    margin:0.5rem 0;
    display: inline-block;
}
form label span {
    display: inline-block;
    padding: 0.5rem;
}
input, textarea, button {
    padding:0.5rem;
}
.feedback {
    padding:1rem;
    font-weight:700;
}

.w50 {
    width:50%;
}
.w100 {
    width:100%;
}

/* TABLES */
table {
    table-layout:fixed;
    padding:1rem;
}
thead td {
    background-color:#cccccc;
}
td {
    border: 1px solid #dddddd;
    padding:0.25rem;
    overflow-x:hidden;
    text-align:left;
}

@media (min-width: 480px)
{
    section {
        display:flex;
        flex-wrap: wrap;
    }
    section article {
        margin:0.25rem;
        width: calc(100% / 2 - 0.5rem);
    }
}
@media (min-width: 640px)
{
    section article {
        width: calc(100% / 3 - 0.5rem);
    }
}
@media (min-width: 720px)
{
    section article {
        width: calc(100% / 4 - 0.5rem);
    }
}
@media (min-width: 960px)
{
    section article {
        width: calc(100% / 5 - 0.5rem);
    }
}
@media (min-width: 1200px)
{
    section article {
        width: calc(100% / 6 - 0.5rem);
    }
}


/* SPECIFIC CODE */
td.id {
    width:40px;
}
td.update, td.delete {
    width:80px;
}
td.code {
    width:40vmin;
}
td.code pre {
    overflow-x: auto;
}
td.datePublication {
    width:160px;
}

form label span {
    display:none;
}
    </style>
</head>
<body>
    <div class="page">
        <mypage></mypage>
    </div>

    <script type="module">
// load Vue from module        
import * as Vue from 'https://cdn.jsdelivr.net/npm/vue@3.0.0-rc.1/dist/vue.esm-browser.js';

let mydata = {
    h1: 'Studio',
};

let appconf = {
    data () {
        return mydata;
    }
};

let mycompo = {
    mypage : './vue-mypage.vjs', 
};

const app = Vue.createApp(appconf);

// dynamic loading of components
// https://v3.vuejs.org/guide/component-dynamic-async.html#async-components
let myloader = function (name, url)
{
    let interload = async function (resolve, reject) {
        let fd = new FormData;
        fd.append('loginToken', sessionStorage.getItem('loginToken')); 
        fd.append('classApi', 'Member');
        fd.append('methodApi', 'runVue');
        fd.append('compoName', name);
        fd.append('compoUrl', url);

        let response    = await fetch('api', {
            method: 'POST',
            body: fd
        }); 
        let json = await response.json();
        // server debug
        if (json.debug) console.log(json.debug);

        if(name in json) {
            let code = `
            Object.assign({}, 
            ${json[name]}
            );
            `;
            let compocode = eval(code);
            resolve(compocode);

            // FIXME: VueJS SHOULD ADD A DEFAULT COMPONENT LOAADER CALLBACK (AS IN PHP...) 
            // register new sub-components needed
            if (json.xcompo) {
                for(let c in json.xcompo) {
                    myloader(c, json.xcompo[c]);
                }
            }

        }
    }
    let asyncComp = Vue.defineAsyncComponent(() => new Promise(interload));
    app.component(name, asyncComp);
}
// load the components
for(let c in mycompo) {
    myloader(c, mycompo[c]);
}

app.mount('.page');

    </script>
</body>
</html>