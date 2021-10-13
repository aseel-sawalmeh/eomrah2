<div class="content-wrapper">
    <div class="card text-center">
        <div class="card-header">
            <h3>Add New Product Category</h3>

        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mx-auto">
                  
                    <?= form_open("pcat_add_go")?>
                        <h4><?= validation_errors() ?></h4>

                        <div class="form-group">
                            <label>Category Name</label>
                            <input type="text" class="form-control" name="pcat_name" />

                        </div>

                        <div class="form-group">
                            <label>Category Description</label>
                            <textarea class="form-control" name="pcat_desc"></textarea>
                        </div>

                        <div class="form-group">
                            <label>Category Order</label>
                            <input type="number" class="form-control" name="pcat_order" />
                        </div>
                    <!--<div class="form-group">
                            <select name="pcat_parent" class="form-control">
                                <option value="" disabled="" selected="" hidden="">Choose Category Parent</option>
                                <optgroup label="All Categories">
                                    <option value="">No Parent</option>
                                    <?php foreach ($pcats as $cat) : ?>
                                        <option value="<?= $cat->P_Category_ID ?>"><?= $cat->P_Category_Name ?></option>
                                    <?php endforeach; ?>
                                </optgroup>
                            </select>
                        </div>-->
                  
                        <input type="submit" class="btn btn-success btn-block" value="Add" />





                   <?=form_close()?>
                </div>
            </div>

        </div>
    </div>
</div>