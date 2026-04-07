@extends('layouts.app')

@section('title', $hospital->name)

@section('page-title')
    <div class="hospital-header">
        <div class="hospital-header-inner">
            @if($hospital->logo)
                <img src="{{ asset('storage/' . $hospital->logo) }}" alt="{{ $hospital->name }}" class="hospital-logo" />
            @else
                <span class="hospital-logo-placeholder">
                    <i class="ti ti-building-hospital"></i>
                </span>
            @endif
            <div class="hospital-info">
                <div class="hospital-name">{{ $hospital->name }}</div>
                @if($hospital->location)
                    <div class="hospital-location"><i class="ti ti-map-pin"></i> {{ $hospital->location }}</div>
                @endif
            </div>
            <button type="button" class="help-trigger" id="open-help-btn" title="عرض الاختصارات">
                <i class="ti ti-keyboard"></i>
                <span>الاختصارات</span>
            </button>
        </div>
    </div>
@endsection

@section('content')
    @php
        $departments = $hospital->departments;
        $wards = $hospital->wards;
        $hasDepts = $departments->isNotEmpty();
        $hasWards = $wards->isNotEmpty();
        $totalBeds = $departments->sum('pivot.beds') + $wards->sum('pivot.beds');
        $totalVacant = $departments->sum('pivot.vacant_beds') + $wards->sum('pivot.vacant_beds');
        $totalOccupied = $totalBeds - $totalVacant;
    @endphp

    {{-- Summary Cards --}}
    <div class="row mb-3 g-3">
        <div class="col-md-4">
            <div class="stat-card stat-blue">
                <div class="stat-value" id="stat-total">{{ $totalBeds }}</div>
                <div class="stat-label">إجمالي الأسرّة</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card stat-green">
                <div class="stat-value" id="stat-vacant">{{ $totalVacant }}</div>
                <div class="stat-label">أسرّة شاغرة</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card stat-red">
                <div class="stat-value" id="stat-occupied">{{ $totalOccupied }}</div>
                <div class="stat-label">أسرّة مشغولة</div>
            </div>
        </div>
    </div>

    {{-- Help Mode Overlay --}}
    <div class="help-overlay" id="help-overlay">
        <div class="help-modal">
            <button type="button" class="help-close" id="close-help-btn"><i class="ti ti-x"></i></button>
            <div class="help-title">
                <i class="ti ti-keyboard"></i>
                <span>اختصارات لوحة المفاتيح</span>
            </div>
            <div class="help-grid">
                <div class="help-row">
                    <div class="help-keys"><kbd>←</kbd><kbd>→</kbd></div>
                    <div class="help-desc">التنقل بين الكروت</div>
                </div>
                <div class="help-row">
                    <div class="help-keys"><kbd>↑</kbd></div>
                    <div class="help-desc">إضافة سرير شاغر</div>
                </div>
                <div class="help-row">
                    <div class="help-keys"><kbd>↓</kbd></div>
                    <div class="help-desc">إنقاص سرير شاغر</div>
                </div>
                <div class="help-row">
                    <div class="help-keys"><kbd>Space</kbd></div>
                    <div class="help-desc">تبديل أقسام / عنايات</div>
                </div>
            </div>

            <div class="help-title" style="margin-top:18px;">
                <i class="ti ti-palette"></i>
                <span>دلالة الألوان</span>
            </div>
            <div class="help-grid">
                <div class="help-row">
                    <div class="help-color-box color-green"></div>
                    <div class="help-desc">في متسع — أكثر من سريرين شاغرين</div>
                </div>
                <div class="help-row">
                    <div class="help-color-box color-orange"></div>
                    <div class="help-desc">تحذير — سريران أو أقل شاغرين</div>
                </div>
                <div class="help-row">
                    <div class="help-color-box color-red"></div>
                    <div class="help-desc">ممتلئ — لا توجد أسرّة شاغرة</div>
                </div>
            </div>
            <button type="button" class="help-got-it" id="dismiss-help-btn">
                <i class="ti ti-check"></i> فهمت
            </button>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if($hasDepts || $hasWards)
                {{-- Tabs --}}
                @if($hasDepts && $hasWards)
                    <ul class="nav nav-tabs nav-fill mb-0" id="unit-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="tab-depts" data-bs-toggle="tab" data-bs-target="#pane-depts" type="button" role="tab">
                                <i class="ti ti-category me-1"></i> الأقسام
                                <span class="badge bg-primary-subtle text-primary ms-1">{{ $departments->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab-wards" data-bs-toggle="tab" data-bs-target="#pane-wards" type="button" role="tab">
                                <i class="ti ti-heartbeat me-1"></i> العنايات
                                <span class="badge bg-info-subtle text-info ms-1">{{ $wards->count() }}</span>
                            </button>
                        </li>
                    </ul>
                @endif

                <div class="tab-content">
                    {{-- Departments --}}
                    @if($hasDepts)
                        <div class="tab-pane fade {{ $hasDepts ? 'show active' : '' }}" id="pane-depts" role="tabpanel">
                            <div class="bed-grid">
                                @foreach($departments as $dept)
                                    <div class="bed-unit" data-type="department" data-id="{{ $dept->id }}" data-max="{{ $dept->pivot->beds }}" data-vacant="{{ $dept->pivot->vacant_beds }}" tabindex="0">
                                        <div class="bed-unit-name">{{ $dept->name }}</div>
                                        <div class="vacant-stepper">
                                            <button type="button" class="stepper-btn stepper-plus" tabindex="-1"><i class="ti ti-plus"></i></button>
                                            <input type="number" class="stepper-value" value="{{ $dept->pivot->vacant_beds }}" min="0" max="{{ $dept->pivot->beds }}" />
                                            <button type="button" class="stepper-btn stepper-minus" tabindex="-1"><i class="ti ti-minus"></i></button>
                                        </div>
                                        <div class="bed-unit-meta">
                                            <span class="occupied-count">{{ $dept->pivot->beds - $dept->pivot->vacant_beds }}</span>
                                            <span class="meta-sep">/</span>
                                            <span>{{ $dept->pivot->beds }}</span>
                                        </div>
                                        <div class="vacant-bar">
                                            <div class="vacant-bar-fill" style="width: {{ $dept->pivot->beds > 0 ? round(($dept->pivot->vacant_beds / $dept->pivot->beds) * 100) : 0 }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Wards --}}
                    @if($hasWards)
                        <div class="tab-pane fade {{ !$hasDepts ? 'show active' : '' }}" id="pane-wards" role="tabpanel">
                            <div class="bed-grid">
                                @foreach($wards as $ward)
                                    <div class="bed-unit" data-type="ward" data-id="{{ $ward->id }}" data-max="{{ $ward->pivot->beds }}" data-vacant="{{ $ward->pivot->vacant_beds }}" tabindex="0">
                                        <div class="bed-unit-name">{{ $ward->name }}</div>
                                        <div class="vacant-stepper">
                                            <button type="button" class="stepper-btn stepper-plus" tabindex="-1"><i class="ti ti-plus"></i></button>
                                            <input type="number" class="stepper-value" value="{{ $ward->pivot->vacant_beds }}" min="0" max="{{ $ward->pivot->beds }}" />
                                            <button type="button" class="stepper-btn stepper-minus" tabindex="-1"><i class="ti ti-minus"></i></button>
                                        </div>
                                        <div class="bed-unit-meta">
                                            <span class="occupied-count">{{ $ward->pivot->beds - $ward->pivot->vacant_beds }}</span>
                                            <span class="meta-sep">/</span>
                                            <span>{{ $ward->pivot->beds }}</span>
                                        </div>
                                        <div class="vacant-bar">
                                            <div class="vacant-bar-fill" style="width: {{ $ward->pivot->beds > 0 ? round(($ward->pivot->vacant_beds / $ward->pivot->beds) * 100) : 0 }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @else
                <div class="text-center text-muted py-5">
                    <i class="ti ti-mood-empty fs-1 d-block mb-2"></i>
                    <p>لا توجد أقسام أو عنايات مسجلة لهذا المستشفى بعد.</p>
                </div>
            @endif
        </div>
    </div>

    <style>
        :root {
            --bold-green: #00b341;
            --bold-red: #e60026;
            --bold-blue: #0066ff;
            --bold-orange: #ff6b00;
            --bold-dark: #0a0a0a;
        }
        /* Hospital Header */
        .hospital-header {
            background: var(--bold-dark);
            border: 4px solid var(--bold-dark);
            border-radius: 12px;
            padding: 0;
            margin-bottom: 18px;
            box-shadow: 6px 6px 0 var(--bold-blue);
            overflow: hidden;
        }
        .hospital-header-inner {
            display: flex;
            align-items: center;
            gap: 18px;
            padding: 18px 22px;
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);
        }
        .hospital-logo {
            width: 72px;
            height: 72px;
            object-fit: cover;
            border-radius: 8px;
            border: 4px solid var(--bold-blue);
            background: #fff;
            flex-shrink: 0;
        }
        .hospital-logo-placeholder {
            width: 72px;
            height: 72px;
            background: var(--bold-blue);
            border: 4px solid var(--bold-blue);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 2rem;
            flex-shrink: 0;
        }
        .hospital-info { flex: 1; min-width: 0; }
        .hospital-name {
            font-size: 1.7rem;
            font-weight: 900;
            color: #fff;
            line-height: 1.1;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .hospital-location {
            font-size: 0.9rem;
            color: #fff;
            opacity: 0.85;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .help-trigger {
            background: var(--bold-orange);
            color: #fff;
            border: 3px solid #fff;
            border-radius: 8px;
            padding: 10px 16px;
            font-weight: 800;
            font-size: 0.9rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.15s;
            text-transform: uppercase;
            flex-shrink: 0;
        }
        .help-trigger:hover {
            background: #fff;
            color: var(--bold-orange);
            border-color: var(--bold-orange);
            transform: translate(-2px, -2px);
        }
        .help-trigger i { font-size: 1.2rem; }

        /* Help Overlay */
        .help-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.85);
            z-index: 99990;
            align-items: center;
            justify-content: center;
            animation: help-fade 0.2s ease;
        }
        .help-overlay.show { display: flex; }
        @keyframes help-fade { from { opacity: 0; } to { opacity: 1; } }
        .help-modal {
            background: #fff;
            border: 5px solid var(--bold-dark);
            border-radius: 14px;
            padding: 30px 36px;
            max-width: 480px;
            width: 90%;
            box-shadow: 10px 10px 0 var(--bold-orange);
            position: relative;
            animation: help-pop 0.25s ease;
        }
        @keyframes help-pop {
            from { transform: scale(0.85) translateY(-20px); opacity: 0; }
            to { transform: scale(1) translateY(0); opacity: 1; }
        }
        .help-close {
            position: absolute;
            top: -5px;
            left: -5px;
            width: 38px; height: 38px;
            background: var(--bold-red);
            color: #fff;
            border: 4px solid var(--bold-dark);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1.1rem;
            font-weight: 900;
            padding: 0;
        }
        .help-close:hover { background: #b30020; }
        .help-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.3rem;
            font-weight: 900;
            color: var(--bold-dark);
            margin-bottom: 22px;
            padding-bottom: 14px;
            border-bottom: 3px solid var(--bold-dark);
            text-transform: uppercase;
        }
        .help-title i { color: var(--bold-blue); font-size: 1.6rem; }
        .help-grid {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 24px;
        }
        .help-row {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 10px 14px;
            background: #f5f5f5;
            border: 2px solid var(--bold-dark);
            border-radius: 8px;
        }
        .help-keys {
            display: flex;
            gap: 4px;
            flex-shrink: 0;
            min-width: 80px;
        }
        .help-color-box {
            width: 36px; height: 36px;
            border-radius: 6px;
            border: 3px solid var(--bold-dark);
            flex-shrink: 0;
            min-width: 36px;
            box-shadow: 2px 2px 0 var(--bold-dark);
        }
        .color-green { background: var(--bold-green); }
        .color-orange { background: var(--bold-orange); }
        .color-red { background: var(--bold-red); }
        .help-keys kbd {
            background: var(--bold-dark);
            color: #fff;
            border: 2px solid var(--bold-dark);
            border-radius: 6px;
            padding: 6px 10px;
            font-size: 0.95rem;
            font-weight: 900;
            min-width: 36px;
            text-align: center;
            box-shadow: 2px 2px 0 #555;
        }
        .help-desc {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--bold-dark);
        }
        .help-got-it {
            width: 100%;
            background: var(--bold-green);
            color: #fff;
            border: 4px solid var(--bold-dark);
            border-radius: 10px;
            padding: 14px;
            font-weight: 900;
            font-size: 1.05rem;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 4px 4px 0 var(--bold-dark);
            transition: all 0.1s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .help-got-it:hover {
            transform: translate(-2px, -2px);
            box-shadow: 6px 6px 0 var(--bold-dark);
        }
        .help-got-it:active {
            transform: translate(0, 0);
            box-shadow: 2px 2px 0 var(--bold-dark);
        }
        .stat-card {
            border: 4px solid var(--bold-dark);
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            color: #fff;
            box-shadow: 6px 6px 0 var(--bold-dark);
        }
        .stat-blue { background: var(--bold-blue); }
        .stat-green { background: var(--bold-green); }
        .stat-red { background: var(--bold-red); }
        .stat-value {
            font-size: 3rem;
            font-weight: 900;
            line-height: 1;
            margin-bottom: 6px;
        }
        .stat-label {
            font-size: 1rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .bed-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 14px;
            padding: 14px 0;
        }
        @media (max-width: 1400px) { .bed-grid { grid-template-columns: repeat(5, 1fr); } }
        @media (max-width: 1200px) { .bed-grid { grid-template-columns: repeat(4, 1fr); } }
        @media (max-width: 900px)  { .bed-grid { grid-template-columns: repeat(3, 1fr); } }
        @media (max-width: 600px)  { .bed-grid { grid-template-columns: repeat(2, 1fr); } }

        .bed-unit {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 18px 14px 14px;
            border-radius: 10px;
            border: 3px solid var(--bold-dark);
            background: #fff;
            transition: all 0.15s ease;
            outline: none;
            cursor: pointer;
            text-align: center;
            box-shadow: 4px 4px 0 var(--bold-dark);
        }
        .bed-unit:hover {
            transform: translate(-2px, -2px);
            box-shadow: 6px 6px 0 var(--bold-dark);
        }
        /* Status colors based on availability */
        .bed-unit.status-full {
            background: #ffe5e5;
            border-color: var(--bold-red);
            box-shadow: 4px 4px 0 var(--bold-red);
        }
        .bed-unit.status-low {
            background: #fff0d9;
            border-color: var(--bold-orange);
            box-shadow: 4px 4px 0 var(--bold-orange);
        }
        .bed-unit.status-ok {
            background: #e3f9e5;
            border-color: var(--bold-green);
            box-shadow: 4px 4px 0 var(--bold-green);
        }
        .bed-unit.active-row {
            border-color: var(--bold-blue) !important;
            box-shadow: 4px 4px 0 var(--bold-blue) !important;
            background: linear-gradient(270deg, rgba(0,102,255,0.2), rgba(0,102,255,0.05), rgba(0,102,255,0.2)) !important;
            background-size: 200% 100% !important;
            animation: bed-pulse 1.8s ease infinite;
            transform: translate(-2px, -2px);
        }
        @keyframes bed-pulse {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .bed-unit-name {
            font-weight: 900;
            font-size: 1.15rem;
            color: var(--bold-dark);
            line-height: 1.2;
            margin-bottom: 14px;
            min-height: 2.4em;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .vacant-stepper {
            display: flex;
            flex-direction: column;
            align-items: stretch;
            background: #fff;
            border: 3px solid var(--bold-dark);
            border-radius: 8px;
            overflow: hidden;
            width: 90px;
            margin-bottom: 10px;
        }
        .stepper-btn {
            width: 100%;
            height: 32px;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 900;
            transition: all 0.1s;
            padding: 0;
        }
        .stepper-plus { background: var(--bold-green); color: #fff; }
        .stepper-plus:hover { background: #008c33; }
        .stepper-minus { background: var(--bold-red); color: #fff; }
        .stepper-minus:hover { background: #b30020; }
        .stepper-value {
            width: 100%;
            text-align: center;
            border: none;
            border-top: 3px solid var(--bold-dark);
            border-bottom: 3px solid var(--bold-dark);
            font-size: 2rem;
            font-weight: 900;
            color: var(--bold-dark);
            background: #fff;
            padding: 8px 0;
            -moz-appearance: textfield;
            line-height: 1;
        }
        .stepper-value::-webkit-outer-spin-button,
        .stepper-value::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
        .stepper-value:focus { outline: none; background: #fff8c5; }
        .bed-unit-meta {
            font-size: 1rem;
            display: flex;
            gap: 4px;
            font-weight: 800;
            color: var(--bold-dark);
            margin-bottom: 8px;
            justify-content: center;
            align-items: baseline;
        }
        .occupied-count { color: var(--bold-red); font-size: 1.1rem; }
        .meta-sep { color: #999; }
        .vacant-bar {
            width: 100%;
            height: 6px;
            background: #fff;
            overflow: hidden;
            border: 2px solid var(--bold-dark);
        }
        .vacant-bar-fill {
            height: 100%;
            background: var(--bold-green);
            transition: width 0.3s ease, background 0.3s ease;
        }
        .status-full .vacant-bar-fill { background: var(--bold-red); }
        .status-low .vacant-bar-fill { background: var(--bold-orange); }
        #unit-tabs .nav-link {
            font-weight: 800;
            padding: 14px 20px;
            font-size: 1rem;
            border: 3px solid transparent;
            color: #555;
        }
        #unit-tabs .nav-link.active {
            color: var(--bold-blue);
            border-color: var(--bold-blue);
            border-bottom-color: #fff;
        }

        .save-indicator {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%) translateY(120px);
            background: var(--bold-green);
            color: #fff;
            padding: 14px 28px;
            border-radius: 10px;
            border: 4px solid var(--bold-dark);
            font-size: 1.05rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 6px 6px 0 var(--bold-dark);
            z-index: 9999;
            transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.2s;
            pointer-events: none;
            opacity: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .save-indicator i { font-size: 1.3rem; }
        .save-indicator.show {
            transform: translateX(-50%) translateY(0);
            opacity: 1;
        }
    </style>

    <div class="save-indicator" id="save-indicator">
        <i class="ti ti-check me-1"></i> تم الحفظ
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var token = '{{ csrf_token() }}';
        var saveUrl = '{{ route("my-hospital.update-vacant") }}';
        var saveTimer = null;
        var indicator = document.getElementById('save-indicator');

        // Help Mode
        var helpOverlay = document.getElementById('help-overlay');
        function openHelp() { helpOverlay.classList.add('show'); }
        function closeHelp() {
            helpOverlay.classList.remove('show');
            localStorage.setItem('hospital_dashboard_help_seen', '1');
        }
        document.getElementById('open-help-btn').addEventListener('click', openHelp);
        document.getElementById('close-help-btn').addEventListener('click', closeHelp);
        document.getElementById('dismiss-help-btn').addEventListener('click', closeHelp);
        helpOverlay.addEventListener('click', function(e) {
            if (e.target === helpOverlay) closeHelp();
        });
        // Show help on first visit
        if (!localStorage.getItem('hospital_dashboard_help_seen')) {
            setTimeout(openHelp, 500);
        }

        function showSaved() {
            indicator.classList.add('show');
            setTimeout(function() { indicator.classList.remove('show'); }, 1500);
        }

        var currentUnit = null;

        function setActiveRow(unit) {
            if (currentUnit) currentUnit.classList.remove('active-row');
            currentUnit = unit;
            if (unit) {
                unit.classList.add('active-row');
                unit.scrollIntoView({ block: 'nearest' });
            }
        }

        // Click on row
        document.addEventListener('click', function(e) {
            var unit = e.target.closest('.bed-unit');
            if (unit && !e.target.closest('.stepper-btn') && !e.target.closest('.stepper-value')) {
                setActiveRow(unit);
            }
        });

        function getVisibleUnits() {
            var pane = document.querySelector('.tab-pane.active');
            if (pane) return Array.from(pane.querySelectorAll('.bed-unit'));
            return Array.from(document.querySelectorAll('.bed-unit'));
        }

        function applyStatus(unit) {
            var max = parseInt(unit.dataset.max) || 0;
            var vacant = parseInt(unit.querySelector('.stepper-value').value) || 0;
            unit.classList.remove('status-full', 'status-low', 'status-ok');
            if (max === 0) return;
            if (vacant === 0) {
                unit.classList.add('status-full');
            } else if (vacant <= 2) {
                unit.classList.add('status-low');
            } else {
                unit.classList.add('status-ok');
            }
        }

        function reorderGrid(grid) {
            if (!grid) return;
            // Order: ok (3) → low (2) → full (1)
            var rank = function(u) {
                if (u.classList.contains('status-full')) return 1;
                if (u.classList.contains('status-low')) return 2;
                return 3;
            };
            var units = Array.from(grid.querySelectorAll('.bed-unit'));
            units.sort(function(a, b) {
                return rank(b) - rank(a);
            });
            units.forEach(function(u) { grid.appendChild(u); });
        }

        function updateStats() {
            var totalBeds = 0, totalVacant = 0;
            document.querySelectorAll('.bed-unit').forEach(function(u) {
                var max = parseInt(u.dataset.max) || 0;
                var vacant = parseInt(u.querySelector('.stepper-value').value) || 0;
                totalBeds += max;
                totalVacant += vacant;
                u.querySelector('.occupied-count').textContent = max - vacant;
                var bar = u.querySelector('.vacant-bar-fill');
                bar.style.width = max > 0 ? Math.round((vacant / max) * 100) + '%' : '0%';
                applyStatus(u);
            });
            document.getElementById('stat-total').textContent = totalBeds;
            document.getElementById('stat-vacant').textContent = totalVacant;
            document.getElementById('stat-occupied').textContent = totalBeds - totalVacant;
        }

        // Initial status + ordering
        document.querySelectorAll('.bed-unit').forEach(applyStatus);
        document.querySelectorAll('.bed-grid').forEach(reorderGrid);

        function saveVacant(unit) {
            fetch(saveUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
                body: JSON.stringify({
                    type: unit.dataset.type,
                    id: unit.dataset.id,
                    vacant_beds: parseInt(unit.querySelector('.stepper-value').value) || 0
                })
            }).then(function(r) { if (r.ok) showSaved(); });
        }

        var reorderTimer = null;
        function scheduleReorder(unit) {
            clearTimeout(reorderTimer);
            reorderTimer = setTimeout(function() {
                reorderGrid(unit.closest('.bed-grid'));
            }, 800);
        }

        function adjustVacant(unit, delta) {
            var input = unit.querySelector('.stepper-value');
            var max = parseInt(unit.dataset.max) || 0;
            var val = parseInt(input.value) || 0;
            var newVal = Math.max(0, Math.min(max, val + delta));
            if (newVal === val) return;
            input.value = newVal;
            updateStats();
            clearTimeout(saveTimer);
            saveTimer = setTimeout(function() { saveVacant(unit); }, 600);
            scheduleReorder(unit);
        }

        function commitInput(inputEl) {
            var unit = inputEl.closest('.bed-unit');
            var max = parseInt(unit.dataset.max) || 0;
            var val = Math.max(0, Math.min(max, parseInt(inputEl.value) || 0));
            inputEl.value = val;
            updateStats();
            clearTimeout(saveTimer);
            saveTimer = setTimeout(function() { saveVacant(unit); }, 600);
            scheduleReorder(unit);
        }

        // Click +/-
        document.addEventListener('click', function(e) {
            var plus = e.target.closest('.stepper-plus');
            var minus = e.target.closest('.stepper-minus');
            if (plus) {
                var unit = plus.closest('.bed-unit');
                adjustVacant(unit, 1);
                setActiveRow(unit);
            } else if (minus) {
                var unit = minus.closest('.bed-unit');
                adjustVacant(unit, -1);
                setActiveRow(unit);
            }
        });

        // Direct input
        document.querySelectorAll('.stepper-value').forEach(function(input) {
            input.addEventListener('change', function() { commitInput(this); });
            input.addEventListener('blur', function() { commitInput(this); });
        });

        function switchTab() {
            var cur = document.querySelector('#unit-tabs .nav-link.active');
            if (!cur) return;
            var nextId = cur.id === 'tab-depts' ? 'tab-wards' : 'tab-depts';
            var nextTab = document.getElementById(nextId);
            if (!nextTab) return;
            new bootstrap.Tab(nextTab).show();
            setTimeout(function() {
                var u = getVisibleUnits();
                if (u.length > 0) setActiveRow(u[0]);
            }, 200);
        }

        // Keyboard
        document.addEventListener('keydown', function(e) {
            // If help overlay open: Escape closes it
            if (helpOverlay.classList.contains('show')) {
                if (e.key === 'Escape' || e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    closeHelp();
                }
                return;
            }

            var active = document.activeElement;

            // Inside stepper-value input
            if (active && active.classList.contains('stepper-value')) {
                if (e.key === 'Escape' || e.key === 'Enter') {
                    e.preventDefault();
                    commitInput(active);
                    setActiveRow(active.closest('.bed-unit'));
                    active.blur();
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    active.value = Math.min(parseInt(active.max), (parseInt(active.value) || 0) + 1);
                    commitInput(active);
                } else if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    active.value = Math.max(0, (parseInt(active.value) || 0) - 1);
                    commitInput(active);
                }
                return;
            }

            // Use currentUnit instead of focus-based detection
            if (!currentUnit) {
                if (e.key === 'ArrowDown' || e.key === 'ArrowUp' || e.key === 'ArrowLeft' || e.key === 'ArrowRight') {
                    e.preventDefault();
                    var units = getVisibleUnits();
                    if (units.length > 0) setActiveRow(units[0]);
                } else if (e.key === ' ') {
                    e.preventDefault();
                    switchTab();
                }
                return;
            }

            var units = getVisibleUnits();
            var idx = units.indexOf(currentUnit);

            switch(e.key) {
                case 'ArrowUp':
                    e.preventDefault();
                    adjustVacant(currentUnit, 1);
                    break;
                case 'ArrowDown':
                    e.preventDefault();
                    adjustVacant(currentUnit, -1);
                    break;
                case 'ArrowRight':
                    // RTL: right = previous
                    e.preventDefault();
                    if (idx > 0) setActiveRow(units[idx - 1]);
                    break;
                case 'ArrowLeft':
                    // RTL: left = next
                    e.preventDefault();
                    if (idx < units.length - 1) setActiveRow(units[idx + 1]);
                    break;
                case ' ':
                    e.preventDefault();
                    switchTab();
                    break;
            }
        });

        // Select first unit on load
        setTimeout(function() {
            var units = getVisibleUnits();
            if (units.length > 0) setActiveRow(units[0]);
        }, 400);
    });
</script>
@endsection
