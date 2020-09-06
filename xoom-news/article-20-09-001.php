<article>
    <h2>Composer, Packagist et GitHub</h2>
    <p>
    Le projet XoomCoder avance bien en termes de code. Le nombre de classes augmente régulièrement. Pour permettre une meilleur réutilisation des classes, le système avec composer et packagist est très répandu dans le monde PHP.
    En pratique, il faut créer un repository sur github.com, avec un fichier composer.json bien formatté. La commande en ligne "composer init" permet de créer ce fichier rapidement. Ensuite, il faut créer un compte sur le site https://packagist.org, et enfin créer un nouveau package qui sera associé au repository sur https://github.com.
    </p>
    <p>
    En ajoutant un webhook du repo github vers le package packagist, les mises à jour du repo vont déclencher la mise à jour sur packagist. Il suffit de créer des releases sur le repo github et la commande composer ira chercher les bons fichiers en passant par packagist, qui est synchronisé avec le repo github.    
    </p>
    <p>
    Pour la mise en prod sur ionos, le plus simple est d'envoyer aussi les fichiers des librairies. Il faut alors ne pas ajouter le dossier vendor/ dans .gitignore. 
    Généralement le dossier vendor/ est inclus dans le .gitignore car c'est inutile de garder des codes de librairies vu qu'un développeur ne va jamais les modifier mais seulement les utiliser. Cela peut beaucoup surcharger le repo git car les librairies peuvent contenir des milliers de fichiers... C'est généralement la mauvaise surprise quand on regarde un plus en détail les sous-dossiers dans vendor/, beaucoup de packages comprennent l'ensemble du repo github du package... Et il y a plein de fichiers inutiles.
    </p>
    <p>
    Le code actuel va se retrouver réparti dans plusieurs repository github. Le contenu sera séparé pour moins polluer l'historique du code. Et le code des classes PHP sera aussi séparé dans un repository pour le rendre réutilisable pour d'autres projets PHP ou pour un plugin WordPress.
    </p>
    <p>
    Github propose une API publique pour obtenir la dernière release d'un repo:
    https://api.github.com/repos/xoomcoder/xoom-express/releases/latest    
    </p>
</article>
<article>
    <h2>Symfony 5: the fast track</h2>
    <p>
    La documentation de Symfony a connu depuis 10 ans une évolution assez étrange.
    Sur le site, les traductions en plusieurs langues ont un jour disparu, car les contributeurs pour garder à jour les traductions n'étaient pas assez nombreux. C'est une problématique générale aux projets open-source. Mais supprimer les anciennes traductions alors qu'une bonne partie était toujours d'actualité enlevait une aide précieuse pour les débutants.
    </p>
    <p>
    En plus de la documentation technique, il y avait plusieurs autres manuels, de la prise en main aux meilleures pratiques. Ces manuels ont aussi disparu du site il y a quelques années.
    Il ne reste que les "Best Practices" en ligne, mais le téléchargement en PDF n'est plus disponible.
    </p>
    <p>
    https://symfony.com/doc/current/best_practices.html
    </p>
    <p>
    Fin 2019, Fabien Potencier ouvre une campagne kickstarter pour financer l'écriture d'un nouveau livre sur Symfony 5, la version stable actuelle. En premier lieu, c'est assez étonnant, quand on gère une entreprise de formation et consulting sur Symfony depuis plusieurs années, de se faire financer en crowdfunding l'écriture de la documentation ?! Et grâce aux contributeurs, le livre devait être mis à disposition gratuitement à toute la communauté. 
    Quelques mois plus tard, le livre est enfin disponible, en anglais et en français, mais pour la communauté, il est payant ?! Alors que les objectifs de la campagne ont été largement dépassés (plus de 50000 euros réunis).
    Et ce seront aussi des contributeurs qui vont assurer bénévolement les traductions.
    Et quelques mois plus tard, les traductions seront progressivement mises en ligne, mais la version anglaise et française restent encore payantes ! 
    </p>
    <p>
    Enfin début Septembre 2020, la version originale (anglais et français) sont enfin mis à disposition gratuitement pour la communauté. Par contre, si vous préférez la version PDF, elle est payante.
    </p>
    <p>
    https://symfony.com/book
    </p>
</article>

