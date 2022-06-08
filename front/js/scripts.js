jQuery(document).ready(function ($) {


    $('#redirect_to_github').on('click',function () {
        location.assign('https://github.com/login/oauth/authorize?client_id=e03b8f55700cf59a7e79&allow_signup=false&scope=user:email');
    });

    // $('#github_oauth').on('click',function () {
    //     const nonce = $('#github_oauth_nonce').val();
    //
    //     const data = {
    //         action: 'githubOauth',
    //         security : SOCAjax.security,
    //         nonce: nonce,
    //     };
    //     $.ajax({
    //         url: SOCAjax.ajaxurl,
    //         type: 'POST',
    //         data: data,
    //         beforeSend: function () {
    //             // registerSubmitBtn.html('<i class="fa fa-circle-o-notch fa-spin align-middle mx-1"></i>').attr('disabled', true);
    //             // $('#regAlert').fadeOut(500);
    //         },
    //         success: function (res , xhr) {
    //             console.log(res);
    //             // if (xhr === 'success' && res.result ==='ok'){
    //             //
    //             // }
    //         },error:function (jqXHR, textStatus, errorThrown) {
    //             // if(textStatus==="timeout") {
    //             //
    //             // }
    //         }
    //         ,complete:function () {
    //             //registerSubmitBtn.html(register_frm_submit_btn_txt).attr('disabled', false);
    //         },
    //         timeout:SOCAjax.REQUEST_TIMEOUT
    //     });
    // })
});