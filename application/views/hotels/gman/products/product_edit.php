<script src="<?= base_url('admin_design/editor/samples/css/sample.css') ?>"></script>
<script src="<?= base_url('admin_design/editor/samples/toolbarconfigurator/lib/codemirror/neo.css') ?>"></script>

<div class="content-wrapper">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-edit"></i>
                <?php
                function selectcat($prod_cat, $catid)
                {
                    if ($prod_cat == $catid) {
                        return "SELECTED";
                    }
                }
                ?>
                <h3 class="text-center"> <?=lang('edit')?> <span>
                    <?= $product->P_Name ?></span> </h3>
                <h3 class="text-center"> <?= $this->session->flashdata('statusMsg') ?></h3>
            </h3>
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-12">
                    <div class="nav flex-column nav-pills h-nav" id="v-pills-tab" role="tablist"
                        aria-orientation="vertical">
                        <a class="nav-link active " id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home"
                            role="tab" aria-controls="v-pills-home" aria-selected="true"><?=lang('blogdetails')?></a>
                        <!-- <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile"
                            role="tab" aria-controls="v-pills-profile" aria-selected="false">Photos</a> -->
                        <!-- <a class="nav-link" id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-messages"
                            role="tab" aria-controls="v-pills-messages" aria-selected="false">Multimedia</a>
                        <a class="nav-link" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-settings"
                            role="tab" aria-controls="v-pills-settings" aria-selected="false">Multiproduct</a> -->
                        <a class="nav-link" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-Languages"
                            role="tab" aria-controls="v-pills-settings" aria-selected="false"><?=lang('language')?></a>
                    </div>

                </div>
            </div>
            <div class="row border mt-3">
                <div class="col-12 mt-3">
                    <div class="tab-content" id="vert-tabs-right-tabContent">

                        <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel"
                            aria-labelledby="v-pills-home-tab">

                            <?= validation_errors() ?>

                            <?=form_open("gman/products/edit/{$product->P_ID}", ['method'=>'post', 'enctype'=>'multipart/form-data']);?>
                            <input type="hidden" name="product_id" value="$product->P_ID" />
                            <div class="form-group">
                                <label><?=lang('language')?></label>
                                <select class="form-control" name='source_lang' required>
                                    <option value=''>None</option>
                                    <option value='en'>English</option>
                                    <option value='ar'>Arabic</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label><?=lang('blogslug')?></label>
                                <input disabled type="text" class="form-control" name="product_slug"
                                    value='<?= $product->b_slug ?>' />
                            </div>
                            <div class="form-group">
                                <label><?=lang('blogname')?></label>
                                <textarea class="form-control" name="product_name" ><?= $product->P_Name ?></textarea>
                              
                            </div>
                           
                           
                            <!-- <div class="form-group">
                                <label>Inserted By</label>
                                <input readonly type="text" class="form-control" name="Registered_By"
                                    value="<?= $_SESSION['hotels/gman_fullName'] ?>" />
                            </div> -->
                            <div class="form-group">
                                <label><?=lang('blogdesc')?></label>
                                <textarea id="editor2" name='product_desc'
                                    class="form-control"><?= $product->Description ?></textarea>
                            </div>
                            <div class="form-group">
                                <label><?=lang('state')?></label>
                                <select class="form-control" name="product_state">
                                    <option <?php if ($product->State == 1) {
                    echo "SELECTED";
                } ?> value="1">Activate</option>
                                    <option <?php if ($product->State == 0) {
                    echo "SELECTED";
                } ?> value="0">Deactivate</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label><?=lang('catname')?></label>
                                <select id="prodcat" name="pcat" class="form-control">

                                    <option value="" disabled="" selected="" hidden="">Choose
                                        product Category</option>

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


                                </select>
                            </div>


                            <?php function slct($s1, $s2)
                                        {
                                            if ($s1 == $s2) {
                                                echo "SELECTED";
                                            }
                                        } ?>

                            <div class="form-group">
                                <h4><?=lang('blogphoto')?></h4>

                                <label for="main-pic"></label>
                                <input id="main-pic" type="file" name="mainphoto" />

                            </div>


                            <div class="form-group">

                                <h4><?=lang('blogphoto')?></h4>
                                <img src="<?= base_url('assets/images/products/') . $product->Product_Main_Photo ?>"
                                    class="img-fluid">
                            </div>

                            <div class="text-center p-2">
                                <button type="submit" class="btn btn-block btn-success"><?= lang('conf')?></button>
                            </div>

                            </form>

                        </div>


                        <div class="tab-pane fade mt-0" id="v-pills-profile" role="tabpanel"
                            aria-labelledby="v-pills-profile-tab">
                            <h3 class="text-center">
                                Upload Photo
                            </h3>
                            <hr>
                            <?php echo form_open_multipart('gman/multi_upload'); ?> <input type="hidden"
                                name="product_id" value="<?= $product->P_ID ?>" />
                            <p>Upload file(s):</p>
                            <?php echo form_error('uploadedimages[]'); ?>
                            <?php echo form_upload('uploadedimages[]', '', 'multiple'); ?>
                            <br />
                            <br />
                            <?php echo form_submit('submit', 'Upload'); ?>
                            <?php echo form_close(); ?>
                            <hr>
                            <?php if ($product_photos) : ?>
                            <?php foreach ($product_photos as $p_photo) : ?>

                            <div class="uk-width-large-1-5">
                                <form method="post" action="<?= base_url() ?>gman/products/order_product_photo">
                                    <input type="hidden" name="product_id" value="<?= $product->P_ID ?>" />
                                    <input type="hidden" name="photo_id" value="<?= $p_photo->Photo_ID ?>" />
                                    <label>image order</label>
                                    <input type="text" name="photo_order" value="<?= $p_photo->Photo_Order ?>" />
                                    <input type="submit" value="update order" />
                                </form>
                                <hr class="uk-grid-divider uk-hidden-large">
                                <img src="<?= base_url('assets/images/products/') . $p_photo->Photo_Name ?>"
                                    class="img-fluid" width="300" height="100" />
                                <form method="post" action="<?= base_url() ?>gman/products/delete_product_photo">
                                    <input type="hidden" name="product_id" value="<?= $product->P_ID ?>" />
                                    <input type="hidden" name="photo_id" value="<?= $p_photo->Photo_ID ?>" />
                                    <input type="submit" class="btn btn-danger mt-2" value="Delete photo" />
                                </form>
                            </div>

                            <?php endforeach; ?>
                            <?php else : ?>
                            <h3 class="text-center"> Note This Product Has No Images </h3>
                            <?php endif; ?>

                        </div>



                        <div class="tab-pane fade" id="v-pills-messages" role="tabpanel"
                            aria-labelledby="v-pills-messages-tab">
                            <?=form_open('gman/products/set_product_multimedia')?>
                            <input type="hidden" name="product_id" value="<?= $product->P_ID ?>" />
                            <select name="mediacategory">
                                <?php foreach ($multimedia_cats as $multicat) : ?>
                                <option value="<?= $multicat->MC_ID ?>"><?= $multicat->MC_Name ?></option>
                                <?php endforeach; ?>
                            </select>
                            <input type="text" name="mediatext" />
                            <input type="submit" value="save" />
                            </form>

                            <div>
                                <hr>
                                <h4>Multimedia data</h4>
                                <?php if ($product_multimedia) : ?>
                                <?php foreach ($product_multimedia as $pm) : ?>
                                <span><?= $pm->MC_Name ?> </span>
                                <?php if (!null == $pm->Cat_Val && $pm->MultiMedia_Category_ID == 1) : ?>
                                <iframe id="ytplayer" type="text/html" width="250" height="200"
                                    src="https://www.youtube.com/embed/<?= $pm->Cat_Val ?>" frameborder="0"
                                    allowfullscreen></iframe>
                                <?php endif; ?>
                                <hr>
                                <?php endforeach; ?>
                                <?php else : ?>
                                <span>No media entries for this product</span>
                                <?php endif; ?>
                            </div>

                        </div>


                        <div class="tab-pane fade" id="v-pills-settings" role="tabpanel"
                            aria-labelledby="v-pills-settings-tab">
                            <?=form_open('gman/products/sub_product_add')?>
                            <input type="hidden" value="<?= $product->P_ID ?>" name="mainproduct_id" />
                            <p>Select Sub Products</p>
                            <select class="form-control" name="subproduct_id">
                                <option>Select a Sub Product</option>
                                <?php if (!empty($selectproducts)) : ?>
                                <?php foreach ($selectproducts as $sp) : ?>
                                <option value="<?= $sp->P_ID ?>"><?= $sp->P_Name ?></option>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <div class="text-center mt-2">
                                <input type="submit" class="btn btn-info" value="Add to sub list" />
                            </div>

                            </form>
                        </div>
                        <div class="tab-pane fade" id="v-pills-Languages" role="tabpanel"
                            aria-labelledby="v-pills-settings-tab">
                            <!-- <a class="btn btn-primary" type="button"
                                href="<?= base_url() ?>gman/prodtxt/add/prodtitle/<?= $product->P_ID ?>">
                                add new title
                            </a>
                            <br><br>
                            <a class="btn btn-primary" type="button"
                                href="<?= base_url() ?>gman/prodtxt/add/proddesc/<?= $product->P_ID ?>">
                                add new Description
                            </a> -->
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap text-center mt-3">
                                    <thead>
                                        <tr>
                                            <td>type</td>
                                            <!-- <td>Order</td>
                                            <td>Assigned to</td> -->
                                            <td>English </td>
                                            <td>Arabic </td>
                                            <td><?=lang('delete')?></td>
                                            <td><?=lang('edit')?></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($multilang_titles) : foreach ($multilang_titles as $title) : if ($title->assign_to == null) : ?>
                                        <tr class="uk-text-primary">
                                            <td>title</td>
                                            <!-- <td>
                                                <form method="post">
                                                    <input type="text" class="form-control" name="order"
                                                        value="<?= $title->R_Order ?>" size="1" />
                                                    <input type="submit" class="btn btn-warning mt-2"
                                                        value="<?= comtrans('btnorder') ?>" />
                                                </form>
                                            </td> -->
                                          
                                            <td>
                                                <div class="col-12">
                                                    <?= $title->en ?>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="col-12">
                                                    <?= $title->ar ?>
                                                </div>


                                            </td>
                                            <td>
                                                <a class="btn btn-danger"
                                                    href="<?= base_url() ?>gman/prodtxt/delete/<?= $product->P_ID . "/" . $title->Text_ID . "/prodtitle" ?>"><?= comtrans('btndel') ?></a>

                                            </td>
                                            <td> <a class="btn btn-primary"
                                                    href="<?= base_url() ?>gman/prodtxt/edit/<?= $product->P_ID . "/" . $title->Text_ID . "/prodtitle" ?>"><?= comtrans('btnedit') ?></a>
                                            </td>
                                        </tr>
                                        <?php endif;
                                            $descs = $this->Translate_model->product_desc($productid);
                                            if ($descs) : foreach ($descs as $desc) : ?>
                                        <tr>
                                            <td>desc</td>
                                            <!-- <td>
                                                <form method="post">
                                                    <input type="text" class="form-control" name="order"
                                                        value="<?= $desc->R_Order ?>" size="1" />
                                                    <input type="submit" class="btn btn-warning mt-2"
                                                        value="<?= comtrans('btnorder') ?>" />
                                                </form>



                                            </td> -->
                                            <!-- <td class="uk-text-primary"><?= $title->en ?></td> -->
                                            <td><?= $desc->en ?></td>
                                            <td><?= $desc->ar ?></td>
                                            <td>
                                                <a class="btn btn-danger"
                                                    href="<?= base_url() ?>gman/prodtxt/delete/<?= $product->P_ID . "/" . $desc->Text_ID . "/proddesc" ?>"><?= comtrans('btndel') ?></a>

                                            </td>
                                            <td> <a class="btn btn-primary"
                                                    href="<?= base_url() ?>gman/prodtxt/edit/<?= $product->P_ID . "/" . $desc->Text_ID . "/proddesc" ?>"><?= comtrans('btnedit') ?></a>
                                            </td>
                                            <!-- <td>

                                                            <form action="<?= base_url() ?>gman/prodtxt/assign_desc_title" method="post">
                                                                <input type="hidden" name="prod_id" value="<?= $product->P_ID ?>" />
                                                                <input type="hidden" name="assigned" value="<?= $desc->Text_ID ?>" />
                                                                <select name="assign_for" class="form-control" onchange="this.form.submit()">
                                                                    <?php foreach ($multilang_titles as $stitle) : ?>
                                                                        <option <?= ($desc->assign_to == $stitle->Text_ID) ? "SELECTED" : '' ?> value="<?= $stitle->Text_ID ?>"><?= $stitle->en ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </form>

                                                        </td>-->
                                        </tr>
                                        <?php endforeach; endif; endforeach; endif;  ?>


                                    </tbody>
                                </table>


                            </div>



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