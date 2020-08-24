# Site Vitrine. (partie 2).

Voici la suite du projet de site vitrine. 
Nous allons créer des pages internet, en PHP, en séparant le template du contenu.

## Objectifs.

Pour le site xoomcoder.com, on va ajouter plusieurs pages de tutoriels.
Commençons par 2 pages de tutoriels:
🔥 coder une landing page.
🔥 coder un site vitrine.

On commence par un modèle très simple pour les 2 pages.

tutoriel-landing-page.php :

    |-----------------------|
    |   header              |
    |-----------------------|
    |   contenu tuto1       |
    |-----------------------|
    |   footer              |
    |-----------------------|


tutoriel-site-vitrine.php :

    |-----------------------|
    |   header              |
    |-----------------------|
    |   contenu tuto2       |
    |-----------------------|
    |   footer              |
    |-----------------------|

🔥 En comparant les 2 pages, on voit facilement que le contenu doit changer pour chaque page.
🔥 Alors que le header et le footer resteront les mêmes entre les 2 pages, pour garder la même identité visuelle.

Le template va garder les parties communes entre les pages.

    |-----------------------|
    |   header              |
    |-----------------------|
    |   (CONTENU A REMPLIR) |
    |-----------------------|
    |   footer              |
    |-----------------------|

## Méthode pratique pour coder un template.

🔥 Commencer par coder une page HTML en entier.
Par exemple: tutoriel-landing-page.php

    .note.
    remarquez qu'on crée directement un fichier .php
    mais si vous préférez commencer avec une étape en .html c'est aussi possible
    il faut ensuite changer le nom du fichier de .html en .php ...

🔥 Coder la page HTML en entier et ajouter le CSS pour obtenir un affichage satisfaisant.
🔥 Vérifier que la page s'affiche correctement dans le navigateur.

🌞 Astuce: 
    dans le métier de développeur, 
    il est très important de coder progressivement, par petites étapes
😓 Comme vous allez certainement faire des erreurs dans votre code, vérifier régulièrement que votre code fonctionne. 
        Ces tests fréquents permettent de savoir que le problème provient du bout de code ajouté depuis la dernière vérification.
🙂 Il y a 2 avantages: 
        vous accumulez moins d'erreurs à corriger,
        et le volume de code concerné est plus réduit.

### PHP en pratique: Découpage et assemblage du code HTML

Une fois que le fichier .php affiche correctement la page, 
on peut maintenant profiter de PHP pour découper le code HTML, 
et le séparer dans différents fichiers.

On va créer 3 fichiers .php pour chaque partie du code HTML.

🔥 header.php
🔥 contenu-tuto1.php
🔥 footer.php

Et on va déplacer dans chaque fichier .php une partie du code HTML.


🔥. header.php
    |-----------------------|
    |   header              |
    |-----------------------|

🔥. contenu-tuto1.php
    |   contenu tuto1       |

🔥. footer.php
    |-----------------------|
    |   footer              |
    |-----------------------|


Et dans le fichier tutoriel-landing-page.php, 
on va utiliser PHP pour assembler les différentes parties du code.
Avec l'aide de l'instruction: require
.

```php
<?php

require "header.php";
require "contenu-tuto1.php";
require "footer.php";

?>
```

## Vérifier la page dans le navigateur.

🔥 Dans votre navigateur, vérifier que votre page s'affiche comme avant.

😇 PHP a construit le code HTML en assemblant chaque fichier .php 
    chaque fichier ne contenant chacun qu'une partie du code HTML.

😇 Le navigateur reçoit le résultat de cette construction, 
    il ne sait pas ce qui se passe sur le serveur web.

🔥 Pour la 2e page, on pourra recommencer par la même méthode de découpage.
😇 Et on pourra ré-utiliser les fichiers header.php et footer.php 
...

Plusieurs avantages:
😇 Principe D.R.Y : Don't Repeat Yourself
😇 On peut centraliser le code commun et on ne le duplique pas. 
😇 Dès qu'on modifie le code commun, toutes les pages qui utilisent ce code sont mis à jour par PHP.
        On parle de site dynamique.
        note: En opposition: on parle de site statique pour les sites seulement en HTML.

        




