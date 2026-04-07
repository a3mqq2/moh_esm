@extends('layouts.app')

@section('title', 'شاشة المراقبة')

@section('topbar-extras')
    <div class="topbar-monitor d-none d-md-flex align-items-center gap-2 ms-3">
        <span class="topbar-live-dot"></span>
        <span class="topbar-monitor-title">شاشة المراقبة المباشرة</span>
    </div>
    <div class="topbar-mini-stats d-none d-lg-flex align-items-center gap-2 ms-3">
        <div class="mini-stat mini-stat-blue">
            <i class="ti ti-building-hospital"></i>
            <span class="mini-stat-value" id="t-hospitals">0</span>
            <span class="mini-stat-label">مستشفى</span>
        </div>
        <div class="mini-stat mini-stat-dark">
            <i class="ti ti-bed"></i>
            <span class="mini-stat-value" id="t-beds">0</span>
            <span class="mini-stat-label">إجمالي</span>
        </div>
        <div class="mini-stat mini-stat-green">
            <i class="ti ti-bed-flag"></i>
            <span class="mini-stat-value" id="t-vacant">0</span>
            <span class="mini-stat-label">شاغرة</span>
        </div>
    </div>
    <div class="topbar-monitor-controls d-none d-md-flex align-items-center gap-2 ms-3">
        <button type="button" class="topbar-ctrl-btn" id="sound-toggle" title="الصوت (M)">
            <i class="ti ti-volume" id="sound-icon"></i>
        </button>
        <button type="button" class="topbar-ctrl-btn" id="fs-toggle" title="ملء الشاشة (F)">
            <i class="ti ti-maximize"></i>
        </button>
    </div>
@endsection

