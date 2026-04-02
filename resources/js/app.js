import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', function () {

    // 🔥 ЛАЙКИ ДЛЯ ПОСТОВ (универсально)
    document.addEventListener('click', function (e) {
        const button = e.target.closest('.like-btn');
        if (!button) return;

        let storyId = button.dataset.id;
        let countSpan = button.querySelector('.like-count');
        let heart = button.querySelector('.heart');

        fetch(`/stories/${storyId}/like`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
            .then(res => res.json())
            .then(data => {
                if (data.error) return alert(data.error);

                countSpan.textContent = data.likes;
                heart.textContent = data.liked ? '❤️' : '🤍';
            });
    });

    // 🔥 КОММЕНТАРИИ (AJAX) — оставляем твой старый код, если потребуется
    let form = document.getElementById('comment-form');

    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            let contentInput = document.getElementById('comment-input');
            let content = contentInput.value.trim();

            if (!content) return;

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: new URLSearchParams({
                    content: content
                })
            })
                .then(res => {
                    if (!res.ok) throw res;
                    return res.json();
                })
                .then(data => {
                    let commentsDiv = document.getElementById('comments');

                    let newComment = `
                    <div class="mt-4 pb-3 border-b">
                        <p class="font-semibold">${data.user}</p>
                        <p>${data.content}</p>
                    </div>
                `;

                    commentsDiv.insertAdjacentHTML('afterbegin', newComment);
                    contentInput.value = '';
                })
                .catch(async err => {
                    try {
                        const e = await err.json();
                        if (e.errors) {
                            alert(Object.values(e.errors).flat().join("\\n"));
                        }
                    } catch {
                        console.error(err);
                    }
                });
        });
    }
});