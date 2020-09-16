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
                    <h1>XoomCoder Studio / Bienvenue $login</h1>
                    <a class="home" href="/">retourner sur le site</a>
                    <a class="logout" href="#logout" @click="actLogout">déconnexion</a>
                </nav>
            </header>
            <main>
                <template v-for="section in sections" :key="section.id">
                    <section v-if="!hide[section.class]">
                        <h2>{{ section.title }}</h2>
                        <article v-for="article in section.articles" :key="article.id" :class="article.class">
                            <h3 v-if="article.title">{{ article.title }}</h3>
                            <pre v-if="article.code">{{ article.code }}</pre>
                            <template v-if="article.compo">
                                <component :is="article.compo" :params="article.params" v-on:ajaxform="actAjaxForm" v-on:sms="actSms">
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
            "fieldsCreate"    => [
                [ "name" => "title", "type" => "text", "label" => "titre"],
                [ "name" => "category", "type" => "text", "label" => "catégorie"],
                [ "name" => "code", "type" => "textarea", "label" => "code"],
            ], 
            "fieldsUpdate"    => [
                [ "name" => "title", "type" => "text", "label" => "titre"],
                [ "name" => "category", "type" => "text", "label" => "catégorie"],
                [ "name" => "datePublication", "type" => "text", "label" => "date Publication"],
                [ "name" => "code", "type" => "textarea", "label" => "code"],
            ], 
        ];
        $xlistParams = [
            "title"     => "Vos Notes",
            "model"     => "geocms",
            "cols"      => [
                "id" => "id", 
                "title" => "titre", 
                "category" => "catégorie", 
                "code" => "code", 
                "datePublication" => "date Publication", 
            ],
        ];

        $articles3 = [
            [ "id" => 15, "compo" => "xform", "params" => $xformParams, "class" => "w100" ],
            [ "id" => 16, "compo" => "xlist", "params" => $xlistParams, "class" => "w100" ],
        ];

        $articles4 = [
            [ "id" => 17, "title" => "Mind Mapping", "code" => "", "compo" => "xmap" ],
            [ "id" => 18, "title" => "Editeur de Code", "code" => "", "compo" => "xedit" ],
            [ "id" => 19, "title" => "Vos Fichiers", "code" => "", "compo" => "xfiles" ],
        ];

        $jsonData   = [];
        $jsonData["sections"] = [
            [ "id" => 1, "class" => "projets", "title" => "Projets", "articles" => $articles1 ],
            [ "id" => 2, "class" => "technologies", "title" => "Technologies", "articles" => $articles2 ],
            [ "id" => 3, "class" => "dashboard", "title" => "Tableau de Bord", "articles" => $articles3 ],
            [ "id" => 4, "class" => "outils", "title" => "Outils", "articles" => $articles4 ],
        ];
        $jsonData["hide"] = [ "options" => true, "outils" => true ];
        $jsonData["data"] = [ 
            "geocms" => Model::read("geocms", "id_user", $id), 
        ];
        $jsonData["sms"] = [ "event" => null ];
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
            provide () {
                return {
                    sms: this.sms,
                    mydata: this.data
                };
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
                    let fd = null;
                    if ('target' in event) {
                        fd = new FormData(event.target);
                        // reset
                        event.target.reset();
                    }
                    else {
                        fd = new FormData;
                    }
                    if('extrafd' in event) {
                        for(let k in event.extrafd) {
                            fd.append(k, event.extrafd[k]);
                        }            
                    }
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
                        if ('target' in event){
                            let feedback = event.target.querySelector('.feedback');
                            if (feedback) feedback.innerHTML = json.feedback;
                        }

                    }

                    if (('data' in json) && ('geocms' in json.data)) {
                        this.data.geocms = json.data.geocms;
                        console.log(this.data);
                    }
                },
                actSms (event) {
                    console.log(event);
                    this.sms.event = event;
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
        <h4>{{ params.title }}</h4>
        <template v-if="sms.event && sms.event.action=='update'">
            <h4>MODIFIER (<a href="#" @click.prevent="sms.event=null">annuler</a>)</h4>
            <form @submit.prevent="doSubmitUpdate"> 
                <template v-for="field in params.fieldsUpdate">
                    <label>
                        <span>{{ field.label }}</span>
                        <textarea v-if="field.type=='textarea'" :name="field.name" required cols="60" rows="10" v-model="sms.event.line[field.name]"></textarea>
                        <input v-else type="text" :name="field.name" required v-model="sms.event.line[field.name]">
                    </label>
                </template>   
                <input type="hidden" name="id" :value="sms.event.line.id">
                <input type="hidden" name="classApi" value="Member">
                <input type="hidden" name="methodApi" value="geocmsUpdate">
                <button type="submit">modifier</button>
                <div class="feedback"></div> 
            </form>
        </template>
        <template v-else>
            <form @submit.prevent="doSubmitCreate"> 
                <template v-for="field in params.fieldsCreate">
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
        </template>
        x;

        $jsonData   = json_encode($jsonData ?? [], JSON_PRETTY_PRINT);

        $methods =
        <<<'x'
        doSubmitCreate(event) {
            // UX set the focus on first input
            event.target.querySelector('[required]').focus();
            this.$emit('ajaxform', event);        
        },
        doSubmitUpdate(event) {
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
            inject: [ 'mydata', 'sms' ],
            emits: [ 'ajaxform', 'sms' ],
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
            <h4>{{ params.title }} <span v-if="mydata[params.model]">({{ mydata[params.model].length }})</span></h4>
            <div v-if="mydata">
                <table>
                    <thead>
                        <tr>
                            <td v-for="(colv, coln) in params.cols" :class="coln">{{ colv }}</td>
                            <td>modifier</td>  
                            <td>supprimer</td>  
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="w100" v-for="line in mydata[params.model]" :key="line.id">
                            <td v-for="(colv, coln) in params.cols">
                                <pre>{{ line[coln]}}</pre>
                            </td>
                            <td><button @click.prevent="doUpdate(line)">modifier</button></td>  
                            <td><button @click.prevent="doDelete(line.id)">supprimer</button></td>  
                        </tr>
                    </tbody>
                </table>
            </div>  
        x;


        $jsonData   = json_encode($jsonData ?? [], JSON_PRETTY_PRINT);

        $methods =
        <<<'x'
        doUpdate(line) {
            let event = { 
                line: Object.assign({}, line), 
                table: this.params.model, 
                action: 'update'
            };
            this.$emit('sms', event);        
        },
        doDelete(id) {
            let event = {};
            event.extrafd = { 
                classApi: 'Member',
                methodApi: 'run',
                note2: `
                    DbDelete?table=${this.params.model}&id=${id}
                    data/DbRead?table=geocms
                    ` };    
            this.$emit('ajaxform', event);        
        }
        x;

        $compoCode  =
        <<<x
        {
            template:`
            $template
            `,
            inject: [ 'mydata' ],
            emits: [ 'ajaxform', 'sms' ],
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