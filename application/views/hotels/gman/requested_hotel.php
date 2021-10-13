
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="text-center">Activate Hotel</h3>
                </div>
                <div class="card-body">
                    <form action="<?= site_url('gman/providers/activate_request/') . $hotel_info->H_R_ID ?>" method="post">
                        <input type="hidden" value="<?= $hotel_info->H_R_ID ?>" name="hrid" />
                        <input type="hidden" value="<?= $hotel_info->H_User_ID ?>" name="huid" />
                        <input type="hidden" value="<?= $hotel_info->Hotel_ID ?>" name="hid" />

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Hotel Name</label>
                                    <input type="text" class="form-control" name="h_name" value="<?= $hotel_info->Hotel_Name ?>" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Stars Numbers</label>
                                    <select class="form-control" name="s_num">
                                        <option value="<?= $hotel_info->Star_Nums ?>"><?= $hotel_info->Star_Nums ?>
                                        </option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Hotel Description</label>
                                    <textarea class="form-control" name="hotelDescription"><?= $hotel_info->Hotel_Description ?></textarea>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Hotel Full Address</label>
                                    <textarea class="form-control" name="hotelAddress"><?= $hotel_info->Hotel_Address ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <?= form_error('hotelCountry') ?>
                            <label>Country</label>

                            <select name="h_country" onchange="get_cities(this.value)" class="form-control">
                                <?php foreach ($countries as $country) : ?>
                                    <option value="<?= $country->Country_ID ?>" <?= ($hotel_info->Hotel_Country_ID == $country->Country_ID) ? 'SELECTED' : ''; ?>>
                                        <?= $country->Country_Name ?> </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <?= form_error('hotelCity') ?>
                            <label>City</label>
                            <select id="cities" name="hotelCity" onchange="get_regions(this.value)" class="form-control">
                                <?php foreach ($gov as $g) : ?>
                                    <option value="<?= $g->Governorate_ID ?>" <?= ($hotel_info->Hotel_Governorate_ID == $g->Governorate_ID) ? 'SELECTED' : ''; ?>>
                                        <?= $g->Governorate_Name ?> </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <?= form_error('hotelRegion') ?>
                            <label>Select Region</label>
                            <select id="regions" name="hotelRegion" class="form-control">
                                <?php foreach ($regions as $r) : ?>
                                    <option value="<?= $r->City_ID ?>" <?= ($hotel_info->Hotel_Region_ID == $r->City_ID) ? 'SELECTED' : ''; ?>>
                                        <?= $r->City_Name ?> </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">

                                    <label>Phone Number</label>
                                    <input type="text" name="hotelPhone" class="form-control" value="<?= $hotel_info->Hotel_Phone ?>" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Fax Number</label> <span>(Optional)</span>
                                    <input type="text" name="hotelFax" class="form-control" value="<?= $hotel_info->Hotel_Fax ?>" />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="text" name="hotelEmail" class="form-control" value="<?= $hotel_info->Hotel_Email ?>" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <?= "<span style='color:red'>" . form_error('mark') . "</span>" ?>
                                    <label>MarkUp</label>
                                    <input type="text" class="form-control" name="mark" />

                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <?= "<span style='color:red'>" . form_error('discount') . "</span>" ?>
                                    <label>Discount</label>
                                    <input type="text" class="form-control" name="discount" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group border">
                                    <h5 class="text-center">Allow For B2C</h5>
                                    <input class="form-control" type="checkbox" name="allowb2c" value="1" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group border">
                                    <h5 class="text-center">Allow For B2B</h5>
                                    <input class="form-control" type="checkbox" name="allowb2b" value="1" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="text-center">
                                    <input class="btn btn-success" type="submit" value="Activate" />
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="<?= base_url('admin_design/assets/js/custom/gmap.js') ?>"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=<?= API_GOOGLE ?>&libraries=places&callback=initMap" asyncdefer></script>
<script type="text/javascript">
    function get_cities(cid) {
        if (cid.length == 0) {
            document.getElementById("cities").innerHTML = "";
            return;
        } else {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("cities").innerHTML = this.responseText;
                }
            }
        };
        xmlhttp.open("GET", " <?= base_url('/chotel/provider/aj_country_cities/') ?>" + cid, true);
        xmlhttp.send();
    }

    function get_regions(gid) {
        if (gid.length == 0) {
            document.getElementById("regions").innerHTML = "";
            return;
        } else {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("regions").innerHTML = this.responseText;
                }
            }
        };
        xmlhttp.open("GET", "<?= base_url('chotel/provider/aj_country_regions/') ?>" + gid, true);
        xmlhttp.send();
    }
</script>