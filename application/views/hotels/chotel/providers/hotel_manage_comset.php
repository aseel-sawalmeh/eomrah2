<div class="card">
    <div class="card-header">

        <div class="text-center text-success">
            <?php if ($this->session->flashdata('period_mgs') !== null) {
                echo "<span style='text-align:center; color:red'>";
                echo $this->session->flashdata('period_mgs');
                echo "</span>";
            } ?>
            <?php echo $this->session->flashdata('Periodmsg'); ?>
            <div class="text-center">
                <?php echo validation_errors() ?>
            </div>
        </div>

    </div>

    <div class="card-body table-responsive">

        <table class="table table-hover text-nowrap text-center">
            <thead>

                <tr>
                    <th>Period Name</th>
                    <th>Period Type</th>
                    <th>Date From</th>
                    <th>Date To</th>
                    <th>Release period</th>
                    <th>Mnimum Stay</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($hperiods) : foreach ($hperiods as $period) : ?>
                        <tr>
                            <td><?php echo $period->periodName ?></td>
                            <td class='uk-text-center'>
                                <?php echo $this->Hperiods_model->whatperiodtype($period->periodType)->Hp_Type_Name ?>
                            </td>
                            <td><?php echo $period->dateFrom ?></td>
                            <td><?php echo $period->dateTo ?></td>
                            <td><?php echo $period->periodRelease ?></td>
                            <td><?php echo $period->minStay ?></td>
                            <td>
                                <?php if (!$this->session->userdata('Suser')) : ?>
                                    <a class="btn btn-outline-danger" href="<?php echo base_url('chotel') ?>/hotel_manage/delete_period/<?php echo $provider->Provider_ID . '/' . $period->hperiodID ?>">
                                        Delete</a>
                                    <a class="btn btn-outline-primary" href="<?php echo base_url('chotel') ?>/hotel_manage/comedit/<?php echo $provider->Provider_ID . '/' . $period->hperiodID ?>">
                                        Edit</a>
                                <?php else : ?>
                                    <a class="btn btn-outline-danger" href="<?php echo base_url('chotel') ?>/hotel_manage/delete_period/<?php echo $provider->Provider_ID . '/' . $period->hperiodID ?>">
                                        Delete</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php
                    endforeach;
                else : ?>
                    <h3 class="text-center">No Period Data</h3>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>


<div id="dateconflicts" class="text-center"></div>
<div class="card">
    <div class="card-header">
        <h2 class="text-center">Add New Period</h2>
    </div>

    <div class="card-body">
        <?php echo form_open(site_url("chotel/hotel_manage/comset/$providerid")); ?>
        <input id="provider_id" type="hidden" name="providerid" value="<?php echo $provider->Provider_ID ?>" />
        <div class="row">
            <div class="col-md-3">
                <label for="period_name">Period Name</label>
                <input class="form-control" type="text" id="period_name" name="periodname" value="<?php echo set_value('periodname') ?>" /><br>
            </div>
            <div class="col-md-3">
                <label for="periodtypes">Period Type</label>
                <select class="form-control" type="text" id="periodtypes" name="period_type">
                    <?php if ($hperiodtypes) : foreach ($hperiodtypes as $hperiodtype) : ?>
                            <option value="<?php echo $hperiodtype->Hp_Type_ID ?>"> <?php echo $hperiodtype->Hp_Type_Name ?>
                            </option>
                    <?php endforeach;
                    endif; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label for="startdate">Period Start Date</label>
                <input class="form-control" data-date-format="DD MMMM YYYY" type="date" id="startdate" name="startdate" value="<?php echo set_value('startdate') ?>" />
            </div>
            <div class="col-md-3">
                <label for="enddate">Period End Date</label>
                <input class="form-control" data-date-format="DD MMMM YYYY" type="date" id="enddate" name="enddate" onchange="check_dates(this.value)" value="<?php echo set_value('enddate') ?>" />
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <label for="period_release">Period Release</label>
                <input class="form-control" type="number" id="period_release" name="periodrelease" value="<?php echo set_value('periodrelease') ?>" />
            </div>
            <div class="col-md-3">
                <label for="minstay">Minimum Stay</label>
                <input class="form-control" type="number" id="minstay" name="minstay" value="<?php echo set_value('minstay') ?>" />
            </div>
            <div class="col-md-3">
                <label for="res_timeout">Reservation TimeOut</label>
                <input class="form-control" type="number" id="res_timeout" name="Reservation_Timeout" value="<?php echo set_value('Reservation_Timeout') ?>" />
            </div>
            <div class="col-md-3">
                <label for="period_type">Period Price Margin</label>
                <input class="form-control" type="number" id="period_mrprice" name="periodpricemargin" value="<?php echo set_value('periodpricemargin') ?>">
                <select id="pmarginmop" name="pmargin_mop">
                    <option value="+">&#43;</option>
                    <option value="-">&#45;</option>
                </select>
                <select id="pmargintype" name="pmargin_type">
                    <option value="%">&#37;</option>
                    <option value="#">&#35;</option>
                </select>
                <button type="button" onclick="calc_margin()">Update</button>
            </div>
        </div>


    </div>
