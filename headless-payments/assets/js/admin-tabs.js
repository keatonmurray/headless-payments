document.addEventListener('DOMContentLoaded', function () {
    const tabs = document.querySelectorAll('.nav-tab');
    const tabContents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', function (e) {
            e.preventDefault();

            tabs.forEach(t => t.classList.remove('nav-tab-active'));
            tabContents.forEach(c => c.style.display = 'none');

            this.classList.add('nav-tab-active');
            const selectedTab = this.getAttribute('data-tab');
            const content = document.querySelector(`[data-tab-content="${selectedTab}"]`);
            if (content) content.style.display = 'block';
        });
    });
});
