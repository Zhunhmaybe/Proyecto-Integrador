<div class="diente-container" onclick="abrirModalDiente({{ $pieza }})">
    <span class="numero-pieza">{{ $pieza }}</span>
    <svg class="diente-svg" data-pieza="{{ $pieza }}" width="36" height="36" viewBox="0 0 50 50">
        <defs>
            <clipPath id="circleClip">
                <circle cx="25" cy="25" r="24" />
            </clipPath>
        </defs>
        
        <circle cx="25" cy="25" r="24" fill="none" stroke="black" stroke-width="1"/>

        <path class="vestibular" d="M0,0 L50,0 L25,25 Z" fill="white" stroke="black" stroke-width="1" clip-path="url(#circleClip)"/>
        
        <path class="lingual" d="M0,50 L50,50 L25,25 Z" fill="white" stroke="black" stroke-width="1" clip-path="url(#circleClip)"/>
        
        <path class="mesial" d="M0,0 L0,50 L25,25 Z" fill="white" stroke="black" stroke-width="1" clip-path="url(#circleClip)"/>
        
        <path class="distal" d="M50,0 L50,50 L25,25 Z" fill="white" stroke="black" stroke-width="1" clip-path="url(#circleClip)"/>

        <circle class="oclusal" cx="25" cy="25" r="10" fill="white" stroke="black" stroke-width="1"/>
    </svg>
</div>