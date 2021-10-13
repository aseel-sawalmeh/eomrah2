<div class="content-wrapper">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md">
                    <h2 class="text-center"> Hotel : <?php echo "{$providedhotel->Hotel_Name}"; ?></h2>
                    <hr>
                    <p>Hotel address : <?php echo $providedhotel->Hotel_Address; ?> </p>
                </div>
            </div>

        </div>
        <div class="card-body">
            <ul class="list-inline">
                <div class="row">
                    <div class="col-sm">
                        <li class="list-inline-item">
                            <a class="btn" href="<?= site_url("chotel/hotel_manage/comset/{$provider->Provider_ID}") ?>"> Period / Price </a>
                        </li>
                        <li class="list-inline-item"><a class="btn" href="<?= base_url("chotel/hotel/addroom/{$provider->Hotel_ID}/{$provider->Provider_ID}") ?>">Hotel Editing</a></li>

                        <li class="list-inline-item"><a class="btn" href="<?= base_url("chotel/hotel_manage/room_supplements/{$provider->Provider_ID}") ?>">Supplements</a></li>

                        <li class="list-inline-item"><a class="btn" href="<?= base_url("chotel/hotel_manage/room_availability/{$provider->Provider_ID}") ?>">Availability</a></li>

                        <li class="list-inline-item"><a class="btn" href="<?= base_url("chotel/hotel_manage/payment_config/{$provider->Provider_ID}") ?>">Payment</a></li>

                        <div class="btn-group">
                            <button type="button" class="btn">Promotions</button>
                            <button type="button" class="btn dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            </button>
                            <div class="dropdown-menu">

                                <a class="dropdown-item" href="<?= base_url("chotel/hotel_manage/discount_code/{$provider->Provider_ID}") ?>"> Discount Code </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="<?= base_url("chotel/hotel_manage/discount_code_cal/{$provider->Provider_ID}") ?>">Discount Code Calender</a>
                            </div>
                        </div>
                    </div>
                </div>
            </ul>

        </div>



    </div>
