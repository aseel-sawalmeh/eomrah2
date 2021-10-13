<link href="<?= base_url('public_designs/assets') ?>/css/acv.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<div id="srchs" class="search_area position-relative" :style="BackgroundComponent">

    <div class="position-absolute searchformWrapper">

        <div class="accordion" id="formswitch" style="text-align: <?= (lngdir() == "rtl") ? 'right' : 'left' ?> ">

            <div class="mb-3">
                <!-- 
               <button class="btn bt2 mt-2" type="button" data-toggle="collapse" data-target="#collapsethree" aria-expanded="false" aria-controls="collapsethree">
                    Flight Booking
                </button>
                <button class="btn bt2 mt-2" type="button" data-toggle="collapse" data-target="#collapsefour" aria-expanded="false" aria-controls="collapsefour">
                    Car Rental
                </button>
                <button class="btn bt2 mt-2" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                    <?= lang('oPackage') ?>
                </button> -->
            </div>

            <div id="HotelForm" class="collapse show active container px-0 position-relative" aria-labelledby="headingHotelForm" data-parent="#formswitch">
                <!-- <span class="collapsed formhead px-4" type="button" data-toggle="collapse" data-target="#HotelForm" aria-expanded="true" aria-controls="HotelForm">
                    <?= lang('findhotel') ?>
                </span> -->

                <span class="collapsed formhead px-4">
                    <?= lang('findhotel') ?>
                </span>

                <form class="searchform pt-4 pb-5 mb-5" method='get' action='<?php echo site_url('hotels/search'); ?>'>
                    <div class="form-row">
                        <div class="col-md-4 col-sm mb-1 mx-1">
                            <autocomplete :search="search" data-content="<?=lang('find')?>"  placeholder="<?= lang('c/h') ?>" aria-label="<?= lang('c/h') ?>" name="dest" default-value="<?= $this->input->get('dest') ?? 'makkah' ?>" @submit="handleSubmit" :get-result-value="getResultValue">
                                <template #result="{ result, props }">
                                    <li v-bind="props" :class="'autocomplete-result result-'+result.type">
                                        {{ result.title }}
                                    </li>
                                </template>
                            </autocomplete>


                            <!-- datalist  -->
                            <!-- <input class="form-control" list="searchlist" name="dest" id="dest" size="50" autocomplete="off" />

                            <datalist id="searchlist">
                                <option value="1" label="1label">1item</option>
                                <option value="2" label="2label">2item</option>
                                <option value="3" label="3label">3item</option>
                                <option value="4" label="4label">4item</option>
                                <option value="5" label="5label">5item</option>
                            </datalist> -->
                            <!-- datalist -->

                        </div>

                        <input type="hidden" name='dt1' placeholder="<?= lang('df') ?>" :value="datefrom" />
                        <input type="hidden" name='dt2' placeholder="<?= lang('dt') ?>" :value="dateto" />
                        <input type="hidden" name='rooms' :value="roomval" />
                        <div class="col-md-4 col-sm mb-1 mx-1 ">
                            <div class="forminput p-1">
                                <span class="ml-1"><?= lang('checkin') . ' / ' . lang('checkout') ?></span>
                                <input class="form-control position-relative" id="drange" type="text" :value="'Checkin: '+datefrom +' Checkout: '+ dateto" readonly>
                                <i class="fa fa-calendar ico"></i>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm mb-1 mx-1" @click="editrooms">
                            <div class="forminput position-relative p-1">
                                <span class="ml-1"><?= lang('rooms') ?></span>
                                <input ref="roomsedit" class="form-control" :value=" roomcount + ((roomcount > 1)? ' <?= lang('rooms') ?> | ':' <?= lang('room') ?> | ')+ adultscount +' <?= lang('adults') ?> | ' +childcount + ' <?= lang('childs') ?>'" autocapitalize="off" readonly />
                                <i class="fa fa-user ico"></i>
                            </div>
                        </div>

                        <div class="col px-0 col-sm">
                            <button type="submit" class="sbtn btn mt-0 text-white"><i class="fa fa-search fa-lg" aria-hidden="true"></i>
                            </button>
                        </div>

                    </div>
                </form>


            </div>

            <div id="collapseOne" class=" collapse" aria-labelledby="headingOne" data-parent="#accordionExample">

                <form>
                    <div class="row">

                        <div class="col-md-3 col-12">
                            <div class="form-group">
                                <strong class="color-white">Select Destination</strong>
                                <select class="form-control mt-0" placeholder="Select Destination">
                                    <option>Makkah</option>
                                    <option>Madinah</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3 col-12">
                            <div class="form-group">
                                <b class="color-white">Depart Date</b>
                                <input class="form-control" type="date" data-format='yyyy-m-d' placeholder="Depart Date" autocomplete="off">
                            </div>
                        </div>

                        <div class="col-md-3 col-12">
                            <div class="form-group">
                                <b class="color-white">Nights In Makkah</b>
                                <select class="form-control">
                                    <option value="1">1 night</option>
                                    <option value="2">2 nights</option>
                                    <option value="3">3 nights</option>
                                    <option value="4">4 nights</option>
                                    <option value="5">5 nights</option>
                                    <option value="6">6 nights</option>
                                    <option value="7">7 nights</option>
                                    <option value="8">8 nights</option>
                                </select>
                            </div>

                        </div>

                        <div class="col-md-3 col-12">
                            <div class="form-group">
                                <b class="color-white">Nights In Madinah</b>
                                <select class="form-control">
                                    <option value="1">1 night</option>
                                    <option value="2">2 nights</option>
                                    <option value="3">3 nights</option>
                                    <option value="4">4 nights</option>
                                    <option value="5">5 nights</option>
                                    <option value="6">6 nights</option>
                                    <option value="7">7 nights</option>
                                    <option value="8">8 nights</option>
                                </select>
                            </div>

                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-3 col-12">
                            <div class="form-group">
                                <b class="color-white">Travellers</b>
                                <select class="form-control">
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                    <option>6</option>
                                    <option>7</option>
                                    <option>8</option>
                                    <option>9</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3 col-12">
                            <div class="form-group">
                                <b class="color-white">Rooms</b>
                                <select class="form-control">
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                    <option>6</option>
                                    <option>7</option>
                                    <option>8</option>
                                    <option>9</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3 col-12">
                            <div class="form-group">
                                <b class="color-white">Residence</b>
                                <select class="form-control">
                                    <option value="AS">American Samoa</option>
                                    <option value="AD">Andorra</option>
                                    <option value="AO">Angola</option>
                                    <option value="AI">Anguilla</option>
                                    <option value="AQ">Antarctica</option>
                                    <option value="AG">Antigua</option>
                                    <option value="AM">Armenia</option>
                                    <option value="AW">Aruba</option>
                                    <option value="SH">Ascension Island</option>
                                    <option value="BS">Bahamas</option>
                                    <option value="AG_">Barbuda</option>
                                    <option value="BY">Belarus</option>
                                    <option value="BZ">Belize</option>
                                    <option value="BM">Bermuda</option>
                                    <option value="BT">Bhutan</option>
                                    <option value="BO">Bolivia</option>
                                    <option value="VG">British Virgin Islands</option>
                                    <option value="BI">Burundi</option>
                                    <option value="CV">Cape Verde Islands</option>
                                    <option value="KY">Cayman Islands</option>
                                    <option value="CF">Central African Republic</option>
                                    <option value="CL">Chile</option>
                                    <option value="CX">Christmas Island</option>
                                    <option value="CC">Cocos-Keeling Islands</option>
                                    <option value="CO">Colombia</option>
                                    <option value="CG">CONGO</option>
                                    <option value="CD">Congo, Dem. Rep. of (former Zaire)</option>
                                    <option value="CK">Cook Islands</option>
                                    <option value="CR">Costa Rica</option>
                                    <option value="HR">Croatia</option>
                                    <option value="CU">Cuba</option>
                                    <option value="CW">Curacao</option>
                                    <option value="CY">Cyprus</option>
                                    <option value="DA">Dagestan</option>
                                    <option value="IO">Diego Garcia</option>
                                    <option value="DO">Dominican Republic</option>
                                    <option value="TP">East Timor</option>
                                    <option value="EC">Ecuador</option>
                                    <option value="GQ">Equatorial Guinea</option>
                                    <option value="FO">Faeroe Islands</option>
                                    <option value="FK">Falkland Islands Dem. Rep. of (former Zaire)</option>
                                    <option value="FJ">Fiji</option>
                                    <option value="GF">French Guiana</option>
                                    <option value="PF">French Polynesia</option>
                                    <option value="GA">Gabon</option>
                                    <option value="GE">Georgia</option>
                                    <option value="GI">Gibraltar</option>
                                    <option value="GL">Greenland</option>
                                    <option value="GD">Grenada</option>
                                    <option value="BL">Guadeloupe</option>
                                    <option value="GU">Guam</option>
                                    <option value="GT">Guatemala</option>
                                    <option value="GW">Guinea-Bissau</option>
                                    <option value="GY">Guyana</option>
                                    <option value="HT">Haiti</option>
                                    <option value="HN">Honduras</option>
                                    <option value="IS">Iceland</option>
                                    <option value="JM">Jamaica</option>
                                    <option value="KI">Kiribati</option>
                                    <option value="LA">Laos</option>
                                    <option value="LV">Latvia</option>
                                    <option value="LS">Lesotho</option>
                                    <option value="LI">Liechtenstein</option>
                                    <option value="LT">Lithuania</option>
                                    <option value="MO">Macau</option>
                                    <option value="MG">Madagascar</option>
                                    <option value="MH">Marshall Islands</option>
                                    <option value="MQ">Martinique</option>
                                    <option value="YT">Mayotte Island</option>
                                    <option value="MX">Mexico</option>
                                    <option value="MC">Monaco</option>
                                    <option value="MN">Mongolia</option>
                                    <option value="ME">MONTENEGRO</option>
                                    <option value="NA">Namibia</option>
                                    <option value="NR">Nauru</option>
                                    <option value="AN">Netherlands Antilles</option>
                                    <option value="KN">Nevis</option>
                                    <option value="NC">New Caledonia</option>
                                    <option value="NI">Nicaragua</option>
                                    <option value="NU">Niue</option>
                                    <option value="NF">Norfolk Island</option>
                                    <option value="KP">North Korea</option>
                                    <option value="MP">Northern Marianas Islands</option>
                                    <option value="PW">Palau</option>
                                    <option value="PG">Papua New Guinea</option>
                                    <option value="PY">Paraguay</option>
                                    <option value="PE">Peru</option>
                                    <option value="PL">Poland</option>
                                    <option value="PR">Puerto Rico</option>
                                    <option value="RE">R&#xE9;union Island</option>
                                    <option value="RW">Rwanda</option>
                                    <option value="SV">Salvador</option>
                                    <option value="SM">San Marino</option>
                                    <option value="ST">Sao Tome and Principe</option>
                                    <option value="SC">Seychelles</option>
                                    <option value="SK">Slovakia</option>
                                    <option value="SI">Slovenia</option>
                                    <option value="SB">Solomon Islands</option>
                                    <option value="SH_">St. Helena</option>
                                    <option value="KN_">St. Kitts/Nevis</option>
                                    <option value="LC">St. Lucia</option>
                                    <option value="PM">St. Pierre ,Miquelon</option>
                                    <option value="VC">St. Vincent , Grenadines</option>
                                    <option value="SR">Suriname</option>
                                    <option value="TK">Tokelau</option>
                                    <option value="TO">Tonga Islands</option>
                                    <option value="TC">Turks and Caicos Islands</option>
                                    <option value="TV">Tuvalu</option>
                                    <option value="UY">Uruguay</option>
                                    <option value="VI">US Virgin Islands</option>
                                    <option value="VU">Vanuatu</option>
                                    <option value="VA">Vatican City</option>
                                    <option value="WF">Wallis and Futuna Islands</option>
                                    <option value="WS">Western Samoa</option>
                                    <option value="YU">Yugoslavia</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-12">
                            <div class="form-group">
                                <b class="color-white">Nationality</b>
                                <select class="form-control">
                                    <option value="AS">American Samoa</option>
                                    <option value="AD">Andorra</option>
                                    <option value="AO">Angola</option>
                                    <option value="AI">Anguilla</option>
                                    <option value="AQ">Antarctica</option>
                                    <option value="AG">Antigua</option>
                                    <option value="AM">Armenia</option>
                                    <option value="AW">Aruba</option>
                                    <option value="SH">Ascension Island</option>
                                    <option value="BS">Bahamas</option>
                                    <option value="AG_">Barbuda</option>
                                    <option value="BY">Belarus</option>
                                    <option value="BZ">Belize</option>
                                    <option value="BM">Bermuda</option>
                                    <option value="BT">Bhutan</option>
                                    <option value="BO">Bolivia</option>
                                    <option value="VG">British Virgin Islands</option>
                                    <option value="BI">Burundi</option>
                                    <option value="CV">Cape Verde Islands</option>
                                    <option value="KY">Cayman Islands</option>
                                    <option value="CF">Central African Republic</option>
                                    <option value="CL">Chile</option>
                                    <option value="CX">Christmas Island</option>
                                    <option value="CC">Cocos-Keeling Islands</option>
                                    <option value="CO">Colombia</option>
                                    <option value="CG">CONGO</option>
                                    <option value="CD">Congo, Dem. Rep. of (former Zaire)</option>
                                    <option value="CK">Cook Islands</option>
                                    <option value="CR">Costa Rica</option>
                                    <option value="HR">Croatia</option>
                                    <option value="CU">Cuba</option>
                                    <option value="CW">Curacao</option>
                                    <option value="CY">Cyprus</option>
                                    <option value="DA">Dagestan</option>
                                    <option value="IO">Diego Garcia</option>
                                    <option value="DO">Dominican Republic</option>
                                    <option value="TP">East Timor</option>
                                    <option value="EC">Ecuador</option>
                                    <option value="GQ">Equatorial Guinea</option>
                                    <option value="FO">Faeroe Islands</option>
                                    <option value="FK">Falkland Islands Dem. Rep. of (former Zaire)</option>
                                    <option value="FJ">Fiji</option>
                                    <option value="GF">French Guiana</option>
                                    <option value="PF">French Polynesia</option>
                                    <option value="GA">Gabon</option>
                                    <option value="GE">Georgia</option>
                                    <option value="GI">Gibraltar</option>
                                    <option value="GL">Greenland</option>
                                    <option value="GD">Grenada</option>
                                    <option value="BL">Guadeloupe</option>
                                    <option value="GU">Guam</option>
                                    <option value="GT">Guatemala</option>
                                    <option value="GW">Guinea-Bissau</option>
                                    <option value="GY">Guyana</option>
                                    <option value="HT">Haiti</option>
                                    <option value="HN">Honduras</option>
                                    <option value="IS">Iceland</option>
                                    <option value="JM">Jamaica</option>
                                    <option value="KI">Kiribati</option>
                                    <option value="LA">Laos</option>
                                    <option value="LV">Latvia</option>
                                    <option value="LS">Lesotho</option>
                                    <option value="LI">Liechtenstein</option>
                                    <option value="LT">Lithuania</option>
                                    <option value="MO">Macau</option>
                                    <option value="MG">Madagascar</option>
                                    <option value="MH">Marshall Islands</option>
                                    <option value="MQ">Martinique</option>
                                    <option value="YT">Mayotte Island</option>
                                    <option value="MX">Mexico</option>
                                    <option value="MC">Monaco</option>
                                    <option value="MN">Mongolia</option>
                                    <option value="ME">MONTENEGRO</option>
                                    <option value="NA">Namibia</option>
                                    <option value="NR">Nauru</option>
                                    <option value="AN">Netherlands Antilles</option>
                                    <option value="KN">Nevis</option>
                                    <option value="NC">New Caledonia</option>
                                    <option value="NI">Nicaragua</option>
                                    <option value="NU">Niue</option>
                                    <option value="NF">Norfolk Island</option>
                                    <option value="KP">North Korea</option>
                                    <option value="MP">Northern Marianas Islands</option>
                                    <option value="PW">Palau</option>
                                    <option value="PG">Papua New Guinea</option>
                                    <option value="PY">Paraguay</option>
                                    <option value="PE">Peru</option>
                                    <option value="PL">Poland</option>
                                    <option value="PR">Puerto Rico</option>
                                    <option value="RE">R&#xE9;union Island</option>
                                    <option value="RW">Rwanda</option>
                                    <option value="SV">Salvador</option>
                                    <option value="SM">San Marino</option>
                                    <option value="ST">Sao Tome and Principe</option>
                                    <option value="SC">Seychelles</option>
                                    <option value="SK">Slovakia</option>
                                    <option value="SI">Slovenia</option>
                                    <option value="SB">Solomon Islands</option>
                                    <option value="SH_">St. Helena</option>
                                    <option value="KN_">St. Kitts/Nevis</option>
                                    <option value="LC">St. Lucia</option>
                                    <option value="PM">St. Pierre ,Miquelon</option>
                                    <option value="VC">St. Vincent , Grenadines</option>
                                    <option value="SR">Suriname</option>
                                    <option value="TK">Tokelau</option>
                                    <option value="TO">Tonga Islands</option>
                                    <option value="TC">Turks and Caicos Islands</option>
                                    <option value="TV">Tuvalu</option>
                                    <option value="UY">Uruguay</option>
                                    <option value="VI">US Virgin Islands</option>
                                    <option value="VU">Vanuatu</option>
                                    <option value="VA">Vatican City</option>
                                    <option value="WF">Wallis and Futuna Islands</option>
                                    <option value="WS">Western Samoa</option>
                                    <option value="YU">Yugoslavia</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <button disabled type="submit" class="bg2 bt2 btn btn-block"><?= lang('sea') ?></button>
                        </div>
                    </div>
                </form>
            </div>
            <div id="collapsethree" class="srchcontainer collapse" aria-labelledby="headingthree" data-parent="#accordionExample">
                <form class="form">
                    <div class="row">
                        <div class="col-xl-3 col-md-12">
                            <div class="form-group">

                                <input list="from" class="form-control" name="browser" id="browser" placeholder="from">
                                <datalist id="from">
                                    <option value="King Fahd International Airport">
                                    <option value="Amaala International Airport">
                                    <option value="King Abdulaziz International Airport">
                                    <option value="King Khalid International Airport">
                                    <option value="Prince Mohammad Bin Abdulaziz International Airport">
                                    <option value="Prince Nayef Bin Abdulaziz Regional Airport">
                                </datalist>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-12">
                            <div class="form-group">

                                <input list="to" class="form-control" name="browser" id="browser" placeholder="to">

                                <datalist id="to">
                                    <option value="King Fahd International Airport">
                                    <option value="Amaala International Airport">
                                    <option value="King Abdulaziz International Airport">
                                    <option value="King Khalid International Airport">
                                    <option value="Prince Mohammad Bin Abdulaziz International Airport">
                                    <option value="Prince Nayef Bin Abdulaziz Regional Airport">
                                </datalist>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-12">
                            <div class="form-group">
                                <select class="form-control">
                                    <option>One way</option>
                                    <option>Round trip</option>
                                </select>
                            </div>

                        </div>
                        <div class="col-xl-3 col-md-12">
                            <div class="form-group">
                                <input type="date" class="form-control" required />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <button type="submit" class="bg2 bt2 btn btn-block"><?= lang('sea') ?></button>
                        </div>
                    </div>
                </form>
            </div>

            <div id="collapsefour" class="srchcontainer collapse" aria-labelledby="headingfour" data-parent="#accordionExample">
                <form class="form">
                    <div class="row">
                        <div class="col-xl-3 col-md-12">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Pick Up" required />
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-12">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Drof Off" required />
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-12">
                            <input type="date" class="form-control" placeholder="time" required />
                        </div>
                        <div class="col-xl-3 col-md-12">
                            <div class="form-group">
                                <input type="time" class="form-control" placeholder="time" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <button type="submit" class="bg2 bt2 btn btn-block"><?= lang('sea') ?> <i class="fa fa-search" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- rooms edits -->

    <div v-if="shwroom" class="col-12 p-2 roomsmenu" :style="roomseditstyle">
        <span class="arrow"></span>
        <div class="container text-center">
            <div class="row" v-for="(rm, index) in rooms">
                <span class="mx-1 text-small"><i class="fa fa-bed" aria-hidden="true"></i> {{'room '+(parseInt(index)+1) }}</span>
                <div class="col-12 adults mt-1 px-0">
                    <!-- <label for="SelectAdult">Adults</label> -->
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">Adults</div>
                        </div>
                        <select class="custom-select form-control" id="SelectAdult" :rmid="index" @change="setadultscount">
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
                            <div class="input-group-text">Childs</div>
                        </div>
                        <select class="custom-select fomr-control" id="SelectChild" :rmid="index" @change="setchildscount">
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
                            <div class="input-group-text">age{{cage}}</div>
                        </div>
                        <select class="custom-select" id="Selectage" :rmid="index" :cage="cage" @change="setAge">
                            <option value="0">less Than 1</option>
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
                <span class="btn btn-danger btn-sm mx-auto" v-if="rooms.length > 1" type="button" @click="rmroom(index)"><i class="fa fa-trash-alt"></i></span>
            </div>
            <button class="btn btn-sm ebtn" type="button" @click="addroom">Add Room <i class="fa fa-plus"></i></button>
        </div>
    </div>
    <!-- rooms edits -->
