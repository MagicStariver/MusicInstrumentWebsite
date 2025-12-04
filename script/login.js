$(document).ready(function() {
    $('#loginForm').on('submit', function(event) {
        event.preventDefault();
        
        const username = $('#username').val().trim();
        const password = $('#password').val().trim();
        
        //  validation
        if (!username || !password) {
            $('#loginMessage').text('Please enter username and password').css('color', 'red');
            return;
        }
        
        //  show loading state
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.text();
        submitBtn.text('Logging in...').prop('disabled', true);
        
        //  call login API
        $.ajax({
            url: 'api/login.php',
            method: 'POST',
            data: {
                username: username,
                password: password
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    //  redirect to homepage
                    window.location.href = 'index.php';
                } else {
                    // show error message
                    $('#loginMessage').text(response.message || 'Login failed').css('color', 'red');
                    submitBtn.text(originalText).prop('disabled', false);
                }
            },
            error: function() {
                $('#loginMessage').text('Network error. Please try again.').css('color', 'red');
                submitBtn.text(originalText).prop('disabled', false);
            }
        });
    });
});