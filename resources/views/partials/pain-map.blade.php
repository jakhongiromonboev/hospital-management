{{--
    Reusable pain map widget — admin/doctor/patient sahifalarida ishlatiladi.
    Parametr: $painRecords (Collection of PainRecord).
--}}
@php $painRecords = $painRecords ?? collect(); @endphp

<div class="card">
    <div class="card-header py-2">
        <div class="d-flex justify-content-between align-items-center">
            <span>통증 신체 지도</span>
            <div class="d-flex gap-1">
                <span class="bm-legend-dot" style="background:#ef4444" title="{{ $t['pain_severity']['high'] }}"></span>
                <span class="bm-legend-dot" style="background:#f59e0b" title="{{ $t['pain_severity']['medium'] }}"></span>
                <span class="bm-legend-dot" style="background:#22c55e" title="{{ $t['pain_severity']['low'] }}"></span>
            </div>
        </div>
    </div>
    <div class="card-body p-0 body-map-container">
        <svg viewBox="0 0 848 1264" width="100%" id="bodyMap" preserveAspectRatio="xMidYMid meet">
            <defs>
                <radialGradient id="painGlowHigh"><stop offset="0%" stop-color="#ef4444" stop-opacity="0.85"/><stop offset="100%" stop-color="#ef4444" stop-opacity="0"/></radialGradient>
                <radialGradient id="painGlowMed"><stop offset="0%" stop-color="#f59e0b" stop-opacity="0.75"/><stop offset="100%" stop-color="#f59e0b" stop-opacity="0"/></radialGradient>
                <radialGradient id="painGlowLow"><stop offset="0%" stop-color="#22c55e" stop-opacity="0.65"/><stop offset="100%" stop-color="#22c55e" stop-opacity="0"/></radialGradient>
            </defs>

            <image href="{{ asset('images/body.png') }}" x="0" y="0" width="848" height="1264" preserveAspectRatio="xMidYMid meet"/>

            <circle cx="424" cy="135" r="95" class="pain-area" data-area="head" style="display:none"/>
            <ellipse cx="424" cy="225" rx="50" ry="35" class="pain-area" data-area="neck" style="display:none"/>
            <ellipse cx="424" cy="360" rx="140" ry="90" class="pain-area" data-area="chest" style="display:none"/>
            <ellipse cx="424" cy="430" rx="120" ry="65" class="pain-area" data-area="upper_back" style="display:none"/>
            <ellipse cx="424" cy="490" rx="105" ry="70" class="pain-area" data-area="stomach" style="display:none"/>
            <ellipse cx="424" cy="565" rx="115" ry="55" class="pain-area" data-area="lower_back" style="display:none"/>
            <circle cx="285" cy="270" r="65" class="pain-area" data-area="left_shoulder" style="display:none"/>
            <circle cx="563" cy="270" r="65" class="pain-area" data-area="right_shoulder" style="display:none"/>
            <circle cx="355" cy="940" r="60" class="pain-area" data-area="left_knee" style="display:none"/>
            <circle cx="493" cy="940" r="60" class="pain-area" data-area="right_knee" style="display:none"/>
            <circle cx="158" cy="660" r="55" class="pain-area" data-area="left_wrist" style="display:none"/>
            <circle cx="690" cy="660" r="55" class="pain-area" data-area="right_wrist" style="display:none"/>
            <circle cx="328" cy="1180" r="50" class="pain-area" data-area="left_ankle" style="display:none"/>
            <circle cx="520" cy="1180" r="50" class="pain-area" data-area="right_ankle" style="display:none"/>
        </svg>

        <div id="painTooltip" class="pain-tip"></div>

        @if($painRecords->isEmpty())
            <div class="pain-empty-overlay">통증 기록이 없습니다</div>
        @endif
    </div>
</div>

