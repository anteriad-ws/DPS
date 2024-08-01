jQuery(document).ready(function ($) {
    var selectFileFrame;
    var selectThumbFramel
    // Run media uploader for file upload
    $('#upload_image_button').click(function (e) {
        e.preventDefault();
        selectFileFrame = wp.media({
            title: sdm_translations.select_file,
            button: {
                text: sdm_translations.insert,
            },
            multiple: false
        });
        selectFileFrame.open();
        selectFileFrame.on('select', function () {
            var attachment = selectFileFrame.state().get('selection').first().toJSON();

            $('#sdm_upload').val(attachment.url);
        });
        return false;
    });


});