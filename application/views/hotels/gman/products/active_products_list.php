<div class="content-wrapper">

    <div class="card">
        <div class="card-header">
            <?php if ($this->session->flashdata('product_add_mgs') !== NULL) {
                echo "<h4 style='text-align:center; color:Green'>";
                echo $this->session->flashdata('product_add_mgs');
                echo "</h4>";
                } ?>
            <a href="<?= site_url('gman') ?>/products/add" class="btn btn-primary"><?=lang('addblog')?></a>
        </div>
        <div class="card-body">
        <div class="table-responsive">
        <table class="table table-hover border text-nowrap">
                <thead>
                    <tr>

                        <th><?=lang('blogname')?></th>
                        <th><?=lang('publishdate')?></th>
                        <!-- <th>Product Type</th>
                         <th>Product Order</th>
                        <th>rename img</th>-->
                        <th><?=lang('options')?></th>  
                    </tr>
                </thead>
                <tbody>
                    <?php if ($active_products) : ?>
                    <?php foreach ($active_products as $product) : ?>
                    <tr>
                        <td><?= $product->P_Name ?></td>
                        <td>
                            <?= nice_date($product->Create_Date, 'd-m-Y') ?>
                        </td>
                        <!-- <td class="text-center">
                            <?php
                                    if ($product->P_Category_ID == 1) {
                                        echo "Hotel";
                                    } else {
                                        echo "Not Categorized";
                                    }
                                    ?>
                        </td>
                        <td class="text-center"><?php if ($product->Availability == 0) {
                                                            echo "Not Available";
                                                        } else {
                                                            echo "Available";
                                                        } ?></td>

                        <td class="text-center">
                            <?= $product->Product_Main_Photo ?>
                        </td> -->
                        <td>
                            <a class="btn btn-success"
                                href="<?= site_url('gman') ?>/products/edit/<?= $product->P_ID ?>"><?=lang('editblog')?></a>

                        </td>

                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        
        </div>
       
        </div>
    </div>



</div>