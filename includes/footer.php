<footer class="bg-slate-950 text-slate-400 py-16" id="contact">
    <div class="max-w-7xl mx-auto px-4">
        
        <div class="flex flex-col md:flex-row justify-between items-center mb-12 border-b border-slate-900 pb-8">
            <div class="flex items-center gap-2 mb-4 md:mb-0 text-white">
                <img src="<?php echo val('logo'); ?>" class="h-10 brightness-0 invert">
                <span class="text-xl font-bold tracking-wide">Pearls Shine</span>
            </div>
            <div class="flex gap-6">
                <a href="tel:<?php echo val('phone'); ?>" class="flex items-center gap-2 hover:text-blue-400 transition"><span class="material-symbols-outlined">call</span> <?php echo val('phone'); ?></a>
                <a href="https://wa.me/<?php echo val('whatsapp'); ?>" class="flex items-center gap-2 hover:text-green-400 transition"><img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" class="w-5 h-5"> WhatsApp</a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 lg:gap-20">
            <div>
                <h3 class="text-white font-bold text-lg mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-purple-500">location_on</span> 
                    New Alipore Clinic
                </h3>
                <div class="space-y-4 mb-6">
                    <p class="text-sm leading-relaxed text-slate-300">87, Chetla Rd, Tollygunge, Kolkata, West Bengal 700053</p>
                    <div class="bg-slate-900/50 p-4 rounded-lg border border-slate-800 h-[106px] flex flex-col justify-center">
                        <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Opening Hours</h4>
                        <p class="text-sm text-purple-400 font-medium italic flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">event_available</span>
                            Consultations strictly on prior appointments only.
                        </p>
                    </div>
                </div>
                <div class="h-56 rounded-xl overflow-hidden border border-slate-800 bg-slate-900 shadow-inner group hover:border-purple-500/30 transition">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3685.568700208226!2d88.33967637599666!3d22.52033103487373!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3a02775a00305001%3A0x6296767676767676!2s87%2C%20Chetla%20Rd%2C%20New%20Alipore!5e0!3m2!1sen!2sin!4v1700000000000!5m2!1sen!2sin" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" class="grayscale group-hover:grayscale-0 transition duration-700"></iframe>
                </div>
            </div>

            <div>
                <h3 class="text-white font-bold text-lg mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-blue-500">location_on</span> 
                    Budge Budge Clinic
                </h3>
                <div class="space-y-4 mb-6">
                    <p class="text-sm leading-relaxed text-slate-300"><?php echo val('address_full'); ?></p>
                    <div class="bg-slate-900/50 p-4 rounded-lg border border-slate-800">
                        <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Opening Hours</h4>
                        <div class="flex justify-between text-sm mb-1"><span>Sun - Fri</span> <span class="text-blue-400 font-medium"><?php echo val('hours_morn'); ?></span></div>
                        <div class="flex justify-between text-sm"><span></span> <span class="text-blue-400 font-medium"><?php echo val('hours_eve'); ?></span></div>
                        <div class="flex justify-between text-sm text-slate-600 mt-1 pt-1 border-t border-slate-800"><span>Saturday</span> <span>Closed</span></div>
                    </div>
                </div>
                <div class="h-56 rounded-xl overflow-hidden border border-slate-800 bg-slate-900 shadow-inner group hover:border-blue-500/30 transition">
                    <iframe src="<?php echo val('map_url'); ?>" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" class="grayscale group-hover:grayscale-0 transition duration-700"></iframe>
                </div>
            </div>
        </div>
    </div>
    <div class="text-center mt-16 pt-8 border-t border-slate-900 text-xs text-slate-600">© 2026 Pearls Shine Oral and Dental Care. All Rights Reserved.</div>
</footer>

