<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tuto Vue JS v3 en mode facile</title>
</head>
<body>
    <h1>Tutoriel VueJS v3 en mode facile</h1>

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
</body>
</html>