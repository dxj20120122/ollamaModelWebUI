function getRelativePath() {
    // 获取当前页面的路径
    const currentPath = window.location.pathname;
    
    // 找到Website目录的位置
    const websiteIndex = currentPath.indexOf('/Website/');
    
    if (websiteIndex === -1) {
        // 如果找不到Website目录，返回默认路径
        return '/Website';
    }
    
    // 计算相对路径
    const basePath = currentPath.substring(0, websiteIndex + '/Website'.length);
    return basePath;
}

// 当DOM加载完成后执行
document.addEventListener('DOMContentLoaded', function() {
    const basePath = getRelativePath();
    
    // 获取所有导航链接
    const navLinks = document.querySelectorAll('.nav-links a, .nav-brand a');
    
    // 更新每个链接的href属性
    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href.startsWith('/Website/')) {
            // 移除开头的/Website/并使用计算出的基础路径
            link.href = basePath + href.substring('/Website'.length);
        }
    });
});