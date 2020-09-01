
<section>
    <h1>Créez votre compte</h1>
    <form action="api" method="POST">
        <!-- partie publique -->
        <label>
            <div>votre login</div>
            <input type="text" name="login" required placeholder="votre login">
        </label>
        <label>
            <div>votre email</div>
            <input type="email" name="email" required placeholder="votre email">
        </label>
        <label>
            <div>votre mot de passe</div>
            <input type="password" name="password" required placeholder="votre mot de passe">
        </label>
        <button type="submit">Créer votre compte</button>
        <div class="feedback"></div>
        <p>Vous recevrez ensuite un mail pour activer votre compte.</p>
        <!-- partie technique -->
        <input type="hidden" name="classApi" value="User">
        <input type="hidden" name="methodApi" value="register">
    </form>
</section>
