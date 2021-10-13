<div id="userProfile" class="row m-1">
    <div v-if="updated" class="col">
        <p v-show="message" :class="'text-'+(updated?'success':'danger')">{{message}}</p>
    </div>
    <div v-else class="col">
        <!-- give a form to complete the data then reload with signed in state sending some feed back notifing him of where the profile is  -->
        <p>
            <button class="btn pbtn py-2" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                 <?=lang('completeProfile')?>
            </button>
        </p>
        <div class="collapse complete-profile" id="collapseExample">
            <div class="card card-body">
            <p><?=lang('completeProfileDesc')?></p>
                <?= form_open('user/update', ['id' => 'profform']) ?>

                <div class="row">
                    <div class="form-group col-6-md col-sm">
                        <label for="Fname"><?=lang('fullname')?></label>
                        <input id="Fname" type="text" class="form-control" placeholder="Full name" name="fullname" value="<?= $user->Public_UserFullName ?? '' ?>" readonly>
                    </div>
                    <div class="form-group col-6-md col-sm">
                        <label for="username"> <?=lang('uname')?> <small class="text-danger">*</small> <small class="text-danger" v-if="formErrors.username">{{ formErrors.username }}</small></label>
                        <input id="username" type="text" class="form-control" placeholder="User Name" name="username" value="<?= $user->Public_UserName ?? '' ?>" required>
                    </div>
                </div>
                <input type="hidden" name="uid" value="<?= $user->Public_User_ID ?>" />
                <div class="row">
                    <div class="form-group col-6-md col-sm">
                        <label for="password"><?=lang('pass')?> <small class="text-danger">*</small> <small class="text-danger" v-if="formErrors.password">{{ formErrors.password }}</small></label>
                        <input id="password" type="password" ref="passfiled" class="form-control position-relative" name="password" pattern=".{8,}" required>
                        <i class="fa fa-eye icon position-absolute" data-toggle="tooltip" data-placement="top" title="Show Password" @click="showpass('passfiled')" style="top: 43px; right: 30px;"></i>

                    </div>
                    <div class="form-group col-6-md col-sm">
                        <label for="passwordconfirm"> <?=lang('passConf')?> <small class="text-danger">*</small> <small class="text-danger" v-if="formErrors.confirmpassword">{{ formErrors.confirmpassword }}</small></label>
                        <input id="passwordconfirm" ref="cpassfiled" type="password" class="form-control position-relative" name="confirmpassword" pattern=".{8,}" required>
                        <i class="fa fa-eye icon position-absolute" data-toggle="tooltip" data-placement="top" title="Show Password" @click="showpass('cpassfiled')" style="top: 43px; right: 30px;"></i>

                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-6-md col-sm">
                        <label for="email"><?=lang('em')?></label>
                        <input id="email" type="text" class="form-control" placeholder="Email" name="email" readonly value="<?= $user->Public_UserEmail ?? '' ?>">
                    </div>
                    <div class="form-group col-6-md col-sm">
                        <label for="phone"><?=lang('phone_number')?></label>
                        <input id="phone" type="tel" class="form-control" placeholder="phone number" value="<?= $user->Public_UserPhone ?>" name="phone" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-6-md col-sm">
                        <label for="countries"><?=lang('country')?></label>
                        <select id="countries" class="form-control" ref="sCountry" name="country" @change="get_cites" v-model="selectedcountry">
                            <?php foreach ($countries as $country) : ?>
                                <option value="<?= $country->country_code ?>" data-name="<?= $country->country_name ?>"><?= $country->country_name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group col-sm col-md-6">
                        <label for="cities"><?=lang('city')?></label>
                        <select id="cities" class="form-control" ref="sCity" name="city" v-model="selectedcity">
                            <option v-if="cities" v-for="(city, key) in cities" :key="key" :data-name="city.city_name" :value="city.city_code">
                                {{city.city_name}}
                            </option>
                            <option v-else data-name="no_city" value="0">No Cities</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm col-md-8">
                        <label for="address"><?=lang('adr')?></label>
                        <input id="address" type="text" class="form-control" name="address" :value="addresscountry+' , '+ addresscity">
                    </div>
                    <div class="form-group col-sm col-md-4">
                        <button type="button" class="btn pbtn mt-4 w-100 text-bold" @click="update"><b><?=lang('conf')?></b></button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    Vue.config.devtools = true;

    var upProfile = new Vue({
        el: "#userProfile",
        data() {
            return {
                cities: [],
                selectedcountry: '4',
                selectedcity: '164',
                addresscountry: 'Saudi Arabia',
                addresscity: 'Makkah',
                form: {

                },
                formErrors: {},
                message: '',
                updated: false,
            }
        },
        methods: {
            get_cites: function(v) {
                var country = v.target.value;
                fetch('/user/get_cities/' + country).then((res) => res.json()).then(data => {
                    if (data.result) {
                        this.cities = data.cities;
                    } else {
                        console.log('getting cities error ' + data.error);
                    }
                });
            },
            showpass: function(val) {
                var attr = this.$refs[val].getAttribute('type');
                if (attr == 'password') {
                    this.$refs[val].setAttribute('type', 'text');
                } else {
                    this.$refs[val].setAttribute('type', 'password');
                }
            },
            update: function() {
                form = document.getElementById('profform');
                pform = new FormData(form);
                var formErrors = {};
                for (el of form.elements) {
                    if (!el.checkValidity()) {
                        if (el.validity.valueMissing) {
                            formErrors[el.name] = el.name + ' Required';
                        } else if (el.validity.patternMismatch) {
                            formErrors[el.name] = el.name + ' Should be 8 chars or more';
                        }
                        el.focus();
                    }
                }
                if (form.elements.confirmpassword.value.length && form.elements.password.value != form.elements.confirmpassword.value) {
                    formErrors['confirmpassword'] = "password does not match";
                }
                if (Object.keys(formErrors).length > 0) {
                    this.formErrors = formErrors;
                    return;
                }
                fetch('/en/user/update', {
                    method: 'POST',
                    body: pform,
                }).then(res => res.json()).then(data => {
                    if (data.result) {
                        this.message = data.message;
                        this.updated = true;
                        setTimeout(() => this.message = '', 3000);
                    } else {
                        this.message = data.message;
                    }
                });
            },
        },
        watch: {
            selectedcountry: function() {
                console.log(this.$refs);
                try {
                    this.addresscountry = this.$refs.sCountry.options[this.$refs.sCountry.options.selectedIndex].dataset.name;
                    console.log('city text' + this.addresscountry);
                } catch (err) {
                    console.log(err);
                }
            },
            selectedcity: function() {
                try {
                    this.addresscity = this.$refs.sCity.options[this.$refs.sCity.options.selectedIndex].dataset.name;
                    console.log('city text' + this.addresscountry);

                } catch (err) {
                    console.log(err);
                }
            },
        },
        computed: {

        },
        mounted() {
            fetch('/user/get_cities/4').then((res) => res.json()).then(data => {
                if (data.result) {
                    this.cities = data.cities;
                    this.selectedcity = '164';
                } else {
                    console.log('getting cities error ' + data.error);
                }
            });
        },
    });
</script>