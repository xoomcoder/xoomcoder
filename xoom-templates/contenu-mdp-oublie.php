
<section id="mdp-oublie">
    <h1>Mot de passe oublié ?!</h1>
    <form action="api" method="POST">
        <!-- partie publique -->
        <label>
            <div>votre email</div>
            <input type="email" name="email" required placeholder="votre email" maxlength="160" value="<?php echo Form::filterEmail("email") ?>">
        </label>
        <button type="submit">Envoyer par email une clé pour changer mon mot de passe</button>
        <div class="feedback"></div>
        <p>Vous pourrez ensuite changer votre mot de passe.</p>
        <!-- partie technique -->
        <input type="hidden" name="classApi" value="User">
        <input type="hidden" name="methodApi" value="passwordLost">
    </form>
</section>

<section id="mdp-change">
    <h2>Changement de mot de passe</h2>
    <form action="" method="POST" @submit.prevent="actPasswordChange">
        <!-- partie publique -->
        <label>
            <div>votre email</div>
            <input type="email" name="email" required placeholder="votre email" maxlength="160" value="<?php echo Form::filterEmail("email") ?>">
        </label>
        <label>
            <div>votre nouveau mot de passe</div>
            <input type="password" name="password" required placeholder="votre mot de passe" pattern="^.{4,100}$" maxlength="100">
        </label>
        <label>
            <div>votre code temporaire</div>
            <input type="text" name="key" required placeholder="votre code temporaire" value="<?php echo Form::filterEmail("key") ?>">
        </label>
        <button type="submit">Changer mon mot de passe</button>
        <div class="feedback"></div>
        <p>Vous pourrez ensuite vous connecter avec votre nouveau mot de passe.</p>
        <!-- partie technique -->
        <input type="hidden" name="classApi" value="User">
        <input type="hidden" name="methodApi" value="passwordChange">
    </form>
</section>

<script src="assets/js/md5.js"></script>
<script src="https://unpkg.com/vue@next"></script>
<script>
// https://v3.vuejs.org/guide/introduction.html#getting-started
const appConfig = {
    data() {
        return {
            // add Here your JS properties to sync with HTML
        }
    },
    methods: {
        actPasswordChange (event) {
            var fd      = new FormData(event.target);
            var pwd     = fd.get('password');
            var email   = fd.get('email');
            var pwdh    = md5(md5(email) + md5(pwd));
            fd.set('password', pwdh);

            fetch('api', {
                method: 'POST',
                body: fd
            })
            .then(function(response) {
                response.json().then(function(json) {
                    console.log(json);
                    if ('feedback' in json) {
                        var f = event.target.querySelector('.feedback');
                        if (f) f.innerHTML = json.feedback;
                    }
                });
            });
        }
    }
};

var app = Vue.createApp(appConfig).mount('#mdp-change');   // css selector to link with HTML
</script>

