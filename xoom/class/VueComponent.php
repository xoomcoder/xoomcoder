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
                    <section>
                        <h2>{{ section.title }}</h2>
                        <article v-for="article in section.articles" :key="article.id">
                            <h3>{{ article.title }}</h3>
                            <pre>{{ article.code }}</pre>
                            <template v-if="article.compo">
                                <component :is="article.compo">
                                </component>
                            </template>
                        </article>
                    </section>
                </template>
            </main> 
            <footer>
                <p>XoomCoder Studio * tous droits réservés</p>
            </footer>  
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
        $articles3 = [
            [ "id" => 15, "title" => "Publier une note", "code" => "", "compo" => "xform" ],
        ];


        $jsonData   = [];
        $jsonData["sections"] = [
            [ "id" => 1, "title" => "Projets", "articles" => $articles1 ],
            [ "id" => 2, "title" => "Technologies", "articles" => $articles2 ],
            [ "id" => 3, "title" => "Bloc-notes", "articles" => $articles3 ],
        ];
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
            <h4>Formulaire</h4>
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