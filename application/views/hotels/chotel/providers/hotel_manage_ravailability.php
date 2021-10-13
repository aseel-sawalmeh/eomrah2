

<div class="card">
    <?php if ($this->session->flashdata('Supplement_mgs') !== null) {
        echo $this->session->flashdata('Supplement_mgs');
    } ?>
    <h2 class="text-center">Room Availability</h2>
    <div class="card-body text-center">
        <div class="row">
            <div class="col-sm-6 mx-auto">
                <form>
                    <span>Choose Month</span>
                    <select class="form-control" onchange="monthnav(this.value)">
                        <?php foreach ($this->avcal->AllPeriodsIntervals() as $date) {
                            $select = ($this->avcal->target_month_num == $this->avcal->tdates('m', $date)) ? 'SELECTED' : '';
                        ?>
                            <option value="<?= $this->avcal->tdates('m', $date) ?>" <?= $select ?>>
                                <?= $this->avcal->tdates('F', $date) ?></option>
                        <?php
                        } ?>
                    </select>
                    <br>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4 m-auto">
                <?= $this->avcal->gen() ?>
                <br>
                <h4><?php echo $this->session->flashdata('roomAvailMsg') ?></h4>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4 mx-auto">
                <?php echo form_open("chotel/hotel_manage/room_availability/$providerid") ?>

                <?php echo form_error('startdate') ?>
                <label for="startdate"> Start Date</label>
                <div class="md-input-wrapper">
                    <input id="startdate" class="form-control" data-date-format="DD MMMM YYYY" type="date" name="startdate" />
                </div>
                <br>
               <p class="text-danger"><?php echo form_error('enddate') ?></p> 
                <label for="enddate"> End Date</label>

                <input id="enddate" class="form-control" data-date-format="DD MMMM YYYY" type="date" name="enddate" />

                <label for="availablecount">Room availability count</label>
                <input class="form-control" type="text" id="availablecount" name="roomsavcount" />

                <label for="periodtypes">Room Category</label>
                <select class="form-control" type="text" id="periodtypes" name="roomcategory">
                    <option value="0" selected="selected"> ALL </option>
                    <?php if ($tyrooms) : foreach ($tyrooms as $tyroom) : ?>
                            <option value="<?php echo $tyroom->R_Type_ID ?>">
                                <?php echo $tyroom->R_Type_Name ?> </option>
                        <?php endforeach;
                    else : ?>
                        <option selected="selected"> No Room Categories Found </option>
                    <?php endif; ?>
                </select>
                <br>
                <input class="btn btn-success" type="submit" value="Validate" />
                <input class="btn btn-danger" type="reset" value="Cancel" />

                </form>


                <?php if (false) : ?>

                    <?php echo $this->session->flashdata('roomAvaildatesMsg') ?>
                    <table class="table table-striped table-responsive">
                        <thead>
                            <tr>
                                <td class="text-center">Action</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($datesava) : foreach ($datesava as $avdate) : ?>
                                    <tr>
                                        <td class="text-center">
                                            <?php echo form_open('chotel/hotel_manage/r_update_av') ?>
                                            <input type="hidden" name="providerid" value="<?php echo $provider->Provider_ID ?>" />
                                            <input type="hidden" name="ravid" value="<?php echo $avdate->ID ?>" />
                                            <label><?php echo ($avdate->R_Type_Sn == null) ? "ALL" : $avdate->R_Type_Sn ?> :
                                            </label>

                                            <label>From date</label>
                                            <input type="date" name="startdate" value="<?php echo $avdate->StartDate ?>" />
                                            <label>To Date</label>
                                            <input type="date" name="enddate" value="<?php echo $avdate->EndDate ?>" />
                                            <label>Availability</label>
                                            <input type="text" name="roomsavcount" value="<?php echo $avdate->Available_Count ?>" />
                                            <input class="btn btn-info" type="submit" value="Update" />
                                            <a class="btn btn-danger" href="<?php echo base_url("chotel/hotel_manage/r_delete_av/{$provider->Provider_ID}/{$avdate->ID}") ?>">Delete</a>
                                        </td>
                                    </tr>
                                    </form>
                            <?php endforeach;
                            endif; ?>
                        </tbody>
                    </table>

                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
</div>
< <script>
    function monthnav(month) {
    location.href = "<?php echo $this->avcal->avlink() ?>" + month;
    }
</script>