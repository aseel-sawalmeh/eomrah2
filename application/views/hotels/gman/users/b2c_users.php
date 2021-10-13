<div class="content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 bg-white mt-3 p-3">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th><?=lang('fullname')?></th>
                                <th><?=lang('uname')?></th>
                                <th><?=lang('em')?></th>
                                <th><?=lang('phone_number')?></th>
                                <th><?=lang('zip')?></th>
                                <th><?=lang('adr')?></th>
                                <th><?=lang('country')?></th>
                                <th><?=lang('city')?></th>
                            </tr>
                        </thead>
                        <tbody>
                        
                            <?php if($b2c_user): foreach ($b2c_user as $user) : ?>
                                <tr>
                                    <td><?= $user->Public_UserName ?></td>
                                    <td><?= $user->Public_UserFullName ?></td>
                                    <td><?= $user->Public_UserEmail ?></td>
                                    <td><?= $user->Public_UserPhone ?></td>
                                    <td><?= $user->Zip_Code ?></td>
                                    <td><?= $user->address ?></td>
                                    <td><?= $user->country ?></td>
                                    <td><?= $user->city ?></td>
                                </tr>
                            <?php endforeach; endif; ?>
                        </tbody>

                    </table>

                </div>

            </div>
        </div>


    </div>
</div>