<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- IMPORTANT: NO INDEX -->
    <meta name="robots" content="noindex">

    <title>XoomCoder Admin</title>
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <!-- VueJS will only work inside this div -->
    <div id="app">
        <header>
            <h1>Admin</h1>
            <nav>
                <a href="#page1" @click.prevent="page=1">Admin</a>
                <a href="#page2" @click.prevent="page=2">CMS</a>
                <a href="./">Site</a>

                <a href="#logout" v-if="apikey" @click.prevent="logout">Logout</a>
                <a href="#page3" v-else @click.prevent="page=3">Login</a>
            </nav>
        </header>

        <section class="page1" v-show="page==1">
            <h1>Tableau de Bord</h1>

            <h2>Panneau de Commande</h2>
            <form id="batchform" action="api" @submit.prevent="sendAjax">
                <textarea id="batchcode" name="command" required cols="80" rows="10"></textarea>
                <button @click.prevent="addUpload">ajouter fichier</button>
                <input type="file" v-for="u in uploads" :name="u.name">
                <button id="batchbutton" type="submit">envoyer la commande</button>
                <div class="feedback"></div>
                <!-- partie technique -->
                <input type="hidden" name="classApi" value="Admin">
                <input type="hidden" name="methodApi" value="doCommand">
                <input type="hidden" name="keyApi" v-model="apikey">
            </form>

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

        <section class="page3" v-if="page==3">
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
            data:             {},
            tables:           [ 
                { name: 'content', title: 'contents' },
                { name: 'user', title: 'users' },
                { name: 'manymany', title: 'manymanys' },
            ],
            uploads:          [],
            manymanys:        [],
            contents:         [],
            users:            [],
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
            this.page = 3;
    },
    methods: {
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
            this.page   = 3;
            localStorage.removeItem('apikey');

            // reload page to reset JS
            location.reload();
        },
        // add here your functions/methods
        sendAjax (event) {
            var fd = new FormData(event.target);
            fetch('api', {
                method: 'POST',
                body: fd
            })
            .then((response) => {
                response
                    .json()
                    .then((json) => {
                        var ajaxpack = {
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
        var f = ajaxpack.event.target.querySelector('.feedback');
        if (f) f.innerHTML = ajaxpack.json.feedback;
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

xcb.users = function (ajaxpack) {
    if (! ('users' in ajaxpack.json)) return;

    app.users = ajaxpack.json.users;
    app.data.user = ajaxpack.json.users;
};
xcb.contents = function (ajaxpack) {
    if (! ('contents' in ajaxpack.json)) return;

    app.contents = ajaxpack.json.contents;
    app.data.content = ajaxpack.json.contents;
};
xcb.manymanys = function (ajaxpack) {
    if (! ('manymanys' in ajaxpack.json)) return;

    app.manymanys = ajaxpack.json.manymanys;
    app.data.manymany = ajaxpack.json.manymanys;
};

xcb.autorun = function (ajaxpack) {
    if (! ('autorun' in ajaxpack.json)) return;

    console.log(ajaxpack.json.autorun);
    // launch initial request
    batchcode.innerHTML = ajaxpack.json.autorun;
    batchbutton.click();
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
