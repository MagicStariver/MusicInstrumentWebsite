$(document).ready(function() {
    // 显示消息函数
    function showMessage(message, type = 'success') {
        const messageContainer = $('#message-container');
        messageContainer.html(`
            <div class="${type === 'success' ? 'success-message' : 'error-message'}">
                ${message}
            </div>
        `);
        
        // 5秒后自动隐藏
        setTimeout(() => {
            messageContainer.empty();
        }, 5000);
    }
    
    // 表单提交处理
    $('#profileForm').on('submit', function(event) {
        event.preventDefault();
        
        // 收集表单数据
        const formData = {
            email: $('#email').val().trim(),
            fullName: $('#fullName').val().trim(),
            birthday: $('#birthday').val(),
            address: $('#address').val().trim()
        };
        
        // 验证
        if (!formData.email || !formData.address) {
            showMessage('Email and address are required!', 'error');
            return;
        }
        
        // 显示加载状态
        const saveBtn = $('.save-btn');
        const originalText = saveBtn.text();
        saveBtn.text('Saving...').prop('disabled', true);
        
        // 发送更新请求到API
        $.ajax({
            url: 'api/update_profile.php',
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    showMessage('Profile updated successfully!', 'success');
                    
                    // 可选：更新页面上的用户名显示
                    if (response.data && response.data.email) {
                        $('#userName').text(response.data.username || $('#username').text());
                    }
                    
                    // 3秒后重定向到个人资料页
                    setTimeout(() => {
                        window.location.href = 'profile.php';
                    }, 3000);
                } else {
                    showMessage('Error: ' + response.message, 'error');
                    saveBtn.text(originalText).prop('disabled', false);
                }
            },
            error: function(xhr, status, error) {
                showMessage('Failed to update profile. Please try again.', 'error');
                console.error('AJAX Error:', error);
                saveBtn.text(originalText).prop('disabled', false);
            }
        });
    });
    
    // 取消按钮处理
    $('#cancelBtn').on('click', function() {
        if (confirm('Are you sure? Unsaved changes will be lost.')) {
            window.location.href = 'profile.php';
        }
    });
});