</div>

<script src="<?= base_url('public_designs/assets') ?>/js/vplugs/acv.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<?php $df = $this->input->get('dt1') ?? date('Y-m-d', strtotime('now')) ?>
<?php $dt = $this->input->get('dt2') ?? date('Y-m-d', strtotime('now +2day')) ?>
<?php $rooms = $rooms ?? json_encode([['adults' => 1, 'children' => 0, 'age' => []]]); ?>
<script>
    Vue.config.devtools = true;
    Vue.use(Autocomplete);
    //Vue.use(axios);
    var srch = new Vue({
        el: "#srchs",
        data() {
            return {
                disable: true,
                srchdata: [],
                datefrom: '<?= $df ?>',
                dateto: '<?= $dt ?>',
                shwroom: false,
                roomseditstyle: '',
                roomcount: 1,
                adultscount: 1,
                childcount: 0,
                roomval: '',
                rooms: <?= $rooms ?>,
                images: [
                    'http://eomrah.h/assets/banners/Backgrounds_Artwork4.jpg',
                    'http://eomrah.h/assets/banners/Background_Madinah4.png',
                    'http://eomrah.h/assets/banners/Background_Madinah11.png',
                    'http://eomrah.h/assets/banners/Background_Madinah8.png',
                    'http://eomrah.h/assets/banners/Backgrounds_Artwork8.png',
                    'http://eomrah.h/assets/banners/Backgrounds_Artwork5.png',
                ],
                timer: null,
                slidecounter: 0,

            }
        },
        components: {
            Autocomplete,
        },
        watch: {
            rooms: function() {
                this.attrcount();
            },
            slidecounter: function() {
                if (this.slidecounter >= this.images.length) {
                    this.slidecounter = 0;
                }
            }

        },
        methods: {
            async search(input) {
                if (input.length < 1) {
                    return []
                }
                console.log(Autocomplete);
                await axios.get("/hotels/s_bar?dest=" + input).then(function(res) {
                    //console.log(res.data);
                    srch.srchdata = res.data;
                }).catch(function(error) {
                    console.log(error);
                });
                //return this.srchdata;
                return this.srchdata.filter(country => {
                    return country.title.toLowerCase().includes(input.toLowerCase());
                })
            },
            handleSubmit(result) {
                this.acres = result.title + 'hh';
                console.log(`You selected ${result.title} `);
                this
            },
            getResultValue(result) {
                return result.title
            },
            initdate() {
                var df = new Date(this.datefrom.replace(/-/g, "/"));
                var dt = new Date(this.dateto.replace(/-/g, "/"));
                // console.log(df);
                // console.log(dt);
                var date = new Date();
                var day = '0' + (date.getMonth() + 1) + '/' + date.getDate() + '/' + date.getFullYear();
                var today = new Date(day);
                // console.log(day);
                // console.log(today);
                $('#drange').daterangepicker({
                    autoUpdateInput: false,
                    minDate: today,
                    locale: {
                        cancelLabel: 'Clear'
                    }
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
                // console.log(v.target.value);
                // console.log(v.target.getAttribute('rmid'));
            },
            setchildscount(v) {
                ind = v.target.getAttribute('rmid');
                this.rooms[ind].children = v.target.value;
                this.attrcount();
                // console.log(v.target.value);
                // console.log(v.target.getAttribute('rmid'));
            },
            setAge(v) {
                ind = v.target.getAttribute('rmid');
                ageind = v.target.getAttribute('cage') - 1;
                this.rooms[ind].age[ageind] = v.target.value;
                this.attrcount();
                // console.log(v.target.value);
                // console.log(v.target.getAttribute('rmid'));
            },
            attrcount() {
                var trooms = this.rooms.length;
                var tchilds = 0;
                var tadults = 0;
                for (rm in this.rooms) {
                    tadults += parseInt(this.rooms[rm].adults);
                    tchilds += parseInt(this.rooms[rm].children);
                    // console.log('adults ' + this.rooms[rm].adults);
                    // console.log('childs' + this.rooms[rm].children);
                }
                this.roomcount = trooms;
                this.adultscount = tadults;
                this.childcount = tchilds;
                this.roomscal();
                // console.log(tadults);
                // console.log(tchilds);
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
                //console.log(hrooms);
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
                this.shwroom = !this.shwroom;
                var x = window.matchMedia("(max-width: 700px)");
                if (document.documentElement.lang == 'ar') {
                    var right = cords.right + window.pageXOffset + 10;
                    console.log(right - window.pageXOffset);
                    right = right + (right * 0.28);
                    if (x.matches) {
                        right = 5;
                    }
                    this.roomseditstyle = `width:${cords.width}px;top:${cords.top+window.pageYOffset-40}px;right:${right}px;left:${cords.left+window.pageXOffset-10}px`;
                } else {
                    this.roomseditstyle = `width:${cords.width}px;top:${cords.top+window.pageYOffset-45}px;right:${cords.right+window.pageXOffset+10}px;left:${cords.left+window.pageXOffset-10}px`;
                }
                // var x = window.matchMedia("(max-width: 700px)");
                // console.log(x);
                // console.log(this.roomseditstyle);
                // console.log(window.pageYOffset);
                // console.log(document.documentElement.lang);
            },
        },
        computed: {
            BackgroundComponent: function() {
                return 'background-image: url("' + this.images[this.slidecounter] + '");-webkit-transition: background-image 2s ease-in-out;transition: background-image 0.2s ease-in-out;';
            }
        },

        mounted() {
            this.initdate();
            this.attrcount();
            this.changeBackground();
        },
    });
</script>