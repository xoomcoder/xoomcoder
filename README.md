# XoomCoder

Projet de Marketplace

En plusieurs étapes:
* Landing Page (OnePage)
* Site Vitrine
* Blog
* CMS (Content Management System)
* MarketPlace

## fonctionnalités de l'API Admin

L'API Admin expose le maximum de possibilités, afin de faciliter le travail de l'administrateur.
Avec une interface web, vous pouvez ainsi gérer votre hébergement ainsi que votre application très efficacement.

Attention: certaines peuvent être très dangereuses pour votre hébergement.
(Veillez bien à être en https et à protéger votre clé API Admin...)

🔥 Gestion des fichiers (CRUD).
🔥 Upload de fichiers.
🔥 Générateur de code (Class builder).
🔥 Envoi de requêtes SQL (CRUD, etc...).
🔥 Envoi de mails.
🔥 Exécution de code PHP avec la fonction eval.
🔥 Exécution de code JS en callback Ajax avec eval.


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

## Images et couleurs

* Privilégier les logos en SVG car vectoriels et plus légers
* Pour un choix de couleurs suivant le Material Design
* https://material.io/resources/color/#!/

* red #ba000d
* yellow #ffeb3b
* grey dark #212121
* grey #9e9e9e
* cyan #00bcd4

## ffmpeg et montage video

    ffmpeg est un outil très puissant, en ligne de commande, pour manipuler les videos

    🔥🔥🔥 FADE MELANGE 2S DES 2 VIDEOS...  
    --------------------
    ffmpeg -i jump4ka.mp4 -i zoom0.mp4 \
    -filter_complex \
    "color=black:3840x2160:d=6[base]; \
    [0:v]setpts=PTS-STARTPTS[v0]; \
    [1:v]format=yuva420p,fade=in:st=0:d=2:alpha=1, \
        setpts=PTS-STARTPTS+(6/TB)[v1]; \
    [base][v0]overlay[tmp]; \
    [tmp][v1]overlay,format=yuv420p[fv]; \
    [0:a][1:a]acrossfade=d=2[fa]" \
    -map [fv] -map [fa] fade.mp4
    --------------------

    ajouter une piste son vide:
    mono aac 32000

    ffmpeg -f lavfi -i anullsrc=channel_layout=mono:sample_rate=32000 -i jump.mp4 -c:v copy -c:a aac -shortest zoom2.mp4










