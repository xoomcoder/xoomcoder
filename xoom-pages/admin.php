<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- IMPORTANT: NO INDEX -->
    <meta name="robots" content="noindex">

    <title>XoomCoder Admin</title>

    <!-- favicon -->
    <link rel="icon" href="assets/img/xoomcoder.svg">

    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <!-- VueJS will only work inside this div -->
    <div id="app">
        <header>
            <h1>Admin</h1>
            <nav>
                <a href="#page1" v-else @click.prevent="page=1">Login</a>
                <a href="#page2" @click.prevent="page=2">CMS</a>
                <a href="#page3" @click.prevent="page=3">Admin</a>
                <a href="./">Site</a>
                <a href="#logout" v-if="apikey" @click.prevent="logout">Logout</a>
                <a href="#page4" v-else @click.prevent="page=4">base64</a>
            </nav>
        </header>

        <section class="page3" v-show="page==3">
            <h1>Tableau de Bord</h1>

            <h2>Bloc Notes</h2>
            <form id="batchform" action="api" @submit.prevent="sendAjax">
                <h3>keep</h3>
                <textarea id="batchcode" name="command" required cols="80" rows="10" v-model="note"></textarea>
                <h3>forget</h3>
                <textarea name="command2" required cols="80" rows="10">
data/DbRequest?keys=blocnote.read
                </textarea>
                <button @click.prevent="addUpload">ajouter fichier</button>
                <input type="file" v-for="u in uploads" :name="u.name">
                <button id="batchbutton" type="submit">envoyer la commande</button>
                <div class="feedback"></div>
                <!-- partie technique -->
                <input type="hidden" name="classApi" value="Admin">
                <input type="hidden" name="methodApi" value="doCommand">
                <input type="hidden" name="keyApi" v-model="apikey">
            </form>
            <div v-if="data.blocnote">
                <h2>Bloc Notes</h2>
                <div class="rowflex x10col">
                    <article v-for="a in data.blocnote" :key="a.id">
                        <button :title="a.code" @click="actNoteCopy(a)">copy</button>
                        <button :title="a.id" @click="actNoteDelete(a)">delete</button>
                        <h3>{{ a.id }} (x{{ a.nbrun }})</h3>
                    </article>
                </div>
            </div>
        </section>
        <section v-if="page==4">
            <h2>Décodeur B64</h2>
            <div class="rowflex x10col">
                <div v-for="(log, index) in logs">
                    <button :title="log" @click="data64=log;decode64()">{{ index+1 }}</button>
                </div>
            </div>
            <form action="" @submit.prevent="decode64">
                <pre class="feedback" :title="data64">{{ data64decode }}</pre>
                <button type="submit">décoder</button>
                <textarea name="data64" required cols="80" rows="10" v-model="data64"></textarea>
            </form>

        </section>

        <section class="page2" v-if="page==2">
            <h1>CMS</h1>

            <div v-for="table in tables">
                <h2>{{ table.title }}</h2>
                <table v-if="table.name in data">
                    <thead>
                        <tr v-if="data[table.name].length > 0">
                            <td v-for="(v, c) in data[table.name][0]">{{ c }}</td>
                            <td>update</td>
                            <td>delete</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in data[table.name]">
                            <td :title="col" v-for="(val, col) in item">{{ val }}</td>
                            <td><button>update</button></td>
                            <td><button>delete</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </section>

        <section class="page1" v-if="page==1">
            <h1>Login avec API Key</h1>
            <form action="api" @submit.prevent="sendAjax">
                <input type="password" name="keyApi" required>
                <button type="submit">vérifier votre clé API</button>
                <div class="feedback"></div>
                <!-- partie technique -->
                <input type="hidden" name="classApi" value="Admin">
                <input type="hidden" name="methodApi" value="checkApiKey">
            </form>
        </section>

        <footer>
            <p>{{ test }}</p>
        </footer>
    </div>

    <!-- TODO: change to the official Vuejs version 3 (when available) -->
    <script src="https://unpkg.com/vue@next"></script>

    <script>

