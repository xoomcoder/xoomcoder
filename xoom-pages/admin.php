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

        <section class="page1" v-if="page==1">
            <h1>Tableau de Bord</h1>

            <h2>Panneau de Commande</h2>
            <form action="api" @submit.prevent="sendAjax">
                <textarea name="command" required cols="80" rows="10"></textarea>
                <button type="submit">envoyer la commande</button>
                <div class="feedback"></div>
                <!-- partie technique -->
                <input type="hidden" name="classApi" value="Admin">
                <input type="hidden" name="methodApi" value="doCommand">
                <input type="hidden" name="keyApi" v-model="apikey">
            </form>
        </section>

        <section class="page2" v-if="page==2">
            <h1>CMS</h1>
        </section>

        <section class="page3" v-if="page==3">
            <h1>Login avec API Key</h1>
            <form action="api" @submit.prevent="sendAjax">
                <input type="password" name="keyApi" required v-model="apikey">
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
            login: '',
            apikey: '',
            page: 1,
            test: 'XoomCoder.com'
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
        logout () {
            // reset api key
            this.apikey = '';
            this.page   = 3;
            localStorage.removeItem('apikey');
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
};

xcb.login = function (ajaxpack) {
    if (! 'login' in ajaxpack.json) return;

    localStorage.setItem('apikey', app.apikey);
};

xcb.test = function (ajaxpack)  {
    console.log(ajaxpack);
};

    </script>
</body>
</html>

<!-- 

Documentation sur VueJS 3

https://v3.vuejs.org/guide/installation.html#release-notes

https://v3.vuejs.org/guide/introduction.html#getting-started

https://v3.vuejs.org/guide/installation.html#release-notes

-->
