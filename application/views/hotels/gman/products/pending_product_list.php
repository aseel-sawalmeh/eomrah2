=
<div class="content-wrapper">
    <div class="card">
        <div class="card-header">
            <div class="text-center">
                <?php if ($this->session->flashdata('product_add_mgs') !== NULL) {
                    echo "<h4 style='text-align:center; color:Green'>";
                    echo $this->session->flashdata('pcat_add_mgs');
                    echo "</h4>";
                } ?>
                <a href="<?= base_url('gman') ?>/product/add" class="btn btn-primary">Add New Product Product</a>
            </div>

        </div>
        <div class="card-body">
            <table class="table table-hover table-responsive">
                <thead>
                    <tr>

                        <th>Product Name</th>
                        <th>Publish Date</th>
                        <th>Product Type</th>
                        <th>Product Order</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($inactive_products) : ?>
                    <?php foreach ($inactive_products as $product) : ?>
                    <tr>

                        <td><?= $product->P_Name ?></td>
                        <td>
                            <?= nice_date($product->Create_Date, 'd-m-Y') ?>
                        </td>
                        <td class="text-center">
                            <?php
                                    if ($product->P_Category_ID == 1) {
                                        echo "Hotel";
                                    } else {
                                        echo "Blog";
                                    }
                                    ?>
                        </td>
                        <td class="text-center"><?php if ($product->Availability == 0) {
                                                            echo "Not Available";
                                                        } else {
                                                            echo "Available";
                                                        } ?>
                        <td class="text-center">
                            <a class="btn-block btn-info"
                                href="<?= base_url('gman') ?>/product/edit/<?= $product->P_ID ?>"></a>

                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>