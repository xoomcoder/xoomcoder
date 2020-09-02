<article class="w67">
    <h2>Level 4: CRUD avec VueJS pour un CMS</h2>
    <p>
    VueJS est un outil qui permet de réaliser des affichages de table SQL très facilement.
    Avec Ajax et la fonction fetch en JS, on peut récupérer du serveur un tableau d'objets au format JSON.
    Les boucles v-for de VueJS permettent alors rapidement de créer un affichage sous forme de table HTML.
    </p>
    <p>
    En PHP, utiliser la méthode fetchAll produit les résultats d'une requête SQL dans un tableau de tableaux associatifs.
    Ensuite, la fonction json_encode transforme les tableaux associatifs en texte au format JSON.
    Cette chaine de production est très rapide à coder à PHP. 
    Pas besoin de passer par l'étape de créer des classes "Entité" en Orienté-Objet.
    avec PHP, ajouter un ORM (Object Relational Mapping) est une étape qui consomme beaucoup de ressources et devient inutile quand au bout, on produit du format JSON.
    En effet, les objets JS dans JSON ne sont pas typés. Cela équivaut aux tableaux associatifs PHP, ce qui permet de créer un code PHP valable pour toutes les tables SQL, sans avoir besoin de créer une classe "Entity" PHP pour chaque nouvelle table SQL.
    </p>
    <p>
    En comparaison, si on regarde un peu plus en détails un ORM comme Doctrine, on voit que la promesse de ne pas tenir compte de la BDD relationnelle est une illusion.
    Il y a en pratique toujours plein de code relié à SQL car cela permet d'optimiser les performances.
    Avec un ORM, On passerait d'un format SQL table avec lignes et colonnes à un objet "Entity", typé avec une classe PHP par table SQL, qui doit ensuite être retransformé en JSON... pour être finalement affiché sous la forme d'une table HTML avec des lignes et des colonnes ?! 
    </p>
    <p>
    SQL est un langage très puissant, car on peut créer et modifier dynamiquement les tables SQL et aussi les colonnes.
    L'approche ORM perd alors ce dynamisme, car il est préconisé de créer une classe par table SQL.
    Les propriétés, associées à chaque colonne SQL, doivent alors être codées en dur dans chaque classe... Ce qui tue le dynamisme de SQL et alourdit le code applicatif.
    C'est pour ces raisons que délaisser le typage par des classes PHP, et transporter les informations par des tableaux associatifs PHP, permet de rester dans la dynamique de SQL.
    Et surtout quand du côté front, JS préfère aussi, au final, des données non typées.
    </p>
</article>

<article class="">
    <h2>Checkpoint: WebShell en https</h2>
    <p>
    Avec une connexion sécurisée en https, il est maintenant moins exposé de fournir une API de gestion de l'hébergement, directement dans l'administration d'un CMS.
    Quand vous avez les compétences développeur, c'est régulièrement utile de pouvoir manipuler le code directement.
    Un WebShell permet de gérer les dossiers et les fichiers de l'hébergement.
    Et une console SQL permet de lancer des requêtes SQL sur la base de données.
    Du côté back, cette API ajoute aussi un framework pratique pour composer des requêtes AJAX.
    L'API ajax permet de choisir le nom de la propriété JSON qui va recevoir les informations demandées.
    Cette architecture se rapproche de GraphQL car dans une seule requête, on peut envoyer plusieurs instructions au serveur.
    Il est ainsi possible d'enchainer un "create" et plusieurs "read" en une seule requête.
    Toutes ces possibilités forment un nouveau mini-langage de programmation ?! 
    </p>
    <pre>
🔥 Gestion des fichiers (CRUD).
🔥 Upload de fichiers.
🔥 Générateur de code (Class builder).
🔥 Envoi de requêtes SQL (CRUD, etc...).
🔥 Envoi de mails.
🔥 Exécution de code PHP avec la fonction eval.
🔥 Exécution de code JS en callback Ajax avec eval.
    </pre>
</article>

<article class="">
    <h2>Checkpoint: SEO avec YouTube</h2>
    <p>
    Le référencement des mots clé avec YouTube est vraiment impressionnant ! 
    Une vidéo publiée hier est déjà bien classée dans le résultats de recherche Google, avec les mots clé "coder vuejs 3".
    La vidéo dure moins de 3 minutes et propose très peu de contenu texte.
    Et cette vidéo récente se positionne déjà en 2è résultat Videos sur la recherche "générale" et dans les 10 premiers en recherche "video".
    </p>
</article>
<script type="text/xoomcoder">
        <img class="h50" src="assets/news/screenshot-2020-08-28-coder-vuejs-3-recherche-google.png" alt="coder vuejs 3">
        <img class="h50" src="assets/news/screenshot-2020-08-28-coder-vuejs-3-recherche-google-video.png" alt="coder vuejs 3 video">
</script>

