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

                <h2>Bloc-Notes</h2>
                <form action="" @submit.prevent="saveNote">
                    <input type="text" name="title" placeholder="entrez un titre" v-model="title">
                    <textarea id="batchcode" name="note" cols="80" rows="10" placeholder="prenez des notes" v-model="code"></textarea>
                    <textarea class="hidden" name="note2" cols="80" rows="10">
                    </textarea>
                    <button id="batchbutton" type="submit">enregistrer votre note</button>
                    <div class="feedback"></div>
                    <!-- partie technique -->
                    <input type="hidden" name="classApi" value="Member">
                    <input type="hidden" name="methodApi" value="run">
                    <input type="hidden" name="loginToken" v-model="loginToken">
                </form>

            </section>

            <section v-if="content.blocnote">
                <h2>Votre liste de notes ({{ content.blocnote.length }})</h2>
                <div class="rowflex x3col" v-if="content.blocnote">
                    <article class="rowflex" v-for="bn in content.blocnote" :key="bn.id">
                        <button class="w50" @click="actCopy(bn)">copier</button>
                        <button class="w50" @click="actDelete(bn)">supprimer</button>
                        <h3 v-if="bn.title">{{ bn.title }}</h3>
                        <pre v-if="bn.code">{{ bn.code }}</pre>
                        <small>{{ bn.dateLastRun }}</small>
                    </article>
                </div>

            </section>
        </main>
    </div>
    <script src="https://unpkg.com/vue@next"></script>
    <script>
// https://v3.vuejs.org/guide/introduction.html#getting-started
const appConfig = {    
    data() {
        return {
            title: '',
            code: '',
            content: {},
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
        actCopy (bn) {
            this.title = bn.title;
            this.code = bn.code;
        },
        actDelete (bn) {
            console.log(bn);
            let fd = new FormData;
            fd.append('classApi', 'Member');
            fd.append('methodApi', 'run');
            fd.append('loginToken', this.loginToken);
            let note2 = `
            DbDelete?table=blocnote&id=${bn.id}
            `;
            fd.append('note2', note2);
            this.sendAjax({ 'formdata' : fd });
        },
        saveNote (event) {
            this.sendAjax(event);
            this.title = '';
            this.code  = '';
        },
        sendAjax (event) {
            let myapp = this;   // ?? hack
            var fd = {};
            if ('target' in event) fd = new FormData(event.target);
            else if ('formdata' in event) fd = event.formdata;
            fetch('api', {
                method: 'POST',
                body: fd
            })
            .then((response) => {
                response
                    .json()
                    .then((json) => {
                        var ajaxpack = {
                            'app': myapp,
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

        this.actDelete({id: 0});
    }
};
const app = Vue.createApp(appConfig);

app.component('todo-item', {
  props: ['todo'],
  template: `<li>{{ todo.text }}</li>`
});

app.mount('#app');   // css selector to link with HTML



var xcb = {};
// add callbacks to activate on ajax response
xcb.feedback = function (ajaxpack)  {
    if ('feedback' in ajaxpack.json) {
        if ('target' in ajaxpack.event) {
            var f = ajaxpack.event.target.querySelector('.feedback');
            if (f) f.innerHTML = ajaxpack.json.feedback;
        }
    }
};

xcb.data = function (ajaxpack) {
    if('data' in ajaxpack.json) {
        for (table in ajaxpack.json.data) {
            console.log(ajaxpack.json.data[table]);
            console.log(ajaxpack.app);
            ajaxpack.app.content[table] = ajaxpack.json.data[table];
        }
    }
}
    </script>
</body>
</html>