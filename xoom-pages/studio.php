<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- IMPORTANT: NO INDEX -->
    <meta name="robots" content="noindex">
    <!-- favicon -->
    <link rel="icon" href="assets/img/xoomcoder.svg">

    <title>XoomCoder Studio</title>

    <style>
html, body {
    width:100%;
    padding:0;
    margin:0;
    font-size:12px;
    text-align: center;
}

* {
    box-sizing: border-box;
    width:100%;
    max-width:100%;
    transition: all 0.5s ease-out;
}
a {
    text-decoration: none;
    color: #aaaaaa;
}
p, pre {
    white-space: pre-wrap;
}

section {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: stretch;
}

/* Small devices (landscape phones, 576px and up) */
@media (min-width: 576px) { 
    section article {
        margin: 0.25rem;
        width: calc(100% / 2 - 0.5rem); /* 2 columns and remove 2x margin */
    }
}

/* Medium devices (tablets, 768px and up) */
@media (min-width: 768px) { 

}

/* Large devices (desktops, 992px and up) */
@media (min-width: 992px) { 
    section article {
        margin: 0.25rem;
        width: calc(100% / 3 - 0.5rem); /* 3 columns and remove 2x margin */
    }

}

/* Extra large devices (large desktops, 1200px and up) */
@media (min-width: 1200px) { 
    section article {
        margin: 0.25rem;
        width: calc(100% / 4 - 0.5rem); /* 4 columns and remove 2x margin */
    }

}     

/* toolbar */
img.action {
    width:2rem;
    height:2rem;
    cursor:pointer;
}
.action.settings {
    position:fixed;
    top:2rem;
    right:2rem;
}
.options {
    position:fixed;
    top:100%;
    right:0;
    background-color: rgba(0,0,0,.9);
    height:100%;
    padding:1rem;
}
.options.active {
    top:0;
}
.options .panel {
    background-color: #dddddd;
    padding:1rem;
}
    </style>
</head>
<body>
    <div class="page">
        <header>
            <h1><a href="">XoomCoder Studio</a></h1>
            <h6><a href="./">revenir sur le site</a></h6>
        </header>
        <main>
            <section v-for="section in sections">
                <h2>{{ section.title }}</h2>
                <article v-for="article in section.articles">
                    <h3>{{ article.title }}</h3>
                    <p>{{ article.content }}</p>
                </article>
            </section>
        </main>
        <div :class="{ 'options': true, 'active': show.options }">
            <div class="panel">
                <h2>options</h2>
            </div>
        </div>
        <footer>
            <div class="toolbar">
                <img @click="switchOptions" class="action settings" src="assets/img/settings.svg" alt="settings">
            </div>
            <p>all rights reserved - © 2020 - {{ debug }}</p>
        </footer>

    </div><!--/.page-->

    <script type="module">
// load Vue from module        
import * as Vue from 'https://cdn.jsdelivr.net/npm/vue@3.0.0-rc.1/dist/vue.esm-browser.js';

const mymethods = {
    switchOptions () {
        this.show.options = !this.show.options;
    }
};

const appconf = {
    methods: mymethods,
    data() {
        return {
            show: { options: false },
            sections: [
                { title: 'section1', articles: [
                    { title: 'article1', content: 'contenu1' },
                    { title: 'article2', content: 'contenu2' },
                    { title: 'article3', content: 'contenu3' },
                    { title: 'article4', content: 'contenu4' },
                    { title: 'article5', content: 'contenu5' },
                    { title: 'article6', content: 'contenu6' },
                ] },
                { title: 'section2', articles: [
                    { title: 'article1' },
                    { title: 'article2' },
                    { title: 'article3' },
                ]},
                { title: 'section3', articles: [
                    { title: 'article1' },
                    { title: 'article2' },
                    { title: 'article3' },
                ] }
            ],
            debug: 'xoomcoder.com'
        }
  }
}

let app = Vue.createApp(appconf);

app.mount('.page');

    </script>
</body>
</html>