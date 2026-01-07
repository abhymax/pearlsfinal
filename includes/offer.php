<section class="py-24 bg-gradient-to-br from-[#4f46e5] to-[#2563eb] relative overflow-hidden flex items-center justify-center">
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden opacity-10 pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-white rounded-full mix-blend-overlay filter blur-3xl"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-white rounded-full mix-blend-overlay filter blur-3xl"></div>
    </div>

    <div class="max-w-5xl mx-auto px-4 relative z-10 w-full">
        <div class="bg-white rounded-[2.5rem] p-8 md:p-14 shadow-2xl text-center mx-auto">
            
            <div class="inline-block bg-[#FFD600] text-slate-900 text-[11px] font-extrabold px-6 py-2 rounded-full uppercase tracking-wider mb-8 shadow-sm">
                <?php echo val('offer_badge') ?: 'EXCLUSIVE ONLINE OFFER'; ?>
            </div>

            <h2 class="text-4xl md:text-5xl font-extrabold text-slate-900 mb-6 font-['Exo_2']">
                <?php echo val('offer_title') ?: 'First Visit Privilege'; ?>
            </h2>

            <p class="text-lg text-slate-600 mb-10 max-w-3xl mx-auto leading-relaxed font-medium">
                <?php echo val('offer_desc') ?: 'New to Pearls Shine? Experience our state-of-the-art care with a comprehensive consultation package including digital X-rays and a personalized treatment plan.'; ?>
            </p>

            <div class="flex flex-col md:flex-row justify-center items-center gap-4 md:gap-8 mb-12">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-green-500 text-2xl">check_circle</span>
                    <span class="font-bold text-slate-800 text-lg"><?php echo val('offer_f1') ?: 'Comprehensive Exam'; ?></span>
                </div>
                
                <div class="hidden md:block w-px h-6 bg-slate-300"></div>

                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-green-500 text-2xl">check_circle</span>
                    <span class="font-bold text-slate-800 text-lg"><?php echo val('offer_f2') ?: 'Digital X-Rays'; ?></span>
                </div>

                <div class="hidden md:block w-px h-6 bg-slate-300"></div>

                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-green-500 text-2xl">check_circle</span>
                    <span class="font-bold text-slate-800 text-lg"><?php echo val('offer_f3') ?: 'Expert Consultation'; ?></span>
                </div>
            </div>

            <button onclick="openModal('offer')" class="bg-[#5667FF] hover:bg-[#4351ee] text-white text-xl font-bold px-10 py-5 rounded-xl shadow-lg shadow-blue-500/30 transition transform hover:-translate-y-1 w-full md:w-auto">
                Claim Your Offer Now
            </button>

            <p class="text-slate-400 text-xs mt-8 italic">
                *Terms and conditions apply. Valid for new patients only.
            </p>

        </div>
    </div>
</section>