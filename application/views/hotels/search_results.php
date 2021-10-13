<div id="sresult" class="container p-4 home_container bg-white mt-3">
    <div class="row">
        <div class="col-md-12">
            <h6 v-if="sresults.length" class="text-center search_count_head d-none d-md-block">{{sresults.length}}
                <?= lang('htavailablein') ?> <span class="mcolor">
                    <?= ($this->input->get('city') == 164) ? lang('mak') : lang('mad') ?></span></h6>
            <h6 v-if="resloading" class="text-center search_count_head"><?= lang('searching') ?>...</h6>
            <div class="row justify-content-between">
                <!-- sidebar -->
                <div class="col-md-3 d-none d-lg-block pl-3">
                    <div class="px-3 sidebar">
                        <div class="row mt-4 p-2">
                            <div class="col">
                                <a class="btn btn-block ebtn" href="#" data-toggle='modal' data-target='#MapAllModal'
                                    data-backdrop="false">Map</a>
                            </div>
                        </div>
                        <div class="row mt-4 p-2">
                            <div class="col">
                                <label class="font-weight-bold" for="priceRange"><?= lang('price') ?> :
                                    <?= lang('startfrom') ?>
                                    {{ pricefilter.pricerate }}</label>
                                <input type="range" class="form-control-range" id="priceRange"
                                    :min="pricefilter.minprice" :max="pricefilter.maxprice"
                                    v-model="pricefilter.pricerate" step="5">
                            </div>
                        </div>
                        <div class="row mb-2 mt-2">
                            <div class="col">
                                <ul class="list-unstyled p-1">
                                    <h6 class="font-weight-bold"><?= lang('srate') ?></h6>
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="559" id="1stars"
                                                v-model="stars">
                                            <label class="form-check-label" for="1stars">
                                                <i class="fa fa-star mcolor"></i>
                                            </label>
                                        </div>

                                    </li>
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="560" id="2stars"
                                                v-model="stars">
                                            <label class="form-check-label" for="2stars">
                                                <i class="fa fa-star mcolor"></i>
                                                <i class="fa fa-star mcolor"></i>
                                            </label>
                                        </div>

                                    </li>
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="561" id="3stars"
                                                v-model="stars">
                                            <label class="form-check-label" for="3stars">
                                                <i class="fa fa-star mcolor"></i>
                                                <i class="fa fa-star mcolor"></i>
                                                <i class="fa fa-star mcolor"></i>
                                            </label>
                                        </div>

                                    </li>
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="562" id="4stars"
                                                v-model="stars">

                                            <label class="form-check-label" for="4stars">
                                                <i class="fa fa-star mcolor"></i>
                                                <i class="fa fa-star mcolor"></i>
                                                <i class="fa fa-star mcolor"></i>
                                                <i class="fa fa-star mcolor"></i>
                                            </label>
                                        </div>

                                    </li>
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="563" id="5stars"
                                                v-model="stars">
                                            <label class="form-check-label" for="5stars">
                                                <i class="fa fa-star mcolor"></i>
                                                <i class="fa fa-star mcolor"></i>
                                                <i class="fa fa-star mcolor"></i>
                                                <i class="fa fa-star mcolor"></i>
                                                <i class="fa fa-star mcolor"></i>
                                            </label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="row mb-2 mt-2">
                            <div class="col">
                                <ul class="list-unstyled p-1">
                                    <h6 class="font-weight-bold"><?= lang('meals') ?></h6>
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="1" id="breakfast">
                                            <label class="form-check-label" for="breakfast">
                                                <?= lang('incbf') ?>
                                            </label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="row mb-2 mt-2">
                            <div class="col">
                                <ul class="list-unstyled p-1">
                                    <h6 class="font-weight-bold"><?= lang('amenities') ?></h6>
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="1" id="wifi"
                                                v-model="wifisub">
                                            <label class="form-check-label" for="wifi">
                                                <?= lang('wifi') ?>
                                            </label>
                                        </div>
                                    </li>

                                </ul>
                            </div>
                        </div>
                        <div class="row mb-2 mt-2">
                            <div class="col">
                                <ul class="list-unstyled p-1">
                                    <h6 class="font-weight-bold"><?= lang('receservice') ?></h6>
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="2" id="24-7s">
                                            <label class="form-check-label" for="24-7s">
                                                <?= lang('receservice24') ?>
                                            </label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- /sidebar -->
                <div class="col-md-9 col-sm-12 px-md-4">
                    <div class="row">
                        <div class="col-sm-12">
                            <nav class="navbar navbar-expand-lg navbar-light d-lg-none mb-2">
                                <button v-if="sresults.length" class="navbar-toggler" type="button"
                                    data-toggle="collapse" data-target="#navbarSupportedContent"
                                    aria-controls="navbarSupportedContent" aria-expanded="false"
                                    aria-label="Toggle navigation">
                                    <span class="navbar-toggler-icon"></span>
                                </button>
                                <span v-if="sresults.length" class="text-center search_count_head"
                                    style="font-size: 0.95rem;">{{sresults.length}} <?= lang('htavailablein') ?> <span
                                        class="mcolor">
                                        <?= ($this->input->get('city') == 164) ? lang('mak') : lang('mad') ?></span></span>

                                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                    <ul class="navbar-nav mr-auto">
                                        <li class="nav-item dropdown">
                                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown"
                                                role="button" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                Sort By
                                            </a>
                                            <div class="dropdown-menu p-0" aria-labelledby="navbarDropdown">
                                                <ul class="list-group p-0">
                                                    <li class="list-group-item text-center">
                                                        <h6><?= lang('srate') ?></h6>
                                                    </li>
                                                    <li class="list-group-item text-center">
                                                        <div class="form-check">
                                                            <label class="form-check-label" for="1stars">
                                                                <i class="fa fa-star mcolor"></i>
                                                            </label>
                                                            <input class="form-check-input" type="checkbox" value="559"
                                                                id="1stars" v-model="stars">
                                                        </div>

                                                    </li>
                                                    <li class="list-group-item text-center">
                                                        <div class="form-check">

                                                            <label class="form-check-label" for="2stars">
                                                                <i class="fa fa-star mcolor"></i>
                                                                <i class="fa fa-star mcolor"></i>
                                                            </label>
                                                            <input class="form-check-input" type="checkbox" value="560"
                                                                id="2stars" v-model="stars">
                                                        </div>

                                                    </li>
                                                    <li class="list-group-item text-center">
                                                        <div class="form-check">

                                                            <label class="form-check-label" for="3stars">
                                                                <i class="fa fa-star mcolor"></i>
                                                                <i class="fa fa-star mcolor"></i>
                                                                <i class="fa fa-star mcolor"></i>
                                                            </label>
                                                            <input class="form-check-input" type="checkbox" value="561"
                                                                id="3stars" v-model="stars">
                                                        </div>

                                                    </li>
                                                    <li class="list-group-item text-center">
                                                        <div class="form-check">

                                                            <label class="form-check-label" for="4stars">
                                                                <i class="fa fa-star mcolor"></i>
                                                                <i class="fa fa-star mcolor"></i>
                                                                <i class="fa fa-star mcolor"></i>
                                                                <i class="fa fa-star mcolor"></i>
                                                            </label>
                                                            <input class="form-check-input" type="checkbox" value="562"
                                                                id="4stars" v-model="stars">
                                                        </div>

                                                    </li>
                                                    <li class="list-group-item text-center">
                                                        <div class="form-check">
                                                            <label class="form-check-label" for="5stars">
                                                                <i class="fa fa-star mcolor"></i>
                                                                <i class="fa fa-star mcolor"></i>
                                                                <i class="fa fa-star mcolor"></i>
                                                                <i class="fa fa-star mcolor"></i>
                                                                <i class="fa fa-star mcolor"></i>
                                                            </label>
                                                            <input class="form-check-input" type="checkbox" value="563"
                                                                id="5stars" v-model="stars">
                                                        </div>
                                                    </li>
                                                </ul>

                                                <ul class="list-group p-0">
                                                    <li class="list-group-item">
                                                        <div class="form-group">
                                                            <label for="priceRange">Price:
                                                                {{ pricefilter.pricerate }}</label>
                                                            <input type="range" class="form-control-range"
                                                                id="priceRange" :min="pricefilter.minprice"
                                                                :max="pricefilter.maxprice"
                                                                v-model="pricefilter.pricerate" step="5">
                                                        </div>
                                                    </li>
                                                </ul>

                                            </div>
                                        </li>
                                        <li class="nav-item dropdown">
                                            <a class="nav-link dropdown-toggle" href="#" id="AmnetiesDropdown"
                                                role="button" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                <?= lang('amenities') ?>
                                            </a>
                                            <div class="dropdown-menu p-0" aria-labelledby="AmnetiesDropdown">
                                                <ul class="list-group p-0">

                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="1"
                                                                id="wifi" v-model="wifisub">
                                                            <label class="form-check-label" for="wifi">
                                                                <?= lang('wifi') ?>
                                                            </label>
                                                        </div>
                                                        <span class="badge badge-warning badge-pill">14</span>
                                                    </li>

                                                </ul>
                                            </div>
                                        </li>
                                        <li class="nav-item dropdown">
                                            <a class="nav-link dropdown-toggle" href="#" id="MapDropdown" role="button"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Map
                                            </a>
                                            <div class="dropdown-menu p-0" aria-labelledby="MapDropdown">

                                                <ul class="list-group p-0">
                                                    <li class="list-group-item">
                                                        <!-- v-if="Object.keys(res.hotel.geo).length" -->
                                                        <div class="text-center">
                                                            <a class="btn btn-block btn-outline-info" href="#"
                                                                data-toggle='modal' data-target='#MapAllModal'>View On
                                                                Map</a>
                                                        </div>

                                                    </li>
                                                </ul>
                                            </div>

                                        </li>
                                    </ul>
                                </div>
                            </nav>
                        </div>
                    </div>
                    <!-- result loader -->

                    <div v-if="resloading" class="row mt-3">
                        <div class="col mt-4 bg-white pb-4">
                            <div class="spinner">
                                <div class="bounce1"></div>
                                <div class="bounce2"></div>
                                <div class="bounce3"></div>
                            </div>

                        </div>
                    </div>
                    <!-- /result loader -->
                    <search-result v-else-if="sresults != null && sresults.length" v-for="(res, ind) in sresults"
                        :key="ind" :res="res" :rid="ind">
                    </search-result>
                    <div v-else class="alert alert-danger text-center mt-4">
                        <h6 v-if="searcherror" class="text-center search_count_head">{{searcherror}}</h6>
                        <?= lang('noresult') ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- mapmodal -->

    <div class="modal  fade" id="MapModal" tabindex="-1" role="dialog" aria-labelledby="MapModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <gmap-map :center="hotelInitialLatLng" :zoom="18" style="height: 600px; width: 100%;" ref="map"
                        id="themap">

                        <gmap-marker :position="hotelInitialLatLng" :clickable="true"
                            @click="openInfoWindowTemplate(hotelInitialLatLng)" ref="markers"
                            icon="<?= base_url('public_designs/assets/img/favicon.png') ?>" animation="2"
                            :title="thotel.name" ref="map"
                        id="themap" map-type-id="roadmap"></gmap-marker>

                        <gmap-info-window  :options="{maxWidth: 300}" :position="infoWindow.position"
                            :opened="infoWindow.open" @closeclick="infoWindow.open = false">
                            <div id="infowindow">

                                <div class="text-center bordered">
                                    <img :src="thotel.photo" height='100' width='150' />
                                    <hr>
                                    <h6>{{thotel.name}}</h6>
                                    <hr>
                                    <h6 v-html="thotel.price"></h6>
                                    <hr>
                                    <a class="btn btn-warning" target="__blank"
                                        v-bind:href="'https://www.google.com/maps/dir/?api=1&destination='+thotel.name">See
                                        directions</a>
                                </div>

                            </div>
                        </gmap-info-window>
                    </gmap-map>
                </div>
            </div>
        </div>
    </div>
    <!-- mapmodal -->

    <!-- mapmodalAll -->
    <div class="modal  fade" id="MapAllModal" tabindex="-1" role="dialog" aria-labelledby="MapAllModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <gmap-map :center="cityInitialLatLng" :zoom="14" style="height: 600px; width: 100%;" ref="map"
                        id="themap" map-type-id="roadmap">
                        <gmap-marker v-for="(ht, index) in csresults" :key="index"
                            :position="{lat: parseFloat(ht.hotel.geo.lat), lng: parseFloat(ht.hotel.geo.lng)}"
                            :clickable="true" @click="tmopenInfoWindowTemplate(index)" :opened="location.infoBoxOpen"
                            @closeclick="location.infoBoxOpen=false" ref="markers"
                            icon="<?= base_url('public_designs/assets/img/favicon.png') ?>"></gmap-marker>
                        <gmap-info-window v-if="tmhotel" :options="{maxWidth: 300}"
                            :position="{lat: Number(tmhotel.hotel.geo.lat), lng: Number(tmhotel.hotel.geo.lng)}"
                            :opened="tminfoWindow.open" @closeclick="tminfoWindow.open = false">
                            <div id="infowindall">
                                <div class="text-center borderd">
                                    <img :src="tmhotel.hotel.Main_Photo" class="img-fluid img-thumbnail" height='100'
                                        width='150' />
                                    <hr>
                                    <h6>{{tmhotel.hotel.Hotel_Name}}</h6>


                                    <a class="btn btn-warning" target="__blank"
                                        v-bind:href="'https://www.google.com/maps/dir/?api=1&destination='+tmhotel.hotel.Hotel_Name">See
                                        directions</a>




                                </div>
                            </div>
                        </gmap-info-window>
                    </gmap-map>
                </div>
            </div>
        </div>
    </div>
    <!-- mapmodalAll -->
