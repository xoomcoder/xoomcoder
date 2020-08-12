# XoomCoder

Projet de Marketplace

En plusieurs étapes:
* Landing Page (OnePage)
* Site Vitrine
* Blog
* CMS (Content Management System)
* MarketPlace

## Création du dossier du projet

    Et ensuite, on peut ajouter git

    git init

    git config user.name "LH XoomCoder"

    git config user.email github@xoomcoder.com

    Voilà on peut utiliser git dans VSCode


    Créer un Repository sur github.com (vide... ne pas ajouter de fichier...)

    Et ensuite dans le terminal de VSCode

    git remote add origin https://github.com/xoomcoder/xoomcoder.git

    git push -u origin master

    => Avec la ligne de commande, on doit fournir les username/password pour github.com

    Et aussi avec VSCode, on peut synchroniser avec le repository github.com

    => Il y a une extension VSCode qui se connecte au compte github.com


## Ajout du dossier public

    créer le dossier public/
    et ensuite coder une page index.html


## Hébergement avec SSH

    On peut cloner le code du repo sur son hébergement si on a un accès SSH et git
    
    git clone https://github.com/xoomcoder/xoomcoder.git

## Ajout WebHook avec github.com

    github.com permet de déclencher l'appel à une URL d'un autre site à chauqe événement push sur le repository

    On va créer un fichier public/gitpull.php

```php
<?php

// ici on mettra le code pour déclencher la commande git pull
// https://www.php.net/manual/fr/function.passthru.php
$commande = "git pull";

passthru($commande);

```

    et ensuite synchroniser à la main l'hébergement avec github.com
    cela permet d'avoir cette nouvelle page:
    https://xoomcoder.com/gitpull.php

    et on va aller ajouter un nouveau webhhok dans le repository github.com
    
* sur github.com, aller dans le repository du projet
* cliquer sur l'onglet settings
* cliquer sur le menu webhhoks (sur la gauche)
* cliquer sur le bouton "add webhook"
* entrer l'URL dans Payload URL
* on peut laisser les autres paramètres tels quels
* enregistrer en appuyant sur le bouton "add webbhook"

