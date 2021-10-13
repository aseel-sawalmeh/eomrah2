<div class="content-wrapper">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mx-auto">
                    <form action="<?= site_url('gman/providers/activate/').$p_id ?>" method="post" class="form">
                        <input type="hidden" name="activator" value="<?= $admin_user_id ?>" />
                        <?= "<span style='color:red'>" . form_error('markup') . "</span>" ?>
                        <div class="form-group">
                            <label>MarkUp</label>
                            <input type="text" class="form-control" name="markup" value="<?= set_value('markup') ?>" />
                        </div>
                        <div class="form-group">
                            <?= "<span style='color:red'>" . form_error('discount') . "</span>" ?>
                            <label>Discount</label>
                            <input type="text" class="form-control" name="discount" value="<?= set_value('discount') ?>" />
                        </div>
                        <div class="form-group">
                            <h5>Allow For B2C</h5>
                            <input type="checkbox" checked name="allowb2c" value="1" />
                            <h5>Allow For B2B</h5>
                            <input  type="checkbox" checked name="allowb2b" value="1" />
                        </div>
                        <button type="submit" class="btn btn-success">Activate</button>
                    </form>
                </div>
            </div>

        </div>

    </div>
</div>