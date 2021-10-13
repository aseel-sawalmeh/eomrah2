<div class="content-wrapper">
    <div class="row">
        <div class="col-md-6 mx-auto mt-4">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="text-center">List Your Property</h3>
                </div>
                <div class="card-body">
                    <form action="<?= site_url('chotel/provider/hotel_inform') ?>" method="post">
                        <input type="hidden" name="hotelUser" value="<?= $informedBy ?>" />
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <?= form_error('hotelName') ?>
                                    <label>Hotel Name</label>
                                    <input type="text" class="form-control" name="hotelName" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Stars Numbers</label>
                                    <select class="form-control" name="hotel_stars">
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
                                    <?= form_error('hotelDescription') ?>
                                    <label>Hotel Description</label>
                                    <textarea class="form-control" name="hotelDescription"></textarea>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <?= form_error('hotelAddress') ?>
                                    <label>Hotel Full Address</label>
                                    <textarea class="form-control" name="hotelAddress"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <?= form_error('hotelCountry') ?>
                            <label>Country</label>

                            <select name="hotelCountry" onchange="get_cities(this.value)" class="form-control">
                                <?php foreach ($countries as $country) : ?>
                                    <option value="<?= $country->Country_ID ?>">
                                        <?= $country->Country_Name ?> </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <?= form_error('hotelCity') ?>
                            <label>City</label>
                            <select id="cities" name="hotelCity" onchange="get_regions(this.value)" class="form-control"></select>
                        </div>
                        <div class="form-group">
                            <?= form_error('hotelRegion') ?>
                            <label>Select Region</label>
                            <select id="regions" name="hotelRegion" class="form-control">
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <?= form_error('hotelPhone') ?>
                                    <label>Phone Number</label>
                                    <input type="text" name="hotelPhone" class="form-control" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Fax Number</label> <span>(Optional)</span>
                                    <input type="text" name="hotelFax" class="form-control" />

                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <?= form_error('hotelEmail') ?>
                                    <label>Email</label>
                                    <input type="text" name="hotelEmail" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="text-center">
                                    <input type="submit" class="form-control text-center btn btn-success">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


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