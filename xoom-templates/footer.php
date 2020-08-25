</main>
    <footer>
        <a href="?"><img loading="lazy" class="logo" src="assets/img/xoomcoder.svg" alt="xoomcoder.com"></a>
        <h3><a href="?">XoomCoder</a></h3>
        <p>tous droits réservés</p>
        <p>&copy;2020</p>
        <p>Ce site est géré et publié par <a href="https://www.linkedin.com/in/applh">Long Hai LH</a> et le code est hébergé sur ionos.fr</p>
        <p>Des informations statistiques sur les visites sont stockées par l'hébergement.</p>
    </footer>

    <script>
// https://plainjs.com/javascript/events/running-code-when-the-document-is-ready-15/
function run() {
    // do something... 2000ms later

    setTimeout(() => {
        scripts = document.querySelectorAll('script[type="text/xoomcoder"]');
        for(var s=0; s<scripts.length; s++)
        {
            var current = scripts[s];
            // https://developer.mozilla.org/fr/docs/Web/API/Element/insertAdjacentHTML
            current.insertAdjacentHTML('afterend', current.innerHTML);
        }        
    }, 2000);

}

// in case the document is already rendered
if (document.readyState!='loading') run();
// modern browsers
else if (document.addEventListener) document.addEventListener('DOMContentLoaded', run);
// IE <= 8
else document.attachEvent('onreadystatechange', function(){
    if (document.readyState=='complete') run();
});        
    </script>
</body>
</html>