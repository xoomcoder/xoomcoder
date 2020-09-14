<?php
/**
 * author:  Long Hai LH
 * date:    2020-09-03 21:05:06
 * licence: MIT
 */

class ApiMember
{
    static function run ()
    {
        if (Controller::checkMemberToken())
        {
            // FIXME: filter HTML tags with strip_tags...
            $title   = Form::filterInput("title");
            $note    = Form::filterInput("note");
            $note2   = Form::filterInput("note2");
            if ($note != "") {
                MemberAct::save($title, $note);
                MemberAct::run($note);    
            }
            if ($note2 != "") {
                MemberAct::run($note2, false, true);
            }
            
            extract(Controller::$user);
            $blocnotes = Model::read("blocnote", "id_user", $id);
            Form::mergeJson("data", [ "blocnote" => $blocnotes]);

            $now = date("Y-m-d H:i:s");
            Form::setFeedback("($now)...");
        }
        else
        {
            Form::setFeedback("Sorry...");
        }

    }

    static function runVue ()
    {
        if (Controller::checkMemberToken())
        {
            $compoName = Form::filterText("compoName");
            if (Form::isOK()) {
                extract(Controller::$user);

                $template =
                <<<x
                <div class="$compoName">
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
                    [ "id" => 1, "title" => "article 1", "code" => "code1"],
                    [ "id" => 2, "title" => "article 2", "code" => "code2" ],
                    [ "id" => 3, "title" => "article 3", "code" => "code3" ],
                ];
                $articles2 = [
                    [ "id" => 4, "title" => "article 4", "code" => "code4" ],
                    [ "id" => 5, "title" => "article 5", "code" => "code5" ],
                    [ "id" => 6, "title" => "article 6", "code" => "code6" ],
                ];
                $articles3 = [
                    [ "id" => 7, "title" => "article 7", "code" => "code7" ],
                    [ "id" => 8, "title" => "article 8", "code" => "code8" ],
                    [ "id" => 9, "title" => "article 9", "code" => "code9" ],
                ];


                $jsonData   = [];
                $jsonData["sections"] = [
                    [ "id" => 1, "title" => "section 1", "articles" => $articles1 ],
                    [ "id" => 2, "title" => "section 2", "articles" => $articles2 ],
                    [ "id" => 3, "title" => "section 3", "articles" => $articles3 ],
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

                Form::addJson($compoName, $compoCode);

            }
        }
    }

    //@end
}