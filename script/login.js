$(document).ready(function() {
    $('#loginForm').on('submit', function(event) {
        event.preventDefault();
        
        const username = $('#username').val().trim();
        const password = $('#password').val().trim();
        
        // 简单验证
        if (!username || !password) {
            $('#loginMessage').text('Please enter username and password').css('color', 'red');
            return;
        }
        
        // 显示加载状态
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.text();
        submitBtn.text('Logging in...').prop('disabled', true);
        
        // 调用PHP API
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
                    // 登录成功，跳转到首页
                    window.location.href = 'index.php';
                } else {
                    // 显示错误
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