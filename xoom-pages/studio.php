<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- IMPORTANT: NO INDEX -->
    <meta name="robots" content="noindex">

    <title>XoomCoder Studio</title>

    <!-- favicon -->
    <link rel="icon" href="assets/img/xoomcoder.svg">

    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="assets/css/studio.css">
    <link rel="stylesheet" href="assets/leaflet/leaflet.css">

</head>
<body>
    <div id="app">
        <header>
            <h1>BIENVENUE DANS VOTRE STUDIO </h1>
            <h2>({{ username }}) <small><a href="#logout" @click.prevent="actLogout">logout</a></small></h2>
        </header>
        <main>
            <section>
                <h1>Tableau de Bord</h1>
                
                <h2>
                    <input type="checkbox" v-model="showtodo">
                    Vos Projets
                </h2>
                <todo-item
                    v-show="showtodo"
                    v-for="item in todos"
                    :todo="item"
                    :key="item.id"
                ></todo-item>
            </section>

            <section>

                <h2>
                    <input type="checkbox" v-model="showform">
                    Bloc-Notes
                </h2>
                <form v-show="showform" action="" @submit.prevent="saveNote">
                    <input id="notetitle" type="text" name="title" placeholder="entrez un titre" v-model="title">
                    <textarea id="batchcode" name="note" cols="80" rows="10" placeholder="prenez des notes" v-model="code"></textarea>
                    <textarea class="hidden" name="note2" cols="80" rows="10">
                    </textarea>
                    <input id="inlat" type="hidden" name="lat" v-model="userlat">
                    <input id="inlng" type="hidden" name="lng" v-model="userlng">
                    <h6>{{ '(latitude:' + userlat + ', longitude:' + userlng + ')' }}</h6>
                    <button id="batchbutton" type="submit">sauvegarder</button>
                    <div class="feedback"></div>
                    <!-- partie technique -->
                    <input type="hidden" name="classApi" value="Member">
                    <input type="hidden" name="methodApi" value="run">
                    <input type="hidden" name="loginToken" v-model="loginToken">
                </form>

            </section>

            <section v-if="content.blocnote">
                <h2>
                    <input type="checkbox" v-model="shownotes">
                    Votre liste de notes ({{ content.blocnote.length }})
                </h2>
                <div v-show="shownotes && (content.blocnote.length > 1)">
                    <button @click="actRefresh">rafraichir la liste</button>
                    <strong>afficher à partir de la note {{ 1 + 1 * start }}/{{ content.blocnote.length }}</strong>
                    <input type="range" min="0" :max="content.blocnote.length -1" v-model="start">
                </div>
                <div class="rowflex x3col" v-if="shownotes">
                   <template v-for="(bn, index) in content.blocnote" :key="bn.id">
                    <article class="rowflex" v-if="(bn.status != true)" v-show="(start <= index)">
                        <h3 v-if="bn.title" :title="bn.id">{{ bn.title }}</h3>
                        <pre v-if="bn.code">{{ bn.code }}</pre>
                        <pre v-if="bn.json">{{ bn.json }}</pre>
                        <small>
                            <input type="checkbox" @click="inverse(bn)" checked>
                            {{ 1 + index }}/{{ content.blocnote.length }} - {{ bn.dateLastRun }} ({{ bn.id }}) 
                        </small>
                        <button class="w50" @click="actCopy(bn)">copier</button>
                        <button class="w50" @click="actDelete(bn)">supprimer</button>
                    </article>
                    </template>
                </div>

            </section>

            <section class="fullw">
                <h2>
                    <input type="checkbox" v-model="showmap">
                    City Map
                </h2>
                <button id="copos" @click="setPosition">set position</button>
                <button @click="updatePosition">refresh my geolocation</button>
                <button @click="resetPosition">reset my position</button>
                <button @click="flytoPosition">fly to marker</button>
                <input type="range" min="0" max="10" v-model="randradius">
                <button @click="randradius++">{{ randradius }}</button>
                <input type="range" min="0" :max="nbmarker-1" v-model="curmarker" @change="changeMarker">
                <button @click="curmarker++; changeMarker()">{{ 1+ 1* curmarker }} / {{ curTitle() }}</button>
                <div v-show="showmap" style="width: 100%; height: 100%;">
                    <div id="mymap" style="width: 100%; height: 100%;"></div>
                </div>
            </section>

            <footer>
                <p>tous droits réservés</p>
            </footer>
        </main>

    </div>
    <script src="assets/leaflet/leaflet.js"></script>
    <script>

let map = null;
let position0 = null;
let userlat = 0;
let userlng = 0;
let userMarker = null;

