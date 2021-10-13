    <style>
.google-map {
    height: 350px;
}

.pac-container {
    text-align: right;
    direction: rtl;
}

.pac-item {
    float: right;
    direction: rtl;
    margin-top: 10px;
}
    </style>
    <div id="uinit">
        <div class="container">
           
            <div class="row">
                <div class="col">
                    <div class="card card-primary p-3 mt-2">
                        <div class="card-header">
                            <h3 class="text-center">Add Your Property</h3>
                        </div>
                        <div class="card-body">
                            <?php echo form_open('chotel/huserinit');?>
                                <input type="hidden" name="hoteluser"
                                    value="<?=$this->session->userdata('H_User_ID')?>" />

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="alert alert-danger" v-if="!mapselect">Use The Map TO Select Your Property</div>
                                        <input id="place-searchbox" type="text" class="form-control" v-model="arhotelname" />
                                        <div class="form-group">
                                            <!-- Map -->
                                            <div id="map" class="google-map"></div>
                                            <!-- Map -->
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <?=form_error('arhotelname')?>
                                            <div class="alert alert-danger" v-if="notarabic">Is not arabic</div>
                                            <label>Hotel Name ( Arabic )</label>
                                            <input type="text" class="form-control" name="arhotelname" v-model="arhotelname" value="<?=set_value('arhotelname')?>" />
                                        </div>
                                        <div class="form-group">
                                            <?=form_error('enhotelname')?>
                                            <label>Hotel Name ( English )</label>
                                            <input type="text" class="form-control" name="enhotelname" v-model="enhotelname" value="<?=set_value('enhotelname')?>" />
                                        </div>

                                        <div class="form-group">
                                            <label>Country</label>
                                            <select id="countries" name="hotelcountry" class="form-control">
                                                <option value="682">Saudi Arabia</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>City</label>
                                            <select id="cities" name="hotelcity" onchange="get_regions(this.value)"
                                                class="form-control">
                                                <option value="3793">Makkah</option>
                                                <option value="3794">AlMadinah</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <?=form_error('hoteladdress')?>
                                            <label>Hotel Full Address</label>
                                            <textarea class="form-control" name="hoteladdress" v-model="fulladdress">{{ fulladdress }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                             <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Stars Numbers</label>
                                            <select class="form-control" name="hotel_stars">
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <?=form_error('hotelemail')?>
                                            <label>Email</label>
                                            <input type="text" name="hotelemail" class="form-control" value="<?=set_value('hotelemail')?>" />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <?=form_error('hotelphone')?>
                                            <label>Phone Number</label>
                                            <input type="text" name="hotelphone" class="form-control" value="<?=set_value('hotelphone')?>" />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Fax Number</label> <span>(Optional)</span>
                                            <input type="text" name="hotelfax" class="form-control" value="<?=set_value('hotelfax')?>" />

                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <?=form_error('hoteldescription')?>
                                    <label>Hotel Description</label>
                                    <textarea class="form-control" name="hoteldescription"><?=set_value('hoteldescription')?></textarea>
                                </div>
                                <input type="hidden" name="hotelplace_id" :value="hotelplace_id" value="<?=set_value('hotelplace_id')?>" />
                                <input type="hidden" name="hotellat" :value="hotellat" value="<?=set_value('hotellat')?>" />
                                <input type="hidden" name="hotellng" :value="hotellng" value="<?=set_value('hotellng')?>" />
                                <div class="row">
                                    <div class="col">
                                        <div class="text-center">
                                            <input type="submit" class="btn btn-block btn-success" value="Add Property">
                                        </div>
                                    </div>
                                </div>
                            <?php echo form_close();?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
function initAutocomplete() {
    var map = new google.maps.Map(document.getElementById('map'), {
        center: {
            lat: 21.3891,
            lng: 39.8579
        },
        zoom: 6,
        disableDefaultUI: true
    });
    var options = {
        componentRestrictions: {
            country: "sa"
        }
    };
    var input = document.getElementById('place-searchbox');
    var autocomplete = new google.maps.places.Autocomplete(input, options);
    map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);
    var marker = new google.maps.Marker({
        map: map
    });

    autocomplete.bindTo('bounds', map);
    autocomplete.addListener('place_changed', function() {
        var place = autocomplete.getPlace();
        if (!place.geometry) {
            console.log("Returned place contains no geometry");
            return;
        } else {
            console.log(place);
            console.log(place.geometry.location.lat());
            console.log(place.geometry.location.lng());
            uinit.fulladdress = place.formatted_address;
            uinit.hotelplace_id = place.place_id;
            uinit.arhotelname = place.name;
            uinit.hotellat = place.geometry.location.lat();
            uinit.hotellng = place.geometry.location.lng();
            uinit.mapselect = true;
        }
        var bounds = new google.maps.LatLngBounds();
        marker.setPosition(place.geometry.location);

        if (place.geometry.viewport) {
            // Only geocodes have viewport.
            bounds.union(place.geometry.viewport);
        } else {
            bounds.extend(place.geometry.location);
        }
        map.fitBounds(bounds);
    });
}

document.addEventListener("DOMContentLoaded", function(event) {
    initAutocomplete();
});
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=<?=API_GOOGLE?>&libraries=places,geometery"></script>

    <script>

Vue.config.devtools = true;

var jurl = "https://maps.googleapis.com/maps/api/place/textsearch/json?query=حياة&key=";
/*var gpurl =
    "https: //maps.googleapis.com/maps/api/place/details/json?place_id=ChIJN1t_tDeuEmsRUsoyG83frY4&fields=name,rating,formatted_phone_number&key=<?=API_GOOGLE?>"; */
var uinit = new Vue({
    el: "#uinit",
    data: {
        countries: null,
        governorate: null,
        cities: null,
        fulladdress: '',
        hotelplace_id: '',
        notarabic: false,
        arhotelname: '',
        enhotelname: '',
        mapselect: false,
        hotellat: '',
        hotellng: '',
        google_api: '<?=API_GOOGLE?>',
    },
    watch: {
        arhotelname: function() {
            if(this.arhotelname.length > 0 ){
                this.checkarabic();
            }else{
                this.notarabic = false;
            }
        }
    },
    methods: {
        getcountries: function() {
            axios.get('/chotel/huserinit/countries').then(function(res) {
                //console.log(res.data);
                this.countries = res.data;
            }).catch(function(error) {
                console.log(error);
            });
        },
        getplacedetails: function() {
            axios.get(
                `https://maps.googleapis.com/maps/api/place/details/json?place_id=${this.place_id}&key=${this.google_api}`
            ).then(function(res) {
                //console.log(res.data);
                this.countries = res.data;
            }).catch(function(error) {
                console.log(error);
            });
        },
        checkarabic: function() {
            axios.get(`/chotel/huserinit/toarabic?name=`+this.arhotelname).then(function(res) {
                //console.log(res.data);
                if(res.data.isarabic){
                    uinit.notarabic = false;
                    uinit.toenglish();
                }else{
                    uinit.notarabic = true;
                }
            }).catch(function(error) {
                console.log(error);
            });
        },
        toenglish: function() {
            axios.get(`/chotel/huserinit/toenglish?name=`+this.arhotelname).then(function(res) {
                //console.log(res.data);
                uinit.enhotelname = res.data.english_name;
            }).catch(function(error) {
                console.log(error);
            });
        },

    },
    mounted() {
        this.getcountries();
    },
});
    </script>