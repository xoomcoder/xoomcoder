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

                <h2>Panneau de Commande</h2>
                <form action="" @submit.prevent="">
                    <textarea id="batchcode" name="command" required cols="80" rows="10"></textarea>
                    <button id="batchbutton" type="submit">envoyer la commande</button>
                    <div class="feedback"></div>
                    <!-- partie technique -->
                    <input type="hidden" name="classApi" value="Member">
                    <input type="hidden" name="methodApi" value="doCommand">
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
            username: '',
            loginToken: ''
        }
    },
    methods: {
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

app.mount('#app');   // css selector to link with HTML

    </script>
</body>
</html>