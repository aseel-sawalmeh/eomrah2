
<div class="content-wrapper">
    
    <div class="row">
        <div class="col-sm-6 mx-auto">
            <div class="card mt-4">
                <div class="card-header">
                <h2 class="text-center">Request Providing</h2>
                </div>
                <div class="card-body">

                    <form action="" method="post">
                        <input type="hidden" name="activate_for" value="<?= $activate_for ?>" />
                        <h3 class="text-center">select one from the list</h3>
                        <?= "<span style='color:red'>" . form_error('hotels') . "</span>" ?>
                        <select class="form-control mx-auto" name="hotels">
                            <?php foreach ($hotels as $hotel) : ?>
                                <option value="<?= $hotel->Hotel_ID ?>"> <?= $hotel->Hotel_Name ?> </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if ($activate_for !== false) : ?>
                            <div class="text-center">
                                <button type="submit" class="btn btn-success mt-3">Request Providing</button>
                    </form>
                    <br> 
                    Or
                    <br>
                    <a class="btn btn-info" href="<?= base_url("chotel/huserinit") ?>">Make Hotel Request</a>
                </div>

            <?php else : ?>
                <h4>Only A provider Can request That</h4>
            <?php endif; ?>
            </div>
        </div>
    </div>
</div>

