import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: true
});

// الاستماع للإشعارات
window.Echo.private(`App.Models.User.${userId}`)
    .notification((notification) => {
        // إضافة الإشعار للقائمة
        const notificationsList = document.querySelector('#notifications-list');
        if (notificationsList) {
            const notificationHtml = `
                <div class="px-4 py-2 bg-blue-50">
                    <p class="text-sm text-gray-900">${notification.message}</p>
                    <p class="text-xs text-gray-500">الآن</p>
                </div>
            `;
            notificationsList.insertAdjacentHTML('afterbegin', notificationHtml);
        }

        // تحديث عدد الإشعارات
        const counter = document.querySelector('#notifications-counter');
        if (counter) {
            const currentCount = parseInt(counter.textContent || '0');
            counter.textContent = currentCount + 1;
        }

        // عرض إشعار منبثق
        const toast = Swal.fire({
            title: 'طلبية جديدة',
            text: notification.message,
            icon: 'info',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
    });