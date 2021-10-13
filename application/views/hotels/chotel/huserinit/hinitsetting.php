    <style>
.commissioncontainer {
    border: 1px solid green;
    padding: 5px;
    border-radius: 10px;
}

/* The slider itself */
.commission {
    -webkit-appearance: none;
    /* Override default CSS styles */
    appearance: none;
    width: 100%;
    /* Full-width */
    height: 25px;
    /* Specified height */
    background: #d3d3d3;
    /* Grey background */
    outline: none;
    /* Remove outline */
    opacity: 0.7;
    /* Set transparency (for mouse-over effects on hover) */
    -webkit-transition: .2s;
    /* 0.2 seconds transition on hover */
    transition: opacity .2s;
}

/* Mouse-over effects */
.commission:hover {
    opacity: 1;
    /* Fully shown on mouse-over */
}

/* The slider handle (use -webkit- (Chrome, Opera, Safari, Edge) and -moz- (Firefox) to override default look) */
.commission::-webkit-slider-thumb {
    -webkit-appearance: none;
    /* Override default look */
    appearance: none;
    width: 25px;
    /* Set a specific slider handle width */
    height: 25px;
    /* Slider handle height */
    background: #4CAF50;
    /* Green background */
    cursor: pointer;
    /* Cursor on hover */
}

.commission::-moz-range-thumb {
    width: 25px;
    /* Set a specific slider handle width */
    height: 25px;
    /* Slider handle height */
    background: #4CAF50;
    /* Green background */
    cursor: pointer;
    /* Cursor on hover */
}
    </style>
    <div id="hsettings">
        <div class="container">
            <?php echo $this->session->flashdata('settin_error');?>
            <div class="row">
                <div class="col">
                    <p> use this page to set your policies and reservation settings </p>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="card card-info p-3">
                        <div class="card-header">
                            <h3 class="text-center">Complete your Property Setting</h3>
                        </div>
                        <div class="card-body">

                        <form action="<?=site_url('chotel/huserinit/hotelsetting/'.$hotelid)?>" method="post">
                        <input type="hidden" name="token" value="<?php echo uniqid('tok'); ?>" />
                                <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" >
                                <input type="hidden" name="hoteluser"
                                    value="<?=$this->session->userdata('H_User_ID')?>" />
                                <input type="hidden" name="token" value="<?=uniqid('1315664').$hotelid?>" />
                                <input type="hidden" name="hotelid" value="<?=$hotelid?>" />
                                <div class="row">
                                    <div class="col">
                                        <div class="comnote">
                                            <h4>What is The Commission Rate That you want ?</h4>
                                            <h6>Note that the higher your ratio is , the more you will be visible in
                                                search related to you</h6>
                                        </div>
                                        <h4>Current Commission : {{ currentcommission }} %</h4>
                                        <span>To increase you commision rate move the index</span>
                                        <div class="form-group row">
                                            <div class="col-md-6 col-sm">
                                                <div class="commissioncontainer">
                                                    <input class="form-control commission" type="range"
                                                        name="commission" min="12" max="30" :value="currentcommission"
                                                        v-model="currentcommission">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="policies w-100">
                                        <h4> What is you Policies ? </h4>
                                        <h6> Select it carefully this will be applied on you Reservations </h6>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Polices</label>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="b2c" name="b2c"
                                                    value="1">
                                                <label class="form-check-label" for="b2c">Allow B2C</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="b2b" name="b2b"
                                                    value="1">
                                                <label class="form-check-label" for="b2b">Allow b2b</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="policies w-100">
                                        <h4>Agreement and Terms</h4>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <?=form_error('agreement')?>
                                                <label>I read all terms and conditions that mentioned here <a
                                                        href="#">Agreement</a> </label>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="agree"
                                                        name="agreement" value="1">
                                                    <label class="form-check-label" for="agree">I Agree</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6 mx-auto">
                                        <div class="text-center">
                                            <input type="submit" class="btn btn-block btn-success" value="Confirms">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
Vue.config.devtools = true;
var hsetting = new Vue({
    el: "#hsettings",
    data: {
        currentcommission: 12,

    },
    watch: {
        arhotelname: function() {
            if (this.arhotelname.length > 0) {
                this.checkarabic();
            } else {
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
            axios.get(`/chotel/huserinit/toarabic?name=` + this.arhotelname).then(function(res) {
                //console.log(res.data);
                if (res.data.isarabic) {
                    uinit.notarabic = false;
                    uinit.toenglish();
                } else {
                    uinit.notarabic = true;
                }
            }).catch(function(error) {
                console.log(error);
            });
        },
        toenglish: function() {
            axios.get(`/chotel/huserinit/toenglish?name=` + this.arhotelname).then(function(res) {
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