// https://v3.vuejs.org/guide/introduction.html#getting-started
const appConfig = {
    data() {
        return {
            // add Here your JS properties to sync with HTML
            note:             '',
            data:             {},
            tables:           [ 
                { name: 'content', title: 'contents' },
                { name: 'user', title: 'users' },
                { name: 'manymany', title: 'manymanys' },
                { name: 'blocnote', title: 'notes' },
            ],
            uploads:          [],
            data64decode:     '',
            data64:           '',
            logs:             [],
            login:            '',
            apikey:           '',
            page:             1,
            test:             'XoomCoder.com'
        }
    },
    mounted () {
        // load apikey if present from localStorage to Vue
        var apikey = localStorage.getItem('apikey');
        if (apikey)
            this.apikey = apikey;
        else
            this.page = 1;
    },
    methods: {
        actNoteDelete (a) {
            this.note = a.code;
            let fd = new FormData;
            fd.append('classApi', 'Admin');
            fd.append('methodApi', 'doCommand');
            fd.append('keyApi', this.apikey);
            let command = `
            DbDelete?table=blocnote&id=${a.id}
            data/DbRequest?keys=blocnote.read
            `;
            fd.append('command2', command);
            console.log(fd);
            this.sendAjax({ 'formdata' : fd });
        },
        actNoteCopy (a) {
            this.note = a.code;
        },
        addUpload() {
            this.uploads.push({ name: 'upload' + this.uploads.length });
        },
        decode64 () {
            try {
                this.data64decode = atob(this.data64);
            }
            catch(e) {
                console.log(e);
            }
        },
        logout () {
            // reset api key
            this.apikey = '';
            this.page   = 1;
            localStorage.removeItem('apikey');

            // reload page to reset JS
            location.reload();
        },
        // add here your functions/methods
        sendAjax (event) {
            let fd = null;
            if (event.target) 
                // user action
                fd = new FormData(event.target);
            else if (event.formdata) 
                // dev action
                fd = event.formdata;

            fetch('api', {
                method: 'POST',
                body: fd
            })
            .then((response) => {
                response
                    .json()
                    .then((json) => {
                        let ajaxpack = {
                            'event': event,
                            'fd' : fd,
                            'response': response,
                            'json': json 
                            };
                        for(m in xcb) {
                            xcb[m](ajaxpack);
                        }
                    })
            });
        }
    }
};

var app = Vue.createApp(appConfig).mount('#app');   // css selector to link with HTML
var xcb = {};
// add callbacks to activate on ajax response
xcb.feedback = function (ajaxpack)  {
    if ('feedback' in ajaxpack.json) {
        if ('target' in ajaxpack.event) {
            var f = ajaxpack.event.target.querySelector('.feedback');
            if (f) f.innerHTML = ajaxpack.json.feedback;
        }
    }

    if ('commandLogRead' in ajaxpack.json) {
        app.logs = ajaxpack.json.commandLogRead;
        console.log(app.logs);
    }
};

xcb.login = function (ajaxpack) {
    if (! ('login' in ajaxpack.json)) return;

    // store the api key
    localStorage.setItem('apikey', ajaxpack.json.login);
    app.apikey = ajaxpack.json.login;
};

xcb.test = function (ajaxpack)  {
    console.log(ajaxpack);
};

xcb.data = function (ajaxpack) {
    if (! ('data' in ajaxpack.json)) return;

    // update tables in data from response
    for(table in ajaxpack.json.data) {
        app.data[table] = ajaxpack.json.data[table];
    }
};

xcb.autorun = function (ajaxpack) {
    if (! ('autorun' in ajaxpack.json)) return;

    console.log(ajaxpack.json.autorun);
    // launch initial request
    batchcode.innerHTML = ajaxpack.json.autorun;
    batchbutton.click();
}

/**
 * very dangerous ?!
 */
xcb.jseval = function (ajaxpack) {
    if (! ('jseval' in ajaxpack.json)) return;
    // run js code sent from server
    eval(ajaxpack.json.jseval);
}
    </script>
</body>
</html>

<!-- 

Documentation sur VueJS 3

https://v3.vuejs.org/guide/installation.html#release-notes

https://v3.vuejs.org/guide/introduction.html#getting-started

https://v3.vuejs.org/guide/installation.html#release-notes

-->
