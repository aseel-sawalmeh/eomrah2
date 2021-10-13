<!-- Table Cutt of hotels -->
<div id="page_content">
<div id="page_content_inner">
    <div class="md-card uk-margin-medium-bottom">
        <div class="md-card-content">
            <div class="uk-overflow-container">
                <div class="md-card">
                    <div class="md-card-content">
                        <h2 class="text-center"> Hotel : <?php echo "{$providedhotel->Hotel_Name}";?></h2>
                        <p> Hotel address : <?php echo $providedhotel->Hotel_Address;?> </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="md-card uk-margin-medium-bottom">
        <div class="md-card-content">
            <div class="uk-overflow-container">
                    <?php if($this->session->flashdata('period_mgs') !== NULL){
                        echo "<h4 style='text-align:center; color:red'>";
                        echo $this->session->flashdata('period_mgs');
                        echo "</h4>";
                    }?>

                <div class="md-card">
                    <div class="md-card-content">
                <h2 class="text-center">Periods / Prices</h2>
                <table class="uk-table uk-table-nowrap table_check">
                    <thead>
                    <tr>
                        <th class="uk-width-1-10 uk-text-center small_col"><input type="checkbox" data-md-icheck class="check_all"></th>
                        <th class="uk-width-2-10">Period Name</th>
                        <th class="uk-width-2-10">Period Type</th>
                        <th class="uk-width-1-10 uk-text-center">Date From</th>
                        <th class="uk-width-1-10 uk-text-center">Date To</th>
                        <th class="uk-width-1-10 uk-text-center">Release period</th>
                        <th class="uk-width-2-10 uk-text-center">Mnimum Stay</th>
                        <th class="uk-width-2-10 uk-text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php if($hperiods): foreach($hperiods as $period): ?>
                        <tr>
                            <td class="uk-text-center uk-table-middle small_col"><input type="checkbox" data-md-icheck class="check_row"></td>
                            <td><?=$period->periodName?></td>
                            <td class="uk-text-center"><?=$period->periodType?></td>
                            <td class="uk-text-center"><?=$period->dateFrom?></td>
                            <td class="uk-text-center"><?=$period->dateTo?></td>
                            <td class="uk-text-center">
                            <?php if(!$this->session->userdata('Suser')): ?>
                            <a class="md-btn md-btn-danger" href="<?=site_url('chotel')?>/hotel_manage/delete_period/<?=$period->hperiodID?>">
                            Delete</a>
                            <?php else : ?>
                            <a class="md-btn md-btn-success" href="<?=site_url('chotel')?>/hotel_manage/period_details/<?=$period->hperiodID?>">
                            Activate</a>
                            <a href="#"><i class="md-icon material-icons">&#xE88F;</i></a>
                <?php endif; ?>
                            </td>
                        </tr>
                        <?php 
                        endforeach; ?>
                        <?php 
                        else: ?>
                        <h3 class="uk-text-center">No Period Data</h3>
                      <?php endif; 
                        ?>
                    </tbody>
                </table>
                <hr>
                <form action="<?=site_url('chotel/hotel_manage/addperiod')?>" method="post">
                <input type="hidden" name="providerid" value="<?=$provider->Provider_ID?>"/>
                <div class="uk-grid" data-uk-grid-margin="">
                <div class="uk-width-large-1-4 uk-width-medium-2-4 uk-container-left">
                    <div class="uk-form-row">
                        <label for="period_name">Period Name</label>
                        <input class="md-input" type="text" id="period_name" name="periodname" /><br>
                    </div>
                    <div class="uk-form-row">
                        <label for="period_type">Period Type</label>
                        <select class="md-input" type="text" id="period_type" name="type" >
                        <option value="0">Simple</option>
                        <option value="1">Promo</option>
                        </select>
                    </div>
                    <div class="uk-form-row">
                        <label for="startdate">Period Start Date</label><br>
                        <input class="md-input" data-date-format="DD MMMM YYYY" type="date" id="startdate" name="startdate" />
                        <label for="enddate">Period End Date</label><br>
                        <input class="md-input" data-date-format="DD MMMM YYYY" type="date" id="enddate" name="enddate" />
                    </div>
                </div>
                <div class="uk-width-large-1-4 uk-width-medium-2-4 uk-container-left">
                    <div class="uk-form-row">
                        <label for="period_release">Period Release</label>
                        <input class="md-input" type="number" id="period_release" name="periodrelease" /><br>
                        <label for="minstay">Minimum Stay</label>
                        <input class="md-input" type="number" id="minstay" name="minstay" />
                    </div>
                    <div class="uk-form-row">
                        <label for="period_type">Period Price / update</label>
                        <input class="md-input" type="text" id="period_price" name="period_price" >
                        <select name="math_op">
                            <option value="mathpluse">&#43;</option>
                            <option value="mathminus">&#45;</option>
                        </select>
                        <select name="amount_op">
                            <option value="a_percent">&#37;</option>
                            <option value="a_amount">&#35;</option>
                        </select>
                    </div>
                </div>
                </div>
                <hr>
                <h4>Rooms</h4>
                <div class="uk-grid" data-uk-grid-margin="">
                    <div class="uk-width-large-1-1 uk-width-medium-1-1 uk-container-left">
                        <div class="uk-form-row">
                            <table class="uk-table uk-table-nowrap table_check">
                            <thead>
                            <tr>
                            <td class="uk-text-center">Rooms Type/Count</td>
                            <?php if($tymeals) : foreach($tymeals as $tymeal) : ?>
                            <td class="uk-text-center"><?=$tymeal->Meal_Sn?></td>
                            <?php endforeach; ?>
                            <?php else : ?>
                            <td class="uk-text-center">This Hotel Does not Have meals data</td>
                            <?php endif; ?>
                            </tr>
                            </thead>
                            <tbody>
            <?php if($tyrooms) : ?>
                <?php foreach($tyrooms as $tyroom) : ?>
                    <tr>
                    <td class="uk-text-center"><span><?=$tyroom->R_Type_Sn?></span> <input type="number" name="hr_dbl_count" /> </td>
                    <?php if($tymeals) : foreach($tymeals as $tymeal) : ?>
                            <td class="uk-text-center"><span><?=$tyroom->R_Type_Sn?> / <?=$tymeal->Meal_Sn?></span> <input type="number" name="hr_dbl_bb_price" /> </td>
                            <?php endforeach; ?>
                            <?php else : ?>
                            <td class="uk-text-center">This Hotel Does not Have meals data</td>
                            <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
                            </tbody>
                            </table>
                        </div>
                    <div class="uk-form-row">
                    <h4>Room Supplements</h4>
                        <div class="uk-grid" style="background-color:#f5f5f5;" data-uk-grid-margin="">
                            <div class="uk-width-large-1-3 uk-width-medium-1-2 uk-container-left">
                                <h5 style="padding-top:5px">Flower Room</h5>
                            </div>
                            <div class="uk-width-large-1-3 uk-width-medium-1-2 uk-container-left">
                                <label for="rs_flower_price">Price</label>
                                <input id="rs_flower_price" type="number" name="rs_flower_price" />
                                <select id="rs_flower_price_op" name="math_op">
                                    <option value="mathpluse">&#43;</option>
                                    <option value="mathminus">&#45;</option>
                                </select>
                                <select name="amount_op">
                                    <option value="a_percent">&#37;</option>
                                    <option value="a_amount">&#35;</option>
                                </select>
                            </div>
                            <div class="uk-width-large-1-3 uk-width-medium-1-2 uk-container-left" >
                            <?php if($tyrooms) : foreach($tyrooms as $tyroom) :?>
                                <label for="rs_flower_<?=$tyroom->R_Type_Sn?>"><?=$tyroom->R_Type_Sn?></label>
                                <input id="rs_flower_<?=$tyroom->R_Type_Sn?>"  type="checkbox" name="rs_flower_<?=$tyroom->R_Type_Sn?>" value="<?=$tyroom->R_Type_ID?>" />
                            <?php endforeach; 
                            endif; ?>
                            </div>
                        </div>
                        <div class="uk-grid" style="background-color:#f5f5f5;" data-uk-grid-margin="">
                            <div class="uk-width-large-1-3 uk-width-medium-1-2 uk-container-left">
                                <h5 style="padding-top:5px">Kaaba View</h5>
                            </div>
                            <div class="uk-width-large-1-3 uk-width-medium-1-2 uk-container-left">
                                <label for="rs_kaabaview_price">Price</label>
                                <input id="rs_kaabaview_price" type="number" name="rs_flower_price" />
                                <select id="rs_kaabaview_price_op" name="math_op">
                                    <option value="mathpluse">&#43;</option>
                                    <option value="mathminus">&#45;</option>
                                </select>
                                <select name="amount_op">
                                    <option value="a_percent">&#37;</option>
                                    <option value="a_amount">&#35;</option>
                                </select>
                            </div>
                            <div class="uk-width-large-1-3 uk-width-medium-1-2 uk-container-left" >
                            <?php if($tyrooms) : foreach($tyrooms as $tyroom) :?>
                                <label for="rs_kaabaview_<?=$tyroom->R_Type_Sn?>"><?=$tyroom->R_Type_Sn?></label>
                                <input id="rs_kaabaview_<?=$tyroom->R_Type_Sn?>"  type="checkbox" name="rs_kaabaview_<?=$tyroom->R_Type_Sn?>" value="<?=$tyroom->R_Type_ID?>" />
                            <?php endforeach; 
                            endif; ?>
                            </div>
                        </div>
                        <div class="uk-grid" style="background-color:#f5f5f5;" data-uk-grid-margin="">
                            <div class="uk-width-large-1-3 uk-width-medium-1-2 uk-container-left">
                                <h5 style="padding-top:5px">Haram View</h5>
                            </div>
                            <div class="uk-width-large-1-3 uk-width-medium-1-2 uk-container-left">
                                <label for="rs_haramview_price">Price</label>
                                <input id="rs_haramview_price" type="number" name="rs_haramview_price" />
                                <select id="rs_haramview_price_op" name="math_op">
                                    <option value="mathpluse">&#43;</option>
                                    <option value="mathminus">&#45;</option>
                                </select>
                                <select name="amount_op">
                                    <option value="a_percent">&#37;</option>
                                    <option value="a_amount">&#35;</option>
                                </select>
                            </div>
                            <div class="uk-width-large-1-3 uk-width-medium-1-2 uk-container-left" >
                            <?php if($tyrooms) : foreach($tyrooms as $tyroom) :?>
                                <label for="rs_haramview_<?=$tyroom->R_Type_Sn?>"><?=$tyroom->R_Type_Sn?></label>
                                <input id="rs_haramview_<?=$tyroom->R_Type_Sn?>"  type="checkbox" name="rs_haramview_<?=$tyroom->R_Type_Sn?>" value="<?=$tyroom->R_Type_ID?>" />
                            <?php endforeach; 
                            endif; ?>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>

                <hr>
                <h4>Days Supplement</h4>
                <div class="uk-grid" data-uk-grid-margin="">
                    <div class="uk-width-large-1-1 uk-width-medium-1-1 uk-container-left">
                        <div class="uk-form-row">
                            <table class="uk-table uk-table-nowrap table_check">
                            <thead>
                            <tr>
                                <td class="uk-text-center">Sunday</td>
                                <td class="uk-text-center">Monday</td>
                                <td class="uk-text-center">Tuesday</td>
                                <td class="uk-text-center">Wednesday</td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="uk-text-center">
                                    <input type="number" name="rs_sun" />
                                    <select id="rs_sun_price_op" name="math_op">
                                        <option value="mathpluse">&#43;</option>
                                        <option value="mathminus">&#45;</option>
                                    </select>
                                    <select name="amount_op">
                                        <option value="a_percent">&#37;</option>
                                        <option value="a_amount">&#35;</option>
                                    </select>
                                </td>
                                <td class="uk-text-center">
                                    <input type="number" name="rs_mon" />
                                    <select id="rs_mon_price_op" name="math_op">
                                        <option value="mathpluse">&#43;</option>
                                        <option value="mathminus">&#45;</option>
                                    </select>
                                    <select name="amount_op">
                                        <option value="a_percent">&#37;</option>
                                        <option value="a_amount">&#35;</option>
                                    </select>
                                </td>
                                <td class="uk-text-center">
                                    <input type="number" name="rs_tues" />
                                    <select id="rs_tues_op" name="math_op">
                                        <option value="mathpluse">&#43;</option>
                                        <option value="mathminus">&#45;</option>
                                    </select>
                                    <select name="amount_op">
                                        <option value="a_percent">&#37;</option>
                                        <option value="a_amount">&#35;</option>
                                    </select>
                                </td>
                                <td class="uk-text-center">
                                    <input type="number" name="rs_wednes" />
                                    <select id="rs_wednes_op" name="math_op">
                                        <option value="mathpluse">&#43;</option>
                                        <option value="mathminus">&#45;</option>
                                    </select>
                                    <select name="amount_op">
                                        <option value="a_percent">&#37;</option>
                                        <option value="a_amount">&#35;</option>
                                    </select>
                                </td>
                            </tr>
                            </tbody> 
                            <thead>
                            <tr>
                                <td class="uk-text-center">Thursday</td>
                                <td class="uk-text-center">Friday</td>
                                <td class="uk-text-center">Saturday</td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="uk-text-center">
                                    <input type="number" name="rs_thus" />
                                    <select id="rs_wednes_op" name="math_op">
                                        <option value="mathpluse">&#43;</option>
                                        <option value="mathminus">&#45;</option>
                                    </select>
                                    <select name="amount_op">
                                        <option value="a_percent">&#37;</option>
                                        <option value="a_amount">&#35;</option>
                                    </select>
                                </td>
                                <td class="uk-text-center">
                                    <input type="number" name="rs_fri" />
                                    <select id="rs_fri_op" name="math_op">
                                        <option value="mathpluse">&#43;</option>
                                        <option value="mathminus">&#45;</option>
                                    </select>
                                    <select name="amount_op">
                                        <option value="a_percent">&#37;</option>
                                        <option value="a_amount">&#35;</option>
                                    </select>
                                </td>
                                <td class="uk-text-center">
                                    <input type="number" name="rs_sat" />
                                    <select id="rs_sat_op" name="math_op">
                                        <option value="mathpluse">&#43;</option>
                                        <option value="mathminus">&#45;</option>
                                    </select>
                                    <select name="amount_op">
                                        <option value="a_percent">&#37;</option>
                                        <option value="a_amount">&#35;</option>
                                    </select>
                                </td>
                            </tr>
                            </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <hr>
                <div class="uk-grid" data-uk-grid-margin="">
                    <div class="uk-width-large-1-1 uk-width-medium-1-1 uk-container-left">
                        <div class="uk-form-row">
                        <input class="md-btn md-btn-success" type="Submit" value="Update" />
                        <input class="md-btn md-btn-danger" type="reset" value="Cancel" />
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
</div>
