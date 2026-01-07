<section class="py-24 bg-white overflow-hidden" id="reviews">
    <div class="max-w-7xl mx-auto px-4 mb-16 text-center">
        
        <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4 font-['Exo_2']" data-k="reviews">What Our Patients Say</h2>
        
        <div class="flex flex-col items-center gap-2">
            <div class="flex items-center gap-2 justify-center">
                <div class="flex text-yellow-400 text-xl">
                    <span class="material-symbols-outlined fill-current">star</span>
                    <span class="material-symbols-outlined fill-current">star</span>
                    <span class="material-symbols-outlined fill-current">star</span>
                    <span class="material-symbols-outlined fill-current">star</span>
                    <span class="material-symbols-outlined fill-current">star</span>
                </div>
                <span class="font-bold text-slate-700 text-lg">4.9/5 Average Rating</span>
            </div>
            
            <div class="flex flex-wrap justify-center gap-4 mt-2 text-sm font-bold">
                <a href="<?php echo val('google_rev_alipore'); ?>" target="_blank" class="text-blue-600 hover:text-blue-800 flex items-center gap-1 hover:underline transition">
                    See New Alipore reviews <span class="material-symbols-outlined text-sm">open_in_new</span>
                </a>
                <span class="text-gray-300 hidden sm:inline">|</span>
                <a href="<?php echo val('google_rev_budge'); ?>" target="_blank" class="text-blue-600 hover:text-blue-800 flex items-center gap-1 hover:underline transition">
                    See Budge Budge reviews <span class="material-symbols-outlined text-sm">open_in_new</span>
                </a>
            </div>
        </div>
    </div>

    <div class="w-full inline-flex flex-nowrap overflow-hidden [mask-image:_linear-gradient(to_right,transparent_0,_black_128px,_black_calc(100%-128px),transparent_100%)]">
        <ul class="flex items-center justify-center md:justify-start [&_li]:mx-4 [&_img]:max-w-none animate-marquee">
            
            <?php 
            // We loop twice to create the infinite scroll effect
            for($k=0; $k<2; $k++): 
                for($i=1; $i<=4; $i++): 
            ?>
            <li class="w-[400px] flex-shrink-0 bg-slate-50 p-8 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition duration-300 cursor-default">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-blue-200">
                        <?php echo substr(val('rev'.$i.'_name'), 0, 1); ?>
                    </div>
                    
                    <div>
                        <h4 class="font-bold text-slate-900 text-base leading-tight"><?php echo val('rev'.$i.'_name'); ?></h4>
                        <div class="flex text-yellow-400 text-xs mt-1">
                            <span class="material-symbols-outlined text-[16px] fill-current">star</span>
                            <span class="material-symbols-outlined text-[16px] fill-current">star</span>
                            <span class="material-symbols-outlined text-[16px] fill-current">star</span>
                            <span class="material-symbols-outlined text-[16px] fill-current">star</span>
                            <span class="material-symbols-outlined text-[16px] fill-current">star</span>
                        </div>
                    </div>
                </div>
                
                <p class="text-gray-600 italic leading-relaxed text-sm">
                    "<?php echo val('rev'.$i.'_text'); ?>"
                </p>
            </li>
            <?php 
                endfor; 
            endfor; 
            ?>

        </ul>
    </div>
</section>