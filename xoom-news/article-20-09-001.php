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


