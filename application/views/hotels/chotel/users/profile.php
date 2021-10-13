<div id="page_content">
    <div id="page_content_inner">
        <?= $this->session->userdata('profile_msg') ? "<h3 class='text-center'>" . $this->session->userdata('profile_msg') . "</h3>" : '' ?>
        <form method="post">
            <input type="hidden" name="userid" value="<?= $h_user->H_User_ID ?>" />
            <div class="md-card">
                <div class="md-card-content">
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-2">
                            <div class="uk-form-row">
                                <div class="uk-grid" data-uk-grid-margin>
                                    <div class="uk-width-medium-1-2">
                                        <?= "<span style='color:red'>" . form_error('fullname') . "</span>" ?>
                                        <label>FullName</label>
                                        <input class="md-input" type="text" name="fullname" value="<?= $h_user->H_User_FullName ?>" />
                                    </div>
                                    <div class="uk-width-medium-1-2">
                                        <?= "<span style='color:red'>" . form_error('email') . "</span>" ?>
                                        <label>User Email:</label>
                                        <input class="md-input" type="text" name="email" value="<?= $h_user->H_User_Email ?>" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-2">
                            <div class="uk-form-row">
                                <div class="uk-grid" data-uk-grid-margin>
                                    <div class="uk-width-medium-1-2">
                                        <?= "<span style='color:red'>" . form_error('username') . "</span>" ?>
                                        <label>UserName</label>
                                        <input class="md-input" type="text" name="username" value="<?= $h_user->H_UserName ?>" />
                                    </div>
                                    <div class="uk-width-medium-1-2">
                                        <label>Password</label>
                                        <input class="md-input" type="text" name="password" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-2">
                            <div class="uk-form-row">
                                <div class="uk-grid" data-uk-grid-margin>
                                    <div class="uk-width-medium-1-2">
                                        <?= "<span style='color:red'>" . form_error('username') . "</span>" ?>
                                        <label>User Phone Number</label>
                                        <input class="md-input" type="text" name="userphone" value="<?= $h_user->H_User_Phone ?>" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="uk-grid uk-margin-medium-top" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <input class="md-btn md-btn-success" type="submit" value="Save" />
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- Submit -->
</div>