</div>
<div class="card">
    <div class="card-body table-responsive table-borderd">
        <table class="table table-hover text-center">
            <thead>

                <th>Rooms Type/Count</th>
                <?php if ($tymeals) : foreach ($tymeals as $tymeal) : ?>
                        <th><?php echo $tymeal->Meal_Sn ?></th>
                    <?php endforeach; ?>
                <?php else : ?>
                    <th>This Hotel Does not Have meals data</th>
                <?php endif; ?>

            </thead>
            <tbody>
                <?php if ($tyrooms) : ?>
                    <?php foreach ($tyrooms as $tyroom) : ?>

                        <tr>
                            <td class="text-center">
                                <span><?php echo $tyroom->R_Type_Sn ?></span>
                                <input type="number" class="form-control" name="<?php echo $tyroom->R_Type_ID . '_' . $tyroom->R_Type_Sn ?>_count" /> </td>
                            <?php if ($tymeals) : foreach ($tymeals as $tymeal) : ?>
                                    <td class="text-center"><span><?php echo $tyroom->R_Type_Sn ?> /
                                            <?php echo $tymeal->Meal_Sn ?></span> <input class="mrpriced form-control" type="number" name="<?php echo $tyroom->R_Type_Sn . '_typeprice_' . $tymeal->Meal_ID ?>" value="<?php echo set_value($tyroom->R_Type_Sn . '_typeprice_' . $tymeal->Meal_ID) ?>" />
                                    </td>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <td class="text-center">This Hotel Does not Have meals data</td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-body">
                    <h4 class="text-center">Room Supplements</h4>
                    <?php if ($roomSuppTypes) : foreach ($roomSuppTypes as $rstype) : ?>


                            <h5><?php echo $rstype->R_SuppType_Name ?></h5>


                            <label for="rs<?php echo $rstype->R_SuppType_ID ?>_price">Price</label>
                            <input class="form-control" id="rs<?php echo $rstype->R_SuppType_ID ?>_price" type="number" name="rsprice_<?php echo $rstype->R_SuppType_ID ?>" value="<?php echo set_value("rsprice_" . $rstype->R_SuppType_ID) ?>" />
                            <br>
                            <select id="rspriceop_<?php echo $rstype->R_SuppType_ID ?>" name="spricemop_<?php echo $rstype->R_SuppType_ID ?>">
                                <option value="+">&#43;</option>
                                <option value="-">&#45;</option>
                            </select>
                            <select name="spricetype_<?php echo $rstype->R_SuppType_ID ?>">
                                <option value="%">&#37;</option>
                                <option value="#">&#35;</option>
                            </select>

                            <?php if ($tyrooms) : foreach ($tyrooms as $tyroom) : ?>
                                    <label for="rs<?php echo $rstype->R_SuppType_ID ?>_<?php echo $tyroom->R_Type_Sn ?>"><?php echo $tyroom->R_Type_Sn ?></label>
                                    <input id="rs_<?php echo $rstype->R_SuppType_ID ?>_<?php echo $tyroom->R_Type_Sn ?>" type="checkbox" name="<?php echo $tyroom->R_Type_Sn ?>_rsst_<?php echo $rstype->R_SuppType_ID ?>" value="1" />
                            <?php endforeach;
                            endif; ?>


                        <?php endforeach;
                    else : ?>

                        <h3 class="text-center">No Supplements</h3>
                        <input name="nosupp" type="hidden" value="0" />

                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap text-center">
            <thead>
                <tr>
                    <th class="uk-text-center">Sunday</th>
                    <th class="uk-text-center">Monday</th>
                    <th class="uk-text-center">Tuesday</th>
                    <td class="uk-text-center">Wednesday</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="uk-text-center">
                        <input type="number" name="prds_Sunday" value="<?php echo set_value('prds_Sunday') ?>" />
                        <select id="rs_sun_price_op" name="dprsmathop_Sunday">
                            <option value="+">&#43;</option>
                            <option value="-">&#45;</option>
                        </select>
                        <select name="dprstype_Sunday">
                            <option value="%">&#37;</option>
                            <option value="#">&#35;</option>
                        </select>
                    </td>
                    <td class="uk-text-center">
                        <input type="number" name="prds_Monday" value="<?php echo set_value('prds_Monday') ?>" />
                        <select id="rs_mon_price_op" name="dprsmathop_Monday">
                            <option value="+">&#43;</option>
                            <option value="-">&#45;</option>
                        </select>
                        <select name="dprstype_Monday">
                            <option value="%">&#37;</option>
                            <option value="#">&#35;</option>
                        </select>
                    </td>
                    <td class="uk-text-center">
                        <input type="number" name="prds_Tuesday" value="<?php echo set_value('prds_Tuesday') ?>" />
                        <select id="rs_tues_op" name="dprsmathop_Tuesday">
                            <option value="+">&#43;</option>
                            <option value="-">&#45;</option>
                        </select>
                        <select name="dprstype_Tuesday">
                            <option value="%">&#37;</option>
                            <option value="#">&#35;</option>
                        </select>
                    </td>
                    <td class="uk-text-center">
                        <input type="number" name="prds_Wednesday" value="<?php echo set_value('prds_Wednesday') ?>" />
                        <select id="rs_wednes_op" name="dprsmathop_Wednesday">
                            <option value="+">&#43;</option>
                            <option value="-">&#45;</option>
                        </select>
                        <select name="dprstype_Wednesday">
                            <option value="%">&#37;</option>
                            <option value="#">&#35;</option>
                        </select>
                    </td>
                </tr>
            </tbody>
            <thead>
                <tr>
                    <th class="uk-text-center">Thursday</th>
                    <th class="uk-text-center">Friday</th>
                    <th class="uk-text-center">Saturday</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="uk-text-center">
                        <input type="number" name="prds_Thursday" value="<?php echo set_value('prds_Thursday') ?>" />
                        <select id="rs_wednes_op" name="dprsmathop_Thursday">
                            <option value="+">&#43;</option>
                            <option value="-">&#45;</option>
                        </select>
                        <select name="dprstype_Thursday">
                            <option value="%">&#37;</option>
                            <option value="#">&#35;</option>
                        </select>
                    </td>
                    <td class="uk-text-center">
                        <input type="number" name="prds_Friday" value="<?php echo set_value('prds_Friday') ?>" />
                        <select id="rs_fri_op" name="dprsmathop_Friday">
                            <option value="+">&#43;</option>
                            <option value="-">&#45;</option>
                        </select>
                        <select name="dprstype_Friday">
                            <option value="%">&#37;</option>
                            <option value="#">&#35;</option>
                        </select>
                    </td>
                    <td class="uk-text-center">
                        <input type="number" name="prds_Saturday" value="<?php echo set_value('prds_Saturday') ?>" />
                        <select id="rs_sat_op" name="dprsmathop_Saturday">
                            <option value="+">&#43;</option>
                            <option value="-">&#45;</option>
                        </select>
                        <select name="dprstype_Saturday">
                            <option value="%">&#37;</option>
                            <option value="#">&#35;</option>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>
<div class="container">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-body text-center">
                    <input class="btn btn-outline-success" type="Submit" value="Add" />
                    <input class="btn btn-outline-danger" type="reset" value="Cancel" />
                </div>
            </div>

        </div>
    </div>
</div>

</form>


</div>