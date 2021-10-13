<script src="<?= base_url('admin_design/editor/samples/css/sample.css') ?>"></script>
<script src="<?= base_url('admin_design/editor/samples/toolbarconfigurator/lib/codemirror/neo.css') ?>"></script>
<div class="content-wrapper">
    <div class="card">

        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card-body">

                    <?php echo validation_errors(); ?>
                    <?php echo form_open(site_url("gman/prodtxt/add/$txt_type/$product_id")); ?>
                    <input type="hidden" name="product_id" value="<?= $product_id ?>" />
                    <label>Source Language</label>
                    <select class="form-control" name="from_lang">
                        <?php foreach ($langs as $lang) {
                            echo "<option value='{$lang->code}'>{$lang->lang_name}</option> ";
                        } ?>
                    </select>
                    <br>
                    <br>
                    <input type="hidden" name="txt_type" value="<?= $txt_type ?>" />
                    <h3 class="text-center">Product <?php if ($txt_type == 'prodtitle') {
                                    $to_assign = FALSE;
                                    echo "Title";
                                } elseif ($txt_type == 'proddesc') {
                                    $to_assign = TRUE;
                                    echo "Description";
                                }else{
                                    $to_assign = False;
                                    echo "error_happend";
                                } ?>
                    </h3>
                    <?php if ($to_assign) : ?>
                    <p>Assign description to title</p>
                    <select name="assign_to">
                        <?php foreach ($multilang_titles as $stitle) : ?>
                        <option value="<?= $stitle->Text_ID ?>"><?= $stitle->en ?></option>
                        <?php endforeach; ?>
                    </select>
                    <hr>
                    <?php endif; ?>
                  
                        <textarea class="form-control" id="editor" name="s_txt"></textarea>
                       
                    <hr>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <h3 class="text-center">Translate to seleted languages</h3>
                    <div>
                        <?php foreach ($langs as $lang) {
                            echo "<label>{$lang->lang_name}</label>";
                            echo "<input type='checkbox' value='{$lang->code}' name='tolangs[{$lang->code}]' />";
                        } ?>
                    </div>
                    <hr>
                    <div class="text-center">
                        <button class="btn btn-warning" type="submit">Translate</button>
                    </div>

                </div>
            </div>
        </div>
    </div>


    </form>






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