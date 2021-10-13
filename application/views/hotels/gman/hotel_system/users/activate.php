<!-- Table Cutt of users -->
<div id="page_content">
    <div id="page_content_inner">
        <div class="md-card uk-margin-medium-bottom">
            <div class="md-card-content">
                <div class="uk-overflow-container">
                    <?php if ($this->session->flashdata('statusMsg') !== NULL) {
                        echo "<h4 style='text-align:center; color:Green'>";
                        echo $this->session->flashdata('statusMsg');
                        echo "</h4>";
                    } ?>
                    <h2>Hotel User Activation</h2> 
                    <!-- activate form -->
                    <form action="" method="post">
                        <input type="hidden" name="hotel_id" value="<?= $user_hotel->Hotel_ID ?>" />
                        <h3 class="heading_a">Hotel Details</h3>
                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-2">
                                <div class="uk-form-row">
                                    <div class="uk-grid" data-uk-grid-margin>
                                        <div class="uk-width-medium-1-2">
                                            <label>Hotel Name</label>
                                            <input type="text" disabled class="md-input" name="hotel_name" value="<?= $user_hotel->Hotel_Name ?>" />
                                        </div>
                                        <div class="uk-width-medium-1-2">
                                            <label>Stars Numbers</label>
                                            <select class="md-input" name="hotel_stars">
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div> <!-- -->
                        <h3 class="heading_a">Hotel Details</h3>
                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-2">
                                <div class="uk-form-row">
                                    <div class="uk-grid" data-uk-grid-margin>
                                        <div class="uk-width-medium-1-4">
                                            <?= form_error('country', "<span style='color:red'>", "</span>") ?>
                                            <label>Country</label>
                                            <select id="countryopt" name="country" class="md-input" onchange="get_cities(this.value)">
                                                <?php foreach ($countries as $country) : ?>
                                                    <option <?= ($user_hotel->Hotel_Country_ID == $country->Country_ID) ? "selected" : "" ?> value="<?= $country->Country_ID ?>"><?= $country->Country_Name ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="uk-width-medium-1-4">
                                            <?= "<span style='color:red'>" . form_error('governorate') . "</span>" ?>
                                            <label>City</label>
                                            <select id="cities" name="governorate" class="md-input" onchange="get_regions(this.value)">
                                                <option selected>Please Select A Country</option>
                                                <?php foreach ($governorates as $governorate) : ?>
                                                    <option <?= ($user_hotel->Hotel_Governorate_ID == $governorate->Governorate_ID) ? "selected" : "" ?> value="<?= $governorate->Governorate_ID ?>"><?= $governorate->Governorate_Name ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="uk-width-medium-1-4">
                                            <?= form_error('city', "<span style='color:red'>", "</span>") ?>
                                            <label>Region</label>
                                            <select id="regions" name="city" class="md-input">
                                                <option selected>Please Select A City</option>
                                                <?php foreach ($regions as $region) : ?>
                                                    <option <?= ($user_hotel->Hotel_Region_ID == $region->City_ID) ? "selected" : "" ?> value="<?= $region->City_ID ?>"><?= $region->City_Name ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="uk-width-medium-1-4">
                                            <label>Full Address</label>
                                            <textarea id="address-snap" name="full_address" class="md-input"><?= $user_hotel->Hotel_Address ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- -->

                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-2">
                                <div class="uk-form-row">
                                    <div class="uk-grid" data-uk-grid-margin>
                                        <div class="uk-width-medium-1-2">
                                            <input id="searchMapInput" class="mapControls" type="text" placeholder="Enter a location">
                                            <div id="map" style="height:300px; width:100%"></div>
                                        </div>
                                        <div class="uk-width-medium-1-2">
                                            <ul id="geoData">
                                                <li>Full Address: <span id="location-snap"></span></li>

                                                <li>Latitude: <span id="lat-span"></span></li>

                                                <li>Longitude: <span id="lon-span"></span></li>

                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- -->

                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-1">
                                <div class="uk-form-row">
                                    <div class="uk-grid" data-uk-grid-margin>
                                        <div class="uk-width-medium-1-3">
                                            <label>Phone Number</label>
                                            <input type="text" name="hotel_phone" value="<?= $user_hotel->Hotel_Phone ?>" />
                                        </div>
                                        <div class="uk-width-medium-1-3">
                                            <label>Fax Number</label>
                                            <input type="text" name="hotel_fax" value="<?= $user_hotel->Hotel_Fax ?>" />
                                        </div>
                                        <div class="uk-width-medium-1-3">
                                            <label>Email</label>
                                            <input type="text" name="hotel_email" value="<?= $user_hotel->Hotel_Email ?>" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- -->

                        <h3 class="heading_a">Activating Provider Options</h3>
                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-2">
                                <div class="uk-form-row">
                                    <div class="uk-grid" data-uk-grid-margin>
                                        <div class="uk-width-medium-1-2">
                                            <?= "<span style='color:red'>" . form_error('markup') . "</span>" ?>
                                            <?= form_error('markup') ?>
                                            <label>MarkUp</label>
                                            <input type="text" class="md-input" name="markup" value="<?= set_value('markup') ?>" />
                                        </div>
                                        <div class="uk-width-medium-1-2">
                                            <?= "<span style='color:red'>" . form_error('discount') . "</span>" ?>
                                            <?= form_error('discount') ?>
                                            <label>Discount</label>
                                            <input type="text" class="md-input label-fixed" name="discount" value="<?= set_value('discount') ?>" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-2">
                                <div class="uk-form-row">
                                    <div class="uk-grid" data-uk-grid-margin>
                                        <div class="uk-width-medium-1-2">
                                            <h5>Allow For B2C</h5>
                                            <input class="md-input" type="checkbox" checked name="allowb2c" value="1" />
                                        </div>
                                        <div class="uk-width-medium-1-2">
                                            <h5>Allow For B2B</h5>
                                            <input tclass="md-input" type="checkbox" checked name="allowb2b" value="1" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-2">
                                <div class="uk-form-row">
                                    <div class="uk-grid" data-uk-grid-margin>
                                        <div class="uk-width-medium-1-2">
                                            <input class="md-btn md-btn-success" type="submit" value="activate" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                    <!-- /activate form -->
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?=base_url('admin_design/assets/js/custom/gmap.js')?>"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=<?= API_GOOGLE ?>&libraries=places&callback=initMap" async defer></script>