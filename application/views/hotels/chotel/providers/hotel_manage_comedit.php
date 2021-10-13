
<div class="card">
    <div class="card-header">
        <div id="dateconflicts" class="text-center"></div>
        <?php if ($this->session->flashdata('period_mgs') !== null) {
            echo "<h4 style='text-align:center; color:red'>";
            echo $this->session->flashdata('period_mgs');
            echo "</h4>";
        } ?>
        <div class="text-center text-danger">
            <?php echo $this->session->flashdata('Periodmsg');
            if ($this->session->flashdata('date_conflicts') !== null) {
                foreach ($this->session->flashdata('date_conflicts') as $conflict) {
                    echo $conflict;
                }
            }
            ?>
            <div id="dateconflicts"></div>
        </div>

        <div class="text-center" style="color:red">
            <?php echo validation_errors() ?>
        </div>
    </div>

    <div class="card-body table-responsive p-0">


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
                            <td class='text-center'>
                                <?php echo $this->Hperiods_model->whatperiodtype($period->periodType)->Hp_Type_Name ?>
                            </td>
                            <td><?php echo $period->dateFrom ?></td>
                            <td><?php echo $period->dateTo ?></td>
                            <td><?php echo $period->periodRelease ?></td>
                            <td><?php echo $period->minStay ?></td>
                            <td>
                                <?php if (!$this->session->userdata('Suser')) : ?>
                                    <a class="btn btn-danger" href="<?php echo base_url('chotel') ?>/hotel_manage/delete_period/<?php echo $provider->Provider_ID . '/' . $period->hperiodID ?>">
                                        Delete</a>
                                    <a class="btn btn-primary" href="<?php echo base_url('chotel') ?>/hotel_manage/comedit/<?php echo $provider->Provider_ID . '/' . $period->hperiodID ?>">
                                        Edit</a>
                                <?php else : ?>
                                    <a class="btn btn-success" href="<?php echo base_url('chotel') ?>/hotel_manage/period_details/<?php echo $period->hperiodID ?>">
                                        Activate</a>
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
<div class="card">
    <div class="card-header">
        <h2 class="text-center">Edit Period</h2>
    </div>
    <div class="card-body">
        <?php echo form_open(site_url("chotel/hotel_manage/comedit/$providerid/$periodid"), 'id="period_form"') ?>
        <input id="provider_id" type="hidden" name="providerid" value="<?php echo $provider->Provider_ID ?>" />
        <input type="hidden" name="periodid" value="<?php echo $tperiod->hperiodID ?>" />
        <div class="row">
            <div class="col-md-3">
                <label for="period_name">Period Name</label>
                <input class="form-control" type="text" id="period_name" name="periodname" value="<?php echo $tperiod->periodName ?>" /><br>
            </div>
            <div class="col-md-3">
                <label for="periodtypes">Period Type</label>
                <select class="form-control" type="text" id="periodtypes" name="period_type">
                    <?php if ($hperiodtypes) : foreach ($hperiodtypes as $hperiodtype) : ?>
                            <option value="<?php echo $hperiodtype->Hp_Type_ID ?>" <?php echo ($hperiodtype->Hp_Type_ID == $tperiod->periodType) ? "SELECTED" : '' ?>>
                                <?php echo $hperiodtype->Hp_Type_Name ?>
                            </option>
                    <?php endforeach;
                    endif; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label for="startdate">Period Start Date</label>
            

                <input class="form-control" data-date-format="DD MMMM YYYY" type="date" id="startdate" name="startdate" value="<?php echo $tperiod->dateFrom ?>" readonly />
            </div>
            <div class="col-md-3">
                <label for="enddate">Period End Date</label>
                <input class="form-control" onchange="check_dates(this.value)" data-date-format="DD MMMM YYYY" type="date" id="enddate" name="enddate" value="<?php echo $tperiod->dateTo ?>" readonly />
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <label for="period_release">Period Release</label>
                <input class="form-control" type="number" id="period_release" name="periodrelease" value="<?php echo $tperiod->periodRelease ?>" />
            </div>
            <div class="col-md-3">
                <label for="minstay">Minimum Stay</label>
                <input class="form-control" type="number" id="minstay" name="minstay" value="<?php echo $tperiod->minStay ?>" />
            </div>
            <div class="col-md-3">
                <label for="res_timeout">Reservation TimeOut</label>
                <input class="form-control" type="number" id="res_timeout" name="Reservation_Timeout" value="<?php echo $tperiod->Reservation_Timeout ?>" />
            </div>
            <div class="col-md-3">
                <label for="period_type">Period Price Margin</label>
                <input class="form-control" type="text" id="period_mrprice" name="periodpricemargin" value="">
                <select id="pmarginmop"  name="pmargin_mop">
                    <option value="+">&#43;</option>
                    <option value="-">&#45;</option>
                </select>
                <select id="pmargintype"  name="pmargin_type">
                    <option value="%">&#37;</option>
                    <option value="#">&#35;</option>
                </select>
                <button type="button" class="btn-md btn-info mt-2" onclick="calc_margin()">Update all</button>
            </div>
        </div>


    </div>
