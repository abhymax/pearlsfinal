<section class="py-20 bg-[#0F172A]" id="gallery">
    <div class="max-w-7xl mx-auto px-4 grid lg:grid-cols-2 gap-16 items-center">
        
        <div class="text-white">
            <span class="text-blue-400 font-bold uppercase tracking-wider text-sm">Real Results</span>
            <h2 class="text-4xl font-bold mt-2 mb-6">Smile Transformations</h2>
            <p class="text-slate-400 mb-8 text-lg">See the difference our expert care makes. Drag the slider to compare.</p>
            
            <div class="flex flex-wrap gap-3 mb-8">
                <?php 
                // CHANGED: Replaced 'cleaning' with 'smile' => 'Smile Designing'
                $cats=['implant'=>'Implants','veneer'=>'Veneers','smile'=>'Smile Designing','fmr'=>'Full Mouth','restoration'=>'Restoration','gbt'=>'GBT']; 
                $first=true; 
                foreach($cats as $k=>$label){ 
                    $cls = $first ? "bg-blue-600 text-white shadow-lg ring-2 ring-blue-500 ring-offset-2 ring-offset-[#0F172A]" : "bg-slate-800 text-slate-300 hover:bg-slate-700";
                    
                    // Note: Since we changed key to 'smile', the DB will look for 'ba_smile_b'
                    $b_img = val('ba_'.$k.'_b') ?: 'assets/images/ba_before_placeholder.jpg';
                    $a_img = val('ba_'.$k.'_a') ?: 'assets/images/ba_after_placeholder.jpg';
                    
                    echo "<button class=\"ba-btn transition-all duration-300 px-4 py-2 rounded-lg font-bold text-sm $cls\" onclick=\"switchBA('$k',this)\" data-b=\"$b_img\" data-a=\"$a_img\">$label</button>"; 
                    $first=false; 
                } 
                ?>
            </div>
        </div>

        <div class="w-full aspect-[8/3] rounded-2xl overflow-hidden relative select-none shadow-2xl ring-1 ring-slate-700 bg-black group" id="ba-container">
            
            <img src="<?php echo val('ba_implant_b'); ?>" class="absolute inset-0 w-full h-full object-cover pointer-events-none" id="img-before">
            
            <span class="absolute top-4 left-4 bg-black/50 text-white text-[10px] font-bold px-2 py-1 rounded backdrop-blur-sm uppercase tracking-widest">Before</span>

            <div class="absolute top-0 left-0 h-full w-[50%] overflow-hidden border-r-2 border-white/50" id="ba-overlay">
                <img src="<?php echo val('ba_implant_a'); ?>" class="absolute top-0 left-0 h-full object-cover max-w-none pointer-events-none" id="img-after">
                <span class="absolute top-4 right-4 bg-blue-600/80 text-white text-[10px] font-bold px-2 py-1 rounded backdrop-blur-sm uppercase tracking-widest z-10">After</span>
            </div>

            <div class="absolute top-0 bottom-0 left-[50%] w-10 -ml-5 flex items-center justify-center cursor-ew-resize z-20 group-hover:scale-110 transition-transform" id="ba-handle">
                <div class="w-10 h-10 bg-white rounded-full shadow-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-blue-600 text-lg">code</span>
                </div>
            </div>
        </div>

    </div>
</section>

<script>
    function switchBA(key, btn) {
        document.querySelectorAll('.ba-btn').forEach(b => {
            b.classList.remove('bg-blue-600', 'text-white', 'shadow-lg', 'ring-2', 'ring-blue-500', 'ring-offset-2', 'ring-offset-[#0F172A]');
            b.classList.add('bg-slate-800', 'text-slate-300');
        });
        btn.classList.remove('bg-slate-800', 'text-slate-300');
        btn.classList.add('bg-blue-600', 'text-white', 'shadow-lg', 'ring-2', 'ring-blue-500', 'ring-offset-2', 'ring-offset-[#0F172A]');

        document.getElementById('img-before').src = btn.dataset.b;
        document.getElementById('img-after').src = btn.dataset.a;
    }

    const container = document.getElementById('ba-container');
    const overlay = document.getElementById('ba-overlay');
    const handle = document.getElementById('ba-handle');
    const imgAfter = document.getElementById('img-after');
    let isDragging = false;

    function fixImageWidth() {
        if(container && imgAfter) {
            imgAfter.style.width = container.offsetWidth + "px";
        }
    }

    function move(e) {
        if (!isDragging) return;
        const rect = container.getBoundingClientRect();
        let x = (e.clientX || e.touches[0].clientX) - rect.left;
        x = Math.max(0, Math.min(x, rect.width));
        const percent = (x / rect.width) * 100;
        overlay.style.width = percent + "%";
        handle.style.left = percent + "%";
    }

    container.addEventListener('mousedown', () => isDragging = true);
    window.addEventListener('mouseup', () => isDragging = false);
    container.addEventListener('mousemove', move);
    container.addEventListener('touchstart', () => isDragging = true);
    window.addEventListener('touchend', () => isDragging = false);
    container.addEventListener('touchmove', move);

    window.addEventListener('load', fixImageWidth);
    window.addEventListener('resize', fixImageWidth);
    fixImageWidth();
</script>