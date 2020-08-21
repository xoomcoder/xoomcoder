# Landing Page

On va coder une Landing Page comme page d'accueil de notre site

* Cela consiste en un seul fichier HTML

* Pour un bon référencement naturel:
* il faut construire une structure HTML sémantique et bien hiérarchisée
* et il faut viser plus de 3.000 mots de contenu.

* Il faut aussi coder du CSS pour rendre la page responsive, pour optimiser l'affichage
* on pourra gérer 3 tailles d'écran
* smartphone, tablette, desktop

## Mobile-First et responsive CSS

Depuis quelques années, les visiteurs utilisent leurs smartphones comme moyen principal
pour visiter les sites internet.

Le design Mobile-First consiste à commencer à créer le design de la page sur la surface disponible pour un smartphone en mode portrait.

Sur cette base Mobile-First, on crée ensuite les variantes pour les tailles d'écran plus grands
* smartphones
* tablettes
* desktops


Cela nous amène à utiliser les techniques HTML et CSS 
* avec flexbox
* avec les media queries

## SEO

Les conseils pour bien référencer une page par les moteurs de recherche:

* rédiger des pages avec plusieurs milliers de mots (2.000-4.000 mots)
    => en effet, les moteurs de recherche sont essentiellement basés sur le texte
* bien structurer le code HTML avec des balises sémantiques pour aider les moteurs à comprendre la hiérarchie des contenus 
    => utiliser les balises HTML sémantiques

Les conseils pour rédiger une page agréable pour les visiteurs:

* aérer les blocs de texte pour une bonne lisibilité
* limiter la longueur des lignes de texte (400-600 cactacères, limites 200-800 caractères)
* illustrer les textes avec des illustrations 
    en vectoriel (SVG)
    en image (PNG, JPG, GIF)
* utiliser une palette de quelques couleurs différentes (charte graphique)
* utiliser quelques polices de caractères différentes

La méthode "Mobile First" pour le design

🔥 une fois le contenu HTML structuré, ajouter le CSS en premier pour les écrans smartphones

🔥 et ensuite, utiliser les media queries pour ajouter les règles supplémentaires pour les écrans tablette et desktop

🔥 coder le CSS par niveau de HTML, sur les balises parentes et ensuite les balises enfants

## markdown, emoji et images

Insérer un emoji

😇 
🔥 

Insérer une image

![xc]

### liens vers les images

[vue]:../public/assets/img/html5.svg
[xc]:../public/assets/img/xoomcoder.svg
