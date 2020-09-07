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

label {
    padding:0.5rem;
}
label > span {
    width:200px;
}
input[type=checkbox] {
    width:2rem;
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
    main {
        font-size: 14px;
    }
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
    z-index:999;
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

.s1, .s3 {
    background-color: #dddddd;
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
            <template v-for="section in sections" :key="section.id">
                <section :class="section.class" v-if="!hide[section.class]">
                    <h2>{{ section.title }}</h2>
                    <article v-for="article in section.articles">
                        <h3>{{ article.title }}</h3>
                        <p>{{ article.content }}</p>
                        <component v-if="article.compo" :is="article.compo"></component>
                    </article>
                </section>
            </template>
        </main>
        <div :class="{ 'options': true, 'active': !hide.options }">
            <div class="panel">
                <h2>options</h2>
                <label  v-for="section in sections">
                    <span>{{ section.title }}</span>
                    <input type="checkbox" checked @click="hide[section.class] = !hide[section.class]">
                </label>
            </div>
        </div>
        <footer>
            <div class="toolbar">
                <img @click="switchOptions" class="action settings" src="assets/img/settings.svg" alt="settings">
            </div>
            <p>tous droits réservés - © 2020 - {{ debug }}</p>
        </footer>

    </div><!--/.page-->

    <script type="module">
// load Vue from module        
import * as Vue from 'https://cdn.jsdelivr.net/npm/vue@3.0.0-rc.1/dist/vue.esm-browser.js';

const mymethods = {
    switchOptions () {
        this.hide.options = !this.hide.options;
    }
};

const appconf = {
    methods: mymethods,
    data() {
        return {
            hide: { options: true },
            sections: [
                { title: 'Projets', class: 's1', articles: [
                    { title: 'landing page', content: 'level 1' },
                    { title: 'site vitrine', content: 'level 2' },
                    { title: 'blog', content: 'level 3' },
                    { title: 'cms', content: 'level 4' },
                    { title: 'marketplace', content: 'level 5' },
                    { title: 'projet en équipe', content: 'teamwork' },
                ] },
                { title: 'Formation', class: 's2', articles: [
                    { title: 'HTML' },
                    { title: 'CSS' },
                    { title: 'JS' },
                    { title: 'PHP' },
                    { title: 'SQL' },
                    { title: 'VueJs' },
                    { title: 'Laravel' },
                    { title: 'WordPress' },
                ]},
                { title: 'Bloc-Notes', class: 's3', articles: [
                    { title: 'city 1' },
                    { title: 'city 2' },
                    { title: 'city 3' },
                ]},
                { title: 'CodeMap', class: 's4', articles: [
                    { title: 'carte', compo: 'xmap'},
                    { title: 'liste', compo: 'xlist' },
                    { title: 'formulaire', compo: 'xform' },
                ]},
            ],
            debug: 'xoomcoder.com'
        }
  }
}

let app = Vue.createApp(appconf);

app.component('xmap', {
  data() {
    return {
      count: 0
    }
  },
  template: `
    <button @click="count++">{{ count }}</button>
    `
});
app.component('xform', {
  data() {
    return {
      count: 0
    }
  },
  template: `
    <button @click="count++">{{ count }}</button>
    `
});
app.component('xlist', {
  data() {
    return {
      count: 0
    }
  },
  template: `
    <button @click="count++">{{ count }}</button>
    `
});

app.mount('.page');

    </script>
</body>
</html>