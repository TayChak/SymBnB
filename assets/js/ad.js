$(document).ready(function() {
    $('#add-image').click(function(){
        //const index = $('#ad_images div.form-group').length;
        const index = + $('#widgets_counter').val();
        const tmpl = $('#ad_images').data('prototype').replace(/__name__/g, index);
        $('#ad_images').append(tmpl);
        $('#widgets_counter').val(index + 1);
        handleDeleteButtons();
    });

    function handleDeleteButtons() {
        $('button[data-action="delete"]').click(function(){
            const target = this.dataset.target;
            $(target).remove();
        });
    }

    function updateCounter() {
        const count = +$('#ad_images div.form-group').length;
        $('#widgets_counter').val(count);
    }
    
    updateCounter();
    handleDeleteButtons();
});