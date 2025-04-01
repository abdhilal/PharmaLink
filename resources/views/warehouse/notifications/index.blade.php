@extends('layouts.warehouse.app')

@section('title', 'الإشعارات')

@section('content')
    <div class="container mt-5">
        <h2 class="mb-4 text-right">الإشعارات</h2>

        <div id="notifications">
            @if ($notifications->isEmpty())
                <div class="alert alert-info text-right">لا توجد إشعارات جديدة.</div>
            @else
            <ul class="list-group w-80 overflow-auto">
                @foreach ($notifications as $notification)
                        <a href="#"
                            onclick="markNotificationAsRead('{{ route('warehouse.notifications.read', $notification->id) }}', '{{ route('warehouse.orders.show', $notification->order_id) }}')"
                            class="list-group-item mb-2 {{ is_null($notification->read_at) ? 'unread' : '' }} text-decoration-none text-dark d-flex justify-content-between align-items-center">
                            @if (is_null($notification->read_at))
                                <span class="badge bg-primary">جديد</span>
                            @endif
                            @if ($notification->read_at)
                                <span ></span>
                            @endif

                            <div class="text-right">
                                {{ $notification->message }}
                                <br>
                                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                            </div>

                        </a>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>


    <script>
        function markNotificationAsRead(markAsReadUrl, redirectUrl) {
            event.preventDefault();
            fetch(markAsReadUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = redirectUrl;
                    }
                })

        }

        // إعداد Pusher
        var pusher = new Pusher('df94e3c2097e72396c49', {
            cluster: 'eu',
            forceTLS: true,
            authEndpoint: '/broadcasting/auth',
            auth: {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }
        });

        var channel = pusher.subscribe('private-notifications.{{ Auth::user()->warehouse->id }}');
        channel.bind('notification.received', function(data) {
            var notificationsList = document.querySelector('#notifications ul');
            var newNotification = document.createElement('a');
            newNotification.href = '#';
            newNotification.className =
                'list-group-item mb-2 unread text-decoration-none text-dark d-flex justify-content-between align-items-center';
            newNotification.onclick = function() {
                markNotificationAsRead('/notifications/' + data.notification_id + '/read', '/warehouse/suppliers/' + data.order_id);
            };
            newNotification.innerHTML = `
                            <span class="badge bg-primary">جديد</span>

                <div class="text-right">
                    ${data.message}
                    <br>
                    <small class="text-muted">منذ لحظات</small>
                </div>
            `;

            if (notificationsList) {
                notificationsList.prepend(newNotification);
            } else {
                document.getElementById('notifications').innerHTML = `
                    <ul class="list-group">
                        ${newNotification.outerHTML}
                    </ul>
                `;
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
