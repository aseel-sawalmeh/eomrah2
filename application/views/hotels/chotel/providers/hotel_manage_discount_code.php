

<div class="card">
    <div class="card-header">
        <?php if ($this->session->flashdata('Supplement_mgs') !== NULL) {
            echo $this->session->flashdata('Supplement_mgs');
        } ?>
        <?= $this->session->flashdata('DiscountcodeMsg') ?>
        <?= $this->session->flashdata('payAvailMsg') ?>

    </div>
    <div class="card-body">
        <table class="table table-stripped">
            <thead>
                <tr>
                    <td class="text-center">Discount code</td>
                    <td class="text-center">Start Date</td>
                    <td class="text-center">End Date</td>
                    <td class="text-center">Price</td>
                    <td class="text-center">Allow For B2B</td>
                    <td class="text-center">Allow For B2C</td>
                    <td class="text-center">Discription</td>
                    <td class="text-center">Action</td>
                </tr>
            </thead>
            <tbody>
                <?php if ($discodes) : foreach ($discodes as $discode) : ?>
                        <tr>
                            <td class="text-center"><?= $discode->discountCode ?></td>
                            <td class="text-center"><?= $discode->StartDate ?></td>
                            <td class="text-center"><?= $discode->EndDate ?></td>
                            <td class="text-center"><?= $discode->Price ?></td>
                            <td class="text-center"><?= ($discode->AllowB2B == 1) ? '<span style="background-color:lightgreen">Allowed</span>' : '<span style="background-color:red">Not Allowed</span>' ?></td>
                            <td class="text-center"><?= ($discode->AllowB2C == 1) ? '<span style="background-color:lightgreen">Allowed</span>' : '<span style="background-color:red">Not Allowed</span>' ?></td>
                            <td class="text-center"><?= $discode->Description ?></td>
                            <td class="text-center">
                                <a class="btn btn-info" href="<?= base_url("chotel/hotel_manage/discount_code_edit/{$provider->Provider_ID}/{$discode->ID}") ?>"> edit</a>
                                <a class="btn btn-danger" href="<?= base_url("chotel/hotel_manage/discount_code_del/{$provider->Provider_ID}/{$discode->ID}") ?>">Delete</a>
                            </td>
                        </tr>
                        </form>
                <?php endforeach;
                endif; ?>
            </tbody>
        </table>

    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-6 mx-auto">
                <?= form_open(site_url("chotel/hotel_manage/discount_code/$providerid")) ?>
                <div class="form-group">
                    <label for="discountcode"> Discount Code</label>
                    <input id="discountcode" class="form-control" type="text" name="discode" value="<?= $this->toolset->coupon_generate(8) ?>" />
                    <?= form_error('discode') ?>
                </div>

                <div class="form-group">
                    <label for="discountamount"> Discount Amount</label>
                    <input id="discountamount" class="form-control" type="number" name="disamount" />
                    <select name="discount-amountop" class="form-control">
                        <option>%</option>
                    </select>
                    <?= form_error('disamount') ?>
                </div>
                <div class="form-group">


                    <label for="startdate"> Start Date</label>
                    <input id="startdate" class="form-control" type="date" name="startdate" readonly value="<?= date('Y-m-d') ?>" />
                    <?= form_error('startdate') ?>

                    <br>
                    <?= form_error('enddate') ?>
                    <label for="enddate"> End Date</label>

                    <input id="enddate" class="form-control" type="date" name="enddate" />

                </div>
                <div class="form-group">

                    <label> Allow B2B </label>
                    <input type="checkbox" name="allowb2b" value="1" checked />
                    <label> Allow B2C </label>
                    <input type="checkbox" name="allowb2c" value="1" checked />

                </div>
                <div class="form-group">

                    <label for="periodtypes"> Description </label>
                    <textarea class="form-control" name="discount-description"></textarea>

                </div>
                <div class="form-group">
                    <input class="btn btn-success" type="submit" value="Add" />
                    <input class="btn btn-danger" type="reset" value="Cancel" />
                </div>
                </form>
            </div>
        </div>
    </div>
</div>