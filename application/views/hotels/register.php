<div class="container-fluid page_banner">

</div>
<div id="UserForm" class="container home_container p-3 user-reg">
    <div class="row m-1 reg-head p-4">
        <div class="col col-md-4">
            <i class="fa fa-user"></i>
        </div>
        <div class="col col-md-8 head-title">
            <h3 class="mcolor">User</h3>
            <h4>REGISTRATI<span class="mcolor">ON</span></h4>
        </div>
    </div>
    <?=form_error();?>
    <div class="row m-1 mt-3">
        <div class="col p-md-3 reg-form">
            <?= form_open('User/register', 'class="form"') ?>
            <div class="row">
                <div class="col-12 col-md-6">
                    <label for="fullname"><?= lang('fullname') ?></label>
                    <input id="fullname" type="text" class="form-control" name="fullname" placeholder="<?= lang('enter') . ' ' . lang('fullname') ?>" id='F_name' v-model="fullname" value="fff" />
                    <span class="text-danger"> <?php echo form_error('fullname'); ?></span>

                </div>
                <div class="col-12 col-md-6">
                    <label for="username"><?= lang('uname') ?></label>
                    <input id="username" type="text" class="form-control" placeholder="<?= lang('enter') . ' ' . lang('uname') ?>" name="name" v-model="username" />
                    <span class="text-danger"><?php echo form_error('name'); ?></span>
                    <span class="text-danger"> {{ username_err_message }}</span>
                </div>
            </div>
            <div class="row p-1">
                <div class="col-12 col-md-6">
                    <label for="email"><?= lang('em') ?></label>
                    <input id="email" type="email" name='email' class="form-control" placeholder="<?= lang('enter') . ' ' . lang('em') ?>" v-model="email">
                    <span class="text-danger"> <?php echo form_error('email'); ?></span>
                    <span class="text-danger"> {{email_err_message }}</span>
                </div>
                <div class="col-12 col-md-6">
                    <label for="phone"><?= lang('contact') ?></label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <select id="phone" name="phonecode" class="form-control">
                                <?= phonecodes() ?>
                            </select>
                        </div>
                        <input type="text" class="form-control mx-2" v-model="phone" name='phone' placeholder="<?= lang('enter') . ' ' . lang('contact') ?>">
                    </div>
                    <span class="text-danger"> <?php echo form_error('phone'); ?></span>
                    <span class="text-danger">{{phone_err_message}}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-6">
                    <label for="password"><?= lang('pass') ?></label>
                    <input id="password" class="form-control position-relative" type="password" name="password" ref="passfiled" placeholder="<?= lang('enter') . ' ' . lang('pass') ?>">
                    <i class="fa fa-eye icon position-absolute showpassfield" data-toggle="tooltip" data-placement="top" title="Show Password" @click="showpass('passfiled')"></i>
                    <span class="text-danger"> <?php echo form_error('password'); ?></span>
                </div>
                <div class="col-12 col-md-6">
                    <label for="passwordconfirm"><?=lang('conf') ?>  <?= lang('pass') ?></label>
                    <input id="passwordconfirm" class="form-control position-relative" ref="cpassfiled" type="password" name='pass_conf'  placeholder="<?= lang('enter') . ' ' . lang('pass') ?>">
                    <i class="fa fa-eye icon position-absolute showpassfield" data-toggle="tooltip" data-placement="top" title="Show Password" @click="showpass('cpassfiled')"></i>
                    <span class="text-danger"> <?php echo form_error('pass_conf'); ?></span>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-12 col-md-6">
                    <label for="country"><?= lang('country') ?></label>
                    <select id="country" class="form-control" name="country" v-model="selectedcountry">
                        <?php foreach ($countries as $country) : ?>
                            <option value="<?= $country->country_code ?>" data-name="<?= $country->country_name ?>"><?= $country->country_name ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span class="text-danger"> <?php echo form_error('country'); ?></span>
                </div>
                <div class="col-12 col-md-6">
                    <label for="cities"><?= lang('city') ?></label>
                    <select id="cities" class="form-control" ref="scity" name="city" v-model="selectedcity">
                        <option v-if="cities" v-for="(city, key) in cities" :key="key" :data-name="city.city_name" :value="city.city_code">
                            {{city.city_name}}
                        </option>
                        <option v-else data-name="no_city" value="0">No Cities</option>
                    </select>
                    <span class="text-danger"> <?php echo form_error('city'); ?></span>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col">
                    <label for="address"><?= lang('adr') ?></label>
                    <input id="address" type="text" class="form-control" name="address" value="">
                    <span class="text-center text-danger"><?php echo form_error('adress'); ?></span>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="text-center">
                        <button type="submit" class="bg2 ebtn btn-lg" :disabled="validation_error"><i class="fa fa-user-plus"></i>
                            <?= lang('reg') ?></button>
                    </div>
                </div>
            </div>
            <?= form_close() ?>
        </div>
    </div>
</div>

