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

## SEO : Optimisation pour les Moteurs de Recherche (🔥🔥🔥) 

### Les conseils pour bien référencer une page par les moteurs de recherche

* rédiger des pages avec plusieurs milliers de mots (2.000-4.000 mots)

    => en effet, les moteurs de recherche sont essentiellement basés sur le texte
* bien structurer le code HTML avec des balises sémantiques pour aider les moteurs à comprendre la hiérarchie des contenus 

    => utiliser les balises HTML sémantiques

### Les conseils pour rédiger une page agréable pour les visiteurs

* aérer les blocs de texte pour une bonne lisibilité
* limiter la longueur des lignes de texte (400-600 caractères, limites 200-800 caractères)
* illustrer les textes avec des illustrations 
    en vectoriel (SVG)
    en image (PNG, JPG, GIF)
* utiliser une palette de quelques couleurs différentes (charte graphique)
* utiliser quelques polices de caractères différentes

🌞 au final, le texte devrait couvrir moins de 50% de la surface complète de la page

La méthode "Mobile First" pour le design

🔥 une fois le contenu HTML structuré, ajouter le CSS en premier pour les écrans smartphones

🔥 et ensuite, utiliser les media queries pour ajouter les règles supplémentaires pour les écrans tablette et desktop

🔥 coder le CSS par niveau de HTML, sur les balises parentes et ensuite les balises enfants

## SEO et Performance du code

⏲ Depuis quelques années, les visiteurs consultent plus les sites internet sur leurs smartphones plus que sur leur laptop.

🐉 les débits des réseaux mobiles sont encore lents (4G)

👉 les moteurs de recherche évaluent la rapidité de chargement d'une page et en tiennent compte dans le référencement (SEO)

😓 les développeurs doivent évaluer les performances de leur code HTML et CSS

🔥 Les images sont les plus consommateurs en volume, en temps de chargement et en performance

🔥 le travail d'optimisation des images est important pour assurer une bonne performance

🌞 Utiliser les sites comme https://web.dev pour valider vos pages web


## Insérer un formulaire Google Forms

Très facile à créer avec son compte Google Drive
👌 et à insérer dans une page avec le code iframe
😱 mais ce code ajoute du javascript en plus qui fait tomber le score "Performance" autour de 50
😻 pour retarder ce code JS... il faut ajouter un autre code JS 

## markdown, emoji et images

Insérer un emoji

😇 
🔥 

Insérer une image

![xc]

### liens vers les images

[vue]:../public/assets/img/html5.svg
[xc]:../public/assets/img/xoomcoder.svg


## Art ASCII

```
   __                                                                                      
  /_/l                                                                                     
 : : :                                                                                     
  ; ; ;                                                                                    
  : : :                                                                                    
   L ; ;  __.-._.-+.                                                                       
  /."^.:.L.' .^-.  \`.                                                                     
 :`.`. \"/\ /.-. `. \ \                                                                    
 ;\ \ ` ;-.y(_.-\  \ `.`.                                                                  
 :   _. ;;  `    \  \. `-\                                                                 
  \ T :: :=,   ,=^\  \"-._;          __..------.._                                         
  /;:-'; ; `._L.--^.     .-""-.`.   \     ""--..                                   
 : :_.': :           ;/     \   /      \ \   ;          ""--._                             
 ;  T   \ \  s      /:.---.  ;_/    `-._; ;  :     ______    \"-.      ___                 
:   :\   \ `.-=^" .:-"    _\   \_.      : :  _:.--".-"  .T"---:-.""--""\  ""-.             
;    \\   "-.\__.:'      /-'. ; ;    _. ; ;  /   -'    '    .- \        ;     "-._.-"""""-,
:     ;\     `..'      .'    \: ;      / / .'               )   ;  __  /         T        :
 ;      `,     \    .-"       ;/"---" /.' /                 `- /"""  ""---""""----..___..-'
 :    .-" `.      .'.-\      / ""----""""^-.._              .-"  bug                       
  \_.'      "._.-"-..-'`-..-'                 ""--..__..--""

```


