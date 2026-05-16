<!-- 날씨 위젯 -->
<div class="card mb-4" id="weatherCard">
    <div class="card-body py-0 px-0">
        <div id="weatherWidget">
            <div class="d-flex align-items-center justify-content-center py-3">
                <div class="spinner-border spinner-border-sm text-primary me-2"></div>
                <span class="text-muted small">날씨 불러오는 중...</span>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .w-compact {
        display: flex; align-items: center; justify-content: space-between;
        padding: 14px 24px; flex-wrap: wrap; gap: 8px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px; color: #fff;
    }
    .w-left { display: flex; align-items: center; gap: 12px; }
    .w-left img { width: 48px; height: 48px; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2)); }
    .w-temp { font-size: 1.75rem; font-weight: 700; line-height: 1; }
    .w-desc { font-size: 0.8rem; opacity: 0.85; }
    .w-city { font-size: 0.75rem; opacity: 0.7; }
    .w-stats { display: flex; gap: 20px; }
    .w-stat { text-align: center; }
    .w-stat-val { font-size: 0.9rem; font-weight: 700; }
    .w-stat-lbl { font-size: 0.65rem; opacity: 0.7; text-transform: uppercase; }
    .w-forecast { display: flex; gap: 4px; }
    .w-fc {
        text-align: center; padding: 4px 10px; background: rgba(255,255,255,0.15);
        border-radius: 8px; font-size: 0.7rem;
    }
    .w-fc img { width: 26px; height: 26px; }
    .w-fc-temp { font-weight: 700; font-size: 0.75rem; }
    .w-fc-low { opacity: 0.6; }

    @media (max-width: 768px) {
        .w-compact { padding: 12px 16px; gap: 10px; justify-content: center; }
        .w-left img { width: 40px; height: 40px; }
        .w-temp { font-size: 1.4rem; }
        .w-stats { gap: 14px; }
        .w-stat-val { font-size: 0.8rem; }
        .w-forecast { gap: 3px; flex-wrap: nowrap; overflow-x: auto; }
        .w-fc { padding: 4px 7px; min-width: 52px; }
        .w-fc img { width: 22px; height: 22px; }
    }
    @media (max-width: 480px) {
        .w-compact { flex-direction: column; align-items: stretch; gap: 10px; padding: 12px; }
        .w-left { justify-content: center; }
        .w-stats { justify-content: center; gap: 16px; }
        .w-forecast { justify-content: center; }
    }
</style>
@endpush

@push('scripts')
<script>
(function() {
    function desc(c){const d={0:'맑음',1:'대체로 맑음',2:'부분적으로 흐림',3:'흐림',45:'안개',48:'서리 안개',51:'가벼운 이슬비',53:'이슬비',55:'강한 이슬비',61:'가벼운 비',63:'비',65:'폭우',71:'가벼운 눈',73:'눈',75:'폭설',80:'소나기',81:'보통 소나기',82:'강한 소나기',95:'뇌우'};return d[c]||'알 수 없음';}
    function icon(c,s){let i='01d';if(c===0)i='01d';else if(c<=2)i='02d';else if(c===3)i='04d';else if(c<=48)i='50d';else if(c<=55)i='09d';else if(c<=65)i='10d';else if(c<=77)i='13d';else if(c<=82)i='09d';else if(c<=86)i='13d';else i='11d';return`https://openweathermap.org/img/wn/${i}@${s}x.png`;}
    function dayN(d){return['일','월','화','수','목','금','토'][new Date(d).getDay()];}

    function render(data, city) {
        const c=data.current, d=data.daily;
        let fc='';
        for(let i=1;i<=4;i++) fc+=`<div class="w-fc"><div>${dayN(d.time[i])}</div><img src="${icon(d.weather_code[i],2)}"><div class="w-fc-temp">${Math.round(d.temperature_2m_max[i])}°<span class="w-fc-low"> ${Math.round(d.temperature_2m_min[i])}°</span></div></div>`;

        document.getElementById('weatherWidget').innerHTML=`
        <div class="w-compact">
            <div class="w-left">
                <img src="${icon(c.weather_code,2)}">
                <div>
                    <div class="w-temp">${Math.round(c.temperature_2m)}°C</div>
                    <div class="w-desc">${desc(c.weather_code)}</div>
                    <div class="w-city"><i class="bi bi-geo-alt-fill"></i> ${city}</div>
                </div>
            </div>
            <div class="w-stats">
                <div class="w-stat"><div class="w-stat-val">${Math.round(c.apparent_temperature)}°</div><div class="w-stat-lbl">체감</div></div>
                <div class="w-stat"><div class="w-stat-val">${c.relative_humidity_2m}%</div><div class="w-stat-lbl">습도</div></div>
                <div class="w-stat"><div class="w-stat-val">${Math.round(c.wind_speed_10m)}</div><div class="w-stat-lbl">km/h</div></div>
            </div>
            <div class="w-forecast">${fc}</div>
        </div>`;
    }

    function load(lat,lon,city) {
        fetch(`https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current=temperature_2m,relative_humidity_2m,apparent_temperature,weather_code,wind_speed_10m&daily=weather_code,temperature_2m_max,temperature_2m_min&timezone=auto&forecast_days=6`)
            .then(r=>r.json()).then(d=>render(d,city))
            .catch(()=>{document.getElementById('weatherWidget').innerHTML='<div class="text-center py-2 text-muted small"><i class="bi bi-cloud-slash"></i> 날씨를 불러올 수 없습니다</div>';});
    }

    // Geolocation cache: 7 kunlik TTL — har sahifa yangilanganda qayta so'ramaydi
    const CACHE_KEY='hms_geo_cache';
    const TTL_MS=7*24*60*60*1000;

    function getCached(){
        try{
            const raw=localStorage.getItem(CACHE_KEY);
            if(!raw) return null;
            const o=JSON.parse(raw);
            if(!o||!o.savedAt||Date.now()-o.savedAt>TTL_MS) return null;
            return o;
        }catch(e){return null;}
    }
    function saveCache(lat,lon,city){
        try{localStorage.setItem(CACHE_KEY,JSON.stringify({lat,lon,city,savedAt:Date.now()}));}catch(e){}
    }

    const cached=getCached();
    if(cached){
        // Saqlangan koordinatadan to'g'ridan-to'g'ri yuklash, browser prompt yo'q
        load(cached.lat,cached.lon,cached.city);
    } else if(navigator.geolocation){
        navigator.geolocation.getCurrentPosition(
            p=>{
                const lat=p.coords.latitude, lon=p.coords.longitude;
                fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lon}&format=json`)
                    .then(r=>r.json())
                    .then(g=>{
                        const city=g.address.city||g.address.town||g.address.state||'현재 위치';
                        saveCache(lat,lon,city);
                        load(lat,lon,city);
                    })
                    .catch(()=>{saveCache(lat,lon,'현재 위치');load(lat,lon,'현재 위치');});
            },
            ()=>load(37.5665,126.9780,'서울')
        );
    } else load(37.5665,126.9780,'서울');

    // Konsoldan cache tozalash: localStorage.removeItem('hms_geo_cache')
})();
</script>
@endpush