<div id="modal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>
    
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-lg p-4">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden animate-fade-in-down">
            
            <div id="modal-header" class="bg-gradient-to-r from-blue-600 to-indigo-700 px-8 py-6 flex justify-between items-center relative overflow-hidden">
                <div class="relative z-10">
                    <h3 id="modal-title" class="text-2xl font-bold text-white">Book Appointment</h3>
                    <p class="text-blue-100 text-xs mt-1 uppercase tracking-wider font-semibold">Start your smile journey</p>
                </div>
                <button onclick="closeModal()" class="text-white/80 hover:bg-white/20 hover:text-white rounded-full p-2 transition relative z-10">
                    <span class="material-symbols-outlined">close</span>
                </button>
                
                <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            </div>

            <div class="p-8 bg-slate-50">
                <div id="offer-msg" class="hidden mb-6 bg-yellow-50 text-yellow-800 p-4 rounded-xl text-sm font-bold border border-yellow-200 shadow-sm flex items-center gap-3">
                    <span class="material-symbols-outlined text-yellow-600">verified</span>
                    <span>First Visit Special: You get a <span class="underline decoration-wavy decoration-yellow-500">FLAT 20% DISCOUNT!</span></span>
                </div>

                <form id="bookForm" class="space-y-5">
                    <input type="hidden" name="source" id="booking-source" value="General">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 uppercase ml-1">Full Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" placeholder="e.g. Rahul Sharma" required 
                                class="w-full border border-gray-200 bg-white rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition shadow-sm">
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 uppercase ml-1">Phone <span class="text-red-500">*</span></label>
                            <input type="tel" name="phone" placeholder="e.g. 9876543210" required 
                                class="w-full border border-gray-200 bg-white rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition shadow-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 uppercase ml-1">Email <span class="text-gray-400 font-normal">(Optional)</span></label>
                            <input type="email" name="email" placeholder="name@example.com" 
                                class="w-full border border-gray-200 bg-white rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition shadow-sm">
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 uppercase ml-1">Preferred Date <span class="text-red-500">*</span></label>
                            <input type="date" name="date" required 
                                class="w-full border border-gray-200 bg-white rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition shadow-sm text-slate-600">
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-500 uppercase ml-1">Service Type</label>
                        <div class="relative">
                            <select name="service" class="w-full border border-gray-200 bg-white rounded-xl p-3 appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition shadow-sm text-slate-700">
                                <option value="General Checkup">General Checkup</option>
                                <option value="Tooth Pain">Tooth Pain</option>
                                <option value="Root Canal">Root Canal</option>
                                <option value="Implants">Implants</option>
                                <option value="Braces/Aligners">Braces/Aligners</option>
                                <option value="Other">Other</option>
                            </select>
                            <span class="material-symbols-outlined absolute right-3 top-3 text-slate-400 pointer-events-none">expand_more</span>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-500 uppercase ml-1">Reason for Appointment <span class="text-gray-400 font-normal">(Optional)</span></label>
                        <textarea name="reason" placeholder="Briefly describe your issue (e.g., 'Sharp pain in lower left molar when eating sweets')..." rows="3" 
                            class="w-full border border-gray-200 bg-white rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition shadow-sm resize-none"></textarea>
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white py-4 rounded-xl font-bold shadow-lg shadow-blue-500/30 transform transition active:scale-95 flex items-center justify-center gap-2">
                        <span>Confirm Appointment</span>
                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </button>
                    
                    <p class="text-center text-[10px] text-slate-400 uppercase tracking-widest font-bold mt-2">
                        <span class="material-symbols-outlined text-[10px] align-middle">lock</span> Secure Booking
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const dict={en:{home:"Home",about:"About",services:"Departments",reviews:"Reviews",book:"Book Appointment",book_visit:"Book Your Visit",hero_h1:"Advanced Dentistry",hero_sub:"Reimagined.",meet_experts:"Meet The Experts",team_k:"Our Team",comp_care:"Comprehensive Dental Care"},hi:{home:"होम",about:"परिचय",services:"विभाग",reviews:"समीक्षाएं",book:"अपॉइंटमेंट बुक करें",book_visit:"यात्रा बुक करें",hero_h1:"उन्नत दंत चिकित्सा",hero_sub:"पुनर्कल्पित।",meet_experts:"विशेषज्ञों से मिलें",team_k:"हमारी टीम",comp_care:"व्यापक दंत चिकित्सा"},bn:{home:"হোম",about:"পরিচয়",services:"বিভাগ",reviews:"পর্যালোচনা",book:"অ্যাপয়েন্টমেন্ট বুক করুন",book_visit:"বুকিং করুন",hero_h1:"উন্নত দন্তচিকিৎসা",hero_sub:"পুনঃকল্পিত।",meet_experts:"বিশেষজ্ঞদের সাথে দেখা করুন",team_k:"আমাদের দল",comp_care:"বিস্তৃত ডেন্টাল কেয়ার"}};
    function changeLang(l){document.querySelectorAll('[data-k]').forEach(e=>{if(dict[l][e.dataset.k])e.innerText=dict[l][e.dataset.k]})}
    function switchBA(c,b){document.querySelectorAll('.ba-btn').forEach(x=>x.classList.remove('active'));b.classList.add('active');document.getElementById('img-before').src=b.dataset.b;document.getElementById('img-after').src=b.dataset.a}
    
    // REMOVED THE BROKEN SLIDER CODE FROM HERE
    // It is already handled in includes/gallery.php
    
    // UPDATED MODAL LOGIC
    function openModal(t){
        document.getElementById('modal').classList.remove('hidden');
        const sourceInput = document.getElementById('booking-source');
        
        if(t==='offer'){
            document.getElementById('offer-msg').classList.remove('hidden');
            document.getElementById('modal-title').innerText="Claim 20% Discount";
            sourceInput.value = "Offer Claim"; 
        } else {
            document.getElementById('offer-msg').classList.add('hidden');
            document.getElementById('modal-title').innerText="Book Appointment";
            sourceInput.value = "General Booking"; 
        }
    }
    function closeModal(){document.getElementById('modal').classList.add('hidden')}
    
    // THIS LISTENER WAS FAILING TO ATTACH BEFORE. NOW IT WILL WORK.
    document.getElementById('bookForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Stop page reload
        const btn = this.querySelector('button[type="submit"]');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<span class="animate-spin material-symbols-outlined">refresh</span> Processing...';
        btn.disabled = true;
        
        const formData = new FormData(this);
        fetch('api.php', { method: 'POST', body: formData })
        .then(response => response.json())
        .then(data => {
            if(data.status === 'success') { 
                alert("✅ " + data.message); 
                this.reset(); 
                closeModal(); 
            } else { 
                alert("❌ Error: " + data.message); 
            }
        })
        .catch(error => { 
            console.error("Fetch error:", error);
            alert("Connection Error. Please try again."); 
        })
        .finally(() => { 
            btn.innerHTML = originalText; 
            btn.disabled = false;
        });
    });
</script>