</div>
<div class="card">
    <div class="card-body table-responsive table-borderd">


        <table class="table table-hover text-center">
            <thead>
                <tr>
                    <td class="text-center">Rooms Type/Count</td>
                    <?php if ($tymeals) : foreach ($tymeals as $tymeal) : ?>
                            <td class="text-center"><?php echo $tymeal->Meal_Sn ?></td>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <td class="text-center">This Hotel Does not Have meals data</td>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if ($tyrooms) : ?>
                    <?php foreach ($tyrooms as $tyroom) : ?>
                        <tr>
                            <td class="text-center"><span><?php echo $tyroom->R_Type_Sn ?></span>
                                <input type="hidden" name="<?php echo $tyroom->R_Type_Sn ?>_count" value="<?php echo $get_ava($tyroom->R_Type_Sn); ?>" readonly />
                            </td>
                            <?php if ($tymeals) : foreach ($tymeals as $tymeal) : ?>
                                    <td class="text-center"><span><?php echo $tyroom->R_Type_Sn ?> /
                                            <?php echo $tymeal->Meal_Sn ?></span>
                                        <?php if ($PeriodRmPriceOverType) : foreach ($PeriodRmPriceOverType as $rpt) : if ($rpt->Mtype_ID == $tymeal->Meal_ID) : ?>

                                                    <input type="number" class="mrpriced" name="<?php echo $tyroom->R_Type_Sn . '_typeprice_' . $tymeal->Meal_ID ?>" value="<?php echo $rpt->{$tyroom->R_Type_Sn} ?>" />
                                        <?php endif;
                                            endforeach;
                                        endif; ?>
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
                            <?php foreach ($roomSuppPrices as $rSuppPrice) : if ($rSuppPrice->R_SuppType_ID ==  $rstype->R_SuppType_ID) : ?>
                                    <input id="rs<?php echo $rstype->R_SuppType_ID ?>_price" type="number" name="rsprice_<?php echo $rstype->R_SuppType_ID ?>" value="<?php echo $rSuppPrice->R_Supp_Price ?>" />

                                    <select id="rspriceop_<?php echo $rstype->R_SuppType_ID ?>" name="spricemop_<?php echo $rstype->R_SuppType_ID ?>">
                                        <option <?= ($rSuppPrice->SupplPrice_Mop == '+') ? 'selected' : '' ?> value="+">
                                            &#43;</option>
                                        <option <?= ($rSuppPrice->SupplPrice_Mop == '-') ? 'selected' : '' ?> value="-">
                                            &#45;</option>
                                    </select>
                                    <select name="spricetype_<?php echo $rstype->R_SuppType_ID ?>">
                                        <option <?= ($rSuppPrice->SupplPrice_Type == '%') ? 'selected' : '' ?> value="%">
                                            &#37;</option>
                                        <option <?= ($rSuppPrice->SupplPrice_Type == '#') ? 'selected' : '' ?> value="#">
                                            &#35;</option>
                                    </select>
                            <?php endif;
                            endforeach; ?>
                            <?php if ($tyrooms) : foreach ($tyrooms as $tyroom) : ?>
                                    <label for="rs<?php echo $rstype->R_SuppType_ID ?>_<?php echo $tyroom->R_Type_Sn ?>"><?php echo $tyroom->R_Type_Sn ?></label>
                                    <?php foreach ($roomSuppPrices as $rSuppPrice) : if ($rSuppPrice->R_SuppType_ID ==  $rstype->R_SuppType_ID) : ?>
                                            <input id="rs_<?php echo $rstype->R_SuppType_ID ?>_<?php echo $tyroom->R_Type_Sn ?>" type="checkbox" name="<?php echo $tyroom->R_Type_Sn ?>_rsst_<?php echo $rstype->R_SuppType_ID ?>" value="1" <?php echo ($rSuppPrice->{$tyroom->R_Type_Sn} == 1) ? "checked" : '' ?> />
                            <?php endif;
                                    endforeach;
                                endforeach;
                            endif; ?>
                        <?php endforeach;
                    else : ?>
                        <h3 class="text-center">This Hotel Does Not Have Any Supplements</h3>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body table-responsive p-0 mx-auto">
        <table class="table table-hover">
            <thead>
                <tr>
                    <td class="text-center">Sunday</td>
                    <td class="text-center">Monday</td>
                    <td class="text-center">Tuesday</td>
                    <td class="text-center">Wednesday</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">
                        <input type="number" name="prds_Sunday" value="<?php echo $PdaySupp->Sunday ?>" />
                        <select id="rs_sun_price_op" name="dprsmathop_Sunday">
                            <option <?php echo ($PdaySupp->SundayPrice_Mop == '+') ? 'selected' : '' ?> value="+">&#43;</option>
                            <option <?php echo ($PdaySupp->SundayPrice_Mop == '-') ? 'selected' : '' ?> value="-">&#45;</option>
                        </select>
                        <select name="dprstype_Sunday">
                            <option <?php echo ($PdaySupp->SundayPrice_Type == '%') ? 'selected' : '' ?> value="%">&#37;</option>
                            <option <?php echo ($PdaySupp->SundayPrice_Type == '#') ? 'selected' : '' ?> value="#">&#35;</option>
                        </select>
                    </td>
                    <td class="text-center">
                        <input type="number" name="prds_Monday" value="<?php echo $PdaySupp->Monday ?>" />
                        <select id="rs_mon_price_op" name="dprsmathop_Monday">
                            <option <?php echo ($PdaySupp->MondayPrice_Mop == '+') ? 'selected' : '' ?> value="+">&#43;</option>
                            <option <?php echo ($PdaySupp->MondayPrice_Mop == '-') ? 'selected' : '' ?> value="-">&#45;</option>
                        </select>
                        <select name="dprstype_Monday">
                            <option <?php echo ($PdaySupp->MondayPrice_Type == '%') ? 'selected' : '' ?> value="%">&#37;</option>
                            <option <?php echo ($PdaySupp->MondayPrice_Type == '#') ? 'selected' : '' ?> value="#">&#35;</option>
                        </select>
                    </td>
                    <td class="text-center">
                        <input type="number" name="prds_Tuesday" value="<?php echo $PdaySupp->Tuesday ?>" />
                        <select id="rs_tues_op" name="dprsmathop_Tuesday">
                            <option <?php echo ($PdaySupp->TuesdayPrice_Mop == '+') ? 'selected' : '' ?> value="+">&#43;</option>
                            <option <?php echo ($PdaySupp->TuesdayPrice_Mop == '-') ? 'selected' : '' ?> value="-">&#45;</option>
                        </select>
                        <select name="dprstype_Tuesday">
                            <option <?php echo ($PdaySupp->TuesdayPrice_Type == '%') ? 'selected' : '' ?> value="%">&#37;</option>
                            <option <?php echo ($PdaySupp->TuesdayPrice_Type == '#') ? 'selected' : '' ?> value="#">&#35;</option>
                        </select>
                    </td>
                    <td class="text-center">
                        <input type="number" name="prds_Wednesday" value="<?php echo $PdaySupp->Wednesday ?>" />
                        <select id="rs_wednes_op" name="dprsmathop_Wednesday">
                            <option <?php echo ($PdaySupp->WednesdayPrice_Mop == '+') ? 'selected' : '' ?> value="+">&#43;</option>
                            <option <?php echo ($PdaySupp->WednesdayPrice_Mop == '-') ? 'selected' : '' ?> value="-">&#45;</option>
                        </select>
                        <select name="dprstype_Wednesday">
                            <option <?php echo ($PdaySupp->WednesdayPrice_Type == '%') ? 'selected' : '' ?> value="%">&#37;</option>
                            <option <?php echo ($PdaySupp->WednesdayPrice_Type == '#') ? 'selected' : '' ?> value="#">&#35;</option>
                        </select>
                    </td>
                </tr>
            </tbody>
            <thead>
                <tr>
                    <td class="text-center">Thursday</td>
                    <td class="text-center">Friday</td>
                    <td class="text-center">Saturday</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">
                        <input type="number" name="prds_Thursday" value="<?php echo $PdaySupp->Thursday ?>" />
                        <select id="rs_wednes_op" name="dprsmathop_Thursday">
                            <option <?php echo ($PdaySupp->ThursdayPrice_Mop == '+') ? 'selected' : '' ?> value="+">&#43;</option>
                            <option <?php echo ($PdaySupp->ThursdayPrice_Mop == '-') ? 'selected' : '' ?> value="-">&#45;</option>
                        </select>
                        <select name="dprstype_Thursday">
                            <option <?php echo ($PdaySupp->ThursdayPrice_Type == '%') ? 'selected' : '' ?> value="%">&#37;</option>
                            <option <?php echo ($PdaySupp->ThursdayPrice_Type == '#') ? 'selected' : '' ?> value="#">&#35;</option>
                        </select>
                    </td>
                    <td class="text-center">
                        <input type="number" name="prds_Friday" value="<?php echo $PdaySupp->Friday ?>" />
                        <select id="rs_fri_op" name="dprsmathop_Friday">
                            <option <?php echo ($PdaySupp->FridayPrice_Mop == '+') ? 'selected' : '' ?> value="+">&#43;</option>
                            <option <?php echo ($PdaySupp->FridayPrice_Mop == '-') ? 'selected' : '' ?> value="-">&#45;</option>
                        </select>
                        <select name="dprstype_Friday">
                            <option <?php echo ($PdaySupp->FridayPrice_Type == '%') ? 'selected' : '' ?> value="%">&#37;</option>
                            <option <?php echo ($PdaySupp->FridayPrice_Type == '#') ? 'selected' : '' ?> value="#">&#35;</option>
                        </select>
                    </td>
                    <td class="text-center">
                        <input type="number" name="prds_Saturday" value="<?php echo $PdaySupp->Saturday ?>" />
                        <select id="rs_sat_op" name="dprsmathop_Saturday">
                            <option <?php echo ($PdaySupp->SaturdayPrice_Mop == '+') ? 'selected' : '' ?> value="+">&#43;</option>
                            <option <?php echo ($PdaySupp->SaturdayPrice_Mop == '-') ? 'selected' : '' ?> value="-">&#45;</option>
                        </select>
                        <select name="dprstype_Saturday">
                            <option <?php echo ($PdaySupp->SaturdayPrice_Type == '%') ? 'selected' : '' ?> value="%">&#37;</option>
                            <option <?php echo ($PdaySupp->SaturdayPrice_Type == '#') ? 'selected' : '' ?> value="#">&#35;</option>
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
                    <input class="btn-block btn-success" type="Submit" value="Edit" />
                    <input class="btn-block btn-danger" type="reset" value="Cancel" />
                </div>
            </div>

        </div>
    </div>
</div>

</form>


</div>