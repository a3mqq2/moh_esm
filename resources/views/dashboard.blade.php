@extends('layouts.app')

@section('title', 'الرئيسية')

@section('page-title')
    <div class="page-title-head d-flex align-items-center mb-3">
        <div class="flex-grow-1">
            <h4 class="page-main-title m-0">لوحة التحكم</h4>
            <p class="text-muted mb-0 small">نظرة عامة على حالة النظام</p>
        </div>
    </div>
@endsection

@section('content')
    {{-- Main stat cards (clickable shortcuts) --}}
    <div class="row g-3 mb-3">
        <div class="col-md-6 col-lg-3">
            <a href="{{ route('hospitals.index') }}" class="dash-card">
                <div class="dash-card-icon" style="background:#e8f0ff; color:#0066ff;">
                    <i class="ti ti-building-hospital"></i>
                </div>
                <div class="dash-card-body">
                    <div class="dash-card-value">{{ $totalHospitals }}</div>
                    <div class="dash-card-label">المستشفيات</div>
                </div>
            </a>
        </div>
        <div class="col-md-6 col-lg-3">
            <a href="{{ route('departments.index') }}" class="dash-card">
                <div class="dash-card-icon" style="background:#eaf5ff; color:#0a84ff;">
                    <i class="ti ti-category"></i>
                </div>
                <div class="dash-card-body">
                    <div class="dash-card-value">{{ $totalDepartments }}</div>
                    <div class="dash-card-label">الأقسام</div>
                </div>
            </a>
        </div>
        <div class="col-md-6 col-lg-3">
            <a href="{{ route('wards.index') }}" class="dash-card">
                <div class="dash-card-icon" style="background:#fff4e8; color:#ff6b00;">
                    <i class="ti ti-heartbeat"></i>
                </div>
                <div class="dash-card-body">
                    <div class="dash-card-value">{{ $totalWards }}</div>
                    <div class="dash-card-label">العنايات</div>
                </div>
            </a>
        </div>
        <div class="col-md-6 col-lg-3">
            <a href="{{ route('users.index') }}" class="dash-card">
                <div class="dash-card-icon" style="background:#f3eaff; color:#7c3aed;">
                    <i class="ti ti-users"></i>
                </div>
                <div class="dash-card-body">
                    <div class="dash-card-value">{{ $totalUsers }}</div>
                    <div class="dash-card-label">المستخدمين</div>
                </div>
            </a>
        </div>
    </div>

    {{-- Bed stats --}}
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="bed-card">
                <div class="bed-card-label">إجمالي الأسرّة</div>
                <div class="bed-card-value">{{ $totalBeds }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="bed-card">
                <div class="bed-card-label text-success">أسرّة شاغرة</div>
                <div class="bed-card-value text-success">{{ $totalVacant }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="bed-card">
                <div class="bed-card-label text-danger">أسرّة مشغولة</div>
                <div class="bed-card-value text-danger">{{ $totalOccupied }}</div>
            </div>
        </div>
    </div>

    {{-- Occupancy + Critical --}}
    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">معدل الإشغال الكلي</h6>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fs-1 fw-bold">{{ $occupancyRate }}%</span>
                        <span class="text-muted small">{{ $totalOccupied }} من {{ $totalBeds }}</span>
                    </div>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar {{ $occupancyRate >= 80 ? 'bg-danger' : ($occupancyRate >= 60 ? 'bg-warning' : 'bg-success') }}"
                             style="width: {{ $occupancyRate }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="fw-bold mb-3"><i class="ti ti-alert-triangle text-danger me-1"></i> تنبيهات حرجة</h6>
                    @if($criticalHospitals->isEmpty())
                        <div class="text-center text-muted py-3">
                            <i class="ti ti-circle-check text-success fs-3 d-block mb-1"></i>
                            <small>لا توجد تنبيهات</small>
                        </div>
                    @else
                        @foreach($criticalHospitals as $h)
                            <div class="critical-row">
                                <div class="flex-fill text-truncate">{{ $h['name'] }}</div>
                                <span class="badge bg-{{ $h['vacant'] === 0 ? 'danger' : 'warning' }}-subtle text-{{ $h['vacant'] === 0 ? 'danger' : 'warning' }}">
                                    {{ $h['vacant'] === 0 ? 'ممتلئ' : 'متبقي ' . $h['vacant'] }}
                                </span>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .dash-card {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 18px 20px;
            background: #fff;
            border-radius: 10px;
            border: 1px solid #eef0f3;
            text-decoration: none;
            color: inherit;
            transition: border-color 0.15s, transform 0.15s;
        }
        .dash-card:hover {
            border-color: #c7d2fe;
            transform: translateY(-1px);
            color: inherit;
        }
        .dash-card-icon {
            width: 48px; height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
        }
        .dash-card-value { font-size: 1.6rem; font-weight: 700; line-height: 1; }
        .dash-card-label { font-size: 0.85rem; color: #6b7280; margin-top: 4px; }

        .bed-card {
            background: #fff;
            border: 1px solid #eef0f3;
            border-radius: 10px;
            padding: 16px 20px;
            text-align: center;
        }
        .bed-card-label { font-size: 0.85rem; color: #6b7280; margin-bottom: 6px; }
        .bed-card-value { font-size: 1.8rem; font-weight: 700; line-height: 1; }

        .critical-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 0;
            border-bottom: 1px solid #f1f3f6;
            font-size: 0.9rem;
        }
        .critical-row:last-child { border-bottom: none; padding-bottom: 0; }
        .critical-row:first-child { padding-top: 0; }
    </style>
@endsection
