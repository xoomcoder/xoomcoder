<?php
/**
 * author:  Long Hai LH
 * date:    2020-09-14 16:00:29
 * licence: MIT
 */

class VueComponent
{
    static function mypage ()
    {
        extract(Controller::$user);

        $template =
        <<<x
        <div class="mypage">
            <header>
                <nav>
                    <h1>XoomCoder Studio</h1>
                    <h2>Bienvenue $login</h2>
                    <a class="home" href="/">retourner sur le site</a>
                    <a class="logout" href="#logout" @click="actLogout">déconnexion</a>
                </nav>
            </header>
            <main>
                <template v-for="section in sections" :key="section.id">
                    <section v-if="!hide[section.class]">
                        <h2>{{ section.title }}</h2>
                        <article v-for="article in section.articles" :key="article.id">
                            <h3 v-if="article.title">{{ article.title }}</h3>
                            <pre v-if="article.code">{{ article.code }}</pre>
                            <template v-if="article.compo">
                                <component v-on:ajaxform="actAjaxForm" :is="article.compo" :params="article.params">
                                </component>
                            </template>
                        </article>
                    </section>
                </template>
            </main> 
            <footer>
                <p>XoomCoder Studio * tous droits réservés</p>
            </footer>
            <div class="toolbar">
                <img @click="switchOptions" class="action settings" src="assets/img/settings.svg" alt="settings">
            </div>  
            <div :class="{ 'options': true, 'active': !hide.options }">
                <h2>Options</h2>
                <h2>sections</h2>
                <label  v-for="section in sections">
                    <span>{{ section.title }}</span>
                    <input type="checkbox" checked @click="hide[section.class] = !hide[section.class]">
                </label>
            </div>  
        </div> 
        x;
        $articles1 = [
            [ "id" => 1, "title" => "landing page", "code" => "level1"],
            [ "id" => 2, "title" => "site vitrine", "code" => "level2" ],
            [ "id" => 3, "title" => "blog", "code" => "level3" ],
            [ "id" => 4, "title" => "cms", "code" => "level4" ],
            [ "id" => 5, "title" => "marketplace", "code" => "level5" ],
            [ "id" => 6, "title" => "teamwork", "code" => "level6" ],
        ];
        $articles2 = [
            [ "id" => 7, "title" => "html", "code" => "" ],
            [ "id" => 8, "title" => "css", "code" => "" ],
            [ "id" => 9, "title" => "js", "code" => "" ],
            [ "id" => 10, "title" => "php", "code" => "" ],
            [ "id" => 11, "title" => "sql", "code" => "" ],
            [ "id" => 12, "title" => "WordPress", "code" => "" ],
            [ "id" => 13, "title" => "VueJS", "code" => "" ],
            [ "id" => 14, "title" => "Laravel", "code" => "" ],
        ];

        $xformParams = [
            "title"     => "Publier une note",
            "fields"    => [
                [ "name" => "title", "type" => "text", "label" => "titre"],
                [ "name" => "code", "type" => "textarea", "label" => "code"],
            ], 
        ];

        $articles3 = [
            [ "id" => 15, "compo" => "xform", "params" => $xformParams ],
            [ "id" => 16, "title" => "Vos Notes", "code" => "", "compo" => "xlist" ],
            [ "id" => 17, "title" => "Mind Mapping", "code" => "", "compo" => "xmap" ],
            [ "id" => 18, "title" => "Editeur de Code", "code" => "", "compo" => "xedit" ],
            [ "id" => 19, "title" => "Vos Fichiers", "code" => "", "compo" => "xfiles" ],
        ];


        $jsonData   = [];
        $jsonData["sections"] = [
            [ "id" => 1, "class" => "projets", "title" => "Projets", "articles" => $articles1 ],
            [ "id" => 2, "class" => "technologies", "title" => "Technologies", "articles" => $articles2 ],
            [ "id" => 3, "class" => "dashboard", "title" => "Tableau de Bord", "articles" => $articles3 ],
        ];
        $jsonData["hide"] = [ "options" => true ];
        $jsonData   = json_encode($jsonData, JSON_PRETTY_PRINT);

        
        $compoCode  =
        <<<x
        {
            template:`
            $template
            `,
            data() {
                return $jsonData;
            }, 
            methods: {
                actLogout() {
                    sessionStorage.setItem('loginToken', '');
                    location.replace('login');   
                },
                switchOptions () {
                    this.hide.options = !this.hide.options;
                },
                async actAjaxForm (event) {
                    let fd = new FormData(event.target);
                    // add login token
                    let loginToken  = sessionStorage.getItem('loginToken');
                    fd.append('loginToken', loginToken);

                    let response = await fetch('api', {
                        method: 'POST',
                        body: fd
                    });
                    let json = await response.json();

                    console.log(json);
                    // show feedback
                    if ('feedback' in json) {
                        let feedback = event.target.querySelector('.feedback');
                        if (feedback) feedback.innerHTML = json.feedback;
                    }
                }
            }
        }
        x;

        return $compoCode;
    }

