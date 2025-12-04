$(document).ready(function() {
    // 获取用户资料
    $.ajax({
        url: 'api/get_profile.php',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                // 更新页面显示
                $('#username').text(response.data.username || 'User');
                $('#email').text(response.data.email || 'No email available');
                $('#phone').text(response.data.phone || 'No phone available');
                $('#address').text(response.data.address || 'No address available');
            } else {
                // 处理错误
                alert('Failed to load profile: ' + response.message);
                if (response.redirect) {
                    window.location.href = response.redirect;
                }
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
            alert('Error loading profile. Please try again.');
        }
    });
});