<div class="container-fluid b2b_banner m-0 p-0">

</div>
<div class="container home_container b2b-reg mt-3 p-4">
    <div class="row m-1 reg-head p-4">
        <div class="col col-md-4 pt-2">
            <i class="far fa-building mx-3" style="color:#ffff; font-size:5vw; position:relative">
                <i class="fa fa-coins"
                    style="color:#e6a423; font-size:3vw; position:absolute; right: -1vw;bottom: -0.5vw"></i>
            </i>
        </div>
        <div class="col col-md-8 head-title justify-content-center">
            <h3 class="m-auto"><span class="mcolor">B</span>2<span class="mcolor">B</span></h3>
            <h4 class="m-auto">REGISTRATI<span class="mcolor">ON</span></h4>
        </div>
    </div>
    <div class="row p-1">
        <div class="col">
            <?php echo form_open("b2b/register", ["class" => "form reg-form mt-4 p-3", "id" => "B2bForm"]); ?>
            <div class="row">
                <div class="col-xl-6 col-md-12">
                    <div class="form-group mx-auto">
                        <label><?=lang('first_name')?></label>
                        <input type="text" class="form-control bg-grey" 
                            name="first_name" />
                        <?= form_error("first_name") ?>
                    </div>
                </div>
                <div class="col-xl-6 col-md-12">
                    <div class="form-group mx-auto">
                        <label><?=lang("last_name")?></label>
                        <input type="text" class="form-control"  name="last_name" />
                        <?= form_error("last_name") ?>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-xl-6 col-md-12">
                    <div class="form-group mx-auto">
                        <label><?=lang("companyname")?></label>
                        <input type="text" class="form-control"
                            name="company_name" v-model="Company_Name" />
                        <?= form_error("company_name") ?>
                        <p class="text-danger">{{ Name_Error }}</p>
                    </div>

                </div>
                <div class="col-xl-6 col-md-12">
                    <div class="form-group mx-auto">
                        <label for="country"><?= lang('country') ?></label>
                        <select id="country" class="form-control" name="country" >
                            <?php foreach ($countries as $country) : ?>
                            <option value="<?= $country->country_code ?>" data-name="<?= $country->country_name ?>">
                                <?= $country->country_name ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="text-danger"> <?php echo form_error('country'); ?></span>
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-xl-6 col-md-12">
                    <div class="form-group mx-auto">
                        <label><?=lang("mobilenumber")?></label>
                        <div class="row">
                            <div class="col-md-5">
                                <select class="form-control" name="country_code">
                                    <?= phonecodes() ?>
                                </select>

                            </div>
                            <div class="col-md-7">
                                <input type="text" name="mobile_number" class="form-control"
                                   />
                                <?= form_error("mobile_number") ?>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-xl-6 col-md-12">
                    <div class="form-group mx-auto">
                        <label><?=lang("phone_number")?></label>
                        <input type="text" class="form-control" name="office_number"
                            />
                        <?= form_error("office_number") ?>
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-xl-6 col-md-12">
                    <div class="form-group mx-auto">
                        <label><?=lang("em")?></label>
                        <input type="text" name="company_email" class="form-control" 
                            v-model="email" />
                        <?= form_error("company_email") ?>
                        <p class="text-danger">{{ Email_Error }}</p>

                    </div>
                </div>

                <div class="col-xl-6 col-md-12">
                    <div class="form-group mx-auto">
                        <label><?=lang("iban")?></label>
                        <input type="text" name="iban" class="form-control" 
                            v-model="Iban" />
                        <?= form_error("iban") ?>
                        <p class="text-danger">{{ Iban_Error }}</p>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-xl-6 col-md-12">
                    <div class="form-group mx-auto">
                        <label><?=lang("pass")?></label>
                        <input type="text" name="password" class="form-control"
                             />
                        <?= form_error("password") ?>
                    </div>
                </div>

                <div class="col-xl-6 col-md-12">
                    <div class="form-group mx-auto">
                        <label><?=lang("passConf")?></label>
                        <input type="text" name="password_confirm" class="form-control"
                            />
                        <?= form_error("password_confirm") ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="form-group mx-auto">
                        <label><?=lang("vat_num")?></label>
                        <input type="text" class="form-control"  name="vat_number" />
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="form-group mx-auto">
                        <label><?=lang("hajjlicense")?></label> <span><?=lang("optional")?></span>
                        <input type="text" name="hajj_license" class="form-control"
                            />
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="form-group mx-auto">
                        <label><?=lang("regnumber")?></label>
                        <input type="text" name="reg_number" class="form-control"
                             v-model="Reg_Number" />
                        <?= form_error("reg_number") ?>
                        <p class="text-danger">{{ Reg_Error }}</p>
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col text-center">
                    <button class="btn btn-lg ebtn" type="submit" :disabled="validation"> <i
                            class="fa fa-pen-alt"></i><?=lang("reg")?> </button>
                </div>
            </div>
            <?= form_close() ?>
        </div>
    </div>
</div>

<script>
Vue.config.devtools = true;
var B2b = new Vue({
    el: "#B2bForm",
    data: {
        email: "",
        Company_Name: "",
        Iban: "",
        Reg_Number: "",

        Email_Error: "",
        Name_Error: "",
        Iban_Error: "",
        Reg_Error: "",
        validation: false
    },
    watch: {
        email: function() {
            this.CheckEmail(this.email);
        },
        Company_Name: function() {
            this.CheckName(this.Company_Name);
        },
        Iban: function() {
            this.CheckIban(this.Iban);
        },
        Reg_Number: function() {
            this.CheckRegistration(this.Reg_Number);
        }
    },
    methods: {
        CheckEmail: function(val) {
            if (val.length == 0) {
                this.Email_Error = "";

            }
            axios.get('/b2b/register/email_check?email=' + val).then(function(res) {
                if (res.data.error) {
                    B2b.validation = true;
                    B2b.Email_Error = res.data.error;
                }
            }).catch(function() {
                error;
            });
        },
        CheckName: function(val) {
            if (val.length == 0) {
                this.Name_Error = "";
            }
            axios.get("/b2b/register/name_check?name=" + val).then(function(res) {
                if (res.data.error) {
                    B2b.validation = true;
                    B2b.Name_Error = res.data.error;
                }
            }).catch(function() {
                error;
            })
        },
        CheckIban: function(val) {
            if (val.length == 0) {
                this.Iban_Error = "";
            }
            axios.get("/b2b/register/iban_check?iban=" + val).then(function(res) {
                if (res.data.error) {
                    B2b.validation = true;
                    B2b.Iban_Error = res.data.error;
                }
            }).catch(function() {
                error;
            })
        },
        CheckRegistration: function(val) {
            if (val.length == 0) {
                this.Reg_Error = "";
            }
            axios.get("/b2b/register/registration_check?reg_no=" + val).then(function(res) {
                if (res.data.error) {
                    B2b.validation = true;
                    B2b.Reg_Error = res.data.error;
                }
            }).catch(function() {
                error;
            })
        },
    }
})
</script>