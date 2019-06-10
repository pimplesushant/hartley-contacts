$(function () {
    /*For Logo*/

    function readLogo(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('.upload-demo').addClass('ready');
                $('#cropLogoPop').modal('show');
                rawImg = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
        else {
            // console.log("Sorry - you're browser doesn't support the FileReader API");
        }
    }

    $uploadLogo = $('#upload-logo').croppie({
        viewport: {
            width: 120,
            height: 120,
        },
        boundary: {
            width: 145,
            height: 145
        },
        enableExif: true
    });

    $('#cropLogoPop').on('shown.bs.modal', function(){
        $uploadLogo.croppie('bind', {
            url: rawImg
        }).then(function(){
            // console.log('jQuery bind complete');
        });
    });

    $('.item-logo').on('change', function () {
        imageId = $(this).data('id');
        tempFilename = $(this).val();
        $('#cancelCropBtn').data('id', imageId);
        readLogo(this);
    });

    $('#cropLogoBtn').on('click', function (ev) {
        $uploadLogo.croppie('result', {
            type: 'canvas'
        }).then(function (resp) {
            var raw = resp.replace(/^data:image\/[a-z]+;base64,/, "");
            $('#item-logo-output').attr('src', resp);
            $('#photo').val(raw);
            $('#cropLogoPop').modal('hide');
        });
    });
});