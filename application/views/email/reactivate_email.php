<!-- New inherited Block -->
<main>
    <section id="hero" class="login">
    	<div class="container-fluid">
        	<div class="row justify-content-center">
            	<div class="col-md-10 mt-5 pb-5">
             <!-- Message Content -->
                    <p class="mt-5">Use this form to resend activation code</p>
             <!-- / Message Content -->
                    </div>
                </div>
            </div>
    </section>
	</main>
<!-- New inherited Block -->
    	<div class="container-fluid">
        	<div class="row justify-content-center">
            	<div class="col-md-10 mt-5 pb-5">
             <!-- Message Content -->
                    <?=validation_errors()?>
                    <form method="post">
                    <div class="form-row">
                        <div class="form-group col">
                            <h1 class="text-center">Resend Email Verivication</h1>
                        </div>
                        <div class="form-group col">
                        <label for="InputUserMail">Please Enter Your Email Address</label>
                            <input class="form-control" type="text" name="vmail" id="InputUserMail" />
                        </div>
                        </div>
                        <button class="btn btn-primary btn-lg" type="submit">Send</button>
                    </form>
             <!-- / Message Content -->
                        </div>
                </div>
    </div>