<article>
    <h2>Level 5: Espace Membre</h2>
    <p>
    Une fois la partie authentification en place, on peut maintenant créer plusieurs espaces séparés. 
    Un espace public ouvert à tous, un espace membre pour les visiteurs identifiés et un espace administrateur.
    L'espace administrateur est le plus simple car déjà protégé par l'authentification. 
    Une fois identifié, l'administrateur peut faire beaucoup de choses, même dangereuses.
    L'espace membre est assez délicat à restreindre car chaque membre ne doit pouvoir agir que sur ces propres contenus.
    Et sans entrer en conflit avec d'autres contenus existants, auparavant créés par d'autres membres.
    </p>
    <p>
    Dans SQL, on ne va pas créer un jeu de tables pour chaque membre. Les membres partageront le même jeu de tables pour stocker leurs contenus. Une solution simple pour savoir l'auteur de chaque ligne est de rajouter, dans chaque table SQL, une colonne id_user, clé étrangère vers la table user.
    Entre la table user et les tables SQL des membres, on aura ainsi une relation "One to Many".
    Ensuite, toutes les requêtes d'un membre seront avec une clause Where qui filtrera les lignes sélectionnées avec cette clé étrangère id_user.    
    </p>
    <p>
    Dans Xoomcoder, l'espace membre s'appelle le Studio.
    Et vous pouvez sauvegarder des notes avec un titre et un contenu.
    Vous pouvez les supprimer si ces notes ne sont plus utiles.
    Et si vous voulez gérer une sélection temporaire, il est aussi possible de cacher une note.
    Ce qui peut vous aider pour une liste de courses par exemple.
    </p>
    <p>
    En ajoutant le choix entre un statut public ou privé pour les notes, cela donnerait un site comme twitter.
    Chaque membre pourrait publier des notes et une page publique "bloc-notes" les afficherait. 
    </p>
    <p>
    Enfin, VueJS v3 permet de créer des Single Page Applications (SPA) rapidement et assez facilement.
    Il y a assez vite des scenarios un peu tordus et quelques bugs étranges, mais rien de bloquant pour le moment.
    Et c'est assez rare pour le mettre en avant, la documentation aide bien quand on rencontre des situations limites.
    C'est toujours une bonne surprise de trouver que votre scenario à problème est déjà connu et qu'une solution est recommandée.    
    </p>
</article>
<article>
    <h2>Level 5: Gestion des comptes utilisateurs</h2>
    <p>
    Les procédures pour une création de compte sont maintenant bien définis.
    Et la plupart des internautes sont habitués à ces différentes étapes.
    La création du compte doit ensuite être validée par un mail de validation qui contient une clé personnalisée.
    Un hashage entre l'email et le password_hash stocké sur le serveur, fournit un token avec comme clé publique l'email et comme clé privée le password_hash. 
    Un jeton par un hashage md5 donne 32 caractères.
    Du côté serveur, il faut bien vérifier le statut actuel du compte, pour ne pas activer les comptes dans n'importe quel statut. 
    (exemple: un membre banni qui tenterait de réactiver son compte...)
    </p>
    <p>
    Sur le même principe, le mot de passe oublié doit pouvoir être changé par le membre sans avoir à demander à un administrateur.
    Il faut ainsi produire une clé temporaire qui servira à changer le mot de passe.
    Pour sécuriser ces jetons temporaires, on peut leur ajouter une durée de vie de 24H.
    De même, en créant un jeton, qui porte comme payload un timestamp, dont l'intégrité est liée à un hash avec le password_hash en clé privée. La longueur du token est plus longue si on encode le tout en base64.
    On pourra vérifier le timestamp côté serveur.
    2è effet "Kisscool", si la clé privée est liée au password_hash, dès que le mot de passe est modifé, le password_hash est aussi modifié. 
    Ainsi, le jeton temporaire devient immédiatement invalide et ne pourra pas être ré-utilisé.      
    </p>
    <p>On peut noter plusieurs avantages de ces mécanismes qui s'appuient sur le passhword_hash en clé privée: chaque user a sa propre clé privée, et il n'y a pas besoin de stocker des clés dans une table SQL.</p>
</article>

