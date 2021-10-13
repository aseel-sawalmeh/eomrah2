<?php
$df = date('Y-m-d', strtotime('now'));
$dt = date('Y-m-d', strtotime('now +2 days'));

?>
<div id="hdts">
    <div class="container-fluid m-0 p-0 mx-auto">
        <div id="hotelmap">
            <gmap-map :center="InitialLatLng" :zoom="18" ref="map" id="themap" map-type-id="roadmap">
                <gmap-marker :position="InitialLatLng" :clickable="true" @click="" ref="markers"></gmap-marker>
               
                <gmap-info-window :options="{maxWidth: 150}" :position="InitialLatLng">
                    <div id="infowindall">
                        <!-- hoteldetails -->
                        <img class="rounded img-fluid" src="<?= himg($hotel->Main_Photo) ?>" width="120" height="150" />
                        <!-- hoteldetails -->
                    </div>
                </gmap-info-window>
            </gmap-map>
        </div>
    </div>

    <div class="container home_container p-3">
        <!-- Slider -->
        <div class="row">
            <div v-if="fimages.length" class="col hotel-images position-relative">
                <img v-for="(image, key) in fimages" :key="key" class="img-fluid"
                    :src="'https://www.dotwconnect.com/'+image.image" @click="lightboxshow(image)"
                    onerror="this.src='<?= himg() ?>'">
                <div class="fill">
                    <img v-for="(image, key) in ufimages" :key="key" class="" style="height:100px;"
                        :src="'https://www.dotwconnect.com/'+image.image" @click="lightboxshow(image)"
                        onerror="this.src='<?= himg() ?>'">
                </div>

                <i class="far fa-arrow-alt-circle-left fa-lg slide-next" @click="snext"></i>
                <i class="far fa-arrow-alt-circle-right fa-lg slide-back" @click="sback"></i>
            </div>
            <div v-else class="col">
                <img class="card-img-top hotel_img_h" style="max-height:400px" src="<?php echo himg(); ?>">
            </div>
        </div>


        <!-- Images lighbox -->

        <!-- Modal -->
        <div class="modal fade lightimgbox" id="lightbox" tabindex="-1" role="dialog" aria-labelledby="lightboxImages"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered mt-2 mx-auto" role="document">
                <div class="modal-content p-md-2 text-center">
                    <span type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </span>
                    <div class="p-md-4 text-center">
                        <img v-if="lximage" class="lihgtbox-img img-fluid position-relative"
                            :src="'https://www.dotwconnect.com/'+lximage" />
                        <i class="far fa-arrow-alt-circle-left fa-lg lightbox-next" @click="lxnext"></i>
                        <i class="far fa-arrow-alt-circle-right fa-lg lightbox-back" @click="lxback"></i>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Images lighbox -->

        <!--/ Slider -->

        <div class=" row my-3 px-md-4">
            <div class="col">
                <h3 class="hotel-details-name my-2">{{ hdetails.Hotel_Name }}</h3>
                <!-- <h3 class="hotel-details-name my-2">trans($hotel->Hotel_Name, 'en', 'ar')</h3> -->
                <p class="hotel-details-address my-2"><i class="fas fa-map-marker-alt"></i> {{hdetails.Hotel_Address}}
                </p>
                <p v-if="hdetails.Hotel_Phone" class="hotel-details-phone my-2"><i
                        class="fa fa-phone-alt"></i>{{hdetails.Hotel_Phone}}</p>
                <p class="text-justify hotel-details-desc my-3" ref="hoteldesc">{{hdesc}} <a
                        class="mcolor font-weight-bold" @click="expanded = !expanded">
                        {{expanded?' <?= lang('hide') ?>':' ... <?= lang('more') ?>'}} </a></p>
            </div>
        </div>

        <!-- facilities area -->

        <?php if (true) : ?>
        <div class="row">
            <div class="col px-md-4">
                <form method='get' class="form-horizontal" action='<?php echo site_url('b2b/hotel/search'); ?>'>
                    <input id="searchtxt" class="form-control mb-2" autocomplete="off" type="hidden" name="dest"
                        placeholder="<?= lang('c/h') ?>" value="<?= $title ?>">



                    <input id="searchtxt" class="form-control mb-2" autocomplete="off" type="hidden" name="destid"
                        placeholder="<?= lang('c/h') ?>" value="<?=$hotel->Hotel_ID?>">
                    <input id="searchtxt" class="form-control mb-2" autocomplete="off" type="hidden" name="desttype"
                        placeholder="<?= lang('c/h') ?>" value="lodging">

                    <input id="searchtxt" class="form-control mb-2" autocomplete="off" type="hidden" name="city"
                        placeholder="<?= lang('c/h') ?>" value="<?=$hotel->Hotel_City_ID?>">

                    <div class="row mt-3 justify-content-between">
                        <div class="col-sm-6">
                            <input class="form-control mb-2" type="date" id='datefrom' name='dt1' autocomplete="off"
                                value="<?= $checkin ?? $df ?>">
                        </div>
                        <div class="col-sm-6">
                            <input class="form-control mb-2" type="date" id='datefrom' name='dt2' autocomplete="off"
                                value="<?= $checkout ?? $dt ?>">
                        </div>

                    </div>


                    <input class="form-control mb-2" type="hidden" name='rooms' autocomplete="off"
                        value="<?= $roomsSearch ?? '1_adults' ?>">



                    <div class="row  mt-3 justify-content-between">
                        <div class="col-sm-6">
                            <div class="input-group">
                                <div class="forminput">
                                    <span class="px-2"><?= lang('country') ?></span>
                                    <select class="form-control" name="country">
                                        <?php $visitCountry = vistorCountry();
                                    foreach ($countriesNationalities as $country) {
                                        $selected = '';
                                        if ($this->input->get('nationality')) {
                                            $selected = ($country->country_code == $this->input->get('country')) ? 'selected' : '';
                                        } else {
                                            $selected = preg_match("/$visitCountry/i", $country->country_name) == 1 ? 'selected' : '';
                                        };
                                        echo "<option value='$country->country_code' $selected>$country->country_name</option>";
                                    } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-group">
                                <div class="forminput">
                                    <span class="px-2"><?= lang('nationality') ?></span>
                                    <select class="form-control" id="nationality" name="nationality">
                                        <?php
                                    foreach ($countriesNationalities as $country) {
                                        $selected = '';
                                        if ($this->input->get('nationality')) {
                                            $selected = ($country->country_code == $this->input->get('nationality')) ? 'selected' : '';
                                        } else {
                                            $selected = preg_match("/$visitCountry/i", $country->country_name) == 1 ? 'selected' : '';
                                        };
                                        echo "<option value='$country->country_code' $selected>$country->country_name</option>";
                                    } ?>
                                    </select>
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="row mt-2">
                        <div class="col">
                            <input type="submit" value="<?= lang('ms') ?>" class="btn btn-block py-2 pbtn">

                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php
            if ($datatable) : ?>
        <div class="row px-4">
            <div :class="'col-md-'+(items?'9':'12')+' pt-2'">
                <?php echo $datatable; ?>
            </div>
            <div v-if="ready && items" ref="sumview" class="col-md-3 px-md-4 p-0">
                <div class="hotel-sel-rooms p-3">

                    <strong class="font-weight-bold"><?= lang('ressum') ?></strong>
                    <!-- wallet -->
                    <div class="wallet row mt-4" v-if="items">
                        <div class="col">
                            <h4><strong>{{totals.titems}}</strong>
                                {{ (totals.titems > 1)?'<?= lang('rooms') ?>':'<?= lang('room') ?>' }}</h4>
                            <h3 class=" mcolor font-weight-bold">{{totals.tprice}} <span
                                    class="text-dark"><?= usercur() ?></span></h3>
                            <b v-if="totals.tprice_dis"> after discount {{totals.tprice_dis}}</b>
                            <p>+ <strong class="font-weight-bold"> {{totals.ttax}}</strong>
                                <small><?= lang('taxesfees') ?></small>
                            </p>
                            <ul class="list-unstyled">
                                <li v-for="(room, ind) in wallet" :key="ind">
                                    {{room.rc+' x '+room.rname}}
                                </li>
                            </ul>
                        </div>
                        <!--<span>total:{{totals.tprice + totals.ttax}}</span>-->
                    </div>
                    <!-- /wallet -->
                    <!-- Discount -->
                    <div class="row" v-if="discavailable && items">
                        <div class="col-10 mx-auto">
                            <div class="text-center" v-html="discountStatus">
                            </div>
                            <input id="dschk" type="hidden" value="1" />
                            <div id="discount">
                                <div class="text-center">
                                    <label for="discode" for="discode"><b>Discount Code</b></label>
                                </div>
                                <div class="form-group row">
                                    <input class="form-control bg-white" id="discode" type="text" name="discode"
                                        v-model="discode" placeholder="Enter The Code And Press Check" />
                                </div>
                                <div class="form-group row justify-content-center">
                                    <div class="text-center">
                                        <button id="codecheck" class="btn-sm disbtn" type="button"
                                            @click="chkdisval">check</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Discount -->


                    <button v-if="items" class="btn ebtn btn-block  mt-5" type="button"
                        @click="book"><?= lang("book") ?></button>




                </div>
            </div>
        </div>
        <?php
            else : ?>

        <div class="row px-4 mb-5 mt-3">
            <div class="col pt-2" style="background-color:rgba(240, 113, 0, 0.15) !important">
                <p>
                    <i class="fas fa-clock fa-sm mcolor"></i>
                    <strong class="font-weight-bold"> <?= lang('norooms') ?></strong>
                    <br>
                    <small class="font-weight-bold px-3"> <?= lang('noroomsdates') ?></small>
                </p>
            </div>
        </div>
        <?php endif;
        else : ?>
        <div class="modify-search">

            <form method='get' class="form-horizontal" action='<?php echo site_url('hotels/search'); ?>'>
                <input id="searchtxt" class="form-control mb-2" autocomplete="off" type="hidden" name="dest"
                    placeholder="<?= lang('c/h') ?>" value="<?= $title ?? '' ?>">
                <div class="row mt-3">

                    <div class="col-md-2">
                        <div class="form-group">
                            <label><?= lang('df') ?></label>
                            <input class="form-control mb-2" type="date" id='datefrom' name='dt1'
                                placeholder="<?= lang('df') ?>" autocomplete="off" value="<?= $df ?? '' ?>">

                        </div>
                    </div>

                    <div class="col-md-2 col-12">
                        <div class="form-group">
                            <label><?= lang('dt') ?></label>
                            <input class="form-control mb-2" type="date" id='datefrom' name='dt2'
                                placeholder="<?= lang('dt') ?>" autocomplete="off" value="<?= $dt ?? '' ?>">
                        </div>

                    </div>

                    <div class="col-md-2 col-12">
                        <div class="form-group">
                            <label><?= lang('adults') ?></label>
                            <select name="rooms" class="form-control">
                                <option value="1_adults">1</option>
                                <option value="2_adults">2</option>
                                <option value="3_adults">3</option>
                                <option value="4_adults">4</option>
                                <option value="5_adults">5</option>
                                <option value="6_adults">6</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2 col-12">
                        <div class="form-group">
                            <label><?= lang('child') ?></label>
                            <select class="form-control" class="form-control">
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                                <option>5</option>
                                <option>6</option>
                            </select>
                        </div>

                    </div>
                    <div class="col-md-4 col-12 p-4 mt-2">
                        <input type="submit" value="<?= lang('ms') ?>" class="btn btn-lg sbtn">
                    </div>

                </div>
            </form>
        </div>
        <?php endif; ?>


        <?php if ($hotel_amenities) : ?>
        <div class="row mt-2">
            <div class="col px-md-5">
                <h3 class="fac-title"><?= lang('amenities') ?></h3>
                <ul class="list-inline fac_list">
                    <?php foreach ($hotel_amenities as $amenity) : ?>
                    <li class="list-inline-item fac_item">
                        <?= $amenity->icon ?>
                        <?= $amenity->name ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($hotel_leisures) : ?>
        <div class="row mt-2">
            <div class="col px-md-5">
                <h3 class="fac-title">Hotel lesuires</h3>
                <ul class="list-inline">
                    <?php foreach ($hotel_leisures as $leisure) : ?>
                    <li class="list-inline-item fac_item">
                        <?= $amenity->name ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($transportaion) : ?>
        <div class="row mt-2">
            <div class="col px-md-5">
                <h3 class="fac-title"><?= lang('transportation') ?></h3>
                <ul class="list-inline">
                    <?php foreach ($transportaion as $tr) : ?>
                    <li class="list-inline-item fac_item">
                        <?= lang($tr->trType) ?>:
                        <?= $tr->name ?>
                        Time:
                        <?= $tr->distanceTime ?>
                        Distance:
                        <?= $tr->distance ?>
                        Directions:
                        <?= $tr->directions ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <?php endif; ?>
        <div v-if="false" class="row px-md-4 d-sm-hidden">
            <div class="col justify-content-between hotel-fac-cats">
                <h3>Facilitites in {{ hdetails.Hotel_Name }}</h3>
                <ul class="list-inline mt-5">
                    <li class="list-inline-item">
                        <ul class="list-unstyled">
                            <h6 class="text-center"><i class="fab fa-accessible-icon" dir="rtl"></i> accessibility</h6>
                            <li>1 item</li>
                            <li>2 item</li>
                            <li>3 item</li>
                        </ul>
                    </li>
                    <li class="list-inline-item">
                        <h6 class="text-center"><i class="fab fa-accessible-icon" dir="rtl"></i> accessibility</h6>
                        <ul class="list-unstyled">
                            <li>1 item</li>
                            <li>2 item</li>
                            <li>3 item</li>
                        </ul>
                    </li>
                    <li class="list-inline-item">
                        <h6 class="text-center"><i class="fab fa-accessible-icon" dir="rtl"></i> accessibility</h6>
                        <ul class="list-unstyled">
                            <li>1 item</li>
                            <li>2 item</li>
                            <li>3 item</li>
                        </ul>
                    </li>
                    <li class="list-inline-item">
                        <h6 class="text-center"><i class="fab fa-accessible-icon" dir="rtl"></i> accessibility</h6>
                        <ul class="list-unstyled">
                            <li>1 item</li>
                            <li>2 item</li>
                            <li>3 item</li>
                        </ul>
                    </li>
                    <li class="list-inline-item">
                        <h6>head5</h6>
                        <ul class="list-unstyled">
                            <li>1 item</li>
                            <li>2 item</li>
                            <li>3 item</li>
                        </ul>
                    </li>
                    <li class="list-inline-item">
                        <h6>head5</h6>
                        <ul class="list-unstyled">
                            <li>1 item</li>
                            <li>2 item</li>
                            <li>3 item</li>
                        </ul>
                    </li>
                    <li class="list-inline-item">
                        <h6>head6</h6>
                        <ul class="list-unstyled">
                            <li>1 item</li>
                            <li>2 item</li>
                            <li>3 item</li>
                        </ul>
                    </li>
                    <li class="list-inline-item">
                        <h6>head7</h6>
                        <ul class="list-unstyled">
                            <li>1 item</li>
                            <li>2 item</li>
                            <li>3 item</li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>


    <!-- fixed rooms head -->
    <div v-if="!sumappear && ready && items" class="row p-1 w-100 mx-auto position-fixed d-md-none d-block flysum">
        <div class="col d-flex justify-content-between align-items-center">
            <!-- wallet -->
            <div class="wallet" v-if="items">
                <small><strong>{{totals.titems}}</strong> {{ (totals.titems > 1)?'rooms':'room' }}
                    <strong>{{totals.tprice}}</strong> <?= usercur() ?></small>
                <p>+ <strong class="font-weight-bold"> {{totals.ttax}}</strong> <small><?= lang('taxesfees') ?></small>
                </p>
                <!--<span>total:{{totals.tprice + totals.ttax}}</span>-->
            </div>
            <!-- /wallet -->
            <a class="btn ebtn btn-md" @click="viewcheck"><?= lang('ressum') ?></a>
        </div>
    </div>
    <!-- / fixed rooms head -->