function initMap () {
    mymap = document.querySelector("#mymap");
    if (mymap) {
        mymap.style.width = Math.round(1.0 * document.body.clientWidth) + 'px';
        mymap.style.height = Math.round(0.9 * document.body.clientHeight) + 'px';
    }
    map = L.map('mymap').fitWorld();

    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
        minZoom: 3,
        maxZoom: 10,
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
            '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
            'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        id: 'mapbox/streets-v11',
        tileSize: 512,
        zoomOffset: -1 // 0 // -1 ?
    }).addTo(map);

    map.on('locationfound', onLocationFound);
    map.on('locationerror', onLocationError);

    //map.locate({setView: true, maxZoom: 10});
    map.locate();

    window.addEventListener('resize', function(event) {
        mymap.style.width = Math.round(1.0 * document.body.clientWidth) + 'px';
        mymap.style.height = Math.round(0.9 * document.body.clientHeight) + 'px';
        map.invalidateSize();
    });

}

function userMove (e) {
    // console.log(e);
    userlat = e.latlng.lat;
    userlng = e.latlng.lng;
    // bricolage
    let copos = document.querySelector("#copos");
    if (copos) copos.click();
}
function onLocationFound(e) {
    var radius = Math.round(e.accuracy / 2);
    if (userMarker == null) {
        userMarker = L.marker(e.latlng,{draggable: true})
        .on('move', userMove)
        .addTo(map)
        .bindPopup("Vous avez été géolocalisé à " + radius + " mètres de ce point. Vous pouvez ajuster la position en déplaçant le marqueur.").openPopup();

        L.circle(e.latlng, 1000, { color: 'red'}).addTo(map);
        // L.circle(e.latlng, radius).addTo(map);

    }
    else {
        userMarker.setLatLng(e.latlng);
        position0 = e.latlng;
    }

    position0 = e.latlng;
    userlat = e.latlng.lat;
    userlng = e.latlng.lng;

    map.flyTo(position0, 10);

    setTimeout(function(){
        // bricolage
        let copos = document.querySelector("#copos");
        if (copos) copos.click();
    }, 1000);
}

function onLocationError(e) {
    alert(e.message);
}


</script>

    <script src="https://unpkg.com/vue@next"></script>
    <script>
// https://v3.vuejs.org/guide/introduction.html#getting-started
const appConfig = {    
    data() {
        return {
            nbmarker: 0,
            curmarker: 0,
            randradius: 1,
            userlng: 0,
            userlat: 0,
            showmap: true,
            showtodo: true,
            showform: true,
            shownotes: true,
            start: 0,
            title: '',
            code: '',
            content: {},
            todos: [
                { id: 0, text: 'Level 1: Landing Page' },
                { id: 1, text: 'Level 2: Site Vitrine' },
                { id: 2, text: 'Level 3: Site Blog' },
                { id: 3, text: 'Level 4: Site CMS' },
                { id: 4, text: 'Level 5: Site Marketplace' }
            ],
            username: '',
            loginToken: ''
        }
    },
    computed: {
    },
    methods: {
        curTitle () {
            res = '';
            if (this.content.blocnote) {
                res = this.content.blocnote[this.curmarker].title;
                res += ' / ' + this.content.blocnote[this.curmarker].dateLastRun;
            }
            return res;
        },
        changeMarker () {
            let xMarker = markernotes[this.curmarker];
            map.flyTo(xMarker.getLatLng(), 10, { duration: 0.8 });
            xMarker.openPopup();
        },
        flytoPosition () {
            map.flyTo(userMarker.getLatLng(), 10);
        },
        updatePosition () {
            // map.locate({setView: true, maxZoom: 10});
            map.locate();
        },
        resetPosition () {
            map.flyTo(position0, 10);
            userMarker.setLatLng(position0);
        },
        setPosition () {
            // bricolage
            this.userlat = userlat;
            this.userlng = userlng;
        },
        userlng2 () {
            //console.log(this.userlng);
            return this.userlng;
        },
        userlat2 () {
            //console.log(this.userlat);
            return this.userlat;
        },
        // add here your functions/methods    
        actRefresh() {
            this.actDelete({ id: 0 });
        },   
        inverse(bn) {
            bn.status = !bn.status;
        },
        actCopy (bn) {
            this.title = bn.title;
            this.code = bn.code;
        },
        actDelete (bn) {
            //console.log(bn);
            let fd = new FormData;
            fd.append('classApi', 'Member');
            fd.append('methodApi', 'run');
            fd.append('loginToken', this.loginToken);
            let note2 = `
            DbDelete?table=blocnote&id=${bn.id}
            `;
            fd.append('note2', note2);
            this.sendAjax({ 'formdata' : fd });
        },
        saveNote (event) {
            let oldlat = this.userlat;
            let oldlng = this.userlng;

            // add some random
            document.querySelector('#inlat').value = parseFloat(document.querySelector('#inlat').value) + (0.5 - Math.random()) * this.randradius * 0.5;
            document.querySelector('#inlng').value = parseFloat(document.querySelector('#inlng').value) + (0.5 - Math.random()) * this.randradius * 0.5;

            notetitle.focus(); // send the focus back to the title to keep on taking notes
            
            this.sendAjax(event);
            this.title = '';
            this.code  = '';
            this.userlat = oldlat;
            this.userlng = oldlng;
        },
        sendAjax (event) {
            let myapp = this;   // ?? hack
            var fd = {};
            if ('target' in event) fd = new FormData(event.target);
            else if ('formdata' in event) fd = event.formdata;
            fetch('api', {
                method: 'POST',
                body: fd
            })
            .then((response) => {
                response
                    .json()
                    .then((json) => {
                        var ajaxpack = {
                            'app': myapp,
                            'event': event,
                            'fd' : fd,
                            'response': response,
                            'json': json 
                            };
                        for(m in xcb) {
                            xcb[m](ajaxpack);
                        }
                    })
            });
        },
        actLogout() {
            sessionStorage.setItem('loginToken', '');
            location.replace('login');   
        }
    },
    mounted() {
        let loginToken = sessionStorage.getItem('loginToken');
        if (loginToken) {
            let infos = loginToken.split(',');
            // console.log(infos);
            this.loginToken = loginToken;
            this.username = infos[0];
        }
        else {
            location.replace('login');
        }

        this.actDelete({id: 0});

        initMap();
    }
};
let app = Vue.createApp(appConfig);

