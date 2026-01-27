<section class="py-24 bg-slate-50" id="services">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-16">
            <span class="text-blue-600 font-bold uppercase tracking-wider text-sm" data-k="services">Departments</span>
            <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mt-2" data-k="comp_care">Comprehensive Dental Care</h2>
        </div>

        <div class="flex flex-col lg:flex-row bg-white rounded-3xl shadow-xl overflow-hidden min-h-[600px] border border-gray-100">
            
            <div class="lg:w-1/4 bg-slate-50 border-r border-gray-100 flex flex-col overflow-y-auto max-h-[600px] scrollbar-hide">
                <?php 
                // Ensure list is available
                global $services_list;
                if (!isset($services_list)) { $services_list = []; }
                
                $i = 0;
                foreach($services_list as $k => $info) {
                    $name = $info[0];
                    $icon = $info[1];
                    // First item is active by default
                    $activeClass = ($i === 0) ? "bg-white border-l-4 border-blue-600 text-blue-700 shadow-sm active-tab" : "text-slate-600 border-l-4 border-transparent hover:bg-slate-100";
                    
                    // FIXED: Changed function name to switchServiceTab to avoid conflict
                    echo "
                    <button type=\"button\" onclick=\"switchServiceTab('$k', this)\" class=\"service-tab w-full text-left px-6 py-5 flex items-center gap-4 transition-all duration-300 border-b border-gray-100 $activeClass\" id=\"btn-$k\">
                        <span class=\"material-symbols-outlined text-2xl opacity-80\">$icon</span>
                        <span class=\"font-bold text-sm uppercase tracking-wide\">$name</span>
                    </button>";
                    $i++;
                }
                ?>
            </div>

            <div class="lg:w-3/4 p-8 md:p-12 relative">
                <?php 
                $i = 0;
                foreach($services_list as $k => $info) {
                    $name = $info[0];
                    $display = ($i === 0) ? "block" : "hidden"; // Show first only
                    
                    // Fallbacks
                    $b1 = val('badge1_'.$k) ?: 'Advanced Technology';
                    $b2 = val('badge2_'.$k) ?: 'Expert Care by Dr. Pandey';
                    $img = val('img_'.$k) ?: 'https://images.unsplash.com/photo-1606811841689-23dfddce3e95?auto=format&fit=crop&w=800&q=80';
                    
                    echo "
                    <div id=\"content-$k\" class=\"service-content $display animate-fade-in-down\">
                        <div class=\"h-64 md:h-80 rounded-2xl overflow-hidden mb-8 relative shadow-lg group\">
                            <div class=\"absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent z-10\"></div>
                            <img src=\"$img\" class=\"absolute inset-0 w-full h-full object-cover transition duration-700 group-hover:scale-110\">
                            <h3 class=\"absolute bottom-6 left-6 text-3xl font-bold text-white z-20\">$name</h3>
                        </div>

                        <div class=\"max-w-3xl\">
                            <p class=\"text-slate-600 text-lg leading-relaxed mb-8\">".val('desc_'.$k)."</p>
                            
                            <div class=\"space-y-3 mb-10\">
                                <div class=\"flex items-center gap-3\">
                                    <span class=\"material-symbols-outlined text-green-500 font-bold bg-green-50 p-1 rounded-full\">check_circle</span>
                                    <span class=\"font-bold text-slate-800\">$b1</span>
                                </div>
                                <div class=\"flex items-center gap-3\">
                                    <span class=\"material-symbols-outlined text-green-500 font-bold bg-green-50 p-1 rounded-full\">check_circle</span>
                                    <span class=\"font-bold text-slate-800\">$b2</span>
                                </div>
                            </div>

                            <button onclick=\"openModal()\" class=\"bg-[#0F172A] text-white px-8 py-4 rounded-xl font-bold hover:bg-blue-600 transition flex items-center gap-2 shadow-xl\">
                                Book This Treatment <span class=\"material-symbols-outlined text-sm\">arrow_forward</span>
                            </button>
                        </div>
                    </div>";
                    $i++;
                } 
                ?>
            </div>
        </div>
    </div>
</section>

<script>
// FIXED: Renamed function to switchServiceTab
function switchServiceTab(key, btn) {
    // 1. Hide all content
    document.querySelectorAll('.service-content').forEach(el => el.classList.add('hidden'));
    
    // 2. Show selected content safely
    const target = document.getElementById('content-' + key);
    if (target) {
        target.classList.remove('hidden');
    } else {
        console.error('Service content not found for:', key);
    }
    
    // 3. Reset all buttons
    document.querySelectorAll('.service-tab').forEach(b => {
        b.classList.remove('bg-white', 'border-blue-600', 'text-blue-700', 'shadow-sm', 'active-tab');
        b.classList.add('text-slate-600', 'border-transparent');
    });
    
    // 4. Highlight active button
    btn.classList.remove('text-slate-600', 'border-transparent');
    btn.classList.add('bg-white', 'border-blue-600', 'text-blue-700', 'shadow-sm', 'active-tab');
}
</script>