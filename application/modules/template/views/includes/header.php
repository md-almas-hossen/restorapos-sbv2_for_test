<style>
@keyframes anim_opa {
    50% {
        opacity: 0.2
    }
}

.navbar-nav li .lang_box {
    line-height: 36px;
    color: #374767;
}

.dark-mode .navbar-nav li .lang_box {
    color: #ffffff;
}

.navbar-nav li .lang_options {
    min-width: 90px;
}

.logo-dark {
    display: none;
}

.dark-mode .logo-light {
    display: none;
}

.dark-mode .logo-dark {
    display: inline;
}

.light-mode .logo-light {
    display: inline;
}

.light-mode .logo-dark {
    display: none;
}

.dark-mode .dropdown-menu {
    background-color: #2d2d2d;
    color: #ffffff;
}

.nav .open>a,
.nav .open>a:focus,
.nav .open>a:hover {
    background-color: transparent;
}
</style>
<!-- <a href="<?php echo base_url('dashboard/home') ?>" class="logo">
    <span class="logo-mini">
        <img width="42"
            src="<?php echo base_url((!empty($setting->favicon)?$setting->favicon:'assets/img/icons/favicon.png')) ?>"
            alt="logo">
    </span>

    <span class="logo-lg">
        <img src="<?php echo base_url((!empty($setting->logo)?$setting->logo:'assets/img/icons/mini-logo.png')) ?>"
            alt="logo">
    </span>
</a>
 -->
<a href="<?php echo base_url('dashboard/home') ?>" class="logo">
    <span class="logo-mini">
        <img class="logo-light" width="42"
            src="<?php echo base_url((!empty($setting->light_mode_favicon)?$setting->light_mode_favicon:'assets/img/icons/favicon.png')) ?>"
            alt="logo-light">
        <img class="logo-dark" width="42"
            src="<?php echo base_url((!empty($setting->favicon)?$setting->favicon:'assets/img/icons/favicon.png')) ?>"
            alt="logo-dark">
    </span>

    <span class="logo-lg">
        <img class="logo-light"
            src="<?php echo base_url((!empty($setting->light_mode_logo)?$setting->light_mode_logo:'assets/img/icons/favicon.png')) ?>"
            alt="logo-light">
        <img class="logo-dark"
            src="<?php echo base_url((!empty($setting->logo)?$setting->logo:'assets/img/icons/mini-logo.png')) ?>"
            alt="logo-dark">
    </span>
</a>
<?php 
/* (int)$new_version  = file_get_contents('https://restorapos.com/rposupdate/autoupdate/update_info');
  $myversion = current_version();
function current_version(){

        //Current Version
        $product_version = '';
        $path = FCPATH.'system/core/compat/lic.php'; 
        if (file_exists($path)) {
            
            // Open the file
            $whitefile = file_get_contents($path);

            $file = fopen($path, "r");
            $i    = 0;
            $product_version_tmp = array();
            $product_key_tmp = array();
            while (!feof($file)) {
                $line_of_text = fgets($file);

                if (strstr($line_of_text, 'product_version')  && $i==0) {
                    $product_version_tmp = explode('=', strstr($line_of_text, 'product_version'));
                    $i++;
                }                
            }
            fclose($file);

            $product_version = trim(@$product_version_tmp[1]);
            $product_version = ltrim(@$product_version, '\'');
            $product_version = rtrim(@$product_version, '\';');

            return @$product_version;
            
        } else {
            //file is not exists
            return false;
        }
        
    }*/

