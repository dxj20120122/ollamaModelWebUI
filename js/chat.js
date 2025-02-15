document.addEventListener('DOMContentLoaded', async () => {
    const chatBox = document.getElementById('chat-box');
    const chatForm = document.getElementById('chat-form');
    const userInput = document.getElementById('user-input');
    const thinkingAnimation = document.getElementById('thinking-animation');
    const errorMessage = document.getElementById('error-message');

    // 定义模型配置文件路径
    const modelConfigPath = 'Data/Model.json';

    // 加载模型 JSON 文件
    let modelMap = {};

    try {
        const response = await fetch(modelConfigPath);
        const modelConfig = await response.json();

        // 生成模型映射对象
        modelMap = modelConfig.models.reduce((acc, model) => {
            acc[model.id] = model.name;
            return acc;
        }, {});

        // 设置默认模型
        defaultModel = modelConfig.defaultModel || defaultModel;
    } catch (error) {
        console.error('加载模型配置文件失败:', error);
        showError('加载模型失败，请检查 Model.json 文件是否正确。请刷新页面后重试。联系作者上报错误。查看浏览器控制台的错误信息。');
    }

    // 获取 URL 中的 model 参数
    function getQueryParam(param) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    }

    // 根据 model 参数设置模型
    const modelParam = getQueryParam('model');
    const model = modelMap[modelParam] || modelMap[defaultModel]; // 默认模型

    // 其他默认参数
    const temperature = 0.7;
    const maxTokens = 4096;

    chatForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const userMessage = userInput.value.trim();
        if (userMessage === '') return;

        // 显示用户消息
        appendMessage('您', userMessage, 'user');

        // 禁用表单并清空输入
        chatForm.reset();
        chatForm.querySelector('button').disabled = true;

        // 显示思考动画
        showThinkingAnimation();

        // 发送请求到后端
        try {
            const response = await fetch('process.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    prompt: userMessage,
                    model: model,
                    temperature: temperature,
                    max_tokens: maxTokens
                })
            });

            // 隐藏思考动画
            hideThinkingAnimation();

            // 解析响应
            const result = await response.json();

            if (response.ok) {
                appendMessage('AI', result.text, 'ai');
                // 自动高亮代码
                document.querySelectorAll('pre code').forEach((block) => {
                    hljs.highlightElement(block);
                });
            } else {
                if (result.error) {
                    showError(result.error);
                } else {
                    showError('未知的错误。 您可以尝试重新刷新页面。 查看浏览器控制台的错误信息。或联系作者上报错误。');
                }
            }
        } catch (error) {
            hideThinkingAnimation();
            showError('请求失败，请稍后重试。 Error:'+ error.message);
        } finally {
            chatForm.querySelector('button').disabled = false;
        }
    });




    // 添加全局复制函数
    window.copyCode = function(button) {
        const code = button.nextElementSibling.textContent;
        navigator.clipboard.writeText(code).then(() => {
            showCopyNotification();
        });
    };

    function showCopyNotification() {
        const notification = document.createElement('div');
        notification.className = 'copy-notification';
        notification.textContent = '✓ 复制成功';
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 300);
        }, 2000);
    }

    // 处理思考容器的点击事件
    chatBox.addEventListener('click', (e) => {
        const header = e.target.closest('.think-header');
        if (header) {
            const container = header.parentElement;
            container.classList.toggle('expanded');
        }
    });



    function appendMessage(sender, message, type) {
        const messageElement = document.createElement('div');
        messageElement.classList.add('message', type);
        messageElement.innerHTML = `<p><strong>${sender}:</strong> ${message}</p>`;
        chatBox.appendChild(messageElement);
        
        // 自动展开第一个思考容器
        const firstThink = messageElement.querySelector('.think-container');
        if (firstThink) {
            firstThink.classList.add('expanded');
        }
        
        chatBox.scrollTop = chatBox.scrollHeight;
    }


    


    function showError(message) {
        errorMessage.textContent = message;
        errorMessage.style.display = 'block';
        setTimeout(() => {
            errorMessage.style.display = 'none';
        }, 5000);
    }

    function showThinkingAnimation() {
        thinkingAnimation.style.display = 'flex';
        thinkingAnimation.style.justifyContent = 'center';
        thinkingAnimation.style.alignItems = 'center';
        thinkingAnimation.style.position = 'absolute';
        thinkingAnimation.style.top = '50%';
        thinkingAnimation.style.left = '50%';
        thinkingAnimation.style.transform = 'translate(-50%, -50%)';
    }

    function hideThinkingAnimation() {
        thinkingAnimation.style.display = 'none';
    }

    // 修改表单提交逻辑以支持 Enter, Ctrl+Enter, Shift+Enter
    userInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            if (e.ctrlKey || e.shiftKey) {
                // 插入换行符
                const start = userInput.selectionStart;
                const end = userInput.selectionEnd;
                const text = userInput.value;
                userInput.value = text.substring(0, start) + '\n' + text.substring(end);
                userInput.selectionStart = userInput.selectionEnd = start + 1;
                e.preventDefault();
            } else {
                // 提交表单
                chatForm.dispatchEvent(new Event('submit'));
                e.preventDefault();
            }
        }
    });

    // 固定 JSON.stringify 以处理循环引用
    function jsonStringify(obj) {
        return JSON.stringify(obj);
    }
});