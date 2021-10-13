<?php
$this->load->view('hotels/chotel/providers/hotel_manage_nav');
?>

<div class="card">
    <div class="card-header">
        <?php if ($this->session->flashdata('Supplement_mgs') !== null) {
            echo $this->session->flashdata('Supplement_mgs');
        } ?>
        <?php
        function gebdates($kind, $gdate)
        {
            $raw = new DateTime($gdate);
            return $raw->format($kind);
        }
        $cell = '';
        ?>
        <h4 class="text-center">
        <?php echo $this->session->flashdata('payAvailMsg') ?>
        </h4>
       
        <h4 class="text-center">
            <?php echo $this->session->flashdata('payAvaildatesMsg') ?>
        </h4>

    </div>
    <div class="card-body">
        <?php if ($paymonthsava) : foreach ($paymonthsava as $mava) : ?>

                <table class="table table-striped table-hover">
                    <h3 class="text-center">Your Availability to
                        <?php echo $mava->tmonth . " " . $mava->ryear ?></h3>
                    <thead>
                        <tr>
                            <td>
                                days
                            </td>
                            <?php for ($i = 1; $i <= days_in_month($mava->nMonth, $mava->ryear); $i++) : ?>

                                <td>
                                    <?php if (strlen($i) == 1) {
                                        $cell = '0' . $i;
                                        echo $cell;
                                    } else {
                                        $cell = $i;
                                        echo $cell;
                                    }
                                    ?>
                                </td>
                            <?php endfor; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($paydatesava as $dateava) {
                            echo "<tr><td>" . $dateava->Pay_m_name . "</td>";
                            $drange = date_range($dateava->StartDate, $dateava->EndDate);
                            $alldays = days_in_month($mava->nMonth, $mava->ryear);
                            if (gebdates('m', $drange[0]) == $mava->nMonth) {
                                $diffstart = intval(gebdates('d', $drange[0]));
                                for ($i = 0; $i < $diffstart - 1; $i++) {
                                    echo '<td style="background-color:#eee">-</td>';
                                }
                            }
                            foreach ($drange as $cdate) {
                                if (gebdates('m', $cdate) == $mava->nMonth) {
                                    echo '<td style="background-color:lightgreen; color:blue">' . gebdates('d', $cdate) . '</td>';
                                }
                            }
                        }
                        echo '</tr>';
                        ?>
                    </tbody>
                </table>

        <?php endforeach;
        endif; ?>

    </div>
</div>
<div class="card text-center">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-6 mx-auto">
                <?php echo form_open("chotel/hotel_manage/payment_config/$providerid") ?>
                <div class="uk-form-row">

                    <div class="form-group">
                        <label> Start Date</label>
                        <input id="startdate" class="form-control" data-date-format="DD MMMM YYYY" type="date" name="startdate" />
                        <?php echo form_error('startdate') ?>
                    </div>


                    <div class="form-group">
                        <label> End Date</label>
                        <input id="enddate" class="form-control" data-date-format="DD MMMM YYYY" type="date" name="enddate" />
                        <?php echo form_error('enddate') ?>
                    </div>
                </div>
                <div class="uk-form-row">
                    <div class="md-input-wrapper">
                        <label> Open / Close </label>
                        <select class="form-control" type="text" id="periodtypes" name="paymethodstate">
                            <option value="1"> Open </option>
                            <option value="0"> Close </option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="periodtypes"> Payment Method</label>
                    <select class="form-control" type="text" id="periodtypes" name="paymethod">
                        <option value="0" selected="selected"> ALL </option>
                        <?php if ($Paymethods) : foreach ($Paymethods as $Paymethod) : ?>
                                <option value="<?php echo $Paymethod->ID ?>"> <?php echo $Paymethod->Pay_m_name ?>
                                </option>
                            <?php endforeach;
                        else : ?>
                            <option selected="selected"> No PayMent method Found </option>
                        <?php endif; ?>
                    </select>
                </div>
                <input class="btn btn-success" type="submit" value="Validate" />
                <input class="btn btn-danger" type="reset" value="Cancel" />

                <?=form_close()?>
            </div>
        </div>

    </div>
</div>
<div class="card">
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="text-center">Payment Availability</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($paydatesava) : foreach ($paydatesava as $avdate) : ?>
                        <tr>
                            <td class="text-center">
                                <?php echo form_open('chotel/hotel_manage/pay_update_av') ?>
                                <input type="hidden" name="providerid" value="<?php echo $provider->Provider_ID ?>" />
                                <input type="hidden" name="payavid" value="<?php echo $avdate->paytimeid ?>" />
                                <label><?php echo ($avdate->Pay_m_name == null) ? "ALL" : $avdate->Pay_m_name ?>
                                    :
                                </label>
                                <label>From date</label>
                                <input type="date" name="startdate" value="<?php echo $avdate->StartDate ?>" />
                                <label>To Date</label>
                                <input type="date" name="enddate" value="<?php echo $avdate->EndDate ?>" />
                                <input class="btn btn-info" type="submit" value="Update" />
                                <a class="btn btn-danger" href="<?php echo base_url("chotel/hotel_manage/pay_delete_av/{$provider->Provider_ID}/{$avdate->paytimeid}") ?>">Delete</a>
                            </td>
                        </tr>
                        <?=form_close()?>
                <?php endforeach;
                endif; ?>
            </tbody>
        </table>

    </div>
</div>
</div>

