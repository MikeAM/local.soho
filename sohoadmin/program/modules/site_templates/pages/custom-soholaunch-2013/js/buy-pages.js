$(function() {
    function check_domain() {
        var domname = $('#domain-name-input').val();
        var domtld = $('#domain-tld-input').val();
        var isvalid = false;

        // Valid check
        if ( domname != '' && domtld != '' ) {
            isvalid = true;
        }

        if ( isvalid ) {
            domain_is_good();
        } else {
            domain_is_bad();
        }
    }

    function domain_is_good() {
        // Pull msg from json return in final
        var msg = 'That domain works! You\'re ready to continue to Step 2.';
        $('#instructions-domain-name').html(msg);
        $('#instructions-domain-name').removeClass('error').addClass('success');
    }

    function domain_is_bad() {
        // Pull msg from json return in final
        var msg = 'Sorry, that domain won\'t work. Please try another.';
        $('#instructions-domain-name').html(msg);
        $('#instructions-domain-name').removeClass('success').addClass('error');
    }

    $('#btn-check-domain').click(check_domain);
});