</div>


<script src="<?php echo base_url('public_designs/assets') ?>/js/vplugs/vue-google-maps.js"></script>
<script>
Vue.config.devtools = true;

Vue.use(VueGoogleMaps, {
    load: {
        key: '<?= API_GOOGLE ?>'
    }
});

Vue.component('search-result', {
    props: ['res', 'rid'],
    template: `<div :class="'row rounded p-md-4 mb-3 search_result' + (res.hotel.recommended?' border border-success':'')">
        <div class="col-md-4 p-0 ">
        <a :href="dataParams.surl +'hotel/details/'+ res.hotel.hslug +'/'+rslts.dt1+'/'+ rslts.dt2 +'/?rooms='+rslts.srooms+'&recrooms='+rslts.recrooms(res.hotel.rooms)"><img v-if="res.hotel.Main_Photo" class="img-fluid rounded" :src="res.hotel.Main_Photo"  :alt="res.hotel.Hotel_Name" style="height:230px; width:100%;"></a>
        </div>
        <div class="col res-sum">
        <div class="d-flex justify-content-between pt-2 mb-2">
            <a class="hotel-name":href="dataParams.surl +'hotel/details/'+res.hotel.hslug +'/'+rslts.dt1+'/'+rslts.dt2+'/?rooms='+rslts.srooms+'&recrooms='+rslts.recrooms(res.hotel.rooms)">
                {{ res.hotel.Hotel_Name }} <sup><i v-for="star in parseInt(res.hotel.starno)" class="fa fa-star mcolor" style="font-size:12px"></i></sup>
            </a>
            <a class="clink" v-if="Object.keys(res.hotel.geo).length"href="#" data-toggle='modal' data-target='#MapModal' data-backdrop='false' @click="rslts.shmap(rid)" >
              Map</a>
        </div>
            <div class="d-block">
                <p><small class="font-weight-bold">{{res.hotel.city_name}}</small> <small>{{res.hotel.Hotel_Address}}</small></p>
            </div>

            <div class="row justify-content-between">
                <div v-if="res.hotel.rooms.length" class="rooms-sum mt-1 col-12 col-md-6 mt-2">
                    <div  v-for="room in res.hotel.rooms">
                        <div class="d-inline-block mb-2">
                            <small class="text-dark"> <span v-if="res.hotel.rooms.length <= 1">{{res.hotel.roomscount}} x </span> {{ room.name +' '+ room.attr.adults +'x' }}
                            <i class="fa fa-user gcolor" aria-hidden="true"></i> {{room.attr.children??''}} <i v-if="room.attr.children" class="fa fa-child gcolor" aria-hidden="true"></i>
                            <small v-if="room.attr.extrabeds"> {{room.attr.extrabeds}} extrabeds</small>
                            </small>
                        </div>
                        <br />
                    </div>
                </div>
                <div v-if="!res.hotel.rooms.length" class="mt-1 col-12 mt-2">
                    <div class="alert alert-warning">
                        <strong> <?= lang('noroomsdates') ?> </strong>
                    </div>
                </div>
                <div v-if="res.hotel.totals" class="price-sum col-12 col-md-6">
                    <h5 class="mb-0"> {{ res.hotel.totals }}  <span class="font-weight-normal">{{ res.hotel.currency }} </span></h5>
                    <small>+{{ res.hotel.taxes }} <sub><?= lang('taxesfees') ?></sub></small>
                    <h6>
                        {{ rslts.trkeys.total +' '+ (( res.nights_count > 1 )?res.nights_count+' '+rslts.trkeys.nights: rslts.params.nights +' '+ rslts.trkeys.night ) +', '+ rslts.params.adults +' '+rslts.trkeys.adults + ' '+(rslts.params.children > 0? rslts.params.children+' '+ rslts.trkeys.child:'')}}
                    </h6>
                </div>
            </div>

            <div v-if="res.hotel.totals" class="d-flex justify-content-between mt-4">
            <small class="rcolor mff font-weight-bold" style="line-height: 4em;"><i class="fa fa-trash rcolor" aria-hidden="true"></i> {{rslts.trkeys.freecancelation}}</small>   
            <a :href="dataParams.surl +'hotel/details/'+res.hotel.hslug +'/'+rslts.dt1+'/'+rslts.dt2+'/?rooms='+rslts.srooms+'&recrooms='+rslts.recrooms(res.hotel.rooms)"
                     class="btn btn-lg btn-book mx-1"><i class="fas fa-check-circle"></i> {{rslts.trkeys.book}}</a>
            </div>

        </div>
        <hr>
    </div>`,
});
var rslts = new Vue({
    el: "#sresult",
    props: ['baseset'],
    data: {

        sresults: [],
        csresults: [],
        params: [],
        resloading: true,
        searcherror: '',
        dt1: '',
        dt2: '',
        desttype: '',
        city: '',
        cityname: 164 ? 'makkah' : 'madinah',
        destid: '',
        destination: '',
        srooms: '',
        pricefilter: {
            pricerate: 1,
            minprice: 1,
            maxprice: 10000,
        },
        wifisub: false,
        stars: [],
        base_url: dataParams.surl,
        apikey: 'df311e248416b7d25ac3abeeff284735ec96e817ffe8',
        trkeys: {},
        infoWindow: {
            position: {
                lat: 50,
                lng: 90
            },
            open: false,
            template: ''
        },
        tminfoWindow: {
            position: {
                lat: 50,
                lng: 90
            },
            open: false,
            template: ''
        },
        tmhotel: null,
        tmclicked: false,
        clicked: false,
        thotel: {
            name: '',
            price: null,
            prv: null,
            hslug: null,
            photo: null,
            nightstotal: null,
            geo: {
                lat: 50,
                lng: 90
            }
        },
    },
    watch: {
        'pricefilter.pricerate': function() {
            this.resloading = true;
            setTimeout(this.filterByPrices, 200);
            if (!this.sresults.length) {
                this.sresults = this.csresults;
            }
        },
        stars: function() {
            this.resloading = true;
            setTimeout(this.filterByStars, 200);
            if (!this.sresults.length) {
                this.sresults = this.csresults;
            }
        },
        wifisub: function() {
            if (this.wifisub) {
                rslts.resloading = false;
                setTimeout(function() {
                    rslts.sresults = rslts.sresults.filter(function(el) {
                        return (el.roomsupp[0] == 'wifi');
                    });
                    rslts.resloading = false;
                }, 200);
                if (!rslts.sresults.length) {
                    this.Searchhotels();
                }
            } else {
                this.Searchhotels();
            }
        },
    },
    computed: {
        hotelInitialLatLng() {
            return ({
                lat: Number(this.thotel.geo.lat),
                lng: Number(this.thotel.geo.lng)
            })
        },
        cityInitialLatLng() {
            return ({
                lat: Number('21.3891'),
                lng: Number('39.8579')
            })
        },
    },
    methods: {
        shmap: function(id) {
            try {
                var geo = this.sresults[id].hotel.geo;
                this.thotel.name = this.sresults[id].hotel.Hotel_Name;
                this.thotel.hslug = this.sresults[id].hotel.hslug;
                this.thotel.prv = this.sresults[id].ProviderID;
                this.thotel.photo = this.sresults[id].hotel.Main_Photo;
                this.thotel.price = this.trkeys.from + ' <b style="color: rgb(29, 172, 8)">' + this
                    .sresults[id]
                    .hotel.totals + ' ' + this.sresults[id].hotel.currency + '</b>';
                this.thotel.nightstotal = this.trkeys.total + ' ' + ((this.sresults[id].nights_count > 1) ?
                    this
                    .sresults[id].nights_count + ' ' + this.trkeys.nights : this.sresults[id]
                    .nights_count +
                    ' ' + this.trkeys.night) + ' ' + this.trkeys.vatinc;
                this.thotel.geo = geo;
            } catch (err) {
                alert('catched ' + err);
            }
        },
        initprefs: async function() {
            var self = this;
            //need general urls
            var url = dataParams.burl + 'api/prefs?authkey=' + self.apikey;
            await fetch(url)
                .then(res => res.json())
                .then((data) => {
                    self.base_url = data.base_url;
                    self.pagereso();
                })
                .catch((err) => {
                    console.log("error star rating results err: " + err);
                });
        },
        pagereso: function() {
            var self = this;
            var url = dataParams.surl + 'hotels/pagereso';
            axios.get(url).then(function(res) {
                self.trkeys = res.data;
            }).catch(function(error) {
                console.log(error);
            });
        },
        initSearch: function() {
            try {
                const srchParams = new URLSearchParams(window.location.search);
                this.destination = srchParams.get('dest');
                this.dt1 = srchParams.get('dt1') ?? dataParams.fdf;
                this.dt2 = srchParams.get('dt2') ?? dataParams.fdt;
                this.desttype = srchParams.get('desttype') ?? 'locality';
                this.destid = srchParams.get('destid') ?? (this.destination == 'makkah' ? 164 : 174);
                this.city = srchParams.get('city') ?? (this.destination == 'makkah' ? 164 : 174);
                this.srooms = srchParams.get('rooms') ?? '1_adults';
                this.Searchhotels();
            } catch (err) {
                console.log(err);
                debugger;
            }
        },
        Searchhotels: async function() {
            var url = dataParams.surl + 'hotels/ajsearch?dest=' + this.destination + '&desttype=' + this
                .desttype + '&destid=' + this.destid + '&city=' + this.city + '&dt1=' + this.dt1 +
                '&dt2=' + this.dt2 + '&rooms=' + this.srooms;
            await fetch(url)
                .then(res => res.json())
                .then((data) => {
                    if (data.result) {
                        this.sresults = data.hotels;
                        console.log(this.sresults);
                        this.csresults = data.hotels;
                        this.params = data.params;
                        this.resloading = false;
                        this.priceLimits();
                    } else {
                        if (data.error) {
                            this.searcherror = data.error;
                        }
                        this.resloading = false;
                    }
                })
                .catch((err) => {
                    console.log("error star rating results err: " + err);
                });
        },
        priceLimits: function() {
            //filter orice rates
            var min = null;
            var max = null;
            var comset = [];
            if (this.csresults.length) {
                this.csresults.forEach((el, index) => {
                    comset.push(parseFloat(el.hotel.totals));
                    if (!min || !max) {
                        if (!el.hotel.totals) {
                            return;
                        };
                        min = parseFloat(el.hotel.totals);
                        max = parseFloat(el.hotel.totals);
                        return;
                    }
                    if (min && parseFloat(el.hotel.totals) < min) {
                        min = parseFloat(el.hotel.totals);
                    }
                    if (max && parseFloat(el.hotel.totals) > max) {
                        max = parseFloat(el.hotel.totals);
                    }
                });
                if (!min && min < 1) {
                    this.pricefilter.pricerate = 20;
                    this.pricefilter.minprice = 20;
                } else {
                    this.pricefilter.pricerate = min;
                    this.pricefilter.minprice = min;
                }

                if (!max && max < 1) {
                    this.pricefilter.maxprice = 1000;
                } else {
                    this.pricefilter.maxprice = max;
                }

            }
        },
        shorten: function(txt) {
            return txt.substring(0, 50) + ' ...';
        },
        openInfoWindowTemplate(pos) {
            this.infoWindow.position = pos;
            this.infoWindow.open = true;
            this.clicked = true;
        },
        tmopenInfoWindowTemplate(id) {

            this.tmhotel = this.sresults[id];

            this.tminfoWindow.open = true;
            this.tmclicked = true;
        },
        recrooms: function(rooms) {
            var rcrooms = '';
            for (room of rooms) {
                rcrooms += room.roomtype + '_';
            }
            return rcrooms.slice(0, -1);
        },
        filterByPrices: function() {
            var rate = this.pricefilter.pricerate;
            this.sresults = this.sresults.filter(function(el) {
                return parseInt(el.hotel.totals) >= parseInt(rate);
            });
            if (this.pricefilter.pricerate == this.pricefilter.minprice) {
                this.sresults = this.csresults;
            }
            this.resloading = false;
        },
        filterByStars: function() {
            var stars = this.stars;
            if (stars.length) {
                this.sresults = this.sresults.filter(function(el) {
                    return stars.includes(el.hotel.Star_Nums);
                });
            } else {
                this.sresults = this.csresults;
                this.filterByPrices();
            }
            this.resloading = false;
        },
    },

    created() {

        this.initprefs();
        this.initSearch();
        this.Searchhotels();



    }
});
</script>