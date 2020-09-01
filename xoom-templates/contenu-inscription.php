
<section>
    <h1>Créez votre compte</h1>
    <form action="api" method="POST">
        <!-- partie publique -->
        <label>
            <div>votre identifiant</div>
            <input type="text" name="login" required placeholder="votre identifiant" pattern="^[a-z0-9][a-z0-9-]{1,20}[a-z0-9]$" maxlength="100">
        </label>
        <label>
            <div>votre email</div>
            <input type="email" name="email" required placeholder="votre email" maxlength="160">
        </label>
        <label>
            <div>votre mot de passe</div>
            <input type="password" name="password" required placeholder="votre mot de passe" pattern="^.{4,100}$" maxlength="100">
        </label>
        <button type="submit">Créer votre compte</button>
        <div class="feedback"></div>
        <p>Vous recevrez ensuite un mail pour activer votre compte.</p>
        <!-- partie technique -->
        <input type="hidden" name="classApi" value="User">
        <input type="hidden" name="methodApi" value="register">
    </form>
</section>
