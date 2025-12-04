$(document).ready(function() {
    // use profile API to get current user data
    $.ajax({
        url: 'api/get_profile.php',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                // fill form fields
                $('#email').val(response.data.email || '');
                $('#fullName').val(response.data.full_name || '');
                $('#birthday').val(response.data.birthday || '');
                $('#address').val(response.data.address || '');
            } else {
                alert('Failed to load profile: ' + response.message);
            }
        },
        error: function() {
            alert('Error loading profile data');
        }
    });
    
    // handle form submission
    $('.profile-form').on('submit', function(event) {
        event.preventDefault();
        
        const formData = {
            email: $('#email').val().trim(),
            fullName: $('#fullName').val().trim(),
            birthday: $('#birthday').val(),
            address: $('#address').val().trim()
        };
        
        //  simple validation
        if (!formData.email) {
            alert('Email is required');
            return;
        }
        
        //  show loading state
        const saveBtn = $('.save-btn');
        const originalText = saveBtn.text();
        saveBtn.text('Saving...').prop('disabled', true);
        
        //  call update profile API
        $.ajax({
            url: 'api/update_profile.php',
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('Profile updated successfully!');
                    //  redirect back to profile page
                    setTimeout(function() {
                        window.location.href = 'profile.php';
                    }, 1500);
                } else {
                    alert('Error: ' + response.message);
                    saveBtn.text(originalText).prop('disabled', false);
                }
            },
            error: function() {
                alert('Network error. Please try again.');
                saveBtn.text(originalText).prop('disabled', false);
            }
        });
    });
    
    //  handle cancel button
    $('.cancel-btn').on('click', function() {
        if (confirm('Discard changes?')) {
            window.location.href = 'profile.php';
        }
    });
});