@section('content')
    <div id="monitor-grid" class="monitor-grid"></div>
    <div id="monitor-empty" class="monitor-empty">
        <i class="ti ti-loader fs-1 d-block mb-3"></i>
        <p>جاري التحميل...</p>
    </div>

    <style>
        :root {
            --m-green: #00b341;
            --m-red: #e60026;
            --m-orange: #ff6b00;
            --m-blue: #0066ff;
            --m-dark: #0a0a0a;
        }
        /* Full width - no container constraints */
        .content-page > .container-fluid {
            max-width: none !important;
            padding-left: 12px !important;
            padding-right: 12px !important;
        }

        /* Topbar extras (in main app topbar) */
        .topbar-monitor {
            padding: 5px 14px 5px 8px;
            background: var(--m-red);
            border-radius: 30px;
            border: 2px solid #fff;
            box-shadow: 0 3px 10px rgba(230,0,38,0.45);
        }
        .topbar-live-dot {
            width: 10px; height: 10px;
            border-radius: 50%;
            background: #fff;
            animation: live-pulse 1s infinite;
            display: inline-block;
        }
        @keyframes live-pulse {
            0%, 100% { opacity: 1; transform: scale(1); box-shadow: 0 0 0 0 rgba(255,255,255,0.7); }
            50% { opacity: 0.6; transform: scale(0.8); box-shadow: 0 0 0 6px rgba(255,255,255,0); }
        }
        .topbar-monitor-title {
            color: #fff !important;
            font-weight: 900 !important;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            white-space: nowrap;
        }
        .topbar-ctrl-btn {
            width: 32px; height: 32px;
            background: #fff;
            color: var(--m-dark);
            border: 2px solid #fff;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            transition: all 0.15s;
            padding: 0;
        }
        .topbar-ctrl-btn:hover { background: var(--m-blue); color: #fff; }
        .topbar-ctrl-btn.muted { background: var(--m-dark); color: #fff; }

        /* Mini stats in topbar */
        .topbar-mini-stats { display: flex; }
        .mini-stat {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 12px;
            border-radius: 20px;
            border: 2px solid #fff;
            color: #fff !important;
            font-weight: 800;
            font-size: 0.8rem;
            white-space: nowrap;
        }
        .mini-stat i { font-size: 1rem; }
        .mini-stat-value { font-size: 1rem; font-weight: 900; }
        .mini-stat-label { font-size: 0.7rem; font-weight: 700; opacity: 0.9; text-transform: uppercase; }
        .mini-stat-blue { background: var(--m-blue); }
        .mini-stat-green { background: var(--m-green); }
        .mini-stat-dark { background: var(--m-dark); }

        /* Grid - dense */
        .monitor-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 10px;
        }

        .monitor-empty {
            text-align: center;
            color: #666;
            padding: 80px 20px;
        }

        /* Hospital card - compact */
        .hcard {
            background: #fff;
            border: 2px solid var(--m-dark);
            border-radius: 6px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        .hcard.hcard-flash { animation: card-flash 0.7s ease; }
        @keyframes card-flash {
            0%, 100% { outline: 0 solid transparent; }
            50% { outline: 3px solid var(--m-blue); outline-offset: 2px; }
        }
        .hcard-header {
            padding: 6px 10px;
            background: var(--m-dark);
            color: #fff;
        }
        .hcard-name {
            font-size: 0.92rem;
            font-weight: 900;
            line-height: 1.2;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .hcard-loc {
            font-size: 0.7rem;
            font-weight: 600;
            opacity: 0.85;
            margin-top: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: flex;
            align-items: center;
            gap: 3px;
        }
        .hcard-loc i { font-size: 0.8rem; }
        .hcard-body {
            padding: 6px 8px;
            flex: 1;
        }
        .hcard-section { margin-bottom: 6px; }
        .hcard-section:last-child { margin-bottom: 0; }
        .hcard-section-title {
            font-size: 0.65rem;
            font-weight: 900;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-bottom: 4px;
            display: inline-block;
            padding: 1px 8px;
            border-radius: 3px;
        }
        .hcard-section-title.dept { background: var(--m-blue); }
        .hcard-section-title.ward { background: var(--m-orange); }
        .hcard-units {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(95px, 1fr));
            gap: 4px;
        }
        .hu {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 3px 6px;
            border: 1px solid var(--m-dark);
            border-radius: 3px;
            background: #fff;
            gap: 4px;
        }
        .hu.hu-low { background: #fff5e6; border-color: var(--m-orange); }
        .hu.hu-ok { background: #e8faec; border-color: var(--m-green); }
        .hu-name {
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--m-dark);
            line-height: 1.2;
            word-wrap: break-word;
            overflow-wrap: break-word;
            flex: 1;
            min-width: 0;
        }
        .hu-num {
            font-size: 0.95rem;
            font-weight: 900;
            line-height: 1;
            color: #fff;
            background: var(--m-green);
            border-radius: 3px;
            padding: 2px 6px;
            min-width: 24px;
            text-align: center;
            flex-shrink: 0;
        }
        .hu-low .hu-num { background: var(--m-orange); }

        .no-vacant {
            text-align: center;
            padding: 6px;
            color: var(--m-red);
            font-weight: 800;
            background: #ffe5e5;
            border: 1px dashed var(--m-red);
            border-radius: 3px;
            font-size: 0.75rem;
        }

        .hu.hu-changed { animation: hu-flash 0.7s ease; }
        @keyframes hu-flash {
            0% { transform: scale(1); }
            50% { transform: scale(1.15); }
            100% { transform: scale(1); }
        }
    </style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var dataUrl = '{{ route("monitor.data") }}';
        var grid = document.getElementById('monitor-grid');
        var emptyEl = document.getElementById('monitor-empty');
        var soundEnabled = true;
        var lastSnapshot = {}; // hospitalId -> { vacant, units: { type_id: vacant } }

        // ===== Sound (Web Audio API) =====
        var audioCtx = null;
        function ensureAudio() {
            if (!audioCtx) {
                try { audioCtx = new (window.AudioContext || window.webkitAudioContext)(); } catch(e) {}
            }
        }
        function beep(freq, duration, type) {
            if (!soundEnabled || !audioCtx) return;
            var osc = audioCtx.createOscillator();
            var gain = audioCtx.createGain();
            osc.type = type || 'sine';
            osc.frequency.value = freq;
            gain.gain.setValueAtTime(0.001, audioCtx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.3, audioCtx.currentTime + 0.02);
            gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + duration);
            osc.connect(gain);
            gain.connect(audioCtx.destination);
            osc.start();
            osc.stop(audioCtx.currentTime + duration);
        }
        function beepVacantUp() { beep(880, 0.18, 'sine'); setTimeout(function(){ beep(1320, 0.15, 'sine'); }, 100); }
        function beepVacantDown() { beep(440, 0.18, 'sine'); }

        // ===== Render =====
        function renderHospital(h, isFirstLoad) {
            var prevSnap = lastSnapshot[h.id] || { units: {} };
            var changed = false;
            var changedUnits = {};

            var renderUnit = function(unit, type) {
                if (unit.vacant === 0) return ''; // skip occupied
                var key = type + '_' + unit.id;
                var prevVacant = prevSnap.units[key];
                var isChanged = !isFirstLoad && prevVacant !== undefined && prevVacant !== unit.vacant;
                if (isChanged) changedUnits[key] = true;
                var statusClass = unit.vacant <= 2 ? 'hu-low' : 'hu-ok';
                var changedClass = isChanged ? ' hu-changed' : '';
                return '<div class="hu ' + statusClass + changedClass + '" data-key="' + key + '">'
                    + '<div class="hu-name">' + unit.name + '</div>'
                    + '<div class="hu-num">' + unit.vacant + '</div>'
                    + '</div>';
            };

            var deptsHtml = h.departments.map(function(d) { return renderUnit(d, 'd'); }).join('');
            var wardsHtml = h.wards.map(function(w) { return renderUnit(w, 'w'); }).join('');

            var deptsSection = '';
            if (deptsHtml.trim()) {
                deptsSection = '<div class="hcard-section">'
                    + '<div class="hcard-section-title dept"><i class="ti ti-category"></i> الأقسام</div>'
                    + '<div class="hcard-units">' + deptsHtml + '</div>'
                    + '</div>';
            }
            var wardsSection = '';
            if (wardsHtml.trim()) {
                wardsSection = '<div class="hcard-section">'
                    + '<div class="hcard-section-title ward"><i class="ti ti-heartbeat"></i> العنايات</div>'
                    + '<div class="hcard-units">' + wardsHtml + '</div>'
                    + '</div>';
            }

            var body = (deptsSection || wardsSection)
                ? deptsSection + wardsSection
                : '<div class="no-vacant">لا توجد أسرّة شاغرة</div>';

            // Status of the whole hospital
            var statusClass;
            if (h.total_vacant === 0) statusClass = 'hcard-status-empty';
            else if (h.total_vacant <= 5) statusClass = 'hcard-status-low';
            else statusClass = 'hcard-status-ok';

            // Detect any change to flash the whole card
            if (!isFirstLoad && prevSnap.vacant !== undefined && prevSnap.vacant !== h.total_vacant) {
                changed = true;
            }

            var card = document.createElement('div');
            card.className = 'hcard ' + statusClass + (changed ? ' hcard-flash' : '');
            card.dataset.id = h.id;
            var locHtml = h.location
                ? '<div class="hcard-loc"><i class="ti ti-map-pin"></i>' + h.location + '</div>'
                : '';

            card.innerHTML =
                '<div class="hcard-header">'
                +   '<div class="hcard-name">' + h.name + '</div>'
                +   locHtml
                + '</div>'
                + '<div class="hcard-body">' + body + '</div>';

            // Update snapshot
            var newUnits = {};
            h.departments.forEach(function(d) { newUnits['d_' + d.id] = d.vacant; });
            h.wards.forEach(function(w) { newUnits['w_' + w.id] = w.vacant; });
            lastSnapshot[h.id] = { vacant: h.total_vacant, units: newUnits };

            return { card: card, changed: changed, prevVacant: prevSnap.vacant, newVacant: h.total_vacant };
        }

        function render(data, isFirstLoad) {
            // Totals
            document.getElementById('t-hospitals').textContent = data.totals.hospitals;
            document.getElementById('t-beds').textContent = data.totals.beds;
            document.getElementById('t-vacant').textContent = data.totals.vacant;

            if (data.hospitals.length === 0) {
                grid.innerHTML = '';
                emptyEl.style.display = 'block';
                emptyEl.innerHTML = '<i class="ti ti-mood-empty fs-1 d-block mb-3"></i><p>لا توجد مستشفيات نشطة</p>';
                return;
            }
            emptyEl.style.display = 'none';

            grid.innerHTML = '';
            var anyVacantUp = false, anyVacantDown = false;

            data.hospitals.forEach(function(h) {
                var result = renderHospital(h, isFirstLoad);
                grid.appendChild(result.card);
                if (!isFirstLoad && result.prevVacant !== undefined) {
                    if (result.newVacant > result.prevVacant) anyVacantUp = true;
                    if (result.newVacant < result.prevVacant) anyVacantDown = true;
                }
            });

            // Sound
            if (!isFirstLoad) {
                if (anyVacantUp) beepVacantUp();
                else if (anyVacantDown) beepVacantDown();
            }
        }

        var isFirstLoad = true;
        function fetchData() {
            fetch(dataUrl, { headers: { 'Accept': 'application/json' } })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    render(data, isFirstLoad);
                    isFirstLoad = false;
                });
        }

        fetchData();
        setInterval(fetchData, 5000);

        // ===== Controls =====
        var soundBtn = document.getElementById('sound-toggle');
        var soundIcon = document.getElementById('sound-icon');
        var fsBtn = document.getElementById('fs-toggle');

        function toggleSound() {
            soundEnabled = !soundEnabled;
            if (soundEnabled) {
                ensureAudio();
                soundBtn.classList.remove('muted');
                soundIcon.className = 'ti ti-volume';
            } else {
                soundBtn.classList.add('muted');
                soundIcon.className = 'ti ti-volume-off';
            }
        }

        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(function(){});
            } else {
                document.exitFullscreen();
            }
        }

        if (soundBtn) soundBtn.addEventListener('click', function() { ensureAudio(); toggleSound(); });
        if (fsBtn) fsBtn.addEventListener('click', toggleFullscreen);

        // First click anywhere to unlock audio
        document.addEventListener('click', ensureAudio, { once: true });

        // Keyboard
        document.addEventListener('keydown', function(e) {
            if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;
            switch(e.key.toLowerCase()) {
                case 'm':
                    e.preventDefault();
                    ensureAudio();
                    toggleSound();
                    break;
                case 'f':
                    e.preventDefault();
                    toggleFullscreen();
                    break;
                case 'r':
                    e.preventDefault();
                    fetchData();
                    break;
            }
        });
    });
</script>
@endsection
