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
                    <h1>XoomCoder Studio / Bienvenue $login (level: $level)</h1>
                    <a class="home" href="/">retourner sur le site</a>
                    <a class="logout" href="#logout" @click="actLogout">déconnexion</a>
                </nav>
            </header>
            <main>
                <template v-for="section in sections" :key="section.id">
                    <section v-if="!hide[section.class]">
                        <h2>{{ section.title }}</h2>
                        <template v-if="section.articles.length > 0">
                            <article v-for="article in section.articles" :key="article.id" :class="article.class">
                                <h3 v-if="article.title">{{ article.title }}</h3>
                                <pre v-if="article.code">{{ article.code }}</pre>
                                <template v-if="article.compo">
                                    <component :is="article.compo" :params="article.params" v-on:ajaxform="actAjaxForm" v-on:sms="actSms">
                                    </component>
                                </template>
                            </article>
                        </template>
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
                <label v-for="section in sections">
                    <span>{{ section.title }}</span>
                    <input type="checkbox" checked @click="hide[section.class] = !hide[section.class]">
                </label>
            </div>  
        </div> 
        x;

        $jsonData   = [];

        if (($level < 100) && ($level >= 10)) {
            // MEMBER
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

            $jsonData["sections"] = [
                [ "id" => 1, "class" => "projets", "title" => "Projets", "articles" => $articles1 ?? [] ],
                [ "id" => 2, "class" => "technologies", "title" => "Technologies", "articles" => $articles2 ?? [] ],
            ];
        
        }


        if ($level == 100) {
            $codeDefault = 
            <<<x
            @bloc meta
            { 
                "class" : "",
                "cover" : "" 
            }
            @bloc      

            @bloc markdown

            ## titre 2

            @bloc
            x;

            $xformParams = [
                "title"     => "Publier une note",
                "model"     => "geocms",
                "fieldsCreate"    => [
                    [ "name" => "title", "type" => "text", "label" => "titre"],
                    [ "name" => "category", "type" => "text", "label" => "catégorie"],
                    [ "name" => "template", "type" => "text", "label" => "template", "optional" => true ],
                    [ "name" => "priority", "type" => "number", "label" => "priorité", "default" => $level ],
                    [ "name" => "code", "type" => "textarea", "label" => "code", "default"=> $codeDefault ],
                ], 
                "fieldsUpdate"    => [
                    [ "name" => "title", "type" => "text", "label" => "titre"],
                    [ "name" => "category", "type" => "text", "label" => "catégorie"],
                    [ "name" => "template", "type" => "text", "label" => "template", "optional" => true ],
                    [ "name" => "priority", "type" => "number", "label" => "priorité", "default" => $level ],
                    [ "name" => "datePublication", "type" => "text", "label" => "date Publication"],
                    [ "name" => "code", "type" => "textarea", "label" => "code"],
                ], 
            ];
            $xlistParams = [
                "title"     => "Vos Notes",
                "model"     => "geocms",
                "cols"      => [
                    "id" => "id", 
                    "uri" => "URI", 
                    "title" => "titre", 
                    "category" => "catégorie", 
                    "template" => "template", 
                    "priority" => "priorité", 
                    // "code" => "code", 
                    "datePublication" => "date Publication", 
                ],
            ];
            // WEBMASTER
            $articles3 = [
                [ "id" => 15, "compo" => "xform", "params" => $xformParams, "class" => "w100" ],
                [ "id" => 16, "compo" => "xlist", "params" => $xlistParams, "class" => "w100" ],
            ];
    
            $articles4 = [
                [ "id" => 17, "title" => "Mind Mapping", "code" => "", "compo" => "xmap" ],
                [ "id" => 18, "title" => "Editeur de Code", "code" => "", "compo" => "xedit" ],
                [ "id" => 19, "title" => "Vos Fichiers", "code" => "", "compo" => "xfiles" ],
            ];
            $jsonData["sections"] = [
                [ "id" => 3, "class" => "dashboard", "title" => "Tableau de Bord", "articles" => $articles3 ?? [] ],
                [ "id" => 4, "class" => "outils", "title" => "Outils", "articles" => $articles4 ?? [] ],
            ];
        }

        $jsonData["hide"] = [ "options" => true, "outils" => true ];
        $jsonData["data"] = [ 
            "geocms" => Model::read("geocms", "id_user", $id, "category DESC, template DESC, priority DESC"), 
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
                        if (!event.keepInput)
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

                    // waiting...
                    let feedback = null;
                    if ('target' in event) {
                        feedback = event.target.querySelector('.feedback');
                        if (feedback) feedback.innerHTML = "...";
                        } 

                    let response = await fetch('api', {
                        method: 'POST',
                        body: fd
                    });
                    let json = await response.json();

                    console.log(json);
                    // show feedback
                    if ('feedback' in json) {
                        if ('target' in event) {
                            if (feedback) feedback.innerHTML = json.feedback;
                        }
                    }

                    if (('data' in json) && ('geocms' in json.data)) {
                        this.data.geocms = json.data.geocms;
                        console.log(this.data);
                    }
                },
                actSms (event) {
                    // console.log(event);
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
        <template v-if="params">
            <h4 v-if="params.title">{{ params.title }}</h4>
            <div class="options active" v-if="sms.event && sms.event.action=='update'">
                <form @submit.prevent="doSubmitUpdate"> 
                    <h4>MODIFIER</h4>
                    <button class="w20" @click.prevent="sms.event=null">annuler</button>
                    <button class="w40" type="submit">sauvegarder</button>
                    <button class="w20" @click.prevent="doUpdateLine(1)">suivant</button>
                    <button class="w20" @click.prevent="doUpdateLine(-1)">précédent</button>
                    <template v-for="field in params.fieldsUpdate">
                        <label>
                            <span>{{ field.label }}</span>
                            <textarea v-if="field.type=='textarea'" :name="field.name" :required="!field.optional" cols="60" rows="60" v-model="sms.event.line[field.name]" :placeholder="field.label"></textarea>
                            <input v-else type="text" :name="field.name" :required="!field.optional" v-model="sms.event.line[field.name]" :placeholder="field.label">
                        </label>
                    </template>   
                    <input type="hidden" name="id" :value="sms.event.line.id">
                    <input type="hidden" name="classApi" value="Member">
                    <input type="hidden" name="methodApi" value="geocmsUpdate">
                    <button type="submit">sauvegarder</button>
                    <div class="feedback"></div> 
                </form>
            </div>
            <template v-else>
                <form @submit.prevent="doSubmitCreate"> 
                    <template v-for="field in params.fieldsCreate">
                        <label>
                            <span>{{ field.label }}</span>
                            <textarea v-if="field.type=='textarea'" :name="field.name" :required="!field.optional" cols="60" rows="20" :placeholder="field.label" v-model="current[field.name]"></textarea>
                            <input v-else type="text" :name="field.name" :required="!field.optional" :placeholder="field.label" v-model="current[field.name]">
                        </label>
                    </template>   
                    <input type="hidden" name="classApi" value="Member">
                    <input type="hidden" name="methodApi" value="geocms">
                    <button type="submit">publier</button>
                    <div class="feedback"></div> 
                </form>
            </template>
        </template>
        x;

        $jsonData   = [];
        $jsonData["current"] = [ "id" => null ];
        $jsonData   = json_encode($jsonData ?? [], JSON_PRETTY_PRINT);

        $methods =
        <<<'x'
        doUpdateLine(step) {
            let curdata = this.mydata[this.params.model];

            let index2 = (this.sms.event.index + step) % curdata.length;
            if (index2 < 0) index2 = curdata.length -1; // loop

            let line2  = curdata[index2];

            let event = { 
                line: Object.assign({}, line2), 
                table: this.params.model, 
                action: 'update',
                index: index2
            };
            this.$emit('sms', event);        
        },
        doSubmitCreate(event) {
            // UX set the focus on first input
            let fc = event.target.querySelector('[required]');
            if (fc) fc.focus();

            // add extra option
            event.keepInput = true;

            this.$emit('ajaxform', event);        
        },
        doSubmitUpdate(event) {
            // UX set the focus on first input
            let fc = event.target.querySelector('[required]');
            if (fc) fc.focus();

            // add extra option
            event.keepInput = true;

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
            },
            created () {
                // console.log(this.params.fieldsCreate);
                if (this.params.fieldsCreate) {
                    for(let f=0; f<this.params.fieldsCreate.length; f++) {
                        let field = this.params.fieldsCreate[f];
                        if (field.default) {
                            this.current[field.name] = field.default;
                        }
                    }
                }
            }
        }
        x;

        return $compoCode;

    }

    static function xlist ()
    {
        $template = 
        <<<x
        <template v-if="params">
            <h4>{{ params.title }} <span v-if="mydata[params.model]">({{ mydata[params.model].length }})</span></h4>
            <div v-if="mydata">
                <table>
                    <thead>
                        <tr>
                            <td v-for="(colv, coln) in params.cols" :class="coln">{{ colv }}</td>
                            <td class="view">voir</td>  
                            <td class="update">modifier</td>  
                            <td class="delete">supprimer</td>  
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="w100" v-for="(line, index) in mydata[params.model]" :key="line.id">
                            <td v-for="(colv, coln) in params.cols">
                                <pre>{{ line[coln]}}</pre>
                            </td>
                            <td class="view"><a v-if="line.uri" target="blank" :href="'/' + line.uri + '--' + n2t(line.id)">voir</a></td>  
                            <td><button @click.prevent="doUpdate(line, index)">modifier</button></td>  
                            <td><button @click.prevent="doDelete(line.id)">supprimer</button></td>  
                        </tr>
                    </tbody>
                </table>
            </div>  
        </template>
        x;


        $jsonData   = json_encode($jsonData ?? [], JSON_PRETTY_PRINT);

        $methods =
        <<<'x'
        n2t(n) {
            let res= '';
            let c = "bcdfghjklmnpqrstvwxz";
            let v = "aeiou";
            let cur = n;
            while(cur >0) {
                let p = cur % 100;
                
                let pc = p % 20;
                let curc = c[pc];
                let curv = v[(p-pc)/20];
                res += curc + curv; 

                cur = (cur -p) / 100;
            }
            return res;
        },
        doUpdate(line, index) {
            let event = { 
                line: Object.assign({}, line), 
                table: this.params.model, 
                action: 'update',
                index: index
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