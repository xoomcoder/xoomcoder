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
                                    <component :is="article.compo" :params="article.params" v-on:ajaxform="actAjaxForm" v-on:sms="actSms" v-on:loader="actLoader">
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

            $jsonDefault =
            <<<x
            x;

            $xformParams = [
                "title"     => "Publier une note",
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
            $xlistParams = [
                "title"     => "Vos Contenus",
                "model"     => "geocms",
                "cols"      => [
                    "id" => "id", 
                    "uri" => "URI", 
                    "title" => "titre", 
                    "category" => "catégorie", 
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
            // WEBMASTER
            $articles3 = [
                [ "id" => 15, "compo" => "xform", "params" => $xformParams, "class" => "w100" ],
                [ "id" => 16, "compo" => "xlist", "params" => $xlistParams, "class" => "w100" ],
                // [ "id" => 16, "compo" => "xeditoast", "params" => [], "class" => "w100" ],
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

        $jsonData["sms"]        = [ "event" => null ];
        $jsonData["lastAjax"]   = time();

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
                    lastAjax: this.lastAjax
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
                        // update timestamp
                        this.lastAjax = Date.now();
                        console.log(this.lastAjax);
                    }

                },
                actSms (event) {
                    this.sms.event = event;
                    // update timestamp
                    this.lastAjax = Date.now();
                    console.log(this.lastAjax);

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
                <form @submit.prevent="doSubmitUpdate" method="POST" enctype="multipart/form-data"> 
                    <h4>MODIFIER</h4>
                    <button class="w20" @click.prevent="sms.event=null">annuler</button>
                    <button class="w40" type="submit">sauvegarder</button>
                    <button class="w20" @click.prevent="doUpdateLine(1)">suivant</button>
                    <button class="w20" @click.prevent="doUpdateLine(-1)">précédent</button>
                    <template v-for="field in params.fieldsUpdate">
                        <label>
                            <span>{{ field.label }}</span>
                            <textarea v-if="field.type=='textarea'" :name="field.name" :required="!field.optional" cols="60" rows="60" v-model="sms.event.line[field.name]" :placeholder="field.label"></textarea>
                            <template v-else-if="field.type=='markdown'">
                                <textarea :name="field.name" :required="!field.optional" cols="60" rows="60" v-model="sms.event.line[field.name]" :placeholder="field.label"></textarea>
                            </template>
                            <input v-else-if="field.type=='upload'" type="file" :name="field.name" :required="!field.optional" :placeholder="field.label">
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
                            <template v-else-if="field.type=='markdown'">
                                <textarea :name="field.name" :required="!field.optional" cols="60" rows="20" :placeholder="field.label" v-model="current[field.name]"></textarea>
                            </template>
                            <input v-else-if="field.type=='upload'" type="file" :name="field.name" :required="!field.optional" :placeholder="field.label">
                            <input v-else type="text" :name="field.name" :required="!field.optional" :placeholder="field.label" v-model="current[field.name]">
                        </label>
                    </template>   
                    <input type="hidden" name="classApi" value="Member">
                    <input type="hidden" name="methodApi" value="geocms">
                    <button type="submit">publier</button>
                    <div class="feedback"></div> 
                </form>
                <div class="toasteditor" id="toasteditorCreate"></div>
            </template>
            <component is="xeditoast" v-on:loader="actLoader" :target="'toasteditorUpdate'"></component>
            <div class="toasteditor" id="toasteditorUpdate"></div>

        </template>

        x;

        $jsonData   = [];
        $jsonData["current"] = [ "id" => null ];
        $jsonData   = json_encode($jsonData ?? [], JSON_PRETTY_PRINT);

        $methods =
        <<<'x'
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
            emits: [ 'ajaxform', 'sms', 'loader' ],
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
        <<<'x'
        <template v-if="params">
            <h4>{{ params.title }} <span v-if="mydata[params.model]">({{ filterCount2 + filterCount }})</span></h4>
            <div v-if="mydata">
                <table>
                    <thead>
                        <tr>
                            <td v-for="(colv, coln) in params.cols" :class="coln">
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
                            <td v-for="(colv, coln) in params.cols">
                                <div v-if="params.filters[coln]" :title="line[coln]" v-html="filterShow(line, coln)">
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
            let action = this.params.filters[col];
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

            this.filterList = this.mydata[this.params.model];
            if ((this.filterCol != '') && (this.filterVal != '')) {

                this.filterList = this.filterList.filter((line) => {
                    let col = line[this.filterCol];
                    if (col) {
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
                table: this.params.model, 
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
                    DbDelete?table=${this.params.model}&id=${id}
                    data/DbRead?table=geocms
                    ` };    
            this.$emit('ajaxform', event);        
        }
        x;

        $computed =
        <<<x
        filterCount2() {
            // hack to be updated when source list is updated
            let res = this.mydata[this.params.model];
            this.filterUpdate(null,null);
            return "";
        },
        filterCount() {
            return this.filterList.length + '/' + this.mydata[this.params.model].length;
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
            inject: [ 'mydata', 'lastAjax' ],
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
                mydata(v2, v1) {
                    console.log(v2);
                    this.filterList = this.mydata[this.params.model];
                }
            },
            created() {
                this.filterList = this.mydata[this.params.model];
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

    static function xeditoast ()
    {
        $template = 
        <<<x
        x;

        $jsonData = [];
        $jsonData["modelValue"]  = [ "code" => "" ];
        $jsonData["editor"]  = null;

        $jsonData   = json_encode($jsonData ?? [], JSON_PRETTY_PRINT);

        $extraCode = 
        <<<'x'
        created () {
            let event = { type: 'css', url: 'gogogo'};
            console.log(event);
            this.$emit('loader', event);        
        },
        mounted () {
            let targetId = '#' + this.target;
            this.editor = new Editor({
                el: document.querySelector(targetId),
                previewStyle: 'vertical',
                height: '500px',
                initialValue: '',
                usageStatistics: false,
                plugins: [
                    [chart, chartOptions], codeSyntaxHighlight, colorSyntax, tableMergedCell, [uml, umlOptions]
                ]
            });
    
        }
        x;

        $compoCode  =
        <<<x
        {
            template:`
            $template
            `,
            props: [ 'params', 'edit', 'target' ],
            emits: [ 'ajaxform', 'sms', 'loader' ],
            data() {
                return $jsonData;
            }, 
            methods: {
            },
            $extraCode
        }
        x;

        return $compoCode;

    }

    //@end
}