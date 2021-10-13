<div class="container-fluid">
    <div class="row mb-4 mt-3">
        <div class="col sm-6">
           <div class="card">
               <div class="card-header">
                    <h4><?=lang('paidres')?></h4>
               </div>
               <div class="card-body">
                    <h4><?= $paidres?></h4>
               </div>
           </div>

        </div>
        <div class="col sm-6">
           <div class="card">
               <div class="card-header">
                    <h4><?=lang('unpaidres')?></h4>
               </div>
               <div class="card-body">
                    <h4><?= $unpaidres ?></h4>
               </div>
           </div>

        </div>
        <div class="col sm-6">
           <div class="card">
               <div class="card-header">
                    <h4><?=lang('confres')?></h4>
               </div>
               <div class="card-body">
                    <h4><?=$confirmres?></h4>
               </div>
           </div>

        </div>
    </div>
    <div class="row mb-4 mt-3">
        <div class="col sm-6">
           <div class="card">
               <div class="card-header">
                    <h4><?=lang('totalsales')?></h4>
               </div>
               <div class="card-body">
                    <h4><?=$sales?></h4>
               </div>
           </div>

        </div>
        <div class="col sm-6">
           <div class="card">
               <div class="card-header">
                    <h4><?=lang('remainingdeposit')?></h4>
               </div>
               <div class="card-body">
                    <h4><?=$deposit?></h4>
               </div>
           </div>

        </div>
    </div>
    <div class="row bg-white">
        <div class="col-12">

            <div class="table-responsive">


                <table class="table table-borderd table-hover">
                    <thead>
                        <tr>
                            <th><?= lang('resno')?></th>
                            <th><?= lang('resdate')?></th>
                            <th><?= lang('checkin')?></th>
                            <th><?= lang('checkout')?></th>
                            <th><?= lang('totalrooms')?></th>
                            <th><?= lang('totalprice')?></th>
                            <th><?=lang('p_method')?></th>
                            <th><?=lang('status')?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($list): foreach($list as $l): ?>
                        <tr>
                            <td><a target="__blank" class="nav-link" target="__blank"
                                    href="<?=site_url('b2b/dashboard/pinv/').$l->reservation_ref?>"><?=$l->reservation_ref?></a>
                            </td>
                            <td><?=$l->ResDate?></td>
                            <td><?=$l->CheckInDate?></td>
                            <td><?=$l->CheckOutDate?></td>
                            <td><?=$l->TotalRoomCount?></td>
                            <td><?=$l->NetPrice?></td>
                            <?php if($l->Payment_method == 1):?>
                            <td>Pay At Hotel</td>
                            <?php else:?>
                            <td>Pay By Credit Card</td>
                            <?php endif;?>
                            <?php if($l->Paid == 0):?>
                            <td>Not Paid</td>
                            <?php else:?>
                            <td>Paid</td>
                            <?php endif;?>

                        </tr>
                        <?php endforeach; endif;?>

                    </tbody>

                </table>
            </div>


        </div>
    </div>
    <div class="row bg-white">
        <div class="col">
            <?=$pagination_links?>
        </div>
    </div>


</div>