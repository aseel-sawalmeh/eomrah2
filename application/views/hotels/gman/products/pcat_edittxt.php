<div class="content-wrapper">
   <div class="card">
       <div class="card-body">
       <div id="page_content_inner">
        <div class="md-card uk-margin-medium-bottom">
            <h3><?= $this->session->flashdata('statusMsg') ?></h3>
            <div class="md-card-content">
                <div>
                    <?php echo validation_errors(); ?>
                    <?php echo form_open(site_url("gman/prodtxt/add/$txt_type/$product_id")); ?>
                    <input type="hidden" name="product_id" value="<?= $cat_id ?>" />
                    <label>Source Language</label>
                    <select name="from_lang">
                        <?php foreach ($langs as $lang) {
                            echo "<option value='{$lang->code}'>{$lang->lang_name}</option> ";
                        } ?>
                    </select>
                    <br>
                    <br>
                    <input type="hidden" name="txt_type" value="<?= $txt_type ?>" />
                    <input type="hidden" name="txt_id" value="<?= $txts['Text_ID'] ?>" />
                    <p>Product category <?php if ($txt_type == 'pcattitle') {
                                            echo "title";
                                        } elseif ($txt_type == 'pcatdesc') {
                                            echo "Description";
                                        } else {
                                            "Not determined";
                                        }
                                        ?> English</p>
                    <textarea name="s_txt" cols="40" rows="5"><?= $txts['en'] ?></textarea>
                    <button class="md-btn md-btn-primary" type="submit">Translate to all languages</button>
                    </form>
                    <hr>
                    <p>translate to seleted languages</p>
                    <div>
                         <?php foreach ($langs as $lang) : ?> 
                            <form method="post" action="<?= base_url() ?>gman/pcattxt/edit_single_txt/<?= $cat_id . "/" . $txts['Text_ID'] . "/" . $txt_type . "/" . $lang->code ?>">
                                <fieldset>
                                    <legend><?= $lang->lang_name ?>:</legend>
                                    Text: <input size="50" type="text" name="<?= $lang->code ?>" value='<?= $txts[$lang->code] ?>'>
                                    <input type="submit" class="md-btn md-btn-success" value="Update" />
                                </fieldset>
                            </form>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
       </div>
   </div>
</div>