<script>
    <?php // form data
    $formData = [
        'name' => set_value('name'),
        'password' => set_value('password'),
        'fullname' => set_value('fullname'),
        'email' => set_value('email'),
        'adrress' => set_value('adrress'),
        'phone' => set_value('phone'),
        'country' => set_value('country') ? set_value('country') : 4,
        'city' => set_value('city') ? set_value('country') : 164,
    ];
    echo 'var formData=' . json_encode($formData) . ';';
    ?>
</script>
<script>
    var srl = '<?= site_url() ?>';
    Vue.config.devtools = true;
    var regForm = new Vue({
        el: "#UserForm",
        data: {
            username: formData.name,
            fullname: formData.fullname,
            phone: formData.phone,
            email: formData.email,
            email_err_message: '',
            validation_error: false,
            username_err_message: '',
            phone_err_message: '',
            cities: [],
            selectedcountry: formData.country,
            selectedcity: formData.city,
            form: {

            },
            formErrors: {},
            message: '',
            updated: false,
        },
        watch: {
            username: function() {
                this.CheckUsername(this.username);
            },
            email: function() {
                this.CheckEmail(this.email);
            },
            phone: function(){
                this.CheckPhone(this.phone);
            },
            selectedcountry: function(val) {
                this.get_cites(val);
            }
        },
        methods: {
            CheckUsername: function(val) {
                if (val.length < 6) {
                    this.validation_error = true;
                    this.username_err_message = dataParams.trkeys.usererror;
                } else {
                    this.validation_error = false;
                    this.username_err_message = "";
                }

                if (val.length == 0) {
                    this.validation_error = false;
                    this.username_err_message = "";
                }
                
                axios.get('/user/user_check?name=' + val).then(function(res) {
                    if (res.data.status) {
                         regForm.validation_error = true;
                        regForm.username_err_message = dataParams.trkeys.usertaken;
                        
                    }
                }).catch(function(error) {
                    error;
                });
            },
            CheckEmail: function(val) {
                
                axios.get('/user/email_check?email=' + val).then(function(res) {
                    if (res.data.status) {
                        regForm.validation_error = true;
                        regForm.email_err_message = dataParams.trkeys.emailtaken;
                    }
                }).catch(function(error) {
                    error;
                });


                if (val.length == 0) {
                    this.validation_error = false;
                    this.email_err_message = "";
                }
            },

            CheckPhone: function(val){
                var params = new URLSearchParams();
                params.append('csrftkn', dataParams.csrftkn);
                params.append('phone', val);
                axios.post('/en/user/phoneexist', params).then(function(res){

                    if(res.data.exist){
                        regForm.validation_error = true;
                        regForm.phone_err_message = dataParams.trkeys.phonetaken;
                   
                    }else{
                        console.log(error);
                    }

                }).catch(function(error){
                    console.log(error);

                });

                if(val.length == 0){
                    this.validation_error = false;
                    this.phone_err_message = "";
                }

            },

            get_cites: function(id) {
                fetch('/user/get_cities/' + id).then((res) => res.json()).then(data => {
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
            // update: function() {
            //     form = document.getElementById('profform');
            //     pform = new FormData(form);
            //     var formErrors = {};
            //     for (el of form.elements) {
            //         if (!el.checkValidity()) {
            //             if (el.validity.valueMissing) {
            //                 formErrors[el.name] = el.name + ' Required';
            //             } else if (el.validity.patternMismatch) {
            //                 formErrors[el.name] = el.name + ' Should be 8 chars or more';
            //             }
            //             el.focus();
            //         }
            //     }
            //     if (form.elements.confirmpassword.value.length && form.elements.password.value != form.elements.confirmpassword.value) {
            //         formErrors['confirmpassword'] = "password does not match";
            //     }
            //     if (Object.keys(formErrors).length > 0) {
            //         this.formErrors = formErrors;
            //         return;
            //     }
            //     fetch('/en/user/update', {
            //         method: 'POST',
            //         body: pform,
            //     }).then(res => res.json()).then(data => {
            //         if (data.result) {
            //             this.message = data.message;
            //             this.updated = true;
            //             setTimeout(() => this.message = '', 3000);
            //         } else {
            //             this.message = data.message;
            //         }
            //     });
            // },
            // InsertData: async function() {
            //     var vdata = new FormData();
            //     vdata.append('name', this.username);
            //     vdata.append('fullname', this.fullname);
            //     vdata.append('email', this.email);

            //     axios.post(srl+'/user/registertest', vdata).then(function(res) {
            //         if (res.status) {
            //             console.log(res);
            //         } else {
            //             console.log("Errrroooorrrr Yaaaaa Ziffftttiii");
            //         }

            //     })

            // }

        },
        computed: {

        },
        mounted() {
            fetch('/user/get_cities/' + this.selectedcountry).then((res) => res.json()).then(data => {
                if (data.result) {
                    this.cities = data.cities;
                } else {
                    console.log('getting cities error ' + data.error);
                }
            });
        },
    })
</script>