<article>
    <h2>Level 5: Json Web Tokens (JWT) pour les APIs</h2>
    <p>
    Les sessions PHP, qui permettent de garder des informations sur le serveur sur un visiteur, reposent sur un cookie identifiant créé par le serveur et qui sera ensuite échangé entre le serveur et le navigateur, à chaque requête. Ce qui permet de tagger un visiteur et de le reconnaître.
    Mais les cookies sont problématiques et de plus en plus contestés. De plus ils sont limités au navigateur et à un nom de domaine. Et côté serveur, gérer les sessions créent de nombreux fichiers temporaires.
    Actuellement, d'autres techniques permettent de garantir une bonne sécurité sans cookie.
    </p>
    <p>
    La technique Json Web Token (JWT) permet au serveur de créer une clé API qui pourra être envoyée et stockée dans le navigateur. Ensuite le navigateur (ou application cliente) devra renvoyer ce token au serveur, pour chaque action qui nécessite une autorisation.
    Mais comment garantir que le contenu du token n'est pas hacké pour usurper trop de droits ?
    Le token JWT contient plusieurs informations. Le contenu (payload) mais aussi un hashage (signature) obtenu avec la combinaison entre le contenu (clé publique) et un clé serveur (clé privée).
    Le serveur garde ainsi la clé privée, qui lui permettra de vérifier que le contenu reçu permet de produire le hash associé, et donc garantir que le contenu n'a pas été corrompu. Le hash peut être publié publiquement aussi dans le token, car de toute façon, pour forger un nouveau hash correct, il faudrait la clé privée gardée par le serveur.   
    </p>
    <p>
        https://jwt.io/
    </p>
    <p>
    (Pour renforcer la sécurité, la signature peut être produite avec 2 hashages successifs sur la clé publique et et la clé privée...)
    </p>
    <p>
    Comme ce token peut être envoyé au serveur par de nombreuses manières différentes, cette technique permet de se libérer des cookies. On peut ainsi créer des tokens pour des applications clientes qui pourront agir sur une API serveur, en utilisant les droits d'un compte auparavant identifié et autorisé. Et sans avoir à fournir le mot de passe du compte.    
    </p>
</article>

<article class="w67">
    <h2>Level 5: Hashage en PHP et en JS du mot de passe</h2>
    <p>
    Avec le RGPD, il est légalement obligatoire pour les développeurs d'assurer un niveau de sécurité standard pour les projets internet.
    Pour les mots de passe, il ne faut pas attirer les mauvaises intentions en stockant les mots de passe des utilisateurs en clair.
    Cela ouvre la porte aux usurpations d'identité. Surtout que beaucoup d'internautes utilisent le mot de passe de leur boite email sur d'autres sites.
    C'est vraiment une pratique de base en terme d'hygiène sur internet: le mot de passe de votre compte email ne doit jamais être utilisé ailleurs.
    </p>
    <p>
    Une technique pour cacher les mots de passe est le hashage qui détruit de l'information initiale.
    Ainsi, une information hashée ne permet pas facilement de deviner l'information initiale. Car il y aurait trop de possibilités. 
    Mais l'algorithme de hashage est stable si vous partez de la même information originale, vous obtiendrez le même hashage.
    Pour les mots de passe, c'est encore insuffisant car si plusieurs comptes ont le même mot de passe, on obtiendrait le même hashage.
    Et il suffirait aussi de créer un dictionnaire des hashages des mots de passe les plus courants.
    Pour compliquer la tâche des hackers qui mettraient la main sur la liste des mots de passe, il faut ajouter en plus un grain de sel aléatoire, différent à chaque mot de passe et ensuite de hasher l'ensemble.
    Le hash produit est ainsi unique, car composé du grain de sel aléatoire et du hashage combiné (password + salt) qui devient aussi unique.    
    </p>
    <p>
    PHP gère tout cela pour les développeurs en proposant les fonctions password_hash et password_verify.    
    </p>
    <p>
    Mais avant PHP, le visiteur fournit son mot de passe sur un formulaire qui ensuite l'envoie au serveur PHP.
    C'est pourquoi il est vraiment important de passer son site en https pour crypter la communication entre le navigateur et le serveur.
    Mais le mot de passe utilisateur est quand même transmis au serveur.
    </p>
    <p>
    Pour ajouter encore plus de sécurité, il faut donc hasher le mot de passe fourni par le visiteur et en fait le serveur ne reçoit pas directement le mot de passe, mais un hashage.
    Du côté JS, les fonctions de crypto et autres ne sont pas inclus dans les navigateurs ?! 
    C'est un manque étonnant vu l'importance de JS dans l'internet actuel.
    Pour ne pas trop alourdir le code JS, il existe des fonctions de hashage md5 rapides.
    Le mot de passe serveur peut alors être un hashage qui combine l'email et le mot de passe original.
    On obtient un hash md5 de 32 caractères, en hexadecimal, composé de lettres et de chiffres, qui est produit avec une adresse email et du mot de passe.
    Cela rajoute encore plus de sécurité et le serveur ne reçoit jamais le mot de passe du visiteur, mais un hashage.    
    </p>
    <p>
    https://xoomcoder.com/assets/js/md5.js    
    </p>
    <p>
    La plupart des attaques sur les sites se contentent d'essayer les mots de passe les plus courants, sur moins de 10 caractères.
    En passant le mot de passe sur un md5, on passe sur une longueur de 32 caractères, ce qui va mettre le site hors de portée de ces attaques classiques.
    </p>
