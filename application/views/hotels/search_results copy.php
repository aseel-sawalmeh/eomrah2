<div class="container p-4 bg-white mt-3">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-3 mt-3">
                    <input type="text" class="form-control" name="" id="" placeholder="Search Hotels">
                    <br>
                    <div class="border">
                        <ul class="list-group">
                            <li class="list-group-item">Category One</li>
                            <li class="list-group-item">Category Two</li>
                            <li class="list-group-item">Category Three</li>
                            <li class="list-group-item">Category Four</li>
                            <li class="list-group-item">Category Five</li>
                        </ul>
                    </div>

                </div>

                <div class="col-md-9 mt-3">
                    <div class="row">
                        <div class="col-sm-12">
                            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                    <span class="navbar-toggler-icon"></span>
                                </button>

                                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                    <ul class="navbar-nav mr-auto">
                                        <li class="nav-item dropdown">
                                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Sort By
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                                <a class="dropdown-item" href="#">Highest</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="#">Lowest</a>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </nav>
                        </div>
                    </div>


                    <?php if ($rec_results) : foreach ($rec_results as $res) : if (isset($res['hotel'])) : $hotel_name = tolang($res['hotel']->Hotel_ID, 'hotelname') ? tolang($res['hotel']->Hotel_ID, 'hotelname') : $res['hotel']->Hotel_Name;
                                $hotel_desc = tolang($res['hotel']->Hotel_ID, 'hoteldesc') ? tolang($res['hotel']->Hotel_ID, 'hoteldesc') : $res['hotel']->Hotel_Description;

                    ?>

                                <div class="row pt-2 pr-3 border mt-2">
                                    <div class="col-sm col-md-4">
                                        <a href="<?php echo base_url("hotel/details/") . $res['hotel']->hslug . '/' . $res['ProviderID'] . "/$startdate/$enddate/$adultscount"; ?>"><img src="<?php echo himg($res['hotel']->Main_Photo); ?>" class="img-fluid" alt="">
                                        </a>
                                    </div>
                                    <div class="col-sm col-md-6">
                                        <h4><a style="color:black;font-weight:bold;font-family: Georgia, 'Times New Roman', Times, serif;" href="<?php echo base_url("hotel/details/") . $res['hotel']->hslug . '/' . $res['ProviderID'] . "/$startdate/$enddate/$adultscount"; ?>"><?= $hotel_name ?></a>
                                        </h4>

                                        <p><?php echo $hotel_desc ?></p>
                                    </div>
                                    <div class="col-sm col-md-2">
                                        <span>
                                            <?php for ($i = 0; $i < $res['hotel']->Star_Nums; $i++) {
                                                echo '<i class="fa fa-star" style="color:gold"></i>';
                                            } ?>
                                        </span>
                                        <?php if (isset($res['ref'])) : ?>
                                            <?php $totalprice = ($res['price_per_night'] * $res['nights_count']) + ($res['ref']['price_per_night'] * $res['ref']['nights_count']); ?>
                                        <?php else : ?>
                                            <?php $totalprice = ($res['price_per_night'] * $res['nights_count'] * $res['res_count']); ?>
                                        <?php endif; ?>
                                        <p>
                                            <?= curcal($totalprice) . ' ' . usercur() ?>
                                        </p>
                                        <div class="mb-2">
                                            <a href="<?php echo base_url("hotel/details/") . $res['hotel']->hslug . '/' . $res['ProviderID'] . "/$startdate/$enddate/$adultscount"; ?>" class="btn-sm btn-danger">Book</a>
                                            <?php if (isset($res['hotel']->geo)) {
                                                $shmp = '<a class="btn-sm btn-warning" href="#" ';
                                                $shmp .= " data-toggle='modal' data-target='#MapModal' ";
                                                $shmp .= 'onclick=" shmap(';
                                                $shmp .= "'" . $res['hotel']->hslug . "')" . '" >Map</a>';
                                                echo $shmp;
                                            } ?>

                                        </div>

                                    </div>

                                </div>
                        <?php endif;
                        endforeach;
                    else : ?>
                        <div class="row">
                            <div class="col alert alert-danger">
                                <h3 class="text-center ">No Resutls</h3>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="MapModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center" id="exampleModalLabel">View On Map</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="map" style="width: 100%; height:600px"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    let hotelloc = <?php echo $rec_js ?>;

    function shmap(hotel) {
        //console.log(hotel + ' map shown');
        //hotellloc = hotel + ' map shown';
    }

    // Initialize and add the map
    function initMap() {

        // The location of makka
        var makka = {
            lat: 21.3890824,
            lng: 39.8579118
        };

        for (var h in hotelloc) {
            //console.log(hotelloc[h].hotel);
            var hotel = hotelloc[h].hotel;
            //console.log(hotel);
            var pos = {
                lat: 21.4316491,
                lng: 39.8291253
            };
            // The map, centered at makka
            var map = new google.maps.Map(
                document.getElementById('map'), {
                    zoom: 12,
                    center: pos
                });
            // The marker, positioned at grand makkah hotel
            var marker = new google.maps.Marker({
                position: pos,
                map: map,
                title: "Grand Makkah Hotel"
            });

            var infowindow = new google.maps.InfoWindow({
                content: `<div><a href='${dataParams.surl}/hotel/d/Grand-Makkah-Hotel?pv=7&dt1=04/15/2020%20&dt2=%2004/16/2020&adults=1' ><h6>Grand Makkah Hotel</h6></a><br><img src='${dataParams.burl}/assets/images/products/travelinksa20200302153140.jpg' height='80' width='120' /><br> <h6>Price Per Night: 127 SAR</h6></div>`
            });

            marker.addListener('click', function() {
                infowindow.open(map, marker);
            });

        }

    }
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?= API_GOOGLE ?>&callback=initMap">
</script>