app.component('todo-item', {
  props: ['todo'],
  template: `<li>{{ todo.text }}</li>`
});

app.mount('#app');   // css selector to link with HTML



var xcb = {};
// add callbacks to activate on ajax response
xcb.feedback = function (ajaxpack)  {
    if ('feedback' in ajaxpack.json) {
        if ('target' in ajaxpack.event) {
            var f = ajaxpack.event.target.querySelector('.feedback');
            if (f) f.innerHTML = ajaxpack.json.feedback;
        }
    }
};

let greenIcon = L.icon({
    iconUrl: 'assets/img/leaf-green.png',
    shadowUrl: 'assets/img/leaf-shadow.png',

    iconSize:     [20, 50], // size of the icon
    shadowSize:   [20, 30], // size of the shadow
    iconAnchor:   [10, 50], // point of the icon which will correspond to marker's location
    shadowAnchor: [2, 30],  // the same for the shadow
    popupAnchor:  [0, -30] // point from which the popup should open relative to the iconAnchor
});
let orangeIcon = L.icon({
    iconUrl: 'assets/img/leaf-orange.png',
    shadowUrl: 'assets/img/leaf-shadow.png',

    iconSize:     [20, 50], // size of the icon
    shadowSize:   [20, 30], // size of the shadow
    iconAnchor:   [10, 50], // point of the icon which will correspond to marker's location
    shadowAnchor: [2, 30],  // the same for the shadow
    popupAnchor:  [0, -30] // point from which the popup should open relative to the iconAnchor
});
let redIcon = L.icon({
    iconUrl: 'assets/img/leaf-red.png',
    shadowUrl: 'assets/img/leaf-shadow.png',

    iconSize:     [20, 50], // size of the icon
    shadowSize:   [20, 30], // size of the shadow
    iconAnchor:   [10, 50], // point of the icon which will correspond to marker's location
    shadowAnchor: [2, 30],  // the same for the shadow
    popupAnchor:  [0, -30] // point from which the popup should open relative to the iconAnchor
});
let markernotes = [];
xcb.data = function (ajaxpack) {
    if('data' in ajaxpack.json) {
        for (table in ajaxpack.json.data) {
            // console.log(ajaxpack.json.data[table]);
            // console.log(ajaxpack.app);
            ajaxpack.app.content[table] = ajaxpack.json.data[table];
        }

        // hack pour la liste de blocnote
        if ('blocnote' in ajaxpack.json.data) {
            let bnotes = ajaxpack.json.data.blocnote;
            ajaxpack.app.nbmarker = bnotes.length;

            // reset
            for(let m=0; m<markernotes.length; m++) {
                let marker = markernotes[m];
                map.removeLayer(marker);
            }
            markernotes = [];

            for(let n=0; n<bnotes.length; n++) {
                let note = bnotes[n];
                if (note.json) {
                    let info = JSON.parse(note.json);
                    if ((info != null) && ('lat' in info) && ('lng' in info)) {
                        
                        iIcon = (n == 0) ? redIcon : orangeIcon;

                        // console.log(info.lat + '/' + info.lng);
                        let nmark = L.marker({ 'lat': info.lat, 'lng': info.lng },{icon: iIcon, draggable: true});
                        let nhtml = `
                        <h3>${note.title} (${note.id})</h3>
                        <pre>${note.code}</pre> 
                        <h6>${note.dateLastRun}</h6>  
                        `;
                        nmark
                            .addTo(map)
                            .bindPopup(nhtml);

                        markernotes.push(nmark);
                    }
                }
            }
            if (userMarker) {
                // bring to front ?!
                map.removeLayer(userMarker);
                userMarker.addTo(map);
            }
            if (markernotes.length >0)
                map.flyTo(markernotes[0].getLatLng(), 10);
                markernotes[0].openPopup();
        }

    }

}
    </script>


</body>
</html>