<!DOCTYPE html>
<head>
  <title>Pusher Test</title>
  <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- إضافة CSRF Token -->
  <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
  <script>
    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('df94e3c2097e72396c49', {
      cluster: 'eu',
      forceTLS: true,
      authEndpoint: '/broadcasting/auth', // نقطة نهاية المصادقة
      auth: {
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
      }
    });

    // الاشتراك في القناة الخاصة باستخدام user_id (يجب تمريره من الخلفية)
    var userId = 8; // استبدل هذا بـ user_id الفعلي للمستخدم (يمكن تمريره عبر Blade أو API)
    var channel = pusher.subscribe('private-notifications.' + userId);
    channel.bind('notification.received', function(data) {
      alert(JSON.stringify(data));
    });
  </script>
</head>
<body>
  <h1>Pusher Test</h1>
  <p>Try publishing an event to the private channel.</p>
</body>
