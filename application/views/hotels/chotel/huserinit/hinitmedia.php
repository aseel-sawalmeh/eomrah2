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
    <div id="mediainit">
        <div class="container mt-5">
            <div class="row">
                <div class="col">
                    <?=$this->session->flashdata('media_error')?>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="card card-info p-3">
                        <div class="card-header">
                            <h3 class="text-center">Add Property Media</h3>
                        </div>
                        <div class="card-body">
                            <?php echo validation_errors(); ?>
                            <form @submit.prevent="req" action="<?=site_url("chotel/huserinit/hotelimgs")?>"
                                method="POST">
                                <input type="hidden" name="token" value="<?php echo uniqid('tok'); ?>" />
                                <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" >
                                <input type="hidden" name="hotelid" :value="hotelid" />
                                <div v-if="uploaderror" class="alert alert-danger">{{ uploaderror }}</div>
                                <div class="row">
                                    <label class="control-label">Hotel Photos</label>
                                    <div class="col-sm-4">
                                        
                                        <div class="form-group">
                                            <input id="files" class="form-control" type="file" name="hotelphotos[]" multiple />
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-success"> upload </button>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="hasPhotos" class="row">
                                    <div v-for="(mf, index) in imgfiles" class="col text-center">
                                        <a class="btn btn-danger" @click="delphoto(mf.Photo_ID, mf.Photo_Name)">remove
                                            image</a>
                                        <a class="btn btn-primary" @click="setdefphoto(mf.Photo_Name)">Make
                                            Default</a>
                                            <br>
                                        <div class="form-group mt-2">
                                            <img :src="himg(mf.Photo_Name)" id="img_url" alt="your image" width="200"
                                                height="200" />
                                        </div>
                                        <div class="alert alert-success" v-if="mainphoto == mf.Photo_Name">Default Image</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="text-center">
                                            <a v-if="hasPhotos" href="<?=site_url("chotel/huserinit/hotelsetting/$hotelid")?>" class="btn btn-block btn-success" >Confirm and Conplete</a>
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
var mediainit = new Vue({
    el: "#mediainit",
    data: {
        hotelid: <?=$hotelid?> ,
        imgfiles: null,
        inputfiles: [],
        hasPhotos: false,
        uploaderror: '',
        mainphoto: null,
    },
    watch: {
        arhotelname: function() {
            if (this.arhotelname.length > 0) {
                this.checkarabic();
            } else {
                this.notarabic = false;
            }
        },
        hasPhotos: function() {
            this.getphotos();
        }
    },
    methods: {
        getphotos: function() {
            axios.get('<?php echo site_url('chotel/huserinit/get_photos?hid=')?>' + this.hotelid).then(function(res) {
                console.log(res.data);
                if (res.data.result) {
                    mediainit.imgfiles = res.data.photos;
                    mediainit.hasPhotos = true;
                } else {
                    mediainit.imgfiles = res.data.photos;
                    mediainit.hasPhotos = false;
                }
            }).catch(function(error) {
                console.log(error);
            });
        },
        setdefphoto: function(photo) {
            //setdefphoto(mf.Photo_Name)
            axios.get('<?php echo site_url('chotel/huserinit/setmainphoto?hid=')?>' + this.hotelid+'&photo='+photo).then(function(res) {
                console.log(res.data);
                if (res.data.result) {
                    mediainit.mainphoto = res.data.photo;
                    mediainit.getmainphoto();
                } else {
                    mediainit.mainphoto = null;
                }
            }).catch(function(error) {
                console.log(error);
            });
        },
        getmainphoto: function() {
            //setdefphoto(mf.Photo_Name)
            axios.get('<?php echo site_url('chotel/huserinit/getmainphoto?hid=')?>' + this.hotelid).then(function(res) {
                console.log(res.data);
                if (res.data.result) {
                    mediainit.mainphoto = res.data.mainphoto;
                } else {
                    mediainit.mainphoto = '';
                    console.log('bad');
                }
            }).catch(function(error) {
                console.log(error);
            });
        },
        delphoto: function(photoid, name) {
            axios.get('<?php echo site_url('chotel/huserinit/del_photo?pid=')?>' + photoid + '&name=' + name).then(function(
                res) {
                console.log(res.data);
                if (res.data.result) {
                    mediainit.getphotos();
                }
            }).catch(function(error) {
                console.log(error);
            });
        },
        himg: function(img) {
            return '/assets/images/hotels/' + img;
        },
        req: async function(event) {
            //formElem.preventDefault();
            var form = event.target;
            if(files.files.length > 0){
                console.log(files.files);
                var formdata = new FormData(form);
                console.log(formdata);
                this.uploadphotos(form, formdata);
            }else{
                this.uploaderror = "Please Select images first";
            }
        },
        uploadphotos: async function(form, data, distination = null) {
            await axios({
                method: form.method, //form.method directly
                url: form.action, // Or form.action
                data: data, //ment data form object from formData(idform)
                config: {
                    headers: {
                        "content-type": "multipart/form-data"
                    },
                },

            }).then(function(result) {
                console.log(result);
                if (result.data.status) {
                    mediainit.getphotos();
                    mediainit.hasPhotos = true;
                    form.reset();
                } else {
                    console.log('bad');
                    mediainit.uploaderror = result.data.error;
                }
                // this.hasPhotos update it and grap images
            }).catch(function(error) {
                console.log(error);
            });
        },
    },
    mounted() {
        this.getphotos();
        this.getmainphoto();
    },
});
    </script>