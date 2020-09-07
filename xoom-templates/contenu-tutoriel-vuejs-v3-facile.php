
        <section class="x2col">
            <h1>TUTORIEL POUR CODER AVEC VUE JS V3 EN MODE FACILE</h1>
            <article>
                <h2>Objectifs</h2>
                <pre>
Coder un panier d'achat simple, qui calcule le prix total, en fonction de la quantité choisie et du prix unitaire.

🔥 Coder la page HTML.

Ajouter une balise qui sert de container qui délimitera l'action pour Vue JS.
Généralement, on donne comme id="app".
Dedans, on pourra créer les bases de notre panier d'achat.

👞  nom du produit: chaussures xOOm.
🏪  prix unitaire: 99 euros.
🖱   bouton pour ajouter un produit.
😇  la quantité choisie par le client. 
💵  le prix total du panier. 

<textarea cols="80" rows="10">
<div id="app">
    <h2>Produit: Chaussures xOOm</h2>
    <h2>Prix unitaire: 99 euros</h2>
    <button>ajouter un produit au panier</button>
    <input type="range" min="0" max="100">
    <h2>Quantité: 0</h2>
    <h2>Prix total: 0 euros</h2>
    <div style="border: 5px solid green;"></div>
</div>
</textarea>

🔥 Ajouter le code JS pour Vue JS.

Il faut ajouter une balise script pour charger le code complet de Vue JS.

<textarea>
<script src="https://unpkg.com/vue@next"></script>
</textarea>

Ensuite, il faut ajouter une autre balise script pour ajouter son code JS.

🔥 Dans notre code JS, on ajoute des propriétés qui seront synchronisées avec le code HTML.

<textarea cols="80" rows="20">
<script>
// https://v3.vuejs.org/guide/installation.html#release-notes

// créer un objet qui contient notre code
const appConfig = {
  data() {
    return {
        // ajouter vos propriétés Vue JS ici
        nom: 'Chaussures xOOm',
        prixUnitaire: 99,
        quantite: 0
    }
  }
}

// activer Vue JS avec notre config et ensuite au HTML #app
var app = Vue.createApp(appConfig).mount('#app');

</script>
</textarea>


🔥 Modifier le code HTML pour insérer les codes pour Vue JS.

🔥 Ensuite, si on veut afficher la valeur d'une variable, il suffit d'écrire {{ nomVariable }} dans le HTML.
🔥 Il est possible de faire des calculs simples: par exemple {{ quantite * prixUnitaire }}.

🔥 Et si on veut ajouter un "event listener" sur le "click", il suffit d'ajouter un attribut @click dans le HTML.

🔥 Et il est aussi possible de relier Vue JS avec les champs de formulaire.

🔥 et CSS ? Naturellement, Vue JS peut interagir avec les propriétés CSS ?! 

🔥 Essayez de coder tout ça, sans utiliser Vue JS, vous allez apprécier cette simplicité ?! 
🔥 De plus, la documentation est traduite en français et très bien construite pour démarrer rapidement.

<textarea cols="80" rows="35">
<div id="app">
    <h2>Produit: {{ nom }}</h2>
    <h2>Prix unitaire: {{ prixUnitaire }} euros</h2>
    <button @click="quantite++">ajouter un produit au panier</button>
    <input type="range" min="0" max="100" v-model="quantite">
    <h2>Quantité: {{ quantite }}</h2>
    <h2>Prix total: {{ quantite * prixUnitaire }} euros</h2>
    <div :style="{ width: quantite + '%', border: '5px solid green'}"></div>
</div>

<script src="https://unpkg.com/vue@next"></script>
<script>
// https://v3.vuejs.org/guide/installation.html#release-notes

// créer un objet qui contient notre code
const appConfig = {
  data() {
    return {
        // ajouter vos propriétés Vue JS ici
        nom: 'Chaussures xOOm',
        prixUnitaire: 99,
        quantite: 0
    }
  }
}

// activer Vue JS avec notre config et ensuite au HTML #app
var app = Vue.createApp(appConfig).mount('#app');

</script>

</textarea>

                </pre>
            </article>
            <article>
                <h2>Etapes importantes</h2>
                <p><a href="https://github.com/xoomcoder/">Retrouvez le code sur GitHub</a></p>
                <p>HTML, CSS, JS, Vue JS</p>
                <h2>Résultat</h2>
                <iframe width="100%" height="50%" src="https://demo.xoomcoder.com/vuejs3-tuto-001" frameborder="10"></iframe>
            </article>
            <article>
                <h2>Tuto Video</h2>
                <p><a href="https://www.youtube.com/watch?v=xm4gcoVmTJs">Lien vers la video YouTube</a></p>
                <script type="text/xoomcoder">
                    <iframe title="tutoriels youtube" width="560" height="315" src="https://www.youtube.com/embed/xm4gcoVmTJs" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </script>
            </article>
        </section>
