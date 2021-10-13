<div id="checkout" class="container home_container mb-1 mb-md-n10 checkout p-3">
    <div class="row m-1 checkout-head p-4">
        <div class="col col-md-4 p-3 mb-2 ">
            <i class="fa fa-money-check fi"> <i class="far fa-credit-card li"></i>
            </i>
        </div>
        <div class="col col-md-8 head-title justify-content-center">
            <h3 class="m-auto">CHECK<span class="mcolor">OUT</span></h3>
        </div>
    </div>
    <div class="row p-md-4 mt-md-3 mt-3">
        <div class="col-md-3 pb-3">
            <div class="res-summary">
                <h6 class="block-head p-3 text-white text-center"><?= lang('ressum') ?></h6>
                <div class="col px-3 py-2">
                    <span> <strong class="mcolor font-weight-bold my-0 py-0"> <?= lang('hotelname') ?> </strong> </span>
                    <small class="d-inline-block font-weight-bold"> <?= $hotel->Hotel_Name; ?> </small>
                </div>

                <div class="col p-3 d-flex justify-content-between">
                    <span> <strong class="mcolor font-weight-bold"> <?= lang('checkin') ?></strong> </span>
                    <span> <?= $wallet['checkin']; ?></span>
                </div>

                <div class="col p-3 d-flex justify-content-between">
                    <span><strong class="mcolor font-weight-bold"> <?= lang('checkout') ?></strong> </span>
                    <span> <?= $wallet['checkout']; ?> </span>
                </div>

                <div class="col p-3 d-flex justify-content-between">
                    <span><strong class="mcolor font-weight-bold"> <?= lang('nonights') ?></strong> </span>
                    <span><?= $wallet['nonights']; ?></span>
                </div>

                <div class="col p-3 ">
                    <p><strong class="mcolor font-weight-bold">
                            <?= !empty(lang('guests')) ? lang('guests') : 'Guests'; ?></strong> </p>
                    <p class="font-weight-bold">
                        <?= $wallet['adults'] . lang('adults') . ',<br/> ' . ($wallet['childrens'] ? $wallet['childrens'] . ' Childs (Ages to be here as nyears, nyears)' : ''); ?>
                    </p>
                </div>

                <h6 class="block-head p-3 text-white text-center"><?= lang('totalrooms') ?></h6>
                <div class="col p-3">
                    <?php foreach ($wallet['items'] as $room) : ?>
                    <!-- rooms names in languages needed here -->
                    <small class="font-weight-bold"><?= $room['roomcount'] . " x " . $room['rname'] ?></small>
                    <br />
                    <?php
                    endforeach; ?>
                    <div class="d-flex justify-content-between mt-2">
                        <span class="mcolor font-weight-bold"> <?= $wallet['total_items'] . ' ' . lang('room') ?></span>
                        <small class="font-weight-bold"><?= $wallet['total_price']; ?></small>
                    </div>
                    <div class="d-flex justify-content-between mt-1">
                        <span class="mcolor font-weight-bold"> <?= lang('vat') ?></span>
                        <small class="font-weight-bold"><?= $wallet['total_tax']; ?></small>
                    </div>
                    <div class="d-flex justify-content-between mt-1">
                        <span class="mcolor font-weight-bold"> <?= lang('mantax') ?></span>
                        <small class="font-weight-bold"><?= $wallet['total_municipalityTax']; ?></small>
                    </div>
                    <div class="d-flex justify-content-between mt-1">
                        <span class="mcolor font-weight-bold"> <?= lang('netprice') ?></span>
                        <span class="font-weight-bold"><?= $wallet['net_price']; ?></span>
                    </div>
                    <div class="d-flex justify-content-between mt-1">
                        <strong v-if="discavailable"> <?= lang('disamount') ?></strong>
                        <?= $wallet['dis_amount'] ?? ''; ?>
                        <strong v-if="discavailable"> <?= lang('afterdiscount') ?></strong>
                        <?= $wallet['after_discount'] ?? ''; ?>
                    </div>
                </div>
                <?php if ($hotel_amenities) : ?>
                <h6 class="block-head p-3 text-white text-center"><?= lang('resInclude') ?></h6>
                <div class="col p-3">
                    <ul class="list-unstyled">
                        <li style="color:#539F4E; letter-spacing: -0.33px;"> High Speed Internet</li>
                        <li style="color:#539F4E; letter-spacing: -0.33px;"> Doctor on Call</li>
                        <?php foreach ($hotel_amenities as $am) : ?>
                        <li class="list-group-item"><?= $am->name ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <!-- <div class="offset-1"></div> -->
        <div class="col-md-9 checkout-form p-3">
            <div class="row">
                <div class="col-12 col-md-6">
                    <img src="<?= himg($hotel->Main_Photo) ?>" class="rounded img-fluid" width="400" height="200"
                        alt="<?php echo $hotel->Hotel_Name; ?>" style="max-height:280px">
                </div>
                <div class="col-12 col-md-6">
                    <h5 class="hotel-name"><?php echo $hotel->Hotel_Name; ?></h5>
                    <h6 class="hotel-details-address mt-1"><i class="fas fa-map-marker-alt"></i>
                        <?php echo $hotel->Hotel_Address; ?></h6>
                    <h6 class="hotel-details-phone mt-1"><i class="fa fa-phone-alt"></i>
                        <?php echo $hotel->Hotel_Phone; ?></h6>
                </div>
            </div>
            <form class="form mt-3" method='post' action='<?php echo site_url('reservation/checkout'); ?>'>
                <div v-if="!ulg" class="row">
                    <div class="col">
                        <h5 class="block-head mcolor p-2"><?= lang('customer_details') ?></h5>
                        <small v-if="fdata" v-html="'* '+fdata" class="m-2"></small>

                        <?= "<input type='hidden' name='{$this->security->get_csrf_token_name()}' value='{$this->security->get_csrf_hash()}' />" ?>
                        <input type='hidden' name="hotelid" value="<?= $hotel->hotelLotsId ?>" />
                        <div class="form-row mt-3">
                            <div class="form-group col-md-6 mt-4 mt-md-1">
                                <div class="p-label">
                                    <input class="form-control" id="FirstName" type="text"
                                        placeholder="<?= lang('first_name') ?>" v-model="user.fname">
                                    <label for="FirstName"><?= lang('first_name') ?><span
                                            class="text-danger">*</span></label>

                                </div>
                            </div>
                            <div class="form-group col-md-6 mt-4 mt-md-1">
                                <div class="p-label">
                                    <input type="text" class="form-control" id="FamilyName"
                                        placeholder="<?= lang('family_name') ?>" v-model="user.fmname">
                                    <label for="FamilyName"><?= lang('family_name') ?><span
                                            class="text-danger">*</span></label>
                                </div>
                            </div>
                        </div>

                        <div class="form-row mt-4">
                            <div class="form-group col-md-6 mt-4 mt-md-1 position-relative">
                                <div class="p-label">
                                    <input type="email" class="form-control" id="Email" placeholder="<?= lang('em') ?>"
                                        v-model="user.email" @focusout="validateEmail">
                                    <label for="Email">
                                        <?= lang('em') ?>
                                        <span class="text-danger">*</span>
                                        <span v-if="emailexist" style="color:red">{{emailexist}}</span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group mt-4 mt-md-1 col-md-6">
                                <div class="p-label">
                                    <input type="email" class="form-control" id="EmailConfirm"
                                        placeholder="<?= lang('email_confirm') ?>" v-model="user.emailconfirm"
                                        @focusout="emailconfirm">
                                    <label for="EmailConfirm"><?= lang('email_confirm') ?><span v-if="emailmatch"
                                            style="color:red">
                                            {{emailmatch}} </span></label>
                                </div>
                            </div>
                            <!-- html tooltip -->
                            <!-- mailreconfirm -->
                            <div v-if="emailexist" class="form-row tooltiped w-100">
                                <div class="form-group mt-4 mt-md-1 col">
                                    <i class="fas fa-times-circle fa-lg close"></i>
                                    <span v-if="emailexist" class="d-block text-white font-weight-bold">{{ user.email }}
                                        <span class="mcolor"> <?= lang('alreadyexist') ?> </span></span>
                                    <p class="d-block py-2 font-weight-bold"><?= lang('confirm2ready') ?></p>
                                    <p v-if="!mailtokensent" class="text-center">
                                        <button type="button" class="btn ncbtn btn-sm"
                                            @click="confirmMailAcc"><?= lang('conf') ?></button>
                                        <a data-toggle="modal" href="#" ref="SignInModal" class="btn cbtn btn-sm"
                                            data-target="#signin"><?= lang('login') ?></a>
                                    </p>
                                    <div v-else class="form-row mt-1">
                                        <p><?= lang('confirm2sent') ?></p>
                                        <div class="form-group  mt-4 mt-md-1 col-md-6 col-sm">
                                            <input class="form-control" type="text" v-model="mailtoken" />
                                        </div>
                                        <div class="form-group  mt-4 mt-md-1 col-md-4 col-sm">
                                            <button class="btn ncbtn bg-gcolor btn-sm" type="button"
                                                @click="confirmMailAccToken"><?= lang('verifyCode') ?></button>
                                        </div>
                                        <span><?= lang('nocode') ?> <a class="clink" @click="confirmMailAcc">,
                                                <?= lang('resend') ?></a></span>
                                    </div>
                                </div>
                            </div>
                            <!-- /html tooltip -->
                        </div><!-- email row -->

                        <div class="form-row mt-4">
                            <div class="form-group  mt-4 mt-md-1 col-md-4">
                                <label for="phone"><?= lang('phone_code') ?><span class="text-danger">*</span></label>
                                <select id="phonecode" class="form-control" v-model="user.uphonecode">
                                    <?= phonecodes() ?>
                                </select>
                            </div>
                            <div class="form-group mt-4 mt-md-1 col-md-6">
                                <label for="phone"><?= lang('phone_number') ?><span class="text-danger">*</span>
                                    <small v-if="phoneerror" class="text-danger text-break">{{phoneerror}}</small>
                                    <small v-if="phoneexist" class="text-danger text-break">{{phoneexist}}</small>
                                </label>
                                <input type="tel" pattern="[0-9]{10}" class="form-control" id="phone"
                                    placeholder="<?= lang('phone_number') ?>" v-model="user.phone"
                                    @focusout="validatePhone">
                            </div>
                        </div>

                        <div class="form-group mt-4 mt-md-1">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="forwho" id="forme" value="0"
                                    v-model="forwho">
                                <label class="form-check-label" for="forme"><?= lang('self_reserve') ?></label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="forwho" id="forsomeoneelse" value="1"
                                    v-model="forwho">
                                <label class="form-check-label" for="forsomeoneelse"><?= lang('else_reserve') ?></label>
                            </div>
                        </div>
                        <div class="form-row" v-if="forwho == 1">
                            <div class="form-group mt-4 mt-md-1 col-md-6">
                                <label for="GuestName"><?= lang('guest_name') ?></label>
                                <input type="text" class="form-control" id="GuestName" name="guestname"
                                    v-model="user.gname">
                            </div>
                            <div class="form-group mt-4 mt-md-1 col-md-6">
                                <label for="GuestEmail"><?= lang('guest_email') ?><span v-if="invalidgemail"
                                        style="color:red">
                                        {{invalidgemail}} </span></label>
                                <input type="email" name="guestemail" class="form-control" id="GuestEmail"
                                    v-model="user.gemail">
                            </div>
                            <div v-if="gdata" class="alert alert-danger">{{ gdata }}</div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row mt-2">
                    <div class="col">
                        <h4> <?= lang('room_details') ?></h4>
                        <?php foreach ($wallet['items'] as $idx => $room) : if (!empty($room)) : ?>
                        <div class="bookedroom p-3">
                            <h5 class="font-weight-bold">
                                <?= $room['rname'] . ' <small>(' . $room['meal'] . ')</small>'; ?></h5>
                            <span>
                                <span class="mcolor "> <?= str_replace('<br>', '', $room['cancelline']);   ?> <i
                                        class='fas fa-exclamation-circle' data-toggle='tooltip' data-placement='bottom'
                                        title='<?= $room['cancelref'] ?>' data-html='true' @click='ttip'></i></span>
                            </span>
                            <?php if (false) : ?>
                            <ul class="list-inline">
                                <li class="list-inline-item"> <i class="fa fa-bed mcolor"></i> amenities 1</li>
                                <li class="list-inline-item"> <i class="fa fa-bed mcolor"></i> amenities 2</li>
                                <li class="list-inline-item"> <i class="fa fa-bed mcolor"></i> amenities 3</li>
                                <li class="list-inline-item"> <i class="fa fa-bed mcolor"></i> amenities 4</li>
                                <li class="list-inline-item"> <i class="fa fa-bed mcolor"></i> amenities 5</li>
                            </ul>
                            <?php endif; ?>
                            <br />

                            <div class="form-row mt-5">
                                <div class="form-group mt-4 mt-md-1 col-md-6">
                                    <div class="p-label">
                                        <input id="gname<?= $idx + 1 ?>" class="form-control" type="text"
                                            name="guestname" placeholder="<?= lang('guest_name') ?>"
                                            v-model="rooms[<?= $idx ?>].gname" />
                                        <label for="gname<?= $idx + 1 ?>"><?= lang('guest_name') ?> <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="form-group mt-4 mt-md-1 col-md-6">
                                    <div class="p-label">
                                        <input id="gemail<?= $idx + 1 ?>" type="text" class="form-control"
                                            name="guestemail"
                                            placeholder="<?= lang('guest_email') . ' ' . lang('optional') ?> "
                                            v-model="rooms[<?= $idx ?>].gemail">
                                        <label id="gemail<?= $idx + 1 ?>"><?= lang('guest_email') ?><small
                                                class="text-success"> <?= lang('optional') ?> </small></label>

                                    </div>
                                </div>
                            </div>

                            <div v-if="rooms[<?= $idx ?>].allowsBeddingPreferencies" class="form-row">
                                <div class="form-group mt-4 mt-md-1 col-md-6">
                                    <label for="roombedding<?= $idx + 1 ?>"><?= lang('prefbed') ?></label>
                                    <select id="roombedding<?= $idx + 1 ?>" class="form-control"
                                        v-model="rooms[<?= $idx ?>].bedpref">
                                        <option value="0"><?= lang('nopref') ?></option>
                                        <option value="1"><?= lang('KNG') ?></option>
                                        <option value="2"><?= lang('Queen') ?></option>
                                        <option value="3"><?= lang('TWN') ?></option>
                                    </select>
                                </div>
                            </div>

                            <div v-if="rooms[<?= $idx ?>].allowsSpecialRequests" class="form-row">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="spr<?= $idx + 1 ?>"
                                        v-model="rooms[<?= $idx ?>].spr" />
                                    <label class="form-check-label"
                                        for="spr<?= $idx + 1 ?>"><?= lang('SpReqs') ?></label>
                                </div>
                            </div>

                            <div v-if="rooms[<?= $idx ?>].spr" class="form-row">
                                <hr>
                                <div class="form-group mt-4 mt-md-1 col">
                                    <div class="form-check">

                                        <?php foreach ($sp_requests as $spr) : ?>
                                        <input class="form-check-input" type="checkbox"
                                            id="sp<?= $spr->specialreq_id . $idx ?>"
                                            value="<?= $spr->specialreq_code ?>"
                                            v-model="rooms[<?= $idx ?>].sp_requests">
                                        <label class="form-check-label"
                                            for="sp<?= $spr->specialreq_id . $idx ?>"><?= $spr->specialreq ?></label>
                                        <br />
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <hr>
                            </div>
                        </div>
                        <?php endif;
                        endforeach; ?>
                    </div>
                </div>

                <div class="row p-4">
                    <div class="col">
                        <?php if ($p_methods) : // old payment methods
                        ?>
                        <h4 class="text-center"><span><?= lang('p_methods') ?><span></h4>
                        <div class="form-group mt-4 mt-md-1">
                            <select class="form-control" id="sel1" name="pmethod" v-model="pmethod">
                                <option value="0"><?= lang('select_methods') ?></option>
                                <?php foreach ($p_methods as $pm) : ?>
                                <option value="<?= $pm->ID ?>"><?= $pm->Pay_m_name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="button" @click="bk" class="btn btn-block ebtn mt-2 p-2"
                            :disabled="rsvd" :><?= lang('check_out') ?></button>
                        <?php endif; ?>
                        <?php if (true) : ?>
                        <h4 class="text-center"><span><?= lang('p_methods') ?><span></h4>
                        <button type="button" @click="bk" class="btn btn-block ebtn mt-2 p-3 font-weight-bold"
                        :disabled="rsvd"> <?= lang('check_out') ?></button>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
