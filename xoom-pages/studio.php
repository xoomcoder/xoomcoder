<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- IMPORTANT: NO INDEX -->
    <meta name="robots" content="noindex">

    <title>XoomCoder Studio</title>

    <!-- favicon -->
    <link rel="icon" href="assets/img/xoomcoder.svg">

    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="assets/css/studio.css">

</head>
<body>
    <div id="app">
        <header>
            <h1>BIENVENUE DANS VOTRE STUDIO </h1>
            <h2>({{ username }}) <small><a href="#logout" @click.prevent="actLogout">logout</a></small></h2>
        </header>
        <main>
            <section>
                <h1>Tableau de Bord</h1>
                
                <h2>TODO</h2>
                <todo-item
                    v-for="item in todos"
                    v-bind:todo="item"
                    v-bind:key="item.id"
                ></todo-item>
            </section>

            <section>

                <h2>Panneau de Commande</h2>
                <form action="" @submit.prevent="sendAjax">
                    <textarea id="batchcode" name="command" required cols="80" rows="10"></textarea>
                    <button id="batchbutton" type="submit">envoyer la commande</button>
                    <div class="feedback"></div>
                    <!-- partie technique -->
                    <input type="hidden" name="classApi" value="Member">
                    <input type="hidden" name="methodApi" value="run">
                    <input type="hidden" name="loginToken" v-model="loginToken">
                </form>

            </section>

        </main>
    </div>
    <script src="https://unpkg.com/vue@next"></script>
    <script>
// https://v3.vuejs.org/guide/introduction.html#getting-started
const appConfig = {    
    data() {
        return {
            todos: [
                { id: 0, text: 'Level 1: Landing Page' },
                { id: 1, text: 'Level 2: Site Vitrine' },
                { id: 2, text: 'Level 3: Site Blog' },
                { id: 3, text: 'Level 4: Site CMS' },
                { id: 4, text: 'Level 5: Site Marketplace' }
            ],
            username: '',
            loginToken: ''
        }
    },
    methods: {        // add here your functions/methods
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
        },
        actLogout() {
            sessionStorage.setItem('loginToken', '');
            location.replace('login');   
        }
    },
    mounted() {
        let loginToken = sessionStorage.getItem('loginToken');
        if (loginToken) {
            let infos = loginToken.split(',');
            console.log(infos);
            this.loginToken = loginToken;
            this.username = infos[0];
        }
        else {
            location.replace('login');
        }
    }
};
var app = Vue.createApp(appConfig);

app.component('todo-item', {
  props: ['todo'],
  template: `<li>{{ todo.text }}</li>`
});

app.mount('#app');   // css selector to link with HTML



var xcb = {};
// add callbacks to activate on ajax response
xcb.feedback = function (ajaxpack)  {
    if ('feedback' in ajaxpack.json) {
        var f = ajaxpack.event.target.querySelector('.feedback');
        if (f) f.innerHTML = ajaxpack.json.feedback;
    }

};

    </script>
</body>
</html>