</article>


<article class="">
    <h2>Level 5: PHPMailer pour envoyer des mails simplement</h2>
    <p>
    Les emails restent un moyen de communication essentiel pour un projet internet.
    Création de compte, mot de passe oublié, newsletter, notifications, etc...
    La plupart des hébergements mutualisés donnent un quota limite à surveiller si vous avez besoin d'envoyer plusieurs milliers de mails par jour. Au delà, il faut passer sur un serveur dédié ou un service spécialisé.    
    </p>
    <p>
    PHPMailer est une classe PHP pour envoyer des mails plus complexes (en HTML, avec des pièces jointes, etc...), aussi facilement que la fonction mail disponible dans PHP. Le code est organisé en une classe PHPMailer et quelques autres classes optionnelles. Il est ainsi possible de ne prendre qu'un seul fichier qui comprend autour de 5000 lignes de code (avec plein de commentaires 😇).
    Cette simplicité fait que depuis 20 ans, cette classe est aussi intégrée dans de nombreux frameworks ou CMS du monde PHP.
    </p>
    <p>
    https://github.com/PHPMailer/PHPMailer
    </p>
    <p>
    Il est aussi possible d'utiliser Composer pour ajouter ce module avec d'autres modules dans votre projet. C'est un bon module simple pour commencer à utiliser Composer.
    Composer ajoute un fichier vendor/autoload.php à insérer dans votre projet, au début de votre code. Les différents modules sont dans le dossier vendor/. 
    Composer est assez lourd en ressources consommées et peut installer beaucoup de fichiers sans que vous vous en rendiez compte... A surveiller.    
    </p>
</article>


<article class="">
    <h2>Blood Machines: de la french SF fraîche.</h2>
    <p>
    Dans les sorties ciné de la semaine, il y a Blood Machines, épisode de 1H de SF réalisé par des français.
    Visuellement très impressionnant et plein de références à d'autres univers connus.
    Le making-of est toujours un plaisir pour découvrir les techniques qui se composent pour créer une oeuvre multimedia.
    Le cinema mélange le réel avec des bricolages et des maquettes, des acteurs devant écrans verts jusqu'aux créations virtuelles en 3D gigantesques.
    Et la bande son envoûte les spectateurs et participe à l'expérience presque hypnotique, entre la danse et la transe.
    L'univers de Blood Machines reprend l'ambiance d'un autre clip, intitulé Turbo Killer, qui jouait plus sur les courses de bolides, à la Trackmania.
    </p>
</article>
<script type="text/xoomcoder">
<iframe width="100%" height="315" src="https://www.youtube.com/embed/jLHhr8Xc4AM" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
</script>
<script type="text/xoomcoder">
<iframe width="100%" height="315" src="https://www.youtube.com/embed/WCJxd6rZnZM" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
</script>
<script type="text/xoomcoder">
<iframe width="100%" height="315" src="https://www.youtube.com/embed/er416Ad3R1g" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
</script>
<script type="text/xoomcoder">
<iframe width="100%" height="315" src="https://www.youtube.com/embed/wSc0c5S00xY" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
</script>


