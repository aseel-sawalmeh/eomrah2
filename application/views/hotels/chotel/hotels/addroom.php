<div class="content-wrapper text-center">
    <div class="card mt-3">
        <div class="card-header">
            <h3 class="text-center">
                Hotel Guests
            </h3>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <?= form_open() ?>
            <input id="hotelId" type="hidden" name="hotelId" value="<?= $hotel->Hotel_ID ?>" />
            <input id="provId" type="hidden" value="<?= $provid ?? '' ?>" />
            <div class="row">
                <div class="col-sm-3">
                    <h4 class="mt-4">
                        Adult
                    </h4>
                    <span id="amodmessage"></span>
                </div>
                <div class="col-sm-3">
                    <label>Age From</label>
                    <input type="number" name="adult_age_from" id="wizard_adult_age_from" required class="form-control" value="<?= ($h_age) ? $h_age[0]->A_age_from : ''; ?>" />
                </div>
                <div class="col-sm-3">
                    <label>Age To</label>

                    <input type="number" name="adult_age_to" id="wizard_adult_age_to" required class="form-control" value="<?= ($h_age) ? $h_age[0]->A_age_to : ''; ?>" />
                </div>

                <div class="col-sm-3">
                    <br>
                    <?php if ($h_age && $h_age[0]->A_age_from !== NULL) : ?>
                        <div id="a_age">

                            <a class="btn btn-success mt-2" onclick="age_set('a_age')">Update</a>
                        </div>

                    <?php else : ?>
                        <a class="btn btn-success mt-2" onclick="age_set('a_age')">Add</a>
                    <?php endif; ?>
                </div>

            </div>
            <hr>
            <div class="row">
                <div class="col-sm-3">
                    <h4 class="mt-4">Child</h4>
                    <span id="cmodmessage"></span>
                </div>
                <div class="col-sm-3">
                    <label>Age From</label>
                    <input type="number" name="child_age_from" id="wizard_child_age_from" class="form-control" value="<?= ($h_age) ? $h_age[0]->C_age_from : ''; ?>" />
                </div>
                <div class="col-sm-3">
                    <label>Age To</label>
                    <input type="number" name="child_age_to" id="wizard_child_age_to" class="form-control" value="<?= ($h_age) ? $h_age[0]->C_age_to : ''; ?>" />
                </div>
                <div class="col-sm-3">
                    <br>
                    <?php if ($h_age && $h_age[0]->C_age_from !== NULL) : ?>
                        <a class="btn btn-warning mt-2" onclick="age_update('c_age')">Update</a>
                    <?php else : ?>
                        <a class="btn btn-warning mt-2" onclick="age_set('c_age')">Add</a>
                    <?php endif; ?>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-sm-3">
                    <h4 class="mt-4">Infants</h4>
                    <span id="imodmessage"></span>
                </div>
                <div class="col-sm-3">
                    <label>Age From</label>
                    <input type="number" name="infant_age_from" id="wizard_infant_age_from" class="form-control" value="<?= ($h_age) ? $h_age[0]->infant_age_from : ''; ?>" />
                </div>
                <div class="col-sm-3">
                    <label>Age To</label>
                    <input type="number" name="infant_age_to" id="wizard_infant_age_to" class="form-control" value="<?= ($h_age) ? $h_age[0]->infant_age_to : ''; ?>" />
                </div>
                <div class="col-sm-3">
                    <br>
                    <?php if ($h_age && $h_age[0]->infant_age_from !== NULL) : ?>
                        <a class="btn btn-info mt-2" onclick="age_update('i_age')">Update</a>
                    <?php else : ?>
                        <a class="btn btn-info mt-2" onclick="age_set('i_age')">Add</a>
                    <?php endif; ?>
                </div>
            </div>


        </div>
    </div>
    <div class="card text-center">
        <div class="card-header">
            <h3 class="text-center">Room Category</h3>
            <span id="roomAddErrorMessage"></span>
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-sm-6 mx-auto">

                    <label for="maxocc">Max occuppants</label>
                    <input type="number" name="maxocc" id="maxocc" class="form-control" required />
                    <br>
                    <select class="form-control" id="roomtype" name="roomtype">
                        <option value="">Room Type</option>
                        <?php foreach ($roomTypes as $roomType) : ?>
                            <option value=" <?php echo $roomType->R_Type_ID; ?>">
                                <?php echo $roomType->R_Type_Name; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <br>
                    <div class="text-center">
                        <a class="btn btn-primary" onclick="ins_room()">Add Room</a>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div id="Data-Table-Rooms" class="mx-auto">
                        <?= $data_table ?>
                    </div>
                </div>
            </div>


            <div class="modal fade" id="modal-default">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add Attributes</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div style="margin:10px auto" id="Data-Table-attrs">
                                <span id="AttrAddErrorMessage"></span>
                                <?= $occattr_datatable ?>
                            </div>

                            <input id="AttrRoomID" name="roomid" type="hidden" />
                            <input id="AttrMax" name="maxAdults" type="hidden" />
                            <select name="attr-occ" id="attrType" class="form-control">
                                <option id="adultAtrr" value="1">Adult</option>
                                <option id="childAtrr" value="2">Child</option>
                                <option value="3">Infant</option>
                            </select>
                            <br>
                            <div class="text-center">
                                <a class="btn btn-success" onclick="insAttr()">Add</a>
                            </div>

                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h3 class="text-center">Meals</h3>
        </div>
        <div class="card-body text-center">
            <div class="row">
                <div class="col-sm-6">
                    <label for="wizard_Meal">Hotel Meals</label>
                    <div class="tex-center">
                        <select class="form-control" id="wizard_Meal" name="Meal" required data-md-selectize-delayed>
                            <?php foreach ($Meals as $Meal) : ?>
                                <option value="<?php echo $Meal->Meal_ID; ?>">
                                    <?php echo $Meal->Meal_Name; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <hr>
                    </div>

                    <div class="text-center">
                        <a class="btn btn-primary" onclick="insHotelMeal()"> Add Meal </a>
                    </div>
                    <br>
                </div>
                <div class="col-sm-6">
                    <div id="Data-Table-Meals-container">
                        <table class="table table-responsive">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="Data-Table-Meals">
                                <?php
                                if ($hotel_meals) {
                                    for ($i = 0; $i < count($hotel_meals); $i++) {
                                        $count = $i + 1;
                                        echo "<tr><td>Meal {$count} : {$hotel_meals[$i]->Meal_Name}</td><td> <button class='btn btn-danger' type='button' onclick='delHotelMeal({$hotel_meals[$i]->HM_ID})'>Delete Meal</button> </td></tr>";
                                    }
                                } else {
                                    echo "No Meals added yet";
                                }
                                ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4 mx-auto">
                    <a href="<?= base_url('chotel/hotel_manage/comset/') . "$provid" ?>" role="menuitem" class="btn btn-success d-block">Finish</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= form_close() ?>

</div>

<script src="<?= base_url('admin_design/plugins/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('admin_design/plugins/jquery-ui/jquery-ui.min.js') ?>"></script>
<script src="<?= base_url('admin_design/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('admin_design/dist/js/adminlte.js') ?>"></script>
<script src="<?= base_url('admin_design/custom/tools.js') ?>"></script>

</body>

</html>