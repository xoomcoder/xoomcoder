
<section id="activation">
    <h1>Activez votre compte</h1>
    <form action="api" method="POST">
        <!-- partie publique -->
        <label>
            <div>votre email</div>
            <input type="email" name="email" required placeholder="votre email" maxlength="160" value="<?php echo Form::filterEmail("email") ?>">
        </label>
        <label>
            <div>votre code d'activation</div>
            <input type="text" name="activationKey" required placeholder="votre code d'activation" minlength="32" maxlength="32" value="<?php echo Form::filterMd5("key") ?>">
        </label>
        <button type="submit">Activer votre compte</button>
        <div class="feedback"></div>
        <p>Vous pourrez ensuite vous connecter à votre compte.</p>
        <!-- partie technique -->
        <input type="hidden" name="classApi" value="User">
        <input type="hidden" name="methodApi" value="activate">
    </form>
</section>