@push('styles')
<style>
    .bm-legend-dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; cursor: help; }

    .body-map-container {
        background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
        border-radius: 0 0 12px 12px; position: relative;
        display: flex; justify-content: center; align-items: center; padding: 14px 8px;
        min-height: 360px;
    }
    .body-map-container svg { max-width: 220px; width: 100%; height: auto; display: block; }
    .body-map-container svg image { filter: drop-shadow(0 4px 12px rgba(0,0,0,0.35)); }

    .pain-area {
        fill-opacity: 0; stroke: none; pointer-events: all; cursor: pointer;
    }
    .pain-area.active-high {
        fill: url(#painGlowHigh); fill-opacity: 1;
        animation: pulseHigh 1.2s ease-in-out infinite;
    }
    .pain-area.active-medium {
        fill: url(#painGlowMed); fill-opacity: 1;
        animation: pulseMed 1.8s ease-in-out infinite;
    }
    .pain-area.active-low {
        fill: url(#painGlowLow); fill-opacity: 1;
        animation: pulseLow 2.4s ease-in-out infinite;
    }
    @keyframes pulseHigh { 0%,100% { fill-opacity: 0.4; } 50% { fill-opacity: 1; } }
    @keyframes pulseMed  { 0%,100% { fill-opacity: 0.3; } 50% { fill-opacity: 0.85; } }
    @keyframes pulseLow  { 0%,100% { fill-opacity: 0.2; } 50% { fill-opacity: 0.7; } }

    .pain-row { cursor: pointer; transition: background 0.2s; }
    .pain-row.highlight { background: #fef3c7 !important; }

    .pain-tip {
        position: absolute; background: rgba(15,23,42,0.95); color: #fff;
        padding: 10px 16px; border-radius: 10px; font-size: 0.8rem;
        pointer-events: none; opacity: 0; transition: opacity 0.2s;
        z-index: 10; border: 1px solid rgba(255,255,255,0.1);
        max-width: 180px; text-align: center;
    }
    .pain-tip.show { opacity: 1; }
    .pain-tip .pt-area { font-weight: 700; font-size: 0.9rem; }
    .pain-tip .pt-sev { font-size: 0.7rem; margin-top: 2px; opacity: 0.8; }
    .pain-tip .pt-desc { font-size: 0.7rem; margin-top: 4px; font-style: italic; opacity: 0.7; }

    .pain-empty-overlay {
        position: absolute; bottom: 10px; left: 50%; transform: translateX(-50%);
        color: rgba(255,255,255,0.55); font-size: 0.78rem;
        background: rgba(15,23,42,0.6); padding: 4px 12px; border-radius: 999px;
        border: 1px solid rgba(255,255,255,0.08);
    }

    @media (max-width: 991px) {
        .body-map-container { min-height: 300px; padding: 10px 5px; }
        .body-map-container svg { max-width: 180px; }
    }

    /* Mobile: kompakt pain table */
    @media (max-width: 768px) {
        .body-map-container { min-height: 260px; padding: 8px 4px; }
        .body-map-container svg { max-width: 160px; }

        /* Pain row jadvalda YouTube tugmasi: faqat icon */
        .pain-row .btn-youtube-text { display: none; }
    }

    /* Small phones: description ustunini yashirish, sanani qisqartirish */
    @media (max-width: 576px) {
        .pain-row td:nth-child(3),
        table thead th:nth-child(3) { display: none; }

        .pain-row td:nth-child(4),
        table thead th:nth-child(4) {
            font-size: 0.72rem; max-width: 80px;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }

        .body-map-container { min-height: 220px; }
        .body-map-container svg { max-width: 140px; }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const painAreaKo = @json($t['pain_area']);
    const painSevKo = @json($t['pain_severity']);
    const painData = @json($painRecords->map(fn($r) => ['area' => $r->area, 'severity' => $r->severity, 'description' => $r->description])->values());

    const tooltip = document.getElementById('painTooltip');
    const container = document.querySelector('.body-map-container');
    if (!tooltip || !container) return;

    painData.forEach((pain, i) => {
        const zone = document.querySelector(`.pain-area[data-area="${pain.area}"]`);
        if (!zone) return;

        zone.style.display = '';
        zone.classList.add('active-' + pain.severity);
        zone.style.animationDelay = (i * 0.3) + 's';

        zone.addEventListener('mouseenter', function() {
            const sevColors = { high: '#ef4444', medium: '#f59e0b', low: '#22c55e' };
            const areaLabel = painAreaKo[pain.area] || pain.area.replace(/_/g, ' ');
            const sevLabel = painSevKo[pain.severity] || pain.severity;
            tooltip.innerHTML = `
                <div class="pt-area">${areaLabel}</div>
                <div class="pt-sev" style="color:${sevColors[pain.severity]}">● ${sevLabel}</div>
                ${pain.description ? '<div class="pt-desc">"' + pain.description + '"</div>' : ''}`;
            tooltip.classList.add('show');
        });

        zone.addEventListener('mousemove', function(e) {
            const rect = container.getBoundingClientRect();
            tooltip.style.left = (e.clientX - rect.left + 15) + 'px';
            tooltip.style.top = (e.clientY - rect.top - 20) + 'px';
        });

        zone.addEventListener('mouseleave', function() {
            tooltip.classList.remove('show');
        });
    });

    document.querySelectorAll('.pain-row').forEach(row => {
        row.addEventListener('mouseenter', function() {
            const area = this.dataset.area;
            const zone = document.querySelector(`.pain-area[data-area="${area}"]`);
            if (zone) zone.style.filter = 'brightness(2)';
            this.classList.add('highlight');
        });
        row.addEventListener('mouseleave', function() {
            const area = this.dataset.area;
            const zone = document.querySelector(`.pain-area[data-area="${area}"]`);
            if (zone) zone.style.filter = '';
            this.classList.remove('highlight');
        });
    });
});
</script>
@endpush