</div>
<?php if ($imgs) {
    echo '<script>var hotel_images = ' . json_encode($imgs) . ';</script>';
} else {
    echo '<script>var hotel_images = ' . json_encode([]) . ';</script>';
} ?>
<!-- eof text after -->
<script>
//review header remove duplicates
var checkin = '<?= $checkin ?? '' ?>';
var checkout = '<?= $checkout ?? '' ?>';
var nights = '<?= $nights ?? 0 ?>';
var adults = <?= $adults ?? 1 ?>;
var childrens = <?= $childs ?? 0 ?>;
var hid = <?= $hotel->Hotel_ID ?>;
var lhid = <?= $hotel->hotelLotsId ?>;
var hslug = '<?= $hotel->hslug ?>';
var prvid = <?= $prvid ?? 'null' ?>;
var sel = <?= $sel ?? 'null' ?>;
var gpi = '<?= API_GOOGLE ?>';
var srl = '<?= site_url() ?>';
// var csname = '<?= $this->security->get_csrf_token_name() ?>';
// var cshv = '<?= $this->security->get_csrf_hash() ?>';
</script>
<script src="<?php echo base_url('public_designs/assets') ?>/js/vplugs/vue-google-maps.js"></script>
<script>
Vue.use(VueGoogleMaps, {
    load: {
        key: '<?= API_GOOGLE ?>'
    }
});

