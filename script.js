document.addEventListener('DOMContentLoaded', function() {
    const tagButtons = document.querySelectorAll('.tag-btn');
    const blogCards = document.querySelectorAll('.blog-card');

    // 为每个标签按钮添加点击事件
    tagButtons.forEach(button => {
        button.addEventListener('click', () => {
            // 移除所有标签的active类
            tagButtons.forEach(btn => btn.classList.remove('active'));
            // 为当前点击的标签添加active类
            button.classList.add('active');

            const selectedTag = button.getAttribute('data-tag');

            // 遍历所有博客卡片
            blogCards.forEach(card => {
                if (selectedTag === 'all') {
                    // 如果选择'全部'，显示所有卡片
                    card.style.display = 'block';
                } else {
                    // 获取卡片的标签
                    const cardTags = card.getAttribute('data-tags');
                    if (cardTags && cardTags.split(',').includes(selectedTag)) {
                        // 如果卡片包含所选标签，显示卡片
                        card.style.display = 'block';
                    } else {
                        // 否则隐藏卡片
                        card.style.display = 'none';
                    }
                }
            });
        });
    });

    // 处理联系表单提交
    const contactForm = document.getElementById('contact-form');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // 获取表单数据
            const formData = new FormData(this);

            // 发送AJAX请求
            fetch('submit_form.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // 隐藏表单
                contactForm.style.display = 'none';
                
                if (data.status === 'success') {
                    // 显示成功消息
                    document.getElementById('success-message').style.display = 'block';
                } else {
                    // 显示错误消息
                    document.getElementById('error-message').style.display = 'block';
                }
            })
            .catch(error => {
                // 隐藏表单并显示错误消息
                contactForm.style.display = 'none';
                document.getElementById('error-message').style.display = 'block';
                console.error('Error:', error);
            });
        });
    }

    // 处理轮播控件
    const testimonialsGrid = document.getElementById('testimonials-grid');
    const prevButton = document.getElementById('prev');
    const nextButton = document.getElementById('next');
    const testimonialItems = document.querySelectorAll('.testimonial-item');
    let currentIndex = 0;

    // 更新轮播显示
    function updateCarousel() {
        testimonialsGrid.style.transform = `translateX(-${currentIndex * 100}%)`;
        
        // 更新所有testimonial-item的可见性
        testimonialItems.forEach((item, index) => {
            if (index === currentIndex) {
                item.style.opacity = '1';
            } else {
                item.style.opacity = '0';
            }
        });
    }

    // 添加上一个按钮点击事件
    if (prevButton) {
        prevButton.addEventListener('click', () => {
            currentIndex = (currentIndex - 1 + testimonialItems.length) % testimonialItems.length;
            updateCarousel();
        });
    }

    // 添加下一个按钮点击事件
    if (nextButton) {
        nextButton.addEventListener('click', () => {
            currentIndex = (currentIndex + 1) % testimonialItems.length;
            updateCarousel();
        });
    }

    // 初始化轮播
    if (testimonialsGrid && testimonialItems.length > 0) {
        // 设置初始状态
        updateCarousel();
        
        // 自动轮播
        setInterval(() => {
            currentIndex = (currentIndex + 1) % testimonialItems.length;
            updateCarousel();
        }, 5000);
    }
});