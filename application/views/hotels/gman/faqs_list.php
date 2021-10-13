<div class="content-wrapper">
    <div class="card">
        <div class="card-body">
            <div class=" mr-3 mt-3">
                <a href="<?= site_url('gman/faqs/add') ?>" class="btn btn-info"><?=lang('addfaqs')?></a>
            </div>
            <div class="text-center text-success">
                <?php echo $this->session->flashdata('faq_added'); ?>
                <?php echo $this->session->flashdata('faq_deleted'); ?>
            </div>
            <div class="row mt-4">
                <div class="col mx-auto">

                    <table class="table table-stripped">
                        <thead>
                            <th><?=lang('category')?></th>
                            <th><?=lang('question')?></th>
                            <th><?=lang('answer')?></th>
                            <th><?=lang('edit')?></th>
                            <th><?=lang('delete')?></th>
                        </thead>
                        <tbody>
                            <?php foreach ($faqs as $f) : ?>
                                <tr>
                                    <td><?= lang($f->cat_name) ?></td>
                                    <td><?php echo tolang($f->id, 'qsfaq')?></td>
                                    <td><?php echo tolang($f->id, 'ansfaq')?></td>
                                    <td><a href="<?=site_url('gman/faqs/edit/').$f->id?>" class="btn btn-primary"><?=lang('edit')?></a></td>
                                    <td><a href="<?=site_url('gman/faqs/delete/').$f->id?>" class="btn btn-danger"><?=lang('delete')?></a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>