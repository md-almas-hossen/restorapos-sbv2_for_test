<script type="text/javascript">
    fbLogin = {
        APP_ID: '<?php echo $meta_config->getConfig('appId'); ?>',

        init: function() {
            var config = {
                appId: this.APP_ID,
                cookie: true,
                xfbml: true,
                version: 'v18.0'
            };

            // init fb instance
            FB.init(config);
        },

        handleLoginState: function() {
            FB.getLoginStatus(function(response) {
                if (response.status === 'connected') {
                    var userId = response.authResponse.userID,
                        userAccessToken = response.authResponse.accessToken,
                        pageLongLiveEndpoint = '<?php echo base_url('meta/messenger/long_lived_pages'); ?>';

                    // Exchange long-live access token
                    // And get long-lived page access token
                    fetch(`${pageLongLiveEndpoint}?token=${userAccessToken}&user_id=${userId}`)
                        .then(response => response.json())
                        .then(function(response) {
                            console.log(response);
                            fbLogin.showUserMangedPages(response.data);
                        });
                }

                fbLogin.toggleLogInSection(response.status);
            });
        },

        toggleLogInSection: function(status) {
            if (status == 'connected') {
                $('#logged-in-user-section').show();
                $('#login-with-facebook-btn').addClass('disabled');
                return;;
            }

            $('#logged-in-user-section').hide();
            $('#login-with-facebook-btn').removeClass('disabled');
        },

        showLoggedInUser: function() {
            FB.api('/me', function(response) {
                $('#fb-loggedin-user').html(response.name);
            });
        },

        showUserMangedPages: function(data) {
            var optionsHtml = '<option value="">Select Page</option>';

            if ($.isArray(data) && data.length) {
                $.each(data, function(i, v) {
                    optionsHtml += `<option value="${v.id}" data-token="${v.access_token}">${v.name}</option>`;
                });

                $('#userManagedPages').html(optionsHtml);
            }
        },

        logoutUser: function() {
            FB.logout(function(response) {
                console.log(response);
                fbLogin.handleLoginState();
            });
        }
    };
</script>

<div class="row meta-config-area" id="meta-config-area">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><?php echo $title ?></h4>
                </div>
            </div>

            <div class="panel-body meta-config">
                <?php $this->view('common/progressive_tab') ?>

                <form method="post" action="<?php echo base_url('meta/messenger/set_config/page'); ?>">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash() ?>">

                    <div class="row">
                        <div class="col-lg-4 col-lg-offset-4">
                            <?php $this->view('common/message') ?>

                            <div class="form-group">
                                <label for="page_id" class="form-label"><?php echo display('facebook_page_id') ?> *</label>
                                <input name="pageId" class="form-control" type="text" placeholder="<?php echo display('facebook_page_id') ?>" id="page_id" value="<?php echo $meta_config->getConfig('pageId') ?>" required />
                            </div>

                            <div class="form-group">
                                <label for="access_token" class="form-label"><?php echo display('page_access_token') ?> *</label>
                                <input name="pageAccessToken" class="form-control" type="text" placeholder="<?php echo display('page_access_token') ?>" id="access_token" value="<?php echo $meta_config->getConfig('pageAccessToken') ?>" required />
                            </div>

                            <div class="form-group">
                                <div class="facebook-import">
                                    <label>Import From Facebook</label>

                                    <div id="logged-in-user-section" style="display: none;">
                                        <p class="logged-in-userinfo">
                                            You are logged in:
                                            <!-- <span id="fb-loggedin-user"></span> -->
                                        </p>

                                        <p class="logged-in-userinfo form-group">
                                            <label>Select Page from your managed lists</label>
                                            <select name="" class="form-control" id="userManagedPages"></select>
                                        </p>
                                        <p>User different account? <a href="" id="fb-logout-btn">Logout</a></p>
                                    </div>

                                    <div id="login-with-facebook-btn">
                                        <div class="fb-login-button" data-width="" data-size="large" data-button-type="" data-layout="" data-auto-logout-link="false" data-use-continue-as="false" data-scope="public_profile,pages_messaging,pages_show_list,pages_manage_metadata" onlogin="fbLogin.handleLoginState();"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="webhook_url" class="form-label"><?php echo display('webhook_url') ?> *</label>
                                <div class="input-group">
                                    <input name="webhookUrl" class="form-control" type="text" placeholder="https://my-domain.com" id="webhook_url" value="<?php echo rtrim(base_url(), '/') ?>" required />
                                    <span class="input-group-addon">/meta/messenger/webhook</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-success w-md m-b-5">Next</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $.ajaxSetup({
            cache: true
        });

        $.getScript('https://connect.facebook.net/en_US/sdk.js', function() {
            // bind page select event
            $('#userManagedPages').on('change.select2', function(e) {
                var $selectedOption = $(this).select2().find(":selected"),
                    pageId = $selectedOption.val(),
                    pageAccessToken = $selectedOption.data('token');

                $('#page_id').val(pageId).prop('readonly', (pageId != ''));
                $('#access_token').val(pageAccessToken).prop('readonly', (pageAccessToken != ''));
            });

            // bind logout button
            $('#fb-logout-btn').click(function(e) {
                e.preventDefault();
                fbLogin.logoutUser();
            });

            // init fbLogin
            fbLogin.init();
            fbLogin.handleLoginState();
        });
    });
</script>