
<section>
    <h2>Installation du site sur votre hébergement</h2>
    <form action="api" method="POST">
        <input type="email" name="email" required placeholder="votre email">
        <button type="submit">envoyer ma clé</button>
        <div class="feedback"></div>
        <!-- partie technique -->
        <input type="hidden" name="classApi" value="Install">
        <input type="hidden" name="methodApi" value="startSite">
    </form>
</section>

<section>
    <h2>Activation de votre clé admin</h2>
    <form action="api" method="POST">
        <input type="email" name="email" required placeholder="votre email">
        <input type="text" name="adminkey" required placeholder="votre clé">
        <button type="submit">activer ma clé</button>
        <div class="feedback"></div>
        <!-- partie technique -->
        <input type="hidden" name="classApi" value="Install">
        <input type="hidden" name="methodApi" value="activateAdmin">
    </form>
</section>