<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- IMPORTANT: NO INDEX -->
    <meta name="robots" content="noindex">

    <title>XoomCoder Admin</title>
</head>
<body>
    <!-- VueJS will only work inside this div -->
    <div id="app">

        <h1>Admin</h1>

        <section>
            <button @click="doEx1">click here</button>
            <div>you pressed the button {{ count1 }} times.</div>
        </section>

    </div>

    <!-- TODO: change to the official Vuejs version 3 (when available) -->
    <script src="https://unpkg.com/vue@next"></script>

    <script>

// https://v3.vuejs.org/guide/introduction.html#getting-started
const appConfig = {
    data() {
        return {
            // add Here your JS properties to sync with HTML
            count1: 0,
            test: 'XoomCoder'
        }
    },
    methods: {
        doEx1() {
            // we can manipulate properties here
            this.count1++;
        }
    }
};

var app = Vue.createApp(appConfig).mount('#app');

    </script>
</body>
</html>

<!-- 

Documentation sur VueJS 3

https://v3.vuejs.org/guide/installation.html#release-notes

https://v3.vuejs.org/guide/introduction.html#getting-started

https://v3.vuejs.org/guide/installation.html#release-notes

-->
