<script src="<?= base_url('admin_design/editor/samples/css/sample.css') ?>"></script>
<script src="<?= base_url('admin_design/editor/samples/toolbarconfigurator/lib/codemirror/neo.css') ?>"></script>
<div class="content-wrapper">
    <div class="card">
        <div class="card-body">
            <div id="page_content_inner">
                <div>
                    <h3><?= $this->session->flashdata('statusMsg') ?></h3>
                    <div class="md-card-content">
                        <!-- <div>
                            <?php echo validation_errors(); ?>
                            <?php echo form_open(); ?>
                            <input type="hidden" name="product_id" value="<?= $product_id ?>" />
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
                            <p>Product <?php if ($txt_type == 'prodtitle') {
                                            echo "title";
                                        } elseif ($txt_type == 'proddesc') {
                                            echo "Description";
                                        } else {
                                            "Not determined";
                                        }
                                        ?> English</p>
                            <textarea id="editor" name="s_txt" cols="40" rows="5"><?= $txts['en'] ?></textarea>
                            <button class="btn btn-primary" type="submit">Translate to all languages</button>
                            </form>
                            <hr>
                            <p>translate to seleted languages</p>
                        </div> -->

                        <div>
                            <?php //foreach ($langs as $lang) : ?>
                            <!-- <form method="post" action="<?= base_url() ?>gman/prodtxt/edit_single_txt/<?= $product_id . "/" . $txts['Text_ID'] . "/" . $txt_type . "/" . $lang->code ?>">
                                        <fieldset>
                                            <legend><?= $lang->lang_name ?>:</legend>
                                            Text: <input size="50"  type="text" name="<?= $lang->code ?>" value='<?= $txts[$lang->code] ?>'>
                                            <input type="submit" class="btn btn-success" value="Update" />
                                        </fieldset>
                                        <?php //endforeach; ?>
                                    </form> -->

                            <div class="row">
                                <div class="col">
                                    <?= form_open("gman/prodtxt/edit_single_txt/" . $product_id . "/" . $txts['Text_ID'] . "/" . $txt_type . "/" . "en")?>

                                    <div class="form-group">
                                        <label>English Text</label>
                                        <textarea name="en" id="editor2"
                                            class="form-control"><?= $txts['en'] ?></textarea>
                                    </div>
                                    <input type="submit" class="btn btn-success" value="Edit" />


                                    <?=form_close()?>

                                </div>
                            </div>
                                <hr>
                            <div class="row">
                                <div class="col">
                                    <?= form_open("gman/prodtxt/edit_single_txt/" . $product_id . "/" . $txts['Text_ID'] . "/" . $txt_type . "/" . "ar")?>

                                    <div class="form-group">
                                        <label>Arabic Text</label>
                                        <textarea name="ar" id="editor3"
                                            class="form-control"><?= $txts['ar'] ?></textarea>
                                    </div>
                                    <input type="submit" class="btn btn-success" value="Edit" />


                                    <?=form_close()?>


                                </div>
                            </div>



                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="<?= base_url('admin_design/editor/ckeditor.js') ?>"></script>

<script>
if (CKEDITOR.env.ie && CKEDITOR.env.version < 9)
    CKEDITOR.tools.enableHtml5Elements(document);


CKEDITOR.config.height = 500;
CKEDITOR.config.width = 'auto';

var initSample = (function() {
    var wysiwygareaAvailable = isWysiwygareaAvailable(),
        isBBCodeBuiltIn = !!CKEDITOR.plugins.get('bbcode');

    return function() {
        var editorElement = CKEDITOR.document.getById('editor');



        if (wysiwygareaAvailable) {
            CKEDITOR.replace('editor');
            CKEDITOR.replace('editor2');
            CKEDITOR.replace('editor3');
        } else {
            editorElement.setAttribute('contenteditable', 'true');
            CKEDITOR.inline('editor');


        }
    };

    function isWysiwygareaAvailable() {

        if (CKEDITOR.revision == ('%RE' + 'V%')) {
            return true;
        }

        return !!CKEDITOR.plugins.get('wysiwygarea');
    }
})();

initSample();
</script>