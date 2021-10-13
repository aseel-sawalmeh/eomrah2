<?php
//  $this->load->model('geo_model', 'geo');
$countriesNationalities = $this->geo->get_countries();
?>
<link rel="stylesheet" type="text/css" href="<?= base_url('public_designs/assets') ?>/css/daterangepicker.css" />
<div id="srchs" class="container-fluid search_area" :style="BackgroundComponent">


    <!-- <div id="HotelForm">
            <span class="formhead px-4">
                <?= lang('findhotel') ?>
            </span> -->
    <div class="searchformWrapper row px-md-4">
        <fieldset class="col-md-8 px-md-5 col-sm mx-auto">
            <legend> <?= lang('findhotel') ?>
            </legend>
            <form class="searchform pt-md-4 pt-2 pb-md-5 mb-md-5" method='get'
                action='<?php echo site_url('hotels/search'); ?>'>

                <div class="form-row">
                    <div class="col-md-4 col-sm mb-2">
                        <div id="destdiv" class="autocomplete forminput" ref="acm">
                            <span class="px-2"><?= lang('find') ?></span>
                            <input id="dest" class="form-control px-3 pt-0" type="text" name="dest"
                                placeholder="<?= lang('c/h') ?>" @focus="search" @input="search" @click="acmdev"
                                value="<?= $this->input->get('dest') ?? 'makkah' ?>" required autocomplete="off"
                                v-model="acmvalues.dest" />
                            <i class="fa fa-city ico"></i>
                        </div>
                    </div>
                    <!-- search full -->
                    <div v-if="mobAcm" id="myOverlay" class="searchoverlay"
                        :style="(mobAcm && !acmshow?'display:block;':'')">
                        <div class="searchoverlay-content row">
                            <span class="closebtn" @click="acmvalues.dest=''" title="Reset">×</span>
                            <input id="mobdest" class="form-control" type="text" name="dest"
                                placeholder="<?= lang('c/h') ?>" @focus="search" @input="search"
                                value="<?= $this->input->get('dest') ?? 'makkah' ?>" required autocomplete="off"
                                v-model="acmvalues.dest" />
                            <i class="fas fa-arrow-<?= (userlang() == 'ar') ? 'left' : 'right' ?> backbtn"
                                @click="mobAcm = !mobAcm"></i>
                            <!-- acmisolated -->
                            <div v-if="acmvalues.dest.length" class=" autocomplete-items"
                                :style="acmstyle+'display:block;'">
                                <div v-if="srchdata.locations?.length" class="acmlocations">
                                    <div class="resitem bgmcolor">
                                        <strong class="font-weight-bold text-white"> <?= lang('places') ?></strong>
                                    </div>
                                    <div class="resitem" v-for="(item, ind) in srchdata.locations" :key="ind"
                                        @click.stop="acmselect" :data-dest="item.title" :data-tid="item.tid"
                                        :data-type="item.type" :data-city="item.city">
                                        <strong><i class="fas fa-map-marker-alt mcolor m-1"></i>
                                            {{item.titledd}}</strong>
                                        <small>{{item.label}}</small>
                                    </div>
                                </div>
                                <div v-if="srchdata.hotels?.length" class="acmhotels">
                                    <div class="resitem bgmcolor">
                                        <strong class="font-weight-bold text-white"> <?= lang('hotels') ?> </strong>
                                    </div>
                                    <div class="resitem" v-for="(item, ind) in srchdata.hotels" :key="ind"
                                        @click.stop="acmselect" :data-dest="item.title" :data-tid="item.tid"
                                        :data-type="item.type" :data-city="item.city">
                                        <strong><i class="fas fa-bed mcolor m-1"></i> {{item.titledd}}</strong>
                                        <small>{{item.label}}</small>
                                    </div>
                                </div>
                                <div v-if="!srchdata.locations?.length && srchdata[0]?.city == 0" class="acmhotels">
                                    <div class="resitem bgmcolor">
                                        <strong class="font-weight-bold text-white"> <?= lang('hotels') ?> </strong>
                                    </div>
                                    <div class="resitem">
                                        <strong><i class="fas fa-bed mcolor m-1"></i> {{srchdata[0].titledd}}</strong>
                                    </div>
                                </div>
                            </div>
                            <!-- acmisolated -->
                        </div>
                    </div>
                    <!-- search full -->
                    <div class="col-md-4 col-sm mb-2">
                        <div class="forminput">
                            <span class="px-2"><?= lang('checkin') . ' / ' . lang('checkout') ?></span>
                            <input id="drange" class="form-control px-3 pt-0" type="text"
                                :value="'<?= lang('checkin') ?>: '+datefrom +' <?= lang('checkout') ?>: '+ dateto"
                                readonly>
                            <i class="fa fa-calendar ico"></i>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm mb-2" @click="shwroom = true">
                        <div id="roomseditinput" class="forminput" ref="roomsedit">
                            <span class="px-2"><?= lang('rooms') ?></span>
                            <input class="form-control px-3 pt-0"
                                :value=" roomcount + ((roomcount > 1)? ' <?= lang('rooms') ?> | ':' <?= lang('room') ?> | ')+ adultscount +' <?= lang('adults') ?> | ' +childcount + ' <?= lang('childs') ?>'"
                                autocapitalize="off" readonly />
                            <i class="fa fa-user ico"></i>
                        </div>
                    </div>
                    <input type="hidden" class="form-control" name="destid" v-model="acmvalues.tid" />
                    <input type="hidden" class="form-control" name="desttype" v-model="acmvalues.type" />
                    <input type="hidden" class="form-control" name="city" v-model="acmvalues.city" />
                    <input type="hidden" name='dt1' placeholder="<?= lang('df') ?>" :value="datefrom" />
                    <input type="hidden" name='dt2' placeholder="<?= lang('dt') ?>" :value="dateto" />
                    <input type="hidden" name='rooms' :value="roomval" />

                </div>
                <!-- geust geo -->
                <div class="form-row">
                    <div class="col-md-4 col-sm mb-2">
                        <div class="input-group">
                            <div class="forminput">
                                <span class="px-2"><?= lang('countryOfRes') ?></span>
                                <select class="form-control" id="gcountries" name="country">
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
                    <div class="col-md-4 col-sm mb-2">
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
                    <div class=" col mb-2 col-sm">
                        <button :disabled="disable_search" type="submit" class="sbtn btn btn-block h-100 text-white"><i
                                class="fa fa-search fafill" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
                <!-- /geust geo -->
            </form>
        </fieldset>
    </div>
    <!-- </div> -->

    <!-- rooms edits -->
    <div id="roomeditmenu" v-if="shwroom" class="col-12 p-2 roomsmenu" :style="roomseditstyle">
        <span class="arrow"></span>
        <div class="container text-center">
            <div class="row" v-for="(rm, index) in rooms">
                <span class="mx-1 text-small"><i class="fa fa-bed" aria-hidden="true"></i>
                    {{'<?= lang('room') ?> '+(parseInt(index)+1) }}</span>
                <div class="col-12 adults mt-1 px-0">
                    <!-- <label for="SelectAdult">Adults</label> -->
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><?= lang('adults') ?></div>
                        </div>
                        <select class="custom-select form-control" id="SelectAdult" :rmid="index"
                            @change="setadultscount" v-model="rm.adults">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 childs mt-1 px-0">
                    <!-- <label class="mt-2" for="SelectChild">Childs</label> -->
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><?= lang('childs') ?></div>
                        </div>
                        <select class="custom-select fomr-control" id="SelectChild" :rmid="index"
                            @change="setchildscount" v-model="rm.children">
                            <option value="0">child</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                        </select>
                    </div>
                </div>
                <div class="childage col-12 mt-1 px-0" v-for="cage in parseInt(rm.children)" :key="cage">
                    <!-- <label class="my-1 mr-2" for="Selectage">age</label> -->
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><?= lang('age') ?>{{cage}}</div>
                        </div>
                        <select class="custom-select" id="Selectage" :rmid="index" :cage="cage" @change="setAge"
                            v-model="rm.age[cage-1]">
                            <option value="0">less Than 1</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                            <option value="13">13</option>
                            <option value="14">14</option>
                            <option value="15">15</option>
                            <option value="16">16</option>
                            <option value="17">17</option>
                            <option value="18">18</option>
                        </select>
                    </div>
                </div>
                <span class="btn btn-danger btn-sm mx-auto" v-if="rooms.length > 1" type="button"
                    @click="rmroom(index)"><?= lang('delete') ?> <i class="fa fa-trash-alt"></i></span>
            </div>
            <button class="btn btn-sm ebtn" type="button" @click="addroom"><?= lang('addroom') ?> <i
                    class="fa fa-plus"></i></button>
            <button class="btn btn-sm ebtn" type="button" @click="shwroom = !shwroom"><?= lang('apply') ?> <i
                    class="fa fa-check"></i></button>
        </div>
    </div>
    <!-- rooms edits -->

    <!-- acmisolated -->
    <div id="acmconta" v-if="acmshow && !mobAcm" class="autocomplete-items"
        :style="acmstyle+(acmshow && !mobAcm?'display:block;':'')">
        <div v-if="srchdata.locations?.length" class="acmlocations">
            <div class="resitem bgmcolor">
                <strong class="font-weight-bold text-white"> <?= lang('places') ?></strong>
            </div>
            <div class="resitem" v-for="(item, ind) in srchdata.locations" :key="ind" @click.stop="acmselect"
                :data-dest="item.title" :data-tid="item.tid" :data-type="item.type" :data-city="item.city">
                <strong><i class="fas fa-map-marker-alt mcolor m-1"></i> {{item.titledd}}</strong>
                <small>{{item.label}}</small>
            </div>
        </div>
        <div v-if="srchdata.hotels?.length" class="acmhotels">
            <div class="resitem bgmcolor">
                <strong class="font-weight-bold text-white"> <?= lang('hotels') ?> </strong>
            </div>
            <div class="resitem" v-for="(item, ind) in srchdata.hotels" :key="ind" @click.stop="acmselect"
                :data-dest="item.title" :data-tid="item.tid" :data-type="item.type" :data-city="item.city">
                <strong><i class="fas fa-bed mcolor m-1"></i> {{item.titledd}}</strong>
                <small>{{item.label}}</small>
            </div>
        </div>
        <div v-if="!srchdata.locations?.length && srchdata[0]?.city == 0" class="acmhotels">
            <div class="resitem bgmcolor">
                <strong class="font-weight-bold text-white"> <?= lang('hotels') ?> </strong>
            </div>
            <div class="resitem">
                <strong><i class="fas fa-bed mcolor m-1"></i> {{srchdata[0].titledd}}</strong>
            </div>
        </div>
    </div>
    <!-- acmisolated -->
