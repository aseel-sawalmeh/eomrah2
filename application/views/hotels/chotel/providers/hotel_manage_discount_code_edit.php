<div class="card">
    <div class="card-header">
        <?php if ($this->session->flashdata('Supplement_mgs') !== NULL) {
            echo $this->session->flashdata('Supplement_mgs');
        } ?>
        <h2 class="text-center">Discount Codes Edit</h2>
        <?= $this->session->flashdata('payAvailMsg') ?>
    </div>
    <div class="card-body">
        <?= form_open(site_url("chotel/hotel_manage/discount_code_edit/$providerid/$code")) ?>

        <div class="form-group">
            <input type="hidden" name="discodeid" value="<?= $discode->ID ?>" />
            <?= form_error('discode') ?>
            <label for="discountcode"> Discount Code</label>
            <input id="discountcode" class="form-control" type="text" name="discode" value="<?= $discode->discountCode ?>" />
        </div>

        <div class="form-group">
            <?= form_error('disamount') ?>
            <label for="discountamount"> Discount Amount</label>
            <input id="discountamount" class="form-control" type="number" name="disamount" value="<?= $discode->Price ?>" />
            <select name="discount-amountop">
                <option>%</option>
            </select>
        </div>

        <div class="form-group">
            <?= form_error('startdate') ?>
            <label for="startdate"> Start Date</label>
            <input id="startdate" class="form-control" type="date" name="startdate" readonly value="<?= nice_date($discode->StartDate, 'Y-m-d') ?>" />
        </div>
        <br>

        <div class="form-group">
            <?= form_error('enddate') ?>
            <label for="enddate"> End Date</label>
            <input id="enddate" class="form-control" type="date" name="enddate" value="<?= nice_date($discode->EndDate, 'Y-m-d') ?>" />
        </div>

        <div class="form-group">
            <label> Allow B2B </label> <br>
            <input type="checkbox" name="allowb2b" value="1" <?= $discode->AllowB2B ? 'checked' : '' ?> />
        </div>
        <div class="form-group">
            <label> Allow B2C </label> <br>
            <input type="checkbox" name="allowb2c" value="1" <?= $discode->AllowB2C ? 'checked' : '' ?> />
        </div>

        <div class="form-group">
            <label for="periodtypes"> Description </label>
            <textarea class="form-control" name="discount-description"><?= $discode->Description ?? '' ?></textarea>
        </div>

        <div class="form-group">
            <input class="btn btn-success" type="submit" value="Save" />
            <input class="btn btn-danger" type="reset" value="Cancel" />
        </div>
        </form>



    </div>
</div>
</div>