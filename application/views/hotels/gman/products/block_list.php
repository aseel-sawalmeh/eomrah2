<div id="page_content">
    <div id="page_content_inner">
        <div class="md-card uk-margin-medium-bottom">
            <div class="md-card-content">
                <div class="uk-overflow-container">


                </div>
            </div>
        </div>
    </div>
</div>
<div class="content-wrapper">
    <div class="card">
        <div class="card-header">
            <div class="text-center">
                <h3>Product Position</h3>
            </div>
        </div>
        <div class="card-body mx-auto">
            <table class="table table-responsive border">
                <thead>
                    <th>number</th>
                    <th>location</th>
                    <th>Max Items</th>
                    <th>action</th>
                </thead>
                <tbody>
                    <?php foreach ($blocks as $block) : ?>
                        <tr>
                            <td><?= $block->Block_ID ?></td>
                            <td><?= $block->Place ?></td>
                            <td>
                                <form method="POST" action="<?= base_url('gman') ?>/prodblocks/max_item">
                                    <input type="hidden" name="blockid" value="<?= $block->Block_ID ?>" />
                                    <input type="text" name="maxitems" size="3" value="<?= $block->Max_Items ?>" />
                                    <input type="submit" value="Set" />
                                </form>
                            </td>
                            <td><a href="<?= base_url('gman') ?>/prodblocks/block_items/<?= $block->Block_ID ?>">product list</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>