</div>

<script type="text/javascript" src="<?= base_url('public_designs/assets') ?>/js/moment.min.js"></script>
<script type="text/javascript" src="<?= base_url('public_designs/assets') ?>/js/daterangepicker.min.js"></script>
<script>
<?php
    $dest = $this->input->get('dest');
    $desttype = $this->input->get('desttype') ?? 'locality';
    $tid = $this->input->get('destid') ?? '164';
    $city = $this->input->get('city') ?? '164';
    $rooms = $rooms ?? json_encode([['adults' => 1, 'children' => 0, 'age' => []]]);
    echo 'var dest="' . $dest . '";';
    echo 'var desttype="' . $desttype . '";';
    echo 'var tid="' . $tid . '";';
    echo 'var city="' . $city . '";';
    echo 'var lang="' . userlang() . '";';
    ?>
</script>
<script>
Vue.config.devtools = true;
var srch = new Vue({
    el: "#srchs",
    data() {
        return {
            disable: true,
            disable_search: true,
            srchdata: [],
            datefrom: dataParams.fdf,
            dateto: dataParams.fdt,
            shwroom: false,
            roomseditstyle: '',
            acmstyle: '',
            roomcount: 1,
            adultscount: 1,
            childcount: 0,
            roomval: '',
            rooms: <?= $rooms ?>,
            images: [
                `${dataParams.burl}/assets/banners/Backgrounds_Artwork4.jpg`,
                `${dataParams.burl}/assets/banners/Background_Madinah4.png`,
                `${dataParams.burl}/assets/banners/Background_Madinah11.png`,
                `${dataParams.burl}/assets/banners/Background_Madinah8.png`,
                `${dataParams.burl}/assets/banners/Backgrounds_Artwork8.png`,
                `${dataParams.burl}/assets/banners/Backgrounds_Artwork5.png`,
            ],
            timer: null,
            slidecounter: 0,
            acmshow: false,
            mobAcm: false,
            acmvalues: {
                dest: dest,
                type: desttype,
                tid: tid,
                city: city,
            },
            width: 0,
        }
    },
    watch: {
        rooms: function() {
            this.attrcount();
        },
        slidecounter: function(val) {
            if (val >= this.images.length) {
                this.slidecounter = 0;
            }
        },
        acmshow: function(val) {
            if (val) {
                this.acm();
            }
        },
        shwroom: function(val) {
            if (val) {
                this.editrooms();
                this.disable_search = false;
            }
        },
    },
    methods: {
        async search(e) {
            var input = e.target.value;
            if (input.length > 3) {
                this.acmdev();
                // return;
            } else if (input.length <= 3) {
                this.acmshow = false;
                this.mobAcm = false;
                this.disable_search = true;
            }
            if (input.length > 3) {
                await fetch('/' + lang + '/hotels/s_bar?dest=' + input).then(res => res.json()).then(
                    data => {
                        // console.log(data);
                        if (!data.error) {
                           
                            this.srchdata = data;
                            


                        } else {
                            console.log('bad condition');
                        }
                    }).catch(function(error) {
                    console.log(error);
                });

            }

        },
        handleSubmit(result) {
            this.acres = result.title + 'hh';
        },
        getResultValue(result) {
            return result.title
        },
        initdate() {
            var df = new Date(this.datefrom.replace(/-/g, "/"));
            var dt = new Date(this.dateto.replace(/-/g, "/"));
            var date = new Date();
            var day = '0' + (date.getMonth() + 1) + '/' + date.getDate() + '/' + date.getFullYear();
            var today = new Date(day);
            try {
                var localeAr = {
                    "direction": 'rtl',
                    "format": "YYYY/MM/DD",
                    "separator": " - ",
                    "applyLabel": "موافق",
                    "cancelLabel": "إلغاء",
                    "fromLabel": "من",
                    "toLabel": "إلي",
                    "customRangeLabel": "التواريخ",
                    "daysOfWeek": [
                        "الأحد",
                        "الأثنين",
                        "الثلاثاء",
                        "الأربع",
                        "الخميس",
                        "الجمعة",
                        "السبت"
                    ],
                    "monthNames": [
                        "يناير",
                        "فبراير",
                        "مارس",
                        "أبريل",
                        "مايو",
                        "يونيو",
                        "يوليو",
                        "أغسطس",
                        "سبتمبر",
                        "أكتوبر",
                        "نوفمبر",
                        "ديسمبر"
                    ],
                    "firstDay": 1
                };
                var localeEn = {};
                var locale = <?= userlang() == 'ar' ? 'localeAr' : 'localeEn' ?>;
                var xWidth = window.matchMedia("(min-width: 700px)");

                if (xWidth.matches) {}
                $('#drange').daterangepicker({
                    autoUpdateInput: false,
                    minDate: today,
                    range: {},
                    locale: locale,
                });
                $('#drange').data('daterangepicker').setStartDate(df);
                $('#drange').data('daterangepicker').setEndDate(dt);
                $('#drange').on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format(
                        'YYYY-MM-DD'));
                    srch.datefrom = picker.startDate.format('YYYY-MM-DD');
                    srch.dateto = picker.endDate.format('YYYY-MM-DD');
                });
                $('#drange').on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                });

            } catch (err) {
                console.log('datepicker errro');
            }
        },
        addroom() {
            var room = {
                adults: 1,
                children: 0,
                age: []
            };
            this.rooms.push(room);
        },
        setadultscount(v) {
            ind = v.target.getAttribute('rmid');
            this.rooms[ind].adults = v.target.value;
            this.attrcount();
        },
        setchildscount(v) {
            ind = v.target.getAttribute('rmid');
            this.rooms[ind].children = v.target.value;
            if (v.target.value == 0) {
                this.rooms[ind].age = [];
            }
            this.attrcount();
        },
        setAge(v) {
            ind = v.target.getAttribute('rmid');
            ageind = v.target.getAttribute('cage') - 1;
            this.rooms[ind].age[ageind] = v.target.value;
            this.attrcount();
        },
        attrcount() {
            var trooms = this.rooms.length;
            var tchilds = 0;
            var tadults = 0;
            for (rm in this.rooms) {
                tadults += parseInt(this.rooms[rm].adults);
                tchilds += parseInt(this.rooms[rm].children);
            }
            this.roomcount = trooms;
            this.adultscount = tadults;
            this.childcount = tchilds;
            this.roomscal();

        },
        roomscal() {
            var hrooms = '';
            for (rm in this.rooms) {
                var hroom = '';
                var cage = '';
                hroom += this.rooms[rm].adults + '_adults';
                if (this.rooms[rm].children) {
                    hroom += ',' + this.rooms[rm].children + '_child';
                }
                if (this.rooms[rm].age.length) {
                    for (cg in this.rooms[rm].age) {
                        if (cage.length) {
                            cage += '-' + this.rooms[rm].age[cg];
                        } else {
                            cage += this.rooms[rm].age[cg];
                        }
                    }
                    cage += '_age';
                    hroom += ',' + cage;
                }
                if (hrooms.length) {
                    hrooms += '*' + hroom;
                } else {
                    hrooms += hroom;
                }
            }
            this.roomval = encodeURI(hrooms);
        },
        rmroom(index) {
            this.rooms.splice(index, 1);
        },
        changeBackground: function() {
            this.timer = setInterval(this.slidenext, 3000);
        },
        slidenext: function() {
            this.slidecounter += 1;
        },
        editrooms: function() {
            var cords = this.$refs.roomsedit.getBoundingClientRect();
            var rel = this.$refs.roomsedit;
            var xWidth = window.matchMedia("(min-width: 700px)");

            if (xWidth.matches) {
                if (document.dir == 'rtl') {
                    this.roomseditstyle =
                        `width:${cords.width}px;top:${cords.top+window.pageYOffset-rel.offsetHeight+32}px;right:auto;left:${cords.left}px`;
                } else {
                    this.roomseditstyle =
                        `width:${cords.width}px;top:${cords.top+window.pageYOffset-rel.offsetHeight+22}px;right:auto;left:${cords.left}px`;
                }
            } else {
                if (document.dir == 'rtl') {
                    this.roomseditstyle =
                        `width:${cords.width}px;top:${cords.top+window.pageYOffset+rel.offsetHeight+6}px;right:auto;left:${cords.left}px`;
                } else {
                    this.roomseditstyle =
                        `width:${cords.width}px;top:${cords.top+window.pageYOffset+rel.offsetHeight+6}px;right:auto;left:${cords.left}px`;
                }
            }

        },
        acmdev: function() {
            var xWidth = window.matchMedia("(max-width: 700px)");
            if (xWidth.matches) {
                this.mobAcm = true;
                this.acmshow = false;
            
            } else {
                this.mobAcm = false;
                this.acmshow = true;
            }
            this.disable_search = true;
        },
        acm: function() {
            var cords = this.$refs.acm.getBoundingClientRect();
            var acmi = this.$refs.acm;
            var xWidth = window.matchMedia("(max-width: 700px)");
            if (xWidth.matches) {
                this.acmstyle = `top:45px;`; //fullscreen search
            } else {
                this.acmstyle =
                    `width:${acmi.offsetWidth}px;top:${cords.top-acmi.offsetHeight+15}px;left:${Math.round(cords.left)}px;right:auto;`;
            }
        },
        acmselect: function(e) {
            var dest = e.currentTarget.dataset.dest;
            var type = e.currentTarget.dataset.type;
            var tid = e.currentTarget.dataset.tid;
            var city = e.currentTarget.dataset.city;
            this.disable_search = false;
            this.acmvalues.dest = dest;
            this.acmvalues.type = type;
            this.acmvalues.tid = tid;
            this.acmvalues.city = city;
            this.acmshow = false;
            this.mobAcm = false;
        },
    },
    computed: {
        BackgroundComponent: function() {
            var xWidth = window.matchMedia("(min-width: 700px)");
            if (xWidth.matches) {
                return 'background-image: url("' + this.images[this.slidecounter] +
                    '");-webkit-transition: background-image 2s ease-in-out;transition: background-image 0.2s ease-in-out;';
            } else {
                return '';
            }
        },
        ShowAcm: function() {
            if (this.disable_search == false) {
                this.acmshow = false;
                this.mobAcm = false;
            }
        }
    },
    mounted() {
        this.initdate();
        this.ShowAcm;
        this.acm();
        this.editrooms();
    },
    created() {

        this.attrcount();
        this.changeBackground();
        window.addEventListener('resize', function(event) {
            srch.acm();
            srch.editrooms();
        });
    },
});
</script>