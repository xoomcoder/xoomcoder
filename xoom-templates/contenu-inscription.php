
<section id="register">
    <h1>Créez votre compte</h1>
    <form action="" method="POST" @submit.prevent="actRegister">
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
        actRegister (event) {
            console.log('actRegister');
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

var app = Vue.createApp(appConfig).mount('#register');   // css selector to link with HTML
</script>
