<div class="content-wrapper">
    <div class="row">
        <div class="col mx-auto">
            <div class="card">
                <div class="card-header">
                    <?php if ($this->session->flashdata('pcat_add_mgs') !== NULL) {
                echo "<h4 style='text-align:center; color:Green'>";
                echo $this->session->flashdata('pcat_add_mgs');
                echo "</h4>";
            } ?>
                    <a href="<?= base_url('gman') ?>/pcategory/add" class="btn btn-primary">Add New Product Category</a>
                </div>
                <div class="card-body">
                <div class="table-responsive">
                <table class="table table-hover table-bordered text-nowrap">
                        <thead>
                            <tr>
                                <th>Category Name</th>
                                <th>Category Description</th>
                                <th>Category Type</th>
                                <th>Category Order</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pcats as $category) : ?>
                            <tr>
                                <td class="text-center"><?= $category->P_Category_Name ?></td>
                                <td class="text-center"><?= $category->P_Category_Description ?></td>
                                <td class="text-center">
                                    <?php
                                if ($category->P_Category_Parent == 0) {
                                    echo "Main Category";
                                } else {
                                    echo "Sub Category";
                                }
                                ?>
                                </td>
                                <td class="text-center"><?= $category->P_Category_Order ?></td>
                                <td class="text-center">
                                    <a href="<?= base_url('gman') ?>/pcategory/edit/<?= $category->P_Category_ID ?>"
                                        class="btn btn-success">Edit</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                
                </div>
             
                </div>
            </div>

        </div>
    </div>

</div>