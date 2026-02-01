<div class="diente-wrapper" id="diente-{{ $numero }}">
    <small class="fw-bold">{{ $numero }}</small>
    <div class="diente-svg">
        <div class="zona-click z-top" onclick="clickZona(this, {{ $numero }}, 'top')"></div>
        <div class="zona-click z-bottom" onclick="clickZona(this, {{ $numero }}, 'bottom')"></div>
        <div class="zona-click z-left" onclick="clickZona(this, {{ $numero }}, 'left')"></div>
        <div class="zona-click z-right" onclick="clickZona(this, {{ $numero }}, 'right')"></div>
        <div class="zona-click z-center" onclick="clickZona(this, {{ $numero }}, 'center')"></div>
    </div>
</div>

