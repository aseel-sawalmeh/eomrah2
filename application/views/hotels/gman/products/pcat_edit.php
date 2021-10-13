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
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <form action="<?= base_url('gman/pcategory/edit/') . $pcat->P_Category_ID ?>" method="post">
                        <?= validation_errors() ?>
                        <input type="hidden" name="pcat_id" value="<?= $pcat->P_Category_ID ?>" />


                        <h3>Edit <span><?= $pcat->P_Category_Name ?></span> Category</h3>


                        <div class="form-group">
                            <label>Category Name</label>
                            <input type="text" class="form-control" name="pcat_name" value="<?= $pcat->P_Category_Name ?>" />
                        </div>


                        <div class="form-group">
                            <label>Category Description</label>
                            <textarea class="form-control" name="pcat_desc"><?= $pcat->P_Category_Description ?></textarea>
                        </div>



                        <div class="form-group">
                            <label>Category Order</label>
                            <input type="number" class="form-control" name="pcat_order" value="<?= $pcat->P_Category_Order ?>" />
                        </div>


                        <div class="form-group">
                            <input type="submit" class="btn btn-success" value="Edit">
                        </div>

                        <select name="pcat_parent" class="form-control">
                            <option value="" disabled="" selected="" hidden="">Choose Category Parent</option>
                            <optgroup label="All Categories">
                                <option value="">No Parent</option>

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
                    </form>
                </div>
            </div>
            <br>

            <div class="row">
                <div class="col-md-4">
                    <a class="btn btn-danger" href="<?= base_url() ?>gman/pcattxt/add/pcattitle/<?= $pcat->P_Category_ID ?>">category title translation</a>
                </div>
            </div>
            <br>

            <div class="row">
                <div class="col-md-3">
                <?php if (!empty($multilang_titles)) : ?>
                    <table class="table table-hover border">
                        <thead>
                            <thead>
                                <th>ar title</th>
                                <th>en title</th>
                                <th>Delete</th>
                                <th>Edit</th>
                            </thead>
                        </thead>
                        <tbody>
                           
                                <?php foreach ($multilang_titles as $mtitle) : ?>
                                    <tr>
                                        <td><?= $mtitle->ar ?></td>
                                        <td> <?= $mtitle->en ?></td>
                                        <td>
                                            <a class="btn btn-danger" href="<?= base_url() ?>gman/pcattxt/delete/<?= $pcat->P_Category_ID . "/" . $mtitle->Text_ID . "/pcattitle" ?>">Delete</a>

                                        </td>
                                        <td> <a class="btn btn-warning" href="<?= base_url() ?>gman/pcattxt/edit/<?= $pcat->P_Category_ID . "/" . $mtitle->Text_ID . "/pcattitle" ?>">edit</a></td>
                                    </tr>
                                <?php endforeach; ?>
                        
                        </tbody>
                    </table>
                    <?php else : ?>
                              
                              <b>This product Has no titles Multitexts</b>
                         
                      <?php endif; ?>


                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <a class="btn btn-danger" href="<?= base_url() ?>gman/pcattxt/add/pcatdesc/<?= $pcat->P_Category_ID ?>">category description translation</a>
                </div>
            </div>
            <div class="row">

                <div class="col-md-3">
                    <?php if (!empty($multilang_desc)) : ?>
                        <table class="table border table-hover">
                            <thead>
                                <tr>
                                    <td>ar desc</td>
                                    <td>en desc</td>
                                    <td>Delete</td>
                                    <td>Edit</td>
                                </tr>
                            </thead>
                            <tbody>

                                <?php foreach ($multilang_desc as $mdesc) : ?>
                                    <tr>
                                        <td><?= $mdesc->ar ?></td>
                                        <td> <?= $mdesc->en ?></td>
                                        <td>
                                            <a class="btn btn-danger" href="<?= base_url() ?>gman/pcattxt/delete/<?= $pcat->P_Category_ID . "/" . $mdesc->Text_ID . "/pcatdesc" ?>">Delete</a>

                                        </td>
                                        <td><a class="btn btn-success" href="<?= base_url() ?>gman/pcattxt/edit/<?= $pcat->P_Category_ID . "/" . $mdesc->Text_ID . "/pcatdesc" ?>">Edit</a></td>
                                    </tr>
                                <?php endforeach; ?>

                            </tbody>
                        </table>
                    <?php else : ?>

                        <b>This product Has no description Multitexts</b>

                    <?php endif; ?>
                </div>
            </div>

        </div>

    </div>
</div>