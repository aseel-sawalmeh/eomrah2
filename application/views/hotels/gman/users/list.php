<!-- Table Cutt of users -->
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <a href="<?= site_url('gman') ?>/users/add" class="btn btn-primary mt-2 mb-2"><?=lang('addadmin')?></a>
            <h4 class="text-center text-warning"><?php echo $this->session->flashdata('user_add_msg');?></h4>
            <div class="card">
                <?php if ($this->session->flashdata('user_add_mgs') !== NULL) {
                    echo "<h4 style='text-align:center; color:Green'>";
                    echo $this->session->flashdata('user_add_mgs');
                    echo "</h4>";
                } ?>
                <div class="table-responsive">
                    <table class="table table-bordered text-nowrap">
                        <thead>
                            <tr>
                                <th><?=lang('fullname')?></th>
                                <th><?=lang('uname')?></th>
                                <th><?=lang('em')?></th>
                                <th><?=lang('phone_number')?></th>
                                <th><?=lang('options')?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($gman_users as $user) : ?>
                            <tr>
                                <td><?= $user->gman_FullName ?></td>
                                <td><?= $user->gman_Login ?></td>
                                <td><?= $user->gman_Email ?></td>
                                <td><?= $user->gman_phone ?></td>
                                <td>
                                    <a href="<?= site_url('gman') ?>/users/edit/<?= $user->gman_ID ?>"
                                        class="btn btn-success"><?=lang('edit_profile')?></a>
                                    <a href="#" class="btn btn-danger"><?=lang('delete')?></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>

                        </tbody>
                    </table>

                </div>

            </div>
            <?= $pagination_links ?>
        </div>
    </section>

</div>