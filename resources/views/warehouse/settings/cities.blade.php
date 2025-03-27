<!-- resources/views/warehouse/location.blade.php -->
@extends('layouts.warehouse.app')

@section('title', 'تحديد الموقع ونطاق الخدمة')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">تحديد موقعك ونطاق الخدمة</h3>
            <p class="text-light mb-0">يرجى تحديد موقعك لكي تظهر للصيدليات ضمن نطاق خدمتك</p>
        </div>
        <div class="card-body">
            <form action="{{ route('location.store') }}" method="POST">
                @csrf

                <!-- اسم المدينة -->
                <div class="form-group mb-3">
                    <label for="name" class="form-label fw-bold">اسم المدينة</label>
                    <input type="text" name="name" id="name" class="form-control"
                           placeholder="مثل: حلب - أعزاز" value="{{ $city->name ?? '' }}" required>
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <!-- تحديد الموقع -->
                <div class="form-group mb-4">
                    <label class="form-label fw-bold">تحديد الموقع</label>
                    <div class="mb-2">
                        <button type="button" onclick="getLocation()" class="btn btn-primary">
                            <i class="fas fa-location-arrow me-2"></i> احصل على موقعي
                        </button>
                        <small class="text-muted ms-2">أو أدخل الإحداثيات يدويًا</small>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="latitude" class="form-label">خط العرض (Latitude)</label>
                            <input type="number" name="latitude" id="latitude" class="form-control"
                                   step="0.00000001" placeholder="مثل: 36.58640084600335"
                                   value="{{ $city->latitude ?? '' }}" required>
                            @error('latitude')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="longitude" class="form-label">خط الطول (Longitude)</label>
                            <input type="number" name="longitude" id="longitude" class="form-control"
                                   step="0.00000001" placeholder="مثل: 37.036986790590035"
                                   value="{{ $city->longitude ?? '' }}" required>
                            @error('longitude')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- نطاقات الخدمة -->
                <div class="form-group mb-4">
                    <h4 class="fw-bold mb-3">تحديد نطاق خدمتك من موقعك الحالي</h4>
                    <div class="row">
                        <div class="col-md-3 col-sm-6 mb-3">
                            <label for="range_east" class="form-label">شرقًا (كم)</label>
                            <input type="number" name="range_east" id="range_east" class="form-control"
                                   step="0.1" value="{{ $city->range_east ?? 0 }}" min="0">
                            @error('range_east')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <label for="range_west" class="form-label">غربًا (كم)</label>
                            <input type="number" name="range_west" id="range_west" class="form-control"
                                   step="0.1" value="{{ $city->range_west ?? 0 }}" min="0">
                            @error('range_west')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <label for="range_north" class="form-label">شمالًا (كم)</label>
                            <input type="number" name="range_north" id="range_north" class="form-control"
                                   step="0.1" value="{{ $city->range_north ?? 0 }}" min="0">
                            @error('range_north')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <label for="range_south" class="form-label">جنوبًا (كم)</label>
                            <input type="number" name="range_south" id="range_south" class="form-control"
                                   step="0.1" value="{{ $city->range_south ?? 0 }}" min="0">
                            @error('range_south')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- زر الحفظ -->
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-2"></i> حفظ
                </button>
            </form>
        </div>
    </div>
</div>

<!-- السكربت -->
<script>
function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lon = position.coords.longitude;

                document.getElementById("latitude").value = lat;
                document.getElementById("longitude").value = lon;
                alert("تم تحديد موقعك بنجاح!");
            },
            function(error) {
                let errorMessage;
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        errorMessage = "تم رفض الوصول إلى الموقع.";
                        break;
                    case error.POSITION_UNAVAILABLE:
                        errorMessage = "معلومات الموقع غير متوفرة.";
                        break;
                    case error.TIMEOUT:
                        errorMessage = "انتهت مهلة الطلب.";
                        break;
                    default:
                        errorMessage = "حدث خطأ غير معروف: " + error.message;
                }
                alert("❌ " + errorMessage);
            },
            { enableHighAccuracy: true, timeout: 10000 }
        );
    } else {
        alert("المتصفح لا يدعم تحديد الموقع.");
    }
}
</script>

@endsection

<!-- التنسيق -->
<style>
    .card {
        border-radius: 10px;
        overflow: hidden;
    }

    .card-header {
        padding: 15px;
    }

    .form-label {
        font-weight: bold;
        color: #333;
    }

    .form-control {
        border-radius: 5px;
        padding: 8px;
    }

    .btn-primary {
        background-color: #007bff;
        border: none;
    }

    .btn-success {
        background-color: #28a745;
        border: none;
    }

    .btn:hover {
        opacity: 0.9;
    }

    .text-danger {
        font-size: 0.85rem;
    }

    @media (max-width: 768px) {
        .form-group {
            margin-bottom: 1.5rem;
        }
    }
</style>
