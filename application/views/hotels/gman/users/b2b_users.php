<div class="content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 mt-3 bg-white p-3">
                <div class="table-responsive">


                    <table class="table table-bordered text-nowrap">
                        <thead>
                            <tr>
                                <th><?=lang('fullname')?></th>
                                <th><?=lang('companyname')?></th>
                                <th><?=lang('country')?></th>
                                <th><?=lang('mobilenumber')?></th>
                                <th><?=lang('phone_number')?></th>
                                <th><?=lang('em')?></th>
                                <th><?=lang('iban')?></th>
                                <th><?=lang('vat_num')?></th>
                                <th><?=lang('regnumber')?></th>
                                <th><?=lang('hajjlicense')?></th>
                                <th><?=lang('activate')?></th>
                                <th><?=lang('details')?></th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php foreach ($user as $u) : ?>

                                <tr>
                                    <td><?= $u->C_FullName ?></td>
                                    <td><?= $u->Company_Name ?></td>
                                    <td><?= $u->Country ?></td>
                                    <td><?= $u->C_MobileNumber ?></td>
                                    <td><?= $u->C_PhoneNumber ?></td>
                                    <td><?= $u->C_Email ?></td>
                                    <td><?= $u->IBAN ?></td>
                                    <td><?= $u->C_Vat ?></td>
                                    <td><?= $u->C_Reg ?></td>
                                    <td><?= $u->C_License ?></td>
                                    <?php if ($u->state == 0) : ?>
                                        <td>
                                            <a href="<?= site_url('gman/users/activate_b2b/') . $u->C_ID ?>" class="btn btn-block btn-outline-warning"><?=lang('activate')?></a>
                                        </td>
                                    <?php else : ?>
                                        <td>
                                            <p><?=lang('activated')?></p>
                                        </td>

                                    <?php endif; ?>
                                    <td>
                                        <a href="<?= site_url('gman/users/b2b_user_details/') . $u->C_ID ?>" class="btn btn-block btn-outline-success">
                                        <?=lang('details')?></a>
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