    static function xform ()
    {
        $template = 
        <<<x
        <form @submit.prevent="doSubmit"> 
            <h4>{{ params.title }}</h4>
            <template v-for="field in params.fields">
                <label>
                    <span>{{ field.label }}</span>
                    <textarea v-if="field.type=='textarea'" :name="field.name" required cols="60" rows="10"></textarea>
                    <input v-else type="text" :name="field.name" required>
                </label>
            </template>   
            <input type="hidden" name="classApi" value="Member">
            <input type="hidden" name="methodApi" value="geocms">
            <button type="submit">publier</button>
            <div class="feedback"></div> 
        </form>
        x;

        $jsonData   = json_encode($jsonData ?? [], JSON_PRETTY_PRINT);

        $methods =
        <<<'x'
        doSubmit(event) {
            // UX set the focus on first input
            event.target.querySelector('[required]').focus();
            this.$emit('ajaxform', event);        
        }
        x;

        $compoCode  =
        <<<x
        {
            template:`
            $template
            `,
            emits: [ 'ajaxform' ],
            props: [ 'params' ],
            data() {
                return $jsonData;
            }, 
            methods: {
                $methods
            }
        }
        x;

        return $compoCode;

    }

    static function xlist ()
    {
        $template = 
        <<<x
            <h4>Vos Notes</h4>
        x;

        $jsonData   = json_encode($jsonData ?? [], JSON_PRETTY_PRINT);

        $compoCode  =
        <<<x
        {
            template:`
            $template
            `,
            data() {
                return $jsonData;
            }, 
            methods: {
            }
        }
        x;

        return $compoCode;

    }

    static function xmap ()
    {
        $template = 
        <<<x
            <h4>Carte</h4>
        x;

        $jsonData   = json_encode($jsonData ?? [], JSON_PRETTY_PRINT);

        $compoCode  =
        <<<x
        {
            template:`
            $template
            `,
            data() {
                return $jsonData;
            }, 
            methods: {
            }
        }
        x;

        return $compoCode;

    }

    static function xfiles ()
    {
        $template = 
        <<<x
            <h4>Explorateur de fichiers</h4>
        x;

        $jsonData   = json_encode($jsonData ?? [], JSON_PRETTY_PRINT);

        $compoCode  =
        <<<x
        {
            template:`
            $template
            `,
            data() {
                return $jsonData;
            }, 
            methods: {
            }
        }
        x;

        return $compoCode;

    }

    static function xedit ()
    {
        $template = 
        <<<x
            <h4>Editeur de Code</h4>
        x;

        $jsonData   = json_encode($jsonData ?? [], JSON_PRETTY_PRINT);

        $compoCode  =
        <<<x
        {
            template:`
            $template
            `,
            data() {
                return $jsonData;
            }, 
            methods: {
            }
        }
        x;

        return $compoCode;

    }

    //@end
}