Vue.config.devtools = true;
var hdt = new Vue({
    el: "#hdts",
    data: {
        hdetails: <?= json_encode($hotel) ?>,
        geo: {
            lat: 23.8859,
            lng: 45.0792
        },
        wallet: {},
        items: false,
        totals: {},
        prvid: <?= $prvid ?? 'null' ?>,
        selected: {},
        cal: 0,
        discavailable: false,
        discountStatus: '',
        discode: '',
        discdata: {},
        dis_val: 0,
        fimages: hotel_images.slice(0, 3) ?? hotel_images,
        ufimages: hotel_images.slice(3, -1) ?? hotel_images,
        rimages: hotel_images,
        lximage: '',
        lximageindex: 0,
        ready: false,
        sumappear: false,
        expanded: false,
        blockerror: '',
    },
    methods: {
        price_calc: async function(v) {
            var el = v.currentTarget;
            var roomData = JSON.parse(JSON.stringify(v.currentTarget.dataset));
            var price = Number(roomData.price);
            var texesfees = Number(roomData.texesfees);
            var roomcode = roomData.rid;
            var roomslected = 'r' + roomcode;
            // validating values sent
            if (roomcode == null || !roomcode || roomcode == 'undefined' || !roomcode.length) {
                console.log('invalid room');
                console.log(roomcode);
                return;
            }
            //TODO: clean this code block 
            //check if the room blocked if not return
            var roomParam = new URLSearchParams();
            roomParam.append('csrftkn', dataParams.csrftkn);
            roomParam.append('room', roomcode);
            roomParam.append('checkin', checkin);
            roomParam.append('checkout', checkout);
            roomParam.append('lhid', lhid);
            var roomBlocked = false;
            await fetch(dataParams.surl + '/b2b/hotel/blockroom', {
                method: 'POST',
                body: roomParam,
            }).then((resp) => resp.json()).then((res) => {
                console.log(res);
                if (res.status) {
                    roomBlocked = true;
                    // var elp = document.createElement('p');
                    // var roomBooked = document.createTextNode("ready to book");
                    // elp.appendChild(roomBooked);
                    // v.target.parentElement.appendChild(elp);
                } else {
                    var elp = document.createElement('p');
                    var roomNotAvailable = document.createTextNode(dataParams.trkeys
                        .roomnotavailable);
                    elp.appendChild(roomNotAvailable);
                    el.parentNode.appendChild(elp);
                    el.disabled = true;
                    this.blockerror = res.error;
                }
            }).catch((error) => {
                console.log(error);
            });
            // if (!roomBlocked) return;
            //room blocked

            if (!Object.keys(roomData).length) {
                return;
            }
            this.selected[roomslected] = !this.selected[roomslected];
            if (!this.selected[roomslected]) {
                this.$delete(this.wallet, roomcode);
                this.cal += 1;
            } else {
                var item = {
                    'rid': roomData.rid,
                    'rname': roomData.rname,
                    'rtype': roomData.rtype,
                    'ratebase': roomData.ratebase,
                    'rc': 1,
                    'price': price,
                    'texesfees': texesfees,
                    'allocationDetials': roomData.allodetails,
                    'canceldeadline': roomData.cancelline,
                    'cancellationDetails': roomData.cancelref,
                };
                this.wallet[roomcode] = item;
                this.items = true;
                this.cal += 1;
            }
        },
        get_items: function() {
            axios.get('<?= site_url('hotel/htest') ?>').then((res) => {
                if (res.data.result) {
                    this.titems = res.data.wallet.total_items;
                    this.tprice = res.data.wallet.total_price;
                    this.ttax = res.data.wallet.total_tax;
                }
            }).catch((error) => {
                console.log(error);
            })
        },
        book: function() {
            var rooms = '';
            // for (r in this.wallet) {
            //     rooms += this.wallet[r].rname + '-' + this.wallet[r].rtype + '-' + this.wallet[r].ratebase + '-' + this.wallet[r].rc + '-' + this.wallet[r].price + '-' + this.wallet[r].allocationDetials + '-' + this.wallet[r].canceldeadline + '-' + this.wallet[r].cancellationDetails + '*';
            // }
            for (r in this.wallet) {
                rooms += this.wallet[r].rid + '*';
            }
            var prms = new URLSearchParams();
            prms.append('rooms', rooms.substring(0, (rooms.length - 1)));
            prms.append('hid', hid);
            prms.append('lhid', lhid);
            prms.append('nonights', nights);
            prms.append('adults', adults);
            prms.append('childrens', childrens);
            prms.append('checkin', checkin);
            prms.append('checkout', checkout);
            prms.append('csrftkn', dataParams.csrftkn);
            axios.post('<?= site_url('b2b/hotel/book') ?>', prms).then((res) => {
                if (res.data.status) {
                    window.location.href = '<?= site_url('b2b/reservation ') ?>';
                }
            }).catch((error) => {
                console.log(error);
            })
        },
        hmap: function() {
            axios.get("/hotel/map/" + this.hdetails).then((res) => {
                console.log(res.data);
                if (res.data.status) {
                    this.geo = res.data.data;
                }
            }).catch((error) => {
                console.log(error);
            });
        },
        buttonToggle: function($val) {
            if (!$val) {
                return "<i class='fas fa-shopping-basket'></i> " + dataParams.trkeys.book;
            } else {
                return "<i class='fa fa-trash-alt'></i> " + dataParams.trkeys.delete;
            }
        },
        snext: function() {
            this.ufimages.push(this.fimages[this.fimages.length - 1]);
            this.fimages.unshift(this.ufimages[0]);
            this.fimages.splice(-1, 1);
            this.ufimages.splice(0, 1);
        },
        sback: function() {
            this.fimages.push(this.ufimages[this.ufimages.length - 1]);
            this.ufimages.unshift(this.fimages[0]);
            this.fimages.splice(0, 1);
            this.ufimages.splice(-1, 1);
        },
        lxnext: function() {
            if (this.lximageindex < this.rimages.length - 1) {
                this.lximageindex++;
                this.lximage = this.rimages[this.lximageindex].image;
            } else {
                this.lximageindex = 0;
                this.lximage = this.rimages[this.lximageindex].image;
            }
        },
        lxback: function() {
            if (this.lximageindex > 0) {
                this.lximageindex--;
                this.lximage = this.rimages[this.lximageindex].image;
            } else {
                this.lximageindex = this.rimages.length - 1;
                this.lximage = this.rimages[this.lximageindex].image;
            }
        },
        lightboxshow: function(image) {
            this.lximage = image.image;
            this.lximageindex = 0;
            $('#lightbox').modal({
                backdrop: false,
            });
            $('#lightbox').modal('show');
        },
        textDecorate: function() {
            var txt = this.$refs.hoteldesc.innerHtml;
            var patt = `/${this.hdetails.Hotel_Name}/gi`
            var patt2 = `/ ${this.hdetails.hslug.replace(/-/g,' ')} /gi`
            this.hdetails.Hotel_Description = this.hdetails.Hotel_Description.replace(patt, ('<b>' + this
                .hdetails.Hotel_Name + '</b>'));
        },
        checkready: function() {
            function beReady() {
                hdt.ready = true;
            };
            setTimeout(function() {
                if (document.readyState != 'loading') {
                    beReady();
                } else if (document.addEventListener) {
                    document.addEventListener('DOMContentLoaded', beReady());
                } else document.attachEvent('onreadystatechange', function() {
                    if (document.readyState == 'complete') beReady();
                });
            }, 2000);
        },
        viewcheck: function() {
            // this.$refs.sumview.scrollIntoView();
            consol.log(this.$refs.sumview.scrollIntoView());
        },
        vewappear: function() {
            try {
                const rect = this.$refs.sumview?.getBoundingClientRect();
                if (rect == 'undefined' || rect == null) return;
                this.sumappear = (
                    rect.top >= 0 &&
                    rect.left >= 0 &&
                    rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                    rect.right <= (window.innerWidth || document.documentElement.clientWidth)
                );
            } catch (err) {
                console.log('errror detecting view' + err);
            }
        },
        ttip: function(e) {
            $(e.target).tooltip('toggle');
        },
        centerttip: function(e) {
            // console.log(e);
            // var elWidth = v.target.clientWidth;
            // return elWidth+'px';
        }
    },
    watch: {
        cal() {
            var titems = 0;
            var tprice = 0;
            var ttax = 0;
            for (it in this.wallet) {
                titems += this.wallet[it].rc;
                tprice += this.wallet[it].price;
                ttax += this.wallet[it].texesfees;
            }
            if (this.discdata.codestatus) {
                var disv = tprice * (parseFloat(this.discdata.discount_val) / 100);
                var dis = tprice - disv;
                var tdis = dis - (dis * 0.05);
                var txdis = (dis * 0.05);
                this.totals = {
                    'titems': titems,
                    'tprice_dis': tdis.toFixed(2),
                    'tprice': tprice.toFixed(2),
                    'ttax': txdis.toFixed(2),
                };
            } else {
                this.totals = {
                    'titems': titems,
                    'tprice': tprice.toFixed(2),
                    'ttax': ttax.toFixed(2),
                };
            }

            if (titems == 0) {
                this.items = false;
            }
        }
    },
    computed: {
        mx: function() {
            return (8 - this.totals.titems);
        },
        InitialLatLng: function() {
            return ({
                lat: Number(this.hdetails.lat),
                lng: Number(this.hdetails.lng)
            })
        },
        InitialHaramLatLng: function() {
            return ({
                lat: Number(21.4229),
                lng: Number(39.8257)
            })
        },
        hdesc: function() {
            var length = 300;
            var text = this.hdetails.Hotel_Description;
            if (this.expanded) {
                return text;
            } else {
                return text.length > length ? text.slice(0, length) : text;
            }
        }
    },
    filters: {
        truncate: function(text, length) {
            if (this.expanded) {
                return text + '<a href="#" @click="this.expanded = !this.expanded">Less</a>';
            } else {
                return text.length > length ? text.slice(0, length) : text;
            }
        },
    },
    created() {
        window.onscroll = () => this.vewappear();

        (function() {
            if (typeof window.orientation !== 'undefined') {
                try {
                    var elem = document.documentElement;
                    if (elem.requestFullscreen) {
                        elem.requestFullscreen();
                    } else if (elem.webkitRequestFullscreen) {
                        /* Safari */
                        elem.webkitRequestFullscreen();
                    } else if (elem.msRequestFullscreen) {
                        /* IE11 */
                        elem.msRequestFullscreen();
                    }
                } catch (error) {
                    console.log('fullscreen error' + error);
                }
            }
        })();
    },
    mounted() {
        setInterval(this.snext, 5000);
        this.textDecorate();
        this.checkready();
    },
});
</script>