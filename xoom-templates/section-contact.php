
<section class="">
    <h1>Formulaire de contact</h1>
    <p>Si vous avez des questions, des suggestions, des idées... N'hésitez pas à nous envoyer un message.</p>
    <form action="api" method="POST">
        <!-- partie publique -->
        <label>
            <div>votre nom</div>
            <input type="text" name="nom" required placeholder="votre nom">
        </label>
        <label>
            <div>votre email</div>
            <input type="email" name="email" required placeholder="votre email">
        </label>
        <label>
            <div>votre message</div>
            <textarea name="message" cols="80" rows="10" required placeholder="votre message"></textarea>
        </label>
        <button type="submit">Envoyer votre message</button>
        <div class="feedback"></div>
        <p>Nous vous répondrons dans les meilleurs délais.</p>
        <!-- partie technique -->
        <input type="hidden" name="classApi" value="Contact">
        <input type="hidden" name="methodApi" value="message">
    </form>
</section>
