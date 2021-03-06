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
            <header class="cw100">
                <nav>
                    <h1>XoomCoder Studio / Bienvenue $login (level: $level)</h1>
                    <a class="home" href="/">retourner sur le site</a>
                    <a class="logout" href="#logout" @click="actLogout">déconnexion</a>
                </nav>
            </header>
            <main>

                <template v-for="section in sections" :key="section.id">
                    <section :class="section.class2" v-if="!hide[section.class]">
                        <h2 class="w100">{{ section.title }}</h2>
                        <template v-if="section.articles.length > 0">
                            <article v-for="article in section.articles" :key="article.id" :class="article.class">
                                <h3 v-if="article.title">{{ article.title }}</h3>
                                <pre v-if="article.code">{{ article.code }}</pre>
                                <template v-if="article.compo">
                                    <component :is="article.compo" :params="article.params" v-on:ajaxform="actAjaxForm" v-on:sms="actSms" v-on:loader="actLoader">
                                    </component>
                                </template>
                            </article>
                        </template>
                    </section>
                </template>
            </main> 
            <footer class="cw100">
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
                [ "id" => 1, "class" => "projets", "class2" => "projets cw100", "title" => "Projets", "articles" => $articles1 ?? [] ],
                [ "id" => 2, "class" => "technologies", "class2" => "technologies cw100", "title" => "Technologies", "articles" => $articles2 ?? [] ],
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

            $jsonDefault =
            <<<x
            x;

            $xformParams = [
                "title"     => "Ajouter",
                "model"     => "geocms",
                "fieldsCreate"    => [
                    [ "name" => "title", "type" => "text", "label" => "titre"],
                    [ "name" => "category", "type" => "text", "label" => "catégorie"],
                    [ "name" => "template", "type" => "text", "label" => "template", "optional" => true ],
                    [ "name" => "priority", "type" => "number", "label" => "priorité", "default" => $level ],
                    [ "name" => "image", "type" => "upload", "label" => "media", "optional" => true ],
                    [ "name" => "status", "type" => "text", "label" => "statut", "optional" => true, "default"=> "public" ],
                    [ "name" => "code", "type" => "markdown", "label" => "code", "default"=> $codeDefault ],
                    // [ "name" => "json", "type" => "textarea", "label" => "json", "optional" => true, "default"=> $jsonDefault ],
                ], 
                "fieldsUpdate"    => [
                    [ "name" => "title", "type" => "text", "label" => "titre"],
                    [ "name" => "category", "type" => "text", "label" => "catégorie"],
                    [ "name" => "template", "type" => "text", "label" => "template", "optional" => true ],
                    [ "name" => "priority", "type" => "number", "label" => "priorité", "default" => $level ],
                    [ "name" => "datePublication", "type" => "text", "label" => "date Publication"],
                    [ "name" => "image", "type" => "upload", "label" => "media", "optional" => true ],
                    [ "name" => "status", "type" => "text", "label" => "statut", "optional" => true ],
                    [ "name" => "code", "type" => "markdown", "label" => "code"],
                    // [ "name" => "json", "type" => "textarea", "label" => "json", "optional" => true],
                ], 
            ];
            $xlistGeocmsParams = [
                "title"     => "Vos Contenus",
                "model"     => "geocms",
                "cols"      => [
                    "id" => "id", 
                    "uri" => "URI", 
                    "title" => "titre", 
                    "template" => "template", 
                    "priority" => "priorité", 
                    "image" => "image", 
                    "status" => "statut", 
                    // "json" => "json", 
                    "datePublication" => "date Publication", 
                ],
                "filters" => [
                    "image" => "image",
                ],
            ];

            $xlistUserParams = [
                "title"     => "Vos Membres",
                "model"     => "user",
                "cols"      => [
                    "id"    => "id",
                    "login" => "login",
                    "email" => "email",
                    "level" => "level",
                    "dateCreation" => "dateCreation",
                ],
                "filters" => [
                ],
            ];

            $xlistParams = [
                "geocms" => $xlistGeocmsParams,
                "user" => $xlistUserParams,
            ];

            // WEBMASTER
            $articles3 = [
                [ "id" => 15, "compo" => "xform", "params" => $xformParams, "class" => "w100" ],
                [ "id" => 16, "compo" => "xlist", "params" => $xlistParams, "class" => "w100 cw100" ],
                [ "id" => 17, "compo" => "xsvg", "params" => [], "class" => "w100" ],
                // [ "id" => 16, "compo" => "xeditoast", "params" => [], "class" => "w100" ],
            ];
    
            $articles4 = [
                [ "id" => 17, "title" => "Mind Mapping", "code" => "", "compo" => "xmap" ],
                [ "id" => 18, "title" => "Editeur de Code", "code" => "", "compo" => "xedit" ],
                [ "id" => 19, "title" => "Vos Fichiers", "code" => "", "compo" => "xfiles" ],
            ];
            $articles5 = [
                [ "id" => 20, "title" => "Tableau de Bord", "compo" => "xmenu",
                     "params" => [ "table" => "geocms", "label" => "Tableau de Bord", "name" => "dashboard" ] ],
                [ "id" => 21, "title" => "Messages", "compo" => "xmenu", 
                    "params" => [ "table" => "geocms", "label" => "Messages", "name" => "message" ] ],
                [ "id" => 22, "title" => "News", "compo" => "xmenu", 
                    "params" => [ "table" => "geocms", "label" => "News", "name" => "news" ] ],
                [ "id" => 23, "title" => "Media", "compo" => "xmenu",
                    "params" => [ "table" => "geocms", "label" => "Media", "name" => "media" ] ],
                [ "id" => 24, "title" => "Pages", "compo" => "xmenu",
                    "params" => [ "table" => "geocms", "label" => "Pages", "name" => "page" ] ],
                [ "id" => 25, "title" => "Menus", "compo" => "xmenu",
                    "params" => [ "table" => "geocms", "label" => "Menus", "name" => "menu" ] ],
                [ "id" => 26, "title" => "Templates", "compo" => "xmenu",
                    "params" => [ "table" => "geocms", "label" => "Templates", "name" => "template" ] ],
                [ "id" => 27, "title" => "Tutoriels", "compo" => "xmenu",
                    "params" => [ "table" => "geocms", "label" => "Tutoriels", "name" => "tuto" ] ],
                [ "id" => 28, "title" => "Demos", "compo" => "xmenu",
                    "params" => [ "table" => "geocms", "label" => "Demos", "name" => "demo" ] ],
                [ "id" => 29, "title" => "Formations", "compo" => "xmenu",
                    "params" => [ "table" => "geocms", "label" => "Formations", "name" => "formation" ] ],
                [ "id" => 30, "title" => "Cities", "compo" => "xmenu",
                    "params" => [ "table" => "geocms", "label" => "Cities", "name" => "city" ] ],
                [ "id" => 31, "title" => "Membres", "compo" => "xmenu",
                    "params" => [ "table" => "user", "label" => "Membres", "name" => "user" ] ],
                [ "id" => 32, "title" => "Premium", "compo" => "xmenu",
                    "params" => [ "table" => "geocms", "label" => "Premium", "name" => "product" ] ],
                [ "id" => 33, "title" => "Réglages", "compo" => "xmenu",
                    "params" => [ "table" => "geocms", "label" => "Réglages", "name" => "option" ] ],
            ];
            $jsonData["sections"] = [
                [ "id" => 3, "class" => "dashboard", "class2" => "dashboard", "title" => "Administration", "articles" => $articles3 ?? [] ],
                [ "id" => 4, "class" => "outils", "class2" => "outils cw100", "title" => "Outils", "articles" => $articles4 ?? [] ],
                [ "id" => 5, "class" => "menu", "class2" => "menu", "title" => "Menu", "articles" => $articles5 ?? [] ],
            ];
        }

        $jsonData["hide"] = [ "options" => true, "outils" => true ];
        $jsonData["data"] = [ 
            // "geocms" => Model::read("geocms", "id_user", $id, "category DESC, template DESC, priority DESC, datePublication DESC, id DESC"), 
        ];

        $jsonData["sms"]            = [ "event" => null ];
        $jsonData["lastAjax"]       = time();
        $jsonData["toast"]          = null;
        $jsonData["menuContext"]    = [ 
            "table" => "geocms",
            "name"  => "dashboard", 
            "label" => "Tableau de Bord", 
            "form"  => [ "category" => "dashboard" ], 
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
            provide () {
                return {
                    sms: this.sms,
                    mydata: this.data,
                    lastAjax: this.lastAjax,
                    toast: this.toast,
                    menuContext: this.menuContext
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
                async actLoader (event) {
                    console.log(event);
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
                    // add menu context
                    fd.append('menuContext', this.menuContext.name);

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

                    if ('data' in json) {
                        if ('user' in json.data) {
                            this.data.user = json.data.user;
                            console.log(this.data);
                        }
                        if ('geocms' in json.data) {
                            this.data.geocms = json.data.geocms;
                            console.log(this.data);
                            // update timestamp
                            this.lastAjax = Date.now();
                            console.log(this.lastAjax);
                        }
                    }

                },
                actSms (event) {
                    this.sms.event = event;
                    // update timestamp
                    this.lastAjax = Date.now();
                    // console.log(this.lastAjax);

                }
            },
            mounted () {
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
            <h4 v-if="params.title" @click="actSwitch">
                <a class="act">{{ syncLabel }}</a>
                <input type="checkbox" v-model="show"> 
            </h4>
            <div class="options active" v-if="sms.event && sms.event.action=='update'">
                <form @submit.prevent="doSubmitUpdate" method="POST" enctype="multipart/form-data"> 
                    <h4>MODIFIER</h4>
                    <button class="w20" @click.prevent="sms.event=null">annuler</button>
                    <button class="w40" type="submit">sauvegarder</button>
                    <button class="w20" @click.prevent="doUpdateLine(1)">suivant</button>
                    <button class="w20" @click.prevent="doUpdateLine(-1)">précédent</button>
                    <template v-for="field in params.fieldsUpdate">
                        <label :class="field.name">
                            <span>{{ field.label }}</span>
                            <textarea class="w100" v-if="field.type=='textarea'" :name="field.name" :required="!field.optional" cols="60" rows="30" v-model="sms.event.line[field.name]" :placeholder="field.label"></textarea>
                            <template v-else-if="field.type=='markdown'">
                                <component ref="xeditoast" is="xeditoast" v-on:loader="actLoader" :target="'toasteditorUpdate'" :name="field.name" :data="sms.event.line[field.name]" :field="field"></component>
                            </template>
                            <input v-else-if="field.type=='upload'" type="file" :name="field.name" :required="!field.optional" :placeholder="field.label">
                            <input v-else type="text" :name="field.name" :required="!field.optional" v-model="sms.event.line[field.name]" :placeholder="field.label">
                        </label>
                    </template>   
                    <input type="hidden" name="id" :value="sms.event.line.id">
                    <input type="hidden" name="classApi" value="Member">
                    <input type="hidden" name="methodApi" value="geocmsUpdate">
                    <button type="submit" class="w50">sauvegarder</button>
                    <div class="feedback"></div> 
                </form>
                </div>
            <template v-else-if="show">
                <form @submit.prevent="doSubmitCreate"> 
                    <template v-for="field in params.fieldsCreate">
                        <label :class="field.name">
                            <span>{{ field.label }}</span>
                            <textarea class="w100" v-if="field.type=='textarea'" :name="field.name" :required="!field.optional" cols="60" rows="30" :placeholder="field.label" v-model="current[field.name]"></textarea>
                            <template v-else-if="field.type=='markdown'">
                                <component ref="xeditoast" is="xeditoast" v-on:loader="actLoader" :field="field" :target="'toasteditorCreate'" :name="field.name" data=""></component>
                            </template>
                            <input v-else-if="field.type=='upload'" type="file" :name="field.name" :required="!field.optional" :placeholder="field.label">
                            <input v-else type="text" :ref="field.name" :name="field.name" :required="!field.optional" :placeholder="field.label" v-model="current[field.name]">
                        </label>
                    </template>   
                    <input type="hidden" name="classApi" value="Member">
                    <input type="hidden" name="methodApi" value="geocms">
                    <button type="submit" class="w50">publier</button>
                    <div class="feedback"></div> 

                </form>
            </template>

        </template>

        x;

        $jsonData                   = [];
        $jsonData["current"]        = [ "id" => null ];
        $jsonData["codeMirror"]     = [ "id" => null ];
        $jsonData["codeMirror2"]    = [ "id" => null ];
        $jsonData["show"]           = false;

        $jsonData   = json_encode($jsonData ?? [], JSON_PRETTY_PRINT);

        $methods =
        <<<'x'
        actSwitch () {
            this.show = !this.show;
        },
        actLoader(event) {
            console.log(event);
            this.$emit('loader', event);
        },
        doUpdateLine(step) {
            let curdata = this.sms.event.filterList;

            let index2 = (this.sms.event.index + step) % curdata.length;
            if (index2 < 0) index2 = curdata.length -1; // loop

            let line2  = curdata[index2];

            // copy all necessary data
            let event = { 
                line: Object.assign({}, line2), 
                table: this.params.model, 
                action: 'update',
                index: index2,
                filterList: this.sms.event.filterList
            };
            this.$emit('sms', event);        
        },
        doSubmitCreate(event) {
            // UX set the focus on first input
            let fc = event.target.querySelector('[required]');
            if (fc) fc.focus();

            // add extra option
            event.keepInput = true;

            // FIXME: force sync with textarea
            // console.log(this.$refs.xeditoast);
            // this.$refs.xeditoast.forceSave();

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

        $extraCode =
        <<<'x'
        mounted () {
        }
        x;

        $compoCode  =
        <<<x
        {
            template:`
            $template
            `,
            inject: [ 'mydata', 'sms', 'menuContext' ],
            provide () {
                return {
                    codeMirror: this.codeMirror
                };
            },
            emits: [ 'ajaxform', 'sms', 'loader' ],
            props: [ 'params' ],
            data() {
                return $jsonData;
            }, 
            computed: {
                syncLabel () {
                    // hack: force category in form
                    this.current.category = this.menuContext.form.category;
                    this.show = false;
                    return this.params.title  + ' ' + this.menuContext.label;
                }
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

            },
            $extraCode
        }
        x;

        return $compoCode;

    }

    static function xlist ()
    {
        $template = 
        <<<'x'
        <template v-if="params">
            <h4>{{ menuContext.label }} <span v-if="mydata[menuContext.table]">({{ filterCount2 + filterCount }})</span></h4>
            <div v-if="mydata">
                <table>
                    <thead>
                        <tr>
                            <td v-for="(colv, coln) in params[menuContext.table].cols" :class="coln">
                                <h5>{{ colv }}</h5>
                                <input type="text" @keyup="filterUpdate(coln,$event)">
                            </td>
                            <td class="view"><h5>voir</h5></td>  
                            <td class="update"><h5>modifier</h5></td>  
                            <td class="delete"><h5>supprimer</h5></td>  
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="w100" v-for="(line, index) in showList" :key="line.id">
                            <td v-for="(colv, coln) in params[menuContext.table].cols">
                                <div v-if="params[menuContext.table].filters[coln]" :title="line[coln]" v-html="filterShow(line, coln)">
                                </div>
                                <pre v-else>{{ line[coln]}}</pre>
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

        $jsonData = [];
        $jsonData["filterList"] = [];
        $jsonData["filterCol"]  = "";
        $jsonData["filterVal"]  = "";
        $jsonData["refresh"]    = "";

        $jsonData   = json_encode($jsonData ?? [], JSON_PRETTY_PRINT);

        $methods =
        <<<'x'
        filterShow(line, col) {
            let action = this.params[this.menuContext.table].filters[col];
            let value = line[col];

            let res=value;
            if ((action == 'image') && (value)) {
                let ext = value.split('.').pop();
                if (-1 < "jpg,jpeg,gif,png,svg".indexOf(ext)) {
                    res = '<img src="/' + line.uri + '--' + this.n2t(line.id) + '.' + ext 
                            + '?b64=' + btoa(line.datePublication + '  ')
                            +'&fresh=' + this.lastAjax +'">';
                } 
            }
            return res;
        },
        filterUpdate(name,event) {
            if (name) this.filterCol = name;
            if (event) this.filterVal = event.target.value;

            this.filterList = this.mydata[this.menuContext.table];
            if ((this.filterCol != '') && (this.filterVal != '')) {
                this.filterVal = this.filterVal.toLowerCase();

                this.filterList = this.filterList.filter((line) => {
                    let col = line[this.filterCol];
                    if (col) {
                        col = col.toLowerCase();
                        if (this.filterVal.startsWith('**')) {
                            return col.endsWith(this.filterVal.substring(2));
                        }
                        else if (this.filterVal.startsWith('*')) {
                            return (-1 < col.indexOf(this.filterVal.substring(1)));
                        }
                        else {
                            return col.startsWith(this.filterVal);
                        }
                    }
                    else return false;
                })
            }
        }, 
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
                table: this.menuContext.table, 
                action: 'update',
                index: index,
                filterList: this.filterList
            };
            this.$emit('sms', event);        
        },
        doDelete(id) {
            let event = {};
            event.extrafd = { 
                classApi: 'Member',
                methodApi: 'run',
                note2: `
                    DbDelete?table=${this.menuContext.table}&id=${id}
                    data/DbRead?table=${this.menuContext.table}&category=${this.menuContext.name}
                    ` };    
            this.$emit('ajaxform', event);        
        }
        x;

        $computed =
        <<<x
        filterCount2() {
            // hack to be updated when source list is updated
            let res = this.mydata[this.menuContext.table];
            this.filterUpdate(null,null);
            return "";
        },
        filterCount() {
            return this.filterList.length + '/' + this.mydata[this.menuContext.table].length;
        },
        showList() {
            return this.filterList;
        }
        x;

        $compoCode  =
        <<<x
        {
            template:`
            $template
            `,
            inject: [ 'mydata', 'lastAjax', 'menuContext' ],
            emits: [ 'ajaxform', 'sms', 'loader' ],
            props: [ 'params' ],
            data() {
                return $jsonData;
            },
            computed: {
                $computed
            }, 
            methods: {
                $methods
            },
            watch: {
            },
            created() {
                this.filterList = this.mydata[this.menuContext.table];
            },
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

    static function xsvg ()
    {
        // https://fr.vuejs.org/v2/examples/svg.html
        $template = 
        <<<x
            <h4>SVG</h4>
            <div class="wreset w50 h50">
                <input type="range" v-model="crx" min="-100" max="100">
                <input type="range" v-model="cry">
                <svg viewBox="0 0 1000 1000" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <g id="happy">
                            <rect :x="200+0.1*crx" y="0" :width="200-0.2*crx" :height="200+0.1*cry" rx="16" ry="16" fill="pink" />
                            <circle :cx="250" cy="50" :r="20+0.1*crx" fill="white" />
                            <circle :cx="350" cy="50" :r="20+0.1*crx" fill="white" />
                            <circle :cx="250+0.1*crx" cy="50" :r="10+0.1*crx"/>
                            <circle :cx="350+0.1*crx" cy="50" :r="10+0.1*crx"/>
                            <ellipse cx="300" cy="150" :rx="50+0.1*crx" :ry="0.5*cry"/>
                        </g>
                        <g id="mypoly" fill="none" stroke="#66aa66">
                            <circle cx="100" cy="100" r="100" fill="yellow"/>
                            <circle cx="100" cy="100" r="50" fill="orange" @click="actCircle0"/>
                            <polygon :points="points" />
                        </g>
                    </defs>
                    <use href="#happy" x="100" y="0" width="100" height="100"></use>
                    <use href="#mypoly" x="600" y="0" width="100" height="100"></use>
                </svg>
            </div>
        x;

        $jsonData = [
            "crx"    => 20,
            "cry"    => 50,
            "stats"   => [ 100, 100, 100, 100, 100 ],
        ];

        $jsonData   = json_encode($jsonData ?? [], JSON_PRETTY_PRINT);

        $compoCode  =
        <<<x
        {
            template:`
            $template
            `,
            emits: [ 'ajaxform', 'sms', 'loader' ],
            inject: [ 'menuContext' ],
            props: [ 'params' ],
            data() {
                return $jsonData;
            }, 
            computed: {
                // a computed property for the polygon's points
                points: function() {
                    let total = this.stats.length;
                    let myapp = this;
                    return this.stats
                    .map(function(stat, i) {
                        let point = myapp.valueToPoint(stat, i, total);
                        return point.x + "," + point.y;
                    })
                    .join(" ");
                }
            },
            methods: {
                actCircle0(event) {
                    console.log(event.target);
                },
                valueToPoint(value, index, total) {
                    let x = 0;
                    //let y = -value * 0.8;
                    let y = -value * 1;
                    let angle = ((Math.PI * 2) / total) * index;
                    let cos = Math.cos(angle);
                    let sin = Math.sin(angle);
                    let tx = x * cos - y * sin + 100;
                    let ty = x * sin + y * cos + 100;
                    return { x: tx, y: ty };
                }
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

    static function xmenu ()
    {
        $template = 
        <<<x
            <a class="act" @click="actMenu">{{ params.label }}</a>
        x;

        $jsonData   = [];
        $jsonData   = json_encode($jsonData ?? [], JSON_PRETTY_PRINT);

        $extraCode  = 
        <<<'x'
            methods: {
                actMenu(event) {
                    this.menuContext.name = this.params.name;
                    this.menuContext.label = this.params.label;
                    this.menuContext.table = this.params.table;
                    this.menuContext.form.category = this.params.name;

                    let event2 = { type: 'css', url: 'gogogo'};
                    event2.extrafd = { 
                        classApi: 'Member',
                        methodApi: 'geocmsMenu',
                        code: `
                        data/DbRead?table=${this.params.table}&category=${this.params.name}
                        ` };    
                        
                    this.$emit('ajaxform', event2);

                }
            }
        x;

        $compoCode  =
        <<<x
        {
            template:`
            $template
            `,
            emits: [ 'ajaxform', 'sms', 'loader' ],
            inject: [ 'menuContext' ],
            props: [ 'params' ],
            data() {
                return $jsonData;
            },
            $extraCode 
        }
        x;

        return $compoCode;

    }

    static function xeditoast ()
    {
        $template = 
        <<<x
            <textarea ref="code" class="w100" :name="this.field.name" cols="60" rows="30" :placeholder="this.field.label"></textarea>
            <h1>{{ infos }}</h1>
            <div>
                <a href="#" class="act w50" @click.prevent="actCopyCode">copier le code source</a>
                <a href="#" class="act w50" @click.prevent="actUpdateCode">mettre à jour le code source</a>
            </div>
            <div class="toasteditor" :id="this.target"></div>
        x;

        $jsonData = [];
        $jsonData["modelValue"]  = [ "code" => "" ];
        $jsonData["editor"]  = [ "empty" => true ];
        //$jsonData["codeMirror"]  = [ "empty" => true ];

        $jsonData   = json_encode($jsonData ?? [], JSON_PRETTY_PRINT);

        $extraCode = 
        <<<'x'
        created () {
            let event = { type: 'css', url: 'gogogo'};
            console.log(event);
            this.$emit('loader', event);        
        },
        mounted () {
            console.log(this.target);
            let targetId = '#' + this.target;
            if (this.editor.empty) {
                console.log(targetId);
                let target = document.querySelector(targetId);
                if (target) {
                    this.editor = new Editor({
                            el: document.querySelector(targetId),
                            previewStyle: 'vertical',
                            height: '500px',
                            initialValue: '',
                            usageStatistics: false,
                            plugins: [
                                [chart, chartOptions], 
                                codeSyntaxHighlight, 
                                tableMergedCell,
                                [uml, umlOptions],
                                youtubePlugin
                            ]
                        });    
                }
            }
            else {
                //this.editor.reset();
                //this.editor.setMarkdown('');
            }

            // CODE MIRROR
            if (this.$refs.code) {
                this.codeMirror = CodeMirror.fromTextArea(this.$refs.code, {
                    mode: "markdown",
                    lineNumbers: true
                });
                if ('undefined' !== this.data) {
                    this.codeMirror.setValue(this.data);
                    this.actCopyCode();
                }
                else
                    this.codeMirror.setValue('');

                // FIXME: debug first submit
                this.codeMirror.on('change', (event) => {
                    //console.log(event);
                    this.codeMirror.save();
                });    

            }

    
        },
        beforeDestroy() {
            // if (this.editor) this.editor.remove();
            // let targetId = '#' + this.target;
            // let el = document.querySelector(targetId)
            // if (el) el.innerHTML = '';
        }
        x;

        $compoCode  =
        <<<x
        {
            template:`
            $template
            `,
            inject: [ 'mydata', 'sms', 'toast', 'codeMirror' ], // bug if data ?
            props: [ 'params', 'edit', 'target', 'name', 'data', 'field' ],
            emits: [ 'ajaxform', 'sms', 'loader' ],
            data() {
                return $jsonData;
            }, 
            methods: {
                forceSave () {
                    console.log('forceSave');
                },
                actCopyCode (event) {
                    let code = '';
                    if (this.codeMirror) code = this.codeMirror.getValue();

                    this.editor.changeMode('wysiwyg'); // debug: markdown mode error
                    if (code) this.editor.setMarkdown(code, false);

                    // https://nhn.github.io/tui.editor/latest/ToastUIEditor#moveCursorToStart
                    this.editor.moveCursorToStart();
                },
                actUpdateCode (event) {
                    let code = this.editor.getMarkdown();
                    console.log(this.codeMirror);
                    if (this.codeMirror) {
                        this.codeMirror.setValue(code);
                    }
                    else {            
                        let textarea = event.target.parentNode.querySelector('form textarea[name=' + this.name + ']');
                        if (textarea) textarea.value = code;
                    }
                }

            },
            computed: {
                infos () {
                    if (this.sms.event && this.sms.event.line) {
                        // hack to stay updated...
                        if (this.sms.event.action == 'update') {
                            let code = this.sms.event.line[this.field.name];
                            if (this.codeMirror && this.codeMirror.setValue) this.codeMirror.setValue(code);
                        }
                        return this.sms.event.line.title + ' (' + this.sms.event.line.id + ')';
                    }
                }   
            },
            $extraCode
        }
        x;

        return $compoCode;

    }

    //@end
}