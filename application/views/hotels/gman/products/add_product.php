<script src="<?= base_url('admin_design/editor/samples/css/sample.css') ?>"></script>
<script src="<?= base_url('admin_design/editor/samples/toolbarconfigurator/lib/codemirror/neo.css') ?>"></script>


<?php
    function selectcat($prod_cat, $catid)
    {
        if ($prod_cat == $catid) {
            return "SELECTED";
        }
    }
    ?>


<div class="content-wrapper">
    <div class="card">
        <div class="card-header">
            <h3> <?=lang('addblog')?> </h3>
            <h4 style="color:red"><?= validation_errors() ?></h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <?=form_open_multipart("gman/products/add")?>
                    <input type="hidden" name="product_id" />
                    <div class="form-group">
                        <label><?=lang('language')?></label>
                       <select name="source_lang" class="form-control" required>
                           <option value="">None</option>
                           <option value="en">English</option>
                           <option value="ar">Arabic</option>
                       </select>
                    </div>
                    <div class="form-group">
                        <label><?=lang('blogname')?></label>
                        <textarea type="text" class="form-control" name="product_name"></textarea>
                    </div>


                    <!-- <div class="form-group">
                        <label>Inserted By</label>
                        <input type="text" class="form-control" name="Registered_By"
                            value="<?php echo $this->session->userdata('User_data')['gman_fullName'];?>" readonly />
                    </div> -->
                  

                    <div class="form-group">
                        <label><?=lang('blogdesc')?></label>
                        <textarea id="editor2" name="product_desc"
                            class="form-control"></textarea>
                    </div>
                  


                    <div class="form-group">
                        <label><?=lang('state')?></label>
                        <select class="form-control" name="product_state">
                            <option value="1">Activate</option>
                            <option value="0">Deactivate</option>
                        </select>
                    </div>


                    <div class="form-group">
                        <label for="prodcat"><?=lang('blogcat')?></label>
                        <select id="prodcat" name="pcat" class="form-control">
                            <option value="" disabled="" selected="" hidden=""><?=lang('catname')?>
                            </option>
                            <optgroup label="All Categories">

                                <?php
                                        $cats = $this->Product_Categories_model->get_pcategories();
                                        foreach ($cats as $cat) {
                                            if ($cat->P_Category_Parent == 0) {
                                                echo "<option " . selectcat($prod_cat, $cat->P_Category_ID) . " value='$cat->P_Category_ID'>" . $cat->P_Category_Name . "</option>";
                                            }

                                            if ($this->Product_Categories_model->get_subcategories($cat->P_Category_ID)) {
                                                foreach ($this->Product_Categories_model->get_subcategories($cat->P_Category_ID) as $subcat) {
                                                    echo "<option " . selectcat($prod_cat, $subcat->P_Category_ID) . " value='$subcat->P_Category_ID'>- " . $subcat->P_Category_Name . "</option>";

                                                    if ($this->Product_Categories_model->get_subcategories($subcat->P_Category_ID)) {
                                                        foreach ($this->Product_Categories_model->get_subcategories($subcat->P_Category_ID) as $subofsubcat) {
                                                            echo "<option " . selectcat($prod_cat, $subofsubcat->P_Category_ID) . " value='$subofsubcat->P_Category_ID'> - - " . $subofsubcat->P_Category_Name . "</option>";
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                        ?>

                            </optgroup>
                        </select>

                    </div>


                    <div id="subcategoryholder" style="display:none">
                        <div class="md-input-wrapper md-input-filled">
                            <label for="prodsubcat">Product sub Category</label>
                            <select id="prodsubcat" name="subcat" class="form-control">
                                <option value="" disabled="" selected="" hidden="">Choose product Sub
                                    Category</option>
                                <option value="">Not Products</option>
                            </select>
                            <span class="md-input-bar "></span>
                        </div>
                        <span id="emptyparentcat"></span>
                    </div>



                    <h4><?=lang('blogphoto')?></h4>
                    <div>
                        <input id="main-pic" type="file" name="mainphoto" />
                    </div>
                    <br>
                    <div class="mb-2">
                        <input type="submit" class="btn btn-success btn-block" value="<?=lang('conf')?>">
                    </div>
                    <?=form_close()?>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="<?= base_url('admin_design/editor/ckeditor.js') ?>"></script>

<script>
if (CKEDITOR.env.ie && CKEDITOR.env.version < 9)
    CKEDITOR.tools.enableHtml5Elements(document);

// The trick to keep the editor in the sample quite small
// unless user specified own height.
CKEDITOR.config.height = 150;
CKEDITOR.config.width = 'auto';

var initSample = (function() {
    var wysiwygareaAvailable = isWysiwygareaAvailable(),
        isBBCodeBuiltIn = !!CKEDITOR.plugins.get('bbcode');

    return function() {
        var editorElement = CKEDITOR.document.getById('editor');

        // :(((
        if (isBBCodeBuiltIn) {
            editorElement.setHtml(
                'Hello world!\n\n' +
                'I\'m an instance of [url=https://ckeditor.com]CKEditor[/url].'
            );
        }

        // Depending on the wysiwygarea plugin availability initialize classic or inline editor.
        if (wysiwygareaAvailable) {
            CKEDITOR.replace('editor1');
            CKEDITOR.replace('editor2');

        } else {
            editorElement.setAttribute('contenteditable', 'true');
            CKEDITOR.inline('editor');

            // TODO we can consider displaying some info box that
            // without wysiwygarea the classic editor may not work.
        }
    };

    function isWysiwygareaAvailable() {
        // If in development mode, then the wysiwygarea must be available.
        // Split REV into two strings so builder does not replace it :D.
        if (CKEDITOR.revision == ('%RE' + 'V%')) {
            return true;
        }

        return !!CKEDITOR.plugins.get('wysiwygarea');
    }
})();

initSample();
</script>