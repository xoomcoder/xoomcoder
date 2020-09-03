<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XoomCoder Studio</title>
</head>
<body>
    <div id="app">
        <header>
            <h1>BIENVENUE DANS VOTRE STUDIO ({{ username }})</h1>
        </header>
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