<script>
var rwrooms = <?= json_encode(unserialize($this->session->wallet)) ?>;
</script>
<script>
document.readyState
Vue.config.devtools = true;
var chkout = new Vue({
    el: "#checkout",
    data() {
        return {
            forwho: 0,
            wbs: rwrooms,
            discavailable: false,
            pmethod: 0,
            emailmatch: '',
            emailexist: '',
            phoneexist: '',
            phoneerror: '',
            mailtokensent: '',
            mailtoken: '',
            ulogged: '',
            gdata: '',
            fdata: '',
            invalidgemail: '',
            ulg: false,
            rsv: true,
            user: {
                fname: '',
                fmname: '',
                email: '',
                emailconfirm: '',
                uphonecode: 966,
                phone: '',
                gname: '',
                gemail: '',
            },
            rooms: rwrooms.items,
        }
    },
    methods: {
        bk: async function() {
            var redirect = '';
            var params = new URLSearchParams();
            params.append('csrftkn', dataParams.csrftkn);
            if (this.ulg) {
                params.append('pmethod', this.pmethod);
                params.append('rooms', JSON.stringify(this.rooms));

            } else {
                params.append('fname', this.user.fname);
                params.append('fmname', this.user.fmname);
                params.append('email', this.user.email);
                params.append('emailconfirm', this.user.emailconfirm);
                params.append('phone', this.user.uphonecode + this.user.phone.slice(1));
                params.append('rooms', JSON.stringify(this.rooms));
                if (!this.user.fname.length || !this.user.fmname.length || !this.user.email.length || !this
                    .user.emailconfirm.length ||
                    !this.user.phone.length|| !this.user.gname.length || !this.user.gemail.length) {
                    this.fdata = 'Please Review Your Details And Enter Guest Details'
                    return;
                }
                if (this.user.email != this.user.emailconfirm) {
                    this.fdata = 'please Review your Details';
                    return;
                }
                if (this.forwho == 1) {
                    if (this.user.gname && this.user.gemail) {
                        params.append('gname', this.user.gname);
                        params.append('gemail', this.user.gemail);
                    } else {
                        this.fdata = 'please complete Guest Details';
                        return;
                    }
                    params.append('pmethod', this.pmethod);
                } else {
                    params.append('pmethod', this.pmethod);
                }
            }
            await fetch(dataParams.surl + 'reservation/checkout', {
                method: 'POST',
                body: params,
            }).then((resp) => resp.json()).then((res) => {
                console.log('checkout');
                console.log(dataParams.surl + 'reservation/checkout');
                console.log(res);
                if (res.result) {
                    window.location = res.rd;
                    window.location.href = res.rd;
                    redirect = res.rd;

                } else {
                    this.fdata = res.error;
                }


            }).catch((error) => {
                console.log(error);
            });

            // window.location = redirect;
            // window.location.href = redirect;
        },
        emailconfirm: function() {
            if (this.user.email != this.user.emailconfirm) {
                this.emailmatch = 'email does not match';
                this.rsv = true;
                return false;
            } else {
                this.emailmatch = '';
                this.rsv = false;
                return true;
            }
        },
        validateEmail: async function(v) {
            if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(v.target.value)) {
                //check if email exists
                var params = new URLSearchParams();
                params.append('csrftkn', dataParams.csrftkn);
                params.append('mail', v.target.value);
                await fetch(dataParams.surl + 'user/emailexist', {
                    method: 'POST',
                    body: params,
                }).then(res => res.json()).then(data => {
                    if (data.exist) {
                        this.emailexist = 'this email already exist';
                        this.rsv = true;
                    } else {
                        this.emailexist = '';
                    }
                });
            } else {
                this.rsv = true;
            }
        },
        confirmMailAcc: async function(v) {
            if (this.user.email) {
                //this.mailtokensent
                //check if email exists
                var params = new URLSearchParams();
                params.append('csrftkn', dataParams.csrftkn);
                params.append('mail', this.user.email);
                await fetch(dataParams.surl + 'user/emailconfirmaccess', {
                    method: 'POST',
                    body: params,
                }).then(res => res.json()).then(data => {
                    if (data.sent) {
                        this.mailtokensent = true;
                        this.ulogged = "verify code sent to your email";
                    } else {
                        this.mailtokensent = false;
                    }
                });
            } else {
                console.log(' nothing acccccc');
            }
        },
        confirmMailAccToken: async function() {
            if (this.mailtoken && this.mailtoken.length) {
                //this.mailtokensent
                //check if email exists
                var params = new URLSearchParams();
                params.append('csrftkn', dataParams.csrftkn);
                params.append('mail', this.user.email);
                params.append('token', this.mailtoken);
                await fetch(dataParams.surl + 'user/emailtokenconfirm', {
                    method: 'POST',
                    body: params,
                }).then(res => res.json()).then(data => {
                    if (data.ulogged) {
                        this.ulogged = "successfully Recovered";
                        setTimeout(window.location.reload(), 1500);
                    } else {
                        this.ulogged = data.error;
                    }
                });
            } else {
                this.rsv = true;
            }
        },
        validatePhone: async function(val) {
            if (!val.target.value.match(/^\d{10}$/)) {
                this.phoneerror = 'only 0xxxxxxxxx format';
                this.rsv = true;
                val.target.focus();
            } else {
                this.phoneerror = '';
                var params = new URLSearchParams();
                params.append('csrftkn', dataParams.csrftkn);
                params.append('phone', this.user.uphonecode + val.target.value.slice(1));
                await fetch(dataParams.surl + 'user/phoneexist', {
                    method: 'POST',
                    body: params,
                }).then(res => res.json()).then(data => {
                    if (data.exist) {
                        this.phoneexist = 'this phone already exist';
                        this.rsv = true;
                    } else {
                        this.phoneexist = '';
                        this.rsv = true;
                    }
                });
            }
        },
        uloggedin: async function(v) {
            await fetch(dataParams.surl + 'home/ulogged').then((resp) => resp.json()).then((res) => {
                if (res.status) {
                    this.ulg = true;
                    this.rsv = false;
                }
            }).catch((error) => {
                console.log(error);
            });
        },
        ttip: function(e) {
            $(e.target).tooltip('show');
        },
       
    },
    computed: {
        rsvd: function() {
            if (this.ulg) {
                return false;

            } else {
                if (!this.user.fname.length || !this.user.fmname.length || !this.user.email.length || !this
                    .user.emailconfirm.length || !this.user.phone.length || this.phoneerror || this
                    .emailexist || this.phoneexist || !this.user.gemail.length || !this.user.gname.length ) {
                    this.fdata = 'please complete your Details';
                    return true;
                } else {
                    return false;
                }
            }
        },
    },
    mounted() {
        this.forwho = 1;
        this.uloggedin();
    },
});
</script>