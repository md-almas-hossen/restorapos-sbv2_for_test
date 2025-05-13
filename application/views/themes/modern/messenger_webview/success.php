<strong>Returning to messenger...</strong>

<script type="text/javascript">
    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {
            return;
        }
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/en_US/messenger.Extensions.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'Messenger'));
</script>

<script type="text/javascript">
    window.extAsyncInit = function() {
        MessengerExtensions.requestCloseBrowser(function success() {

        }, function error(err) {
            document.write(JSON.stringify(err));
        });
    }
</script>