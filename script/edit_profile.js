$(document).ready(function() {
    // 加载用户数据
    $.ajax({
        url: 'api/get_profile.php',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                // 填充表单数据
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
    
    // 表单提交
    $('.profile-form').on('submit', function(event) {
        event.preventDefault();
        
        const formData = {
            email: $('#email').val().trim(),
            fullName: $('#fullName').val().trim(),
            birthday: $('#birthday').val(),
            address: $('#address').val().trim()
        };
        
        // 验证
        if (!formData.email) {
            alert('Email is required');
            return;
        }
        
        // 显示加载状态
        const saveBtn = $('.save-btn');
        const originalText = saveBtn.text();
        saveBtn.text('Saving...').prop('disabled', true);
        
        // 调用API
        $.ajax({
            url: 'api/update_profile.php',
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('Profile updated successfully!');
                    // 延迟重定向，让用户看到成功消息
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
    
    // 取消按钮
    $('.cancel-btn').on('click', function() {
        if (confirm('Discard changes?')) {
            window.location.href = 'profile.php';
        }
    });
});