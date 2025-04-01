import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher', // استخدام Pusher بدلاً من Reverb
    key: 'df94e3c2097e72396c49', // مفتاح Pusher الخاص بك
    cluster: 'eu', // الكلستر الخاص بك
    forceTLS: true, // لتفعيل HTTPS
    authEndpoint: '/broadcasting/auth', // نقطة النهاية للمصادقة
    auth: {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    }
});
