<div class="content-wrapper">
    <div class="card">
        <div class="card-header">
            <div class="row mb-2">
                <div class="col">
                    <h1 class="text-center mt-2 text-dark">Welcome <span><?= $_SESSION['H_User_FullName'] ?></span></h1>
                </div>
            </div>
        </div>
    </div>
    <div class="card m-3">
        <div class="card-body">
            <div class="row">
                <div class="col-xl-6 col-md-12">
                    <div class="info-box bg-light">
                        <div class="info-box-icon bg-white">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <div class="info-box-content">
                            <p class="info-box-text">Active Hotels</p>
                            <h3 class="info-box-number"><?= $active_hotels_count ?></h3>
                           <a href="<?= base_url('chotel') ?>/provider/active_list" class="info-box-text">More info 
                           <i class="fas fa-arrow-circle-right "></i></a>
                        </div>
                   
                    </div>
                </div>
                <div class="col-xl-6 col-md-12">
                    <div class="info-box bg-light">
                    <div class="info-box-icon bg-white">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                        <div class="info-box-content">
                            <p class="info-box-text">In Active Hotels</p>
                            <h3  class="info-box-number"><?= $inactive_hotels_count ?></h3>
                            <a href="<?= base_url('chotel') ?>/provider/pending_list"  class="info-box-text"> More info 
                            <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-8 col-md-12 pl-3">

            <div class="card">
                <div class="card-header">
                    <p>Some advertising content like view</p>
                </div>
                <div class="card-body">
                    <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Voluptatem adipisci officiis ullam. Debitis accusamus labore earum alias nihil molestiae, excepturi explicabo sit eaque vel iusto harum dolorum ullam voluptatum nobis!</p>
                    <span class="text-center">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Perferendis magni iure quas </span>
                </div>
            </div>
        </div>
        <div class="col-xl-4 pr-3">
            <div class="card">
                <div class="card-header">
                    <p>Some more content</p>
                </div>
                <div class="card-body">
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Fuga error deserunt eaque aperiam. Suscipit perferendis quas odio delectus ipsa veritatis unde, officia ratione vel deleniti laboriosam tempore vero doloribus distinctio!</p>
                </div>
            </div>
        </div>
    </div>




</div>