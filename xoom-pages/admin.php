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
                <a href="#page1" @click.prevent="page=1">page 1</a>
                <a href="#page2" @click.prevent="page=2">page 2</a>
                <a href="#page3" @click.prevent="page=3">page 3</a>
            </nav>
        </header>

        <section class="page1" v-if="page==1">
            <h1>Page 1</h1>
            <form action="api" @submit.prevent="sendAjax">
                <input type="text" name="command">
                <button type="submit">envoyer la commande</button>
                <div class="feedback"></div>
                <!-- partie technique -->
                <input type="hidden" name="classApi" value="Admin">
                <input type="hidden" name="methodApi" value="doCommand">
            </form>
        </section>

        <section class="page2" v-if="page==2">
            <h1>Page 2</h1>
        </section>

        <section class="page3" v-if="page==3">
            <h1>Page 3</h1>
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
            page: 1,
            test: 'XoomCoder.com'
        }
    },
    methods: {
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
}
xcb.test = function (ajaxpack)  {
    console.log('test');
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
