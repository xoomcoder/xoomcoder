<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- IMPORTANT: NO INDEX -->
    <meta name="robots" content="noindex">

    <title>XoomCoder Admin</title>

    <script type="text/readme">

    https://v3.vuejs.org/guide/installation.html#release-notes

    https://v3.vuejs.org/guide/introduction.html#getting-started

    </script>

</head>
<body>
    <div id="app">

        <h1>{{ test }}</h1>
    </div>

    <!-- TODO: change to the official Vuejs version 3 (when available) -->
    <script src="https://unpkg.com/vue@next"></script>
    <script>

// https://v3.vuejs.org/guide/introduction.html#getting-started
const AppConfig = {
    data() {
        return {
            // add Here your JS properties to sync with HTML
            test: 'XoomCoder'
        }
    }
};

var app = Vue.createApp(AppConfig).mount('#app');

    </script>
</body>
</html>