<article class="w67">
    <h2>Checkpoint: VSCode comme CMS</h2>
    <p>
    Comme point d'étapes sur les premières semaines de développement du site, il y a plusieurs réflexions étonnantes.
    </p>
    <p>
    Une fois que le nom de domaine et l'hébergement sont loués, il faut commencer par publier une landing page pour déclencher le référencement des moteurs de recherche.
    Cela prend quelques semaines et il faut aller pousser Google avec la search console pour l'encourager à indexer votre site.
    De plus, Google Search Console détecte les problèmes d'UX (User Experience) pour la version mobile.
    Il faut aussi valider votre score avec des outils comme web.dev pour mesurer les différents signaux importants. 
    Comme le projet est lié à des vidéos, le plus simple est de passer par YouTube, pour publier les vidéos, et ensuite intégrer le lecteur sur le site.
    Grande surprise: le compte YouTube et les vidéos sont très rapidement référencés, de quelques heures à quelques jours, comparé à des semaines pour les pages d'un site.    
    </p>
    <p>
    Pour obtenir un bon référencement d'une page, les conseils SEO vous encouragent à publier des contenus de plusieurs milliers de mots (2000 à 4000 mots par page).
    C'est un travail considérable et par conséquent, la stratégie est plutôt de créer peu de pages, avec beaucoup de contenu sur chaque page.
    Et pour les visiteurs, il faut aérer et illustrer avec des images ou des photos.
    Techniquement, il n'y a pas besoin d'un framework, ni d'un CMS très évolué pour démarrer.
    </p>
    <p>
    Pour coder, l'atelier VSCode est devenu en quelques années un outil incontournable, car gratuit et très extensible.
    C'est Microsoft qui nous fait profiter de décennies d'expérience dans les ateliers logiciels comme Visual Studio.
    Tout en retrouvant l'aspect ouvert des anciens ateliers comme Emacs ou Eclipse.
    </p>
    <p>
    Comme GitHub est aussi maintenant une entreprise Microsoft, c'est facile de connecter son VSCode à un repository sur GitHub.com pour gérer son code. 
    Cela fournit un historique puissant et une sauvegarde gratuite sur un serveur hors de l'ordinateur.
    </p>
    <p>
    Avec un hébergement qui propose un accès SSH et le programme git, on peut ensuite utiliser un webhook de GitHub.com pour déclencher un "git pull" automatiquement.
    Le code sur l'hébergement est obtenu avec un "git clone" du repository GitHub.com et ensuite, le webhook va déclencher automatiquement le "git pull".
    </p>
    <p>
    Sur mon ordinateur sous Windows, j'utilise WSL2 (Windows Subsystem Linux 2) pour avoir une machine virtuelle sous Ubuntu.
    WSL2 s'intègre aussi directement dans VSCode. Cela permet de créer un environnement de développement en local pour tester son code. 
    Et ensuite, il suffit de créer des sauvegardes régulières avec des "git commit". 
    Enfin pour mettre le code en production sur l'hébergement, il suffit de faire un "git push".
    La synchronisation de mon code avec le repository GitHub déclenche à son tour la synchro avec l'hébergement.
    C'est une mise en production rapide et facile. 
    </p>
    <p>
    Les langages à pratiquer pour créer de bonnes pages web: HTML, CSS, un peu de JS et aussi des bases de PHP.
    Et en quelques semaines, vous pouvez coder vous-même un site Vitrine avec une page Blog (sans base de données).
    (Si vous êtes pressés, le code de ce site est en ligne sur GitHub.com...)
    </p>   
</article>


<article>
    <h2>Level 4: Ajout de page admin avec VueJS v3</h2>
    <p>VueJS version3 devrait devenir disponible bientôt. En attendant, on peut déjà l'utiliser.</p>
    <p>https://v3.vuejs.org/guide/installation.html#release-notes</p>
    <p>Pour la partie admin, nous allons nous baser sur VueJS v3. Le code de démarrage est un peu plus complexe que pour la v2.</p>
    <p>Une partie admin ne doit pas être indexée par les moteurs de recherche. Dans cette situation, l'utilisation intensive de JS nous apporte beaucoup plus de facilités pour le développeur. Les utilisateurs profitent aussi d'une meilleure expérience (UX). Et les moteurs de recherche comprennent très mal le contenu produit par JS et l'indexent mal, ce qui est bien le résultat recherché pour cette page d'administration.</p>
</article>

<article>
    <h2>Level 3: tutoriels sur VueJS 3 en framework front</h2>
    <p>
        VueJS 3 va bientôt devenir la nouvelle version officielle.
        Dans les 3 principaux frameworks front, on peut nommer React, Angular et Vue.
        Cette version VueJS 3 est une évolution importante pour le framework qui a rapidement gagné en popularité ces dernières années.
        Un des aspects très intéressant est la prise en main très facile pour commencer à manipuler les possibilités des frameworks front.
        VueJS propose un package complet en un seul fichier. 
        Il suffit de l'ajouter dans votre page HTML et vous pouvez directement utiliser VueJS !
        Si vous comparez avec React ou Angular, vous allez vous apercevoir que c'est beaucoup plus lourd pour démarrer avec React et Angular.
        Pour coder avec VueJS, on utilise du JS moderne mais aussi des ajouts assez proches du JS.
        React préfère JSX et Angular préfère TypeScript.
        Ensuite si vous voulez pousser VueJS pour un projet plus complexe, il est aussi évidemment possible de créer des composants compilés côté serveur, avec nodeJS et npm...
    </p>
    <p>
        Pour suivre les actualités, visitez cette page: 
        https://news.vuejs.org/
    </p>
