<!-- Table Cutt of users -->
<div id="page_content">
    <div id="page_content_inner">
        <div class="md-card uk-margin-medium-bottom">
            <div class="md-card-content">
                <div class="uk-overflow-container">
                    <h2> Hotel : <?=$user_hotel->Hotel_Name?> </h2>
                    <!-- Photos form product -->
                    <div class="uk-grid uk-grid-medium" data-uk-grid-margin="">
                        <div class="uk-width-xLarge-2-10 uk-width-large-3-10 uk-row-first">
                            <div class="md-card">
                                <div class="md-card-toolbar">
                                    <h3 class="md-card-toolbar-heading-text">
                                        Upload Photo
                                    </h3>
                                </div>
                                <div class="md-card-content">
                                    <div class="uk-margin-bottom uk-text-center">
                                        <!-- multi upload -->
                                        <?php echo form_open_multipart('gman/hotel_system/husers/prodImgs', ['id'=>'pordimgup', 'name'=>'pordimgup']);?>
                                        <input type="hidden" name="product_id" value="<?=$product->P_ID?>" />
                                        <p>Upload file(s):</p>
                                        <?php echo form_error('uploadedimages[]'); ?>
                                        <?php echo form_upload('uploadedimages[]','','multiple'); ?>
                                        <hr />
                                        <?php echo form_submit('submit', 'Upload');?>
                                        <?php echo form_close();?>
                                        <!-- multi upload -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="uk-width-xLarge-8-10 uk-width-large-7-10">
                            <div class="md-card">
                                <div class="md-card-toolbar">
                                    <h2 class="md-card-toolbar-heading-text">
                                        Product <span class="md-bg-light-green-A400">[ <?=$product->P_Name?>
                                            ]</span><?=$product->P_Name?> Photos
                                    </h2>
                                </div>
                                <div class="md-card-content large-padding">
                                    <div class="uk-grid uk-grid-divider uk-grid-medium">
                                        <?php if($product_photos): ?>
                                        <?php foreach($product_photos as $p_photo): ?>
                                        <!-- col -->
                                        <div class="uk-width-large-1-5">
                                            <form method="post"
                                                action="<?=base_url()?>gman/products/order_product_photo">
                                                <input type="hidden" name="toback" value="<?=uri_string()?>" />
                                                <input type="hidden" name="product_id" value="<?=$product->P_ID?>" />
                                                <input type="hidden" name="photo_id" value="<?=$p_photo->Photo_ID?>" />
                                                <label>image order</label>
                                                <input type="text" name="photo_order"
                                                    value="<?=$p_photo->Photo_Order?>" />
                                                <input type="submit" value="update order" />
                                            </form>
                                            <hr class="uk-grid-divider uk-hidden-large">
                                            <img src="<?=base_url('assets/images/products/').$p_photo->Photo_Name?>" />
                                            <form method="post"
                                                action="<?=base_url()?>gman/products/delete_product_photo">
                                                <input type="hidden" name="toback" value="<?=uri_string()?>" />
                                                <input type="hidden" name="product_id" value="<?=$product->P_ID?>" />
                                                <input type="hidden" name="photo_id" value="<?=$p_photo->Photo_ID?>" />
                                                <input type="submit" value="Delete photo" />
                                            </form>
                                        </div>
                                        <!-- /col -->
                                        <?php endforeach; ?>
                                        <?php else :?>
                                        <h3> Note This Product Has No Images </h3>
                                        <?php endif;?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div> <a class="md-btn md-btn-primary"
                            href="<?=base_url("gman/hotel_system/husers/activate/$user_id")?>"> Continue
                            activation</a>
                        <hr>
                    </div>
                    <!-- Photos form product -->
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?=base_url('admin_design/assets/js/custom/gmap.js')?>"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=<?= API_GOOGLE ?>&libraries=places&callback=initMap" async
    defer></script>