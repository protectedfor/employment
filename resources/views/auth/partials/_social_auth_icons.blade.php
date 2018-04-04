<div class="registration_by_social">
    <script src="//ulogin.ru/js/ulogin.js"></script>
    <div id="uLogin" data-ulogin="display=panel;fields=first_name,last_name,email,photo,photo_big;providers=vkontakte,odnoklassniki,mailru,facebook,twitter,google{{--;hidden=other--}};redirect_uri=;callback=getULoginUserInfo"></div>
</div>

<script>
    // Get ULogin User Info
    function getULoginUserInfo(token){
        $.getJSON("//ulogin.ru/token.php?host=" + encodeURIComponent(location.toString()) + "&token=" + token + "&callback=?", function(user_data){
            user_data=$.parseJSON(user_data.toString());
            sendAjaxWIthValidation($(this), user_data, 'GET', '/social/auth');
        });
    }
</script>