</article>

<article>
    <h2>Level 3: Ajout d'une page installation</h2>
    <p>
        Pour gérer le contenu du site, il faut créer un compte administrateur.
        La page d'installation demande l'email de l'administrateur. 
        Et le traitement du formulaire envoie une clé admin sur cet email.
        Ensuite, il y a un 2e formulaire d'activation pour valider que l'adresse email est correcte.
        Il faut alors fournir l'email et la clé envoyée sur cette boite email.
        Une fois le compte administrateur créé, les 2 formulaires sont désactivés: 
        il devient impossible de créer un nouveau compte adminitrateur.
    </p>
</article>

<article>
    <h2>Level 3: Blog en POO sans BDD</h2>
    <p>Le site XoomCoder arrive maintenant au niveau 3. On ajoute en premier une page news pour les actualités.</p>
    <p>Pour le SEO, il n'y a pas encore suffisamment de contenu dans chaque article pour les séparer dans une page à part. Pour le moment, tous les articles sont dans la page news.</p>
    <p>Sur la page contact, il y a aussi un formulaire pour laisser un message. L'envoi du formulaire est réalisé en Ajax, sans chargement de la page.</p>
    <p>Comme souvent en développement local, il n'y a pas de serveur d'email. Il y a maintenant la possibilité d'ajouter un code local pour personnaliser ces fonctionnalités. La Programmation Par Classes (PPC) permet de ranger facilement son code.</p>
</article>

<article>
    <h2>Level 3: Programmation Orienté-Objet</h2>
    <p>
        Avec PHP, il faut vraiment passer rapidement de la programmation fonctionnelle à la programmation orienté-objet (POO).
        En effet, PHP propose pour la programmation Orienté-Objet un mécanisme de chargement automatique de classes.
        Une fois ce mécanisme installé, le développeur peut se concentrer sur l'organisation de son code dans différentes les classes.
        PHP pourra charger automatiquement le code nécessaire, et seulement au moment où l'exécution du code en a besoin.
    </p>
    <p>
        Il y a donc un grand gain en termes de performances, puisque que PHP ne chargera que le code nécessaire, au dernier moment.
        Le développeur peut capitaliser son code dans des classes.
        Et de plus, un code incorrect n'aura pas de conséquences sur le reste de l'application si il n'a pas besoin d'être exécuté.
    </p>
    <p>
        En conclusion: c'est un "must" que de programmer en Orienté-Objet avec PHP. Et c'est bien le niveau demandé dans les entreprises.
    </p>
</article>

<article>
    <h2>Level 3: Blog et astuce SEO</h2>
    <p>
        Les sites ont besoin des moteurs de recherche pour être bien référencés dans les résultats de recherche des visiteurs.
        Mais il faut aussi voir que les moteurs de recherche ont aussi besoin d'être meilleurs que leurs concurrents.
        Le point crucial se focalise sur les actualités. Les internautes veulent avoir les dernières nouvelles et quasiment en temps réel.
        Les moteurs de recherche doivent donc repérer les sites qui sont à la pointe sur les dernières nouvelles selon les domaines populaires.
        Votre site sera forcément mieux référencé que celui de votre concurrent si vous publiez régulièrement des nouveautés en lien avec les actualités.
        Si on regarde l'histoire des sites, c'est le conseil qu'on donnait aux clients qui voulaient que leur site vitrine soit mieux référencé que le site vitrine du concurrent.
        En ajoutant une page de "blog", "news", "actus", etc... Le site donne un signal fort aux moteurs de recherche qu'il est un site où il y a aura des contenus régulièrement et récents.
        Google viendra consulter les pages plus souvent et les pages vont gagner des positions car elles vont proposer des contenus plus frais que les autres.
        Et pour pouvoir publier du contenu régulièrement, il faut évidemment un outil bien adapté pour ce travail: il faut un Content Management System (CMS).
        C'est une des clés du succès de WordPress, qui est utilisé pour publier plus de 35% des sites actuellement. 
    </p>
    <p>
        Par exemple: pour cette page d'actualités, comme le site porte sur le développement web fullstack, il est intéressant de publier du contenu sur les prochaines sorties des frameworks.
        Sur la partie front, VueJS 3 était annoncé pour ces dernières semaines. Le code du noyau VueJS 3 est prêt et ce sont plutôt les extensions et la documentation qui doivent maintenant se mettre à niveau.
        On peut essayer de gagner en référencement sur VueJS 3 et aussi tout son écosystème. 
    </p>
</article>
