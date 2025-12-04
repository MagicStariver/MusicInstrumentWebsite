$(document).ready(function() {
    // get user profile info
    $.ajax({
        url: 'api/get_profile.php',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                // update profile fields
                $('#username').text(response.data.username || 'User');
                $('#email').text(response.data.email || 'No email available');
                $('#phone').text(response.data.phone || 'No phone available');
                $('#address').text(response.data.address || 'No address available');
            } else {
                alert('Failed to load profile: ' + response.message);
                if (response.redirect) {
                    window.location.href = response.redirect;
                }
            }
        },
        error: function() {
            alert('Error loading profile. Please try again.');
        }
    });
});