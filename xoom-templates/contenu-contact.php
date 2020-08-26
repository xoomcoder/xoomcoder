
        <section class="">
            <h1>Formulaire de contact</h1>
            <strong>Si vous avez des questions, des suggestions, des idées... N'hésitez pas à nous envoyer un message.</strong>
            <form action="api" method="POST">
                <!-- partie publique -->
                <label>
                    <div>votre nom</div>
                    <input type="text" name="nom" required placeholder="votre nom">
                </label>
                <label>
                    <div>votre email</div>
                    <input type="email" name="nom" required placeholder="votre email">
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


        <section class="nobg">
            <h1>Formulaire de renseignements</h1>
            <strong>Remplissez le formulaire suivant et recevez un code promo de 20% sur votre prochain achat sur le site.</strong>
            <script type="text/xoomcoder">
                <iframe title="formulaire de contact" src="https://docs.google.com/forms/d/e/1FAIpQLSdz3ZjxG5lYBoV7vwnab00pnldHRl92tRVq9ydH-0WCeIiMug/viewform?embedded=true" width="10%" height="3600" frameborder="0" marginheight="0" marginwidth="0">Chargement…</iframe>   
            </script>
        </section>