?>
<!-- Header Navbar -->
<?php if($title!='Counter Dashboard'){?>
<nav class="navbar navbar-static-top ">

    <a onclick="forSidebarScroll()" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <!-- Sidebar toggle button-->
        <span class="sr-only">
            <?php echo display('toggle_navigation') ?>
        </span>
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd"
                d="M18 8C18 7.73478 17.8946 7.48043 17.7071 7.29289C17.5196 7.10536 17.2652 7 17 7H6.692C6.42678 7 6.17243 7.10536 5.98489 7.29289C5.79736 7.48043 5.692 7.73478 5.692 8C5.692 8.26522 5.79736 8.51957 5.98489 8.70711C6.17243 8.89464 6.42678 9 6.692 9H17C17.2652 9 17.5196 8.89464 17.7071 8.70711C17.8946 8.51957 18 8.26522 18 8ZM18 4C18 3.73478 17.8946 3.48043 17.7071 3.29289C17.5196 3.10536 17.2652 3 17 3H3C2.73478 3 2.48043 3.10536 2.29289 3.29289C2.10536 3.48043 2 3.73478 2 4C2 4.26522 2.10536 4.51957 2.29289 4.70711C2.48043 4.89464 2.73478 5 3 5H17C17.2652 5 17.5196 4.89464 17.7071 4.70711C17.8946 4.51957 18 4.26522 18 4ZM18 12C18 11.7348 17.8946 11.4804 17.7071 11.2929C17.5196 11.1054 17.2652 11 17 11H3C2.73478 11 2.48043 11.1054 2.29289 11.2929C2.10536 11.4804 2 11.7348 2 12C2 12.2652 2.10536 12.5196 2.29289 12.7071C2.48043 12.8946 2.73478 13 3 13H17C17.2652 13 17.5196 12.8946 17.7071 12.7071C17.8946 12.5196 18 12.2652 18 12ZM18 16C18 15.7348 17.8946 15.4804 17.7071 15.2929C17.5196 15.1054 17.2652 15 17 15H6.692C6.42678 15 6.17243 15.1054 5.98489 15.2929C5.79736 15.4804 5.692 15.7348 5.692 16C5.692 16.2652 5.79736 16.5196 5.98489 16.7071C6.17243 16.8946 6.42678 17 6.692 17H17C17.2652 17 17.5196 16.8946 17.7071 16.7071C17.8946 16.5196 18 16.2652 18 16Z"
                fill="#262626" />
        </svg>

    </a>

    <div class="navbar-custom-menu">
        <ul class="nav navbar-nav d-flex align-items-center">
            <!-- Order Alert -->
            <?php /*if ((float)$new_version>$myversion) {if($versioncheck->version!=(float)$new_version){ ?><li><a
                    href="<?php echo base_url("dashboard/autoupdate") ?>"
                    style="display: flex;align-items: center;background: #f81111;padding: 0 10px;margin-top: 12px;color: #fff;animation-name: anim_opa; animation-duration: 0.8s; animation-iteration-count: infinite;"><i
                        class="fa fa-warning" style="background: transparent; border: 0; color: #fff;"></i><span
                        style="font-size: 16px;font-weight: 600;">Update Available</span></a></li><?php } }*/ ?>
            <li class="">
                <button id="theme-toggle" type="button" aria-label="Use Dark Mode" class="btn_theme">
                    <svg class="dark-icon" xmlns="http://www.w3.org/2000/svg" width="28" height="28"
                        viewBox="0 0 32 32">
                        <g fill="none" fill-rule="evenodd" transform="translate(-440 -200)">
                            <path fill="currentColor" fill-rule="nonzero" stroke="currentColor" stroke-width="0.5"
                                d="M102,21 C102,18.1017141 103.307179,15.4198295 105.51735,13.6246624 C106.001939,13.2310647 105.821611,12.4522936 105.21334,12.3117518 C104.322006,12.1058078 103.414758,12 102.5,12 C95.8722864,12 90.5,17.3722864 90.5,24 C90.5,30.6277136 95.8722864,36 102.5,36 C106.090868,36 109.423902,34.4109093 111.690274,31.7128995 C112.091837,31.2348572 111.767653,30.5041211 111.143759,30.4810139 C106.047479,30.2922628 102,26.1097349 102,21 Z M102.5,34.5 C96.7007136,34.5 92,29.7992864 92,24 C92,18.2007136 96.7007136,13.5 102.5,13.5 C102.807386,13.5 103.113925,13.5136793 103.419249,13.5407785 C101.566047,15.5446378 100.5,18.185162 100.5,21 C100.5,26.3198526 104.287549,30.7714322 109.339814,31.7756638 L109.516565,31.8092927 C107.615276,33.5209452 105.138081,34.5 102.5,34.5 Z"
                                transform="translate(354.5 192)"></path>
                            <polygon points="444 228 468 228 468 204 444 204"></polygon>
                        </g>
                    </svg>
                    <svg class="light-icon" xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                        viewBox="0 0 32 32">
                        <g fill="none" fill-rule="evenodd" transform="translate(-442 -200)">
                            <g fill="currentColor" transform="translate(356 144)">
                                <path fill-rule="nonzero"
                                    d="M108.5 24C108.5 27.5902136 105.590214 30.5 102 30.5 98.4097864 30.5 95.5 27.5902136 95.5 24 95.5 20.4097864 98.4097864 17.5 102 17.5 105.590214 17.5 108.5 20.4097864 108.5 24zM107 24C107 21.2382136 104.761786 19 102 19 99.2382136 19 97 21.2382136 97 24 97 26.7617864 99.2382136 29 102 29 104.761786 29 107 26.7617864 107 24zM101 12.75L101 14.75C101 15.1642136 101.335786 15.5 101.75 15.5 102.164214 15.5 102.5 15.1642136 102.5 14.75L102.5 12.75C102.5 12.3357864 102.164214 12 101.75 12 101.335786 12 101 12.3357864 101 12.75zM95.7255165 14.6323616L96.7485165 16.4038616C96.9556573 16.7625614 97.4143618 16.8854243 97.7730616 16.6782835 98.1317614 16.4711427 98.2546243 16.0124382 98.0474835 15.6537384L97.0244835 13.8822384C96.8173427 13.5235386 96.3586382 13.4006757 95.9999384 13.6078165 95.6412386 13.8149573 95.5183757 14.2736618 95.7255165 14.6323616zM91.8822384 19.0244835L93.6537384 20.0474835C94.0124382 20.2546243 94.4711427 20.1317614 94.6782835 19.7730616 94.8854243 19.4143618 94.7625614 18.9556573 94.4038616 18.7485165L92.6323616 17.7255165C92.2736618 17.5183757 91.8149573 17.6412386 91.6078165 17.9999384 91.4006757 18.3586382 91.5235386 18.8173427 91.8822384 19.0244835zM90.75 25L92.75 25C93.1642136 25 93.5 24.6642136 93.5 24.25 93.5 23.8357864 93.1642136 23.5 92.75 23.5L90.75 23.5C90.3357864 23.5 90 23.8357864 90 24.25 90 24.6642136 90.3357864 25 90.75 25zM92.6323616 30.2744835L94.4038616 29.2514835C94.7625614 29.0443427 94.8854243 28.5856382 94.6782835 28.2269384 94.4711427 27.8682386 94.0124382 27.7453757 93.6537384 27.9525165L91.8822384 28.9755165C91.5235386 29.1826573 91.4006757 29.6413618 91.6078165 30.0000616 91.8149573 30.3587614 92.2736618 30.4816243 92.6323616 30.2744835zM97.0244835 34.1177616L98.0474835 32.3462616C98.2546243 31.9875618 98.1317614 31.5288573 97.7730616 31.3217165 97.4143618 31.1145757 96.9556573 31.2374386 96.7485165 31.5961384L95.7255165 33.3676384C95.5183757 33.7263382 95.6412386 34.1850427 95.9999384 34.3921835 96.3586382 34.5993243 96.8173427 34.4764614 97.0244835 34.1177616zM103 35.25L103 33.25C103 32.8357864 102.664214 32.5 102.25 32.5 101.835786 32.5 101.5 32.8357864 101.5 33.25L101.5 35.25C101.5 35.6642136 101.835786 36 102.25 36 102.664214 36 103 35.6642136 103 35.25zM108.274483 33.3676384L107.251483 31.5961384C107.044343 31.2374386 106.585638 31.1145757 106.226938 31.3217165 105.868239 31.5288573 105.745376 31.9875618 105.952517 32.3462616L106.975517 34.1177616C107.182657 34.4764614 107.641362 34.5993243 108.000062 34.3921835 108.358761 34.1850427 108.481624 33.7263382 108.274483 33.3676384zM112.117762 28.9755165L110.346262 27.9525165C109.987562 27.7453757 109.528857 27.8682386 109.321717 28.2269384 109.114576 28.5856382 109.237439 29.0443427 109.596138 29.2514835L111.367638 30.2744835C111.726338 30.4816243 112.185043 30.3587614 112.392183 30.0000616 112.599324 29.6413618 112.476461 29.1826573 112.117762 28.9755165zM113.25 23L111.25 23C110.835786 23 110.5 23.3357864 110.5 23.75 110.5 24.1642136 110.835786 24.5 111.25 24.5L113.25 24.5C113.664214 24.5 114 24.1642136 114 23.75 114 23.3357864 113.664214 23 113.25 23zM111.367638 17.7255165L109.596138 18.7485165C109.237439 18.9556573 109.114576 19.4143618 109.321717 19.7730616 109.528857 20.1317614 109.987562 20.2546243 110.346262 20.0474835L112.117762 19.0244835C112.476461 18.8173427 112.599324 18.3586382 112.392183 17.9999384 112.185043 17.6412386 111.726338 17.5183757 111.367638 17.7255165zM106.975517 13.8822384L105.952517 15.6537384C105.745376 16.0124382 105.868239 16.4711427 106.226938 16.6782835 106.585638 16.8854243 107.044343 16.7625614 107.251483 16.4038616L108.274483 14.6323616C108.481624 14.2736618 108.358761 13.8149573 108.000062 13.6078165 107.641362 13.4006757 107.182657 13.5235386 106.975517 13.8822384z"
                                    transform="translate(0 48)" stroke="currentColor" stroke-width="0.25"></path>
                                <path
                                    d="M98.6123,60.1372 C98.6123,59.3552 98.8753,58.6427 99.3368,58.0942 C99.5293,57.8657 99.3933,57.5092 99.0943,57.5017 C99.0793,57.5012 99.0633,57.5007 99.0483,57.5007 C97.1578,57.4747 95.5418,59.0312 95.5008,60.9217 C95.4578,62.8907 97.0408,64.5002 98.9998,64.5002 C99.7793,64.5002 100.4983,64.2452 101.0798,63.8142 C101.3183,63.6372 101.2358,63.2627 100.9478,63.1897 C99.5923,62.8457 98.6123,61.6072 98.6123,60.1372"
                                    transform="translate(3 11)"></path>
                            </g>
                            <polygon points="444 228 468 228 468 204 444 204"></polygon>
                        </g>
                    </svg>
                </button>
            </li>


            <li>
                <a id="fullscreen" href="#" class="getid1"><i class="pe-7s-expand1"></i></a>
            </li>
            <li class="dropdown messages-menu">

                <!-- <a href="<?php echo base_url("reservation/reservation") ?>" class="dropdown-toggle">
                    <i class="fa fa-bell-o"></i>
                    <span class="label label-success reservenotif">0</span>
                </a> -->
                <input name="csrfres" id="csrfresarvation" type="hidden"
                    value="<?php echo $this->security->get_csrf_token_name(); ?>" />
                <input name="csrfhash" id="csrfhashresarvation" type="hidden"
                    value="<?php echo $this->security->get_csrf_hash(); ?>" />
            </li>
            <!-- Messages -->

            <!-- settings -->
            <li class="dropdown dropdown-user">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="pe-7s-settings"></i></a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo base_url('dashboard/home/profile') ?>"><i class="pe-7s-users"></i>
                            <?php echo display('profile') ?></a></li>
                    <li><a href="<?php echo base_url('dashboard/home/setting') ?>"><i class="pe-7s-settings"></i>
                            <?php echo display('setting') ?></a></li>
                    <li><a href="<?php echo base_url('logout') ?>"><i class="pe-7s-key"></i>
                            <?php echo display('logout') ?></a></li>
                    <?php $languagenames = $this->db->field_data('language');

                ?>
                </ul>
            </li>
            <li class="dropdown dropdown-user">
                <a href="#" class="dropdown-toggle lang_box" data-toggle="dropdown"> <?php if($this->session->has_userdata('language')){  echo mb_strimwidth(strtoupper($this->session->userdata('language')),0,3,''); } else{
                                    echo mb_strimwidth(strtoupper($setting->language),0,3,'');
                                    }?></a>
                <ul class="dropdown-menu lang_options">
                    <?php 
                        $lii=0;
                        foreach($languagenames as $languagename ){
                            if($lii >= 2){
                                            ?>
                    <li><a href="javascript:;" onclick="addlang(this)"
                            data-url="<?php echo base_url();?>hungry/setlangue/<?php echo $languagename->name;?>">
                            <?php echo ucfirst($languagename->name);?></a></li>
                    <?php 
                        }
                        $lii++;
                    }?>
                </ul>
            </li>
        </ul>
    </div>
</nav>
<?php }else{ ?>
<input name="csrfres" id="csrfresarvation" type="hidden"
    value="<?php echo $this->security->get_csrf_token_name(); ?>" />
<input name="csrfhash" id="csrfhashresarvation" type="hidden" value="<?php echo $this->security->get_csrf_hash(); ?>" />

<?php } ?>

<!-- Dark & Light Mode -->
<script>
const body = document.body;
const toggleButton = document.getElementById("theme-toggle");

function applyTheme(theme) {
    if (theme === "dark") {
        body.classList.add("dark-mode");
        body.classList.remove("light-mode");
    } else {
        body.classList.add("light-mode");
        body.classList.remove("dark-mode");
    }
}


const storedTheme = localStorage.getItem("theme") || "light";
applyTheme(storedTheme);


toggleButton.addEventListener("click", () => {
    const newTheme = body.classList.contains("dark-mode") ? "light" : "dark";
    applyTheme(newTheme);
    localStorage.setItem("theme", newTheme);
});
</script>