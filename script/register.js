$(document).ready(function() {
    $('#registerForm').on('submit', function(event) {
        event.preventDefault();
        
        // 收集数据
        const formData = {
            username: $('#username').val().trim(),
            email: $('#email').val().trim(),
            phone: $('#phone').val().trim(),
            address: $('#address').val().trim(),
            password: $('#password').val(),
            confirmPassword: $('#confirmPassword').val()
        };
        
        // 简单验证
        if (formData.password !== formData.confirmPassword) {
            $('#registerMessage').text('Passwords do not match').css('color', 'red');
            return;
        }
        
        if (formData.password.length < 6) {
            $('#registerMessage').text('Password must be at least 6 characters').css('color', 'red');
            return;
        }
        
        // 显示加载
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.text();
        submitBtn.text('Registering...').prop('disabled', true);
        
        // 调用API
        $.ajax({
            url: 'api/register.php',
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // 注册成功，跳转到登录页
                    $('#registerMessage').text('Registration successful! Redirecting...').css('color', 'green');
                    setTimeout(() => {
                        window.location.href = 'login.php';
                    }, 1500);
                } else {
                    $('#registerMessage').text(response.message || 'Registration failed').css('color', 'red');
                    submitBtn.text(originalText).prop('disabled', false);
                }
            },
            error: function() {
                $('#registerMessage').text('Network error. Please try again.').css('color', 'red');
                submitBtn.text(originalText).prop('disabled', false);
            }
        });
    });
});