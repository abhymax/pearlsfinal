<section id="home" class="relative bg-[#0F172A] min-h-[650px] flex items-center pb-24 clip-slant overflow-hidden">
    <div class="absolute inset-0 z-0"><div class="absolute inset-0 bg-gradient-to-r from-[#0F172A] via-[#0F172A]/95 to-blue-900/20"></div></div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 grid lg:grid-cols-2 gap-12 items-center pt-8">
        
        <div class="text-center lg:text-left">
            
            <?php if(val('hero_tag')): ?>
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-900/30 border border-blue-500/30 text-blue-400 text-[11px] font-bold tracking-widest uppercase mb-6 animate-fade-in-down">
                <span class="material-symbols-outlined text-[14px]">verified</span> <?php echo val('hero_tag'); ?>
            </div>
            <?php endif; ?>
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-900/30 border border-blue-500/30 text-blue-400 text-[11px] font-bold tracking-widest uppercase mb-6 animate-fade-in-down">
                <span class="material-symbols-outlined text-[14px]">verified</span> MSME Nominated 
            </div>

            <h1 class="text-5xl lg:text-7xl font-extrabold text-white leading-[1.1] mb-6">
                <span data-k="hero_h1"><?php echo val('hero_h1'); ?></span> <br>
                <span class="text-gradient" data-k="hero_sub"><?php echo val('hero_sub'); ?></span>
            </h1>
            
            <p class="text-lg text-slate-400 leading-relaxed max-w-xl mx-auto lg:mx-0 font-medium mb-8" data-k="hero_desc">
                <?php echo val('hero_desc'); ?>
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start pt-2">
                <button onclick="openModal()" class="btn-gradient px-8 py-4 rounded-xl font-bold flex items-center justify-center gap-2 shadow-2xl hover:shadow-blue-500/20">
                    <span class="material-symbols-outlined">calendar_month</span> <span data-k="book_visit">Book Your Visit</span>
                </button>
                <a href="tel:<?php echo preg_replace('/[^0-9]/', '', val('phone')); ?>" class="border border-slate-600 text-white px-8 py-4 rounded-xl font-bold hover:bg-slate-800 transition flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">call</span> <?php echo val('phone'); ?>
                </a>
            </div>

            <?php if(val('hero_serving')): ?>
            <p class="text-xs text-slate-500 mt-10 font-medium border-t border-slate-800/50 pt-6">
                <?php echo val('hero_serving'); ?>
            </p>
            <?php endif; ?>
        </div>

        <div class="hidden lg:block relative group perspective-1000">
             <div class="relative bg-gradient-to-br from-slate-700 to-slate-900 rounded-3xl p-1 shadow-2xl animate-float">
                 <div class="relative rounded-2xl overflow-hidden h-[500px] w-full bg-slate-800">
                     <img src="<?php echo val('hero_main'); ?>" class="w-full h-full object-cover opacity-90 group-hover:opacity-100 transition duration-700">
                     
                     <?php if(val('hero_badge')): ?>
                     <div class="absolute bottom-8 right-8 bg-black/80 backdrop-blur-md border border-white/10 py-3 px-5 rounded-full flex items-center gap-3 shadow-2xl transform transition hover:scale-105 cursor-default z-20">
                        <span class="relative flex h-3 w-3">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                        </span>
                        <span class="text-white font-bold text-sm tracking-wide font-mono"><?php echo val('hero_badge'); ?></span>
                     </div>
                     <?php endif; ?>
                     
                 </div>
             </div>
        </div>
    </div>
</section>