<div class="content-wrapper">

    <div class="card">
        <div class="card-body">
            <div class="col-sm-4 mx-auto">
                <?= $this->session->flashdata('maxlimit') ?>
                <form method="POST" action="<?= base_url('gman') ?>/prodblocks/add_item">
                    <input type="hidden" name="blockid" value="<?= $block_id ?>" />
                    <select name="prodid" class="form-control">
                        <?php foreach ($products as $product) : ?>
                            <option value="<?= $product->P_ID ?>"> <?= $product->P_Name ?> </option>
                        <?php endforeach; ?>
                    </select>
                    <input type="submit" class="btn btn-info" value="Add product to position" />
                </form>
                <a href="<?= base_url('gman') ?>/prodblocks/list">Back to Block list</a>
                <table class="table table-responsive table-hover border">
                    <thead>
                        <th>product name</th>
                        <th>product order</th>
                        <th>action</th>
                    </thead>
                    <tbody>
                        <?php if ($bitems) : ?>
                            <?php foreach ($bitems as $bitem) : ?>
                                <tr>
                                    <td><?= $bitem->P_Name ?></td>
                                    <td>
                                        <form method="POST" action="<?= base_url('gman') ?>/prodblocks/orderitem">
                                            <input type="hidden" name="blockid" value="<?= $block_id ?>" />
                                            <input type="hidden" name="itemid" value="<?= $bitem->ID ?>" />
                                            <input type="text" name="order" value="<?= $bitem->Item_Order ?>" size="3" />
                                            <input type="submit" value="Order" />
                                        </form>
                                    </td>
                                    <td><a href="<?= base_url('gman') ?>/prodblocks/del_item/<?= $block_id ?>/<?= $bitem->ID ?>">Delete</a> </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <p>This Position Has No Products</p>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>


</div>