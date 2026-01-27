<?php require 'config.php';
$ADMIN_USER = "admin"; $ADMIN_PASS = "Pearls@2025"; 
if (isset($_GET['logout'])) { session_destroy(); echo "<script>location.href='admin.php';</script>"; exit; }
$is_logged_in = (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true);

// LOGIN HANDLER
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    if ($_POST['user'] === $ADMIN_USER && $_POST['pass'] === $ADMIN_PASS) { 
        $_SESSION['logged_in'] = true; 
        header("Location: admin.php"); 
        exit; 
    }
}

// UPDATE HANDLERS (With Redirect to prevent Resubmission)
if ($is_logged_in && $_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 1. Update Section Text/Images
    if (isset($_POST['action']) && $_POST['action'] === 'update_section') {
        foreach ($_POST as $k => $v) {
            if (in_array($k, ['action', 'section_name'])) continue;
            $sk = $conn->real_escape_string($k); $sv = $conn->real_escape_string($v);
            $conn->query("INSERT INTO site_settings (setting_key, setting_value) VALUES ('$sk', '$sv') ON DUPLICATE KEY UPDATE setting_value='$sv'");
        }
        
        $target_dir = __DIR__ . '/assets/images/';
        if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }
        
        foreach ($_FILES as $k => $f) {
            if ($f['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                    $n = $k . '_' . time() . '.' . $ext;
                    if (move_uploaded_file($f['tmp_name'], $target_dir . $n)) {
                        $db_path = 'assets/images/' . $n;
                        $conn->query("INSERT INTO site_settings (setting_key, setting_value) VALUES ('$k', '$db_path') ON DUPLICATE KEY UPDATE setting_value='$db_path'");
                    }
                }
            }
        }
        // Set Flash Message and Redirect
        $_SESSION['msg'] = "✅ " . htmlspecialchars($_POST['section_name']) . " updated successfully!";
        header("Location: admin.php");
        exit;
    }

    // 2. Update Appointment Status
    if (isset($_POST['action']) && $_POST['action'] === 'update_status') {
        $id = intval($_POST['app_id']);
        $status = $conn->real_escape_string($_POST['status']);
        $conn->query("UPDATE appointments SET status='$status' WHERE id=$id");
        $_SESSION['msg'] = "✅ Status updated to $status";
        header("Location: admin.php");
        exit;
    }
}

// FETCH DATA
$s = $site; $apps = [];
$total_apps = 0; $offer_apps = 0;

if ($is_logged_in) { 
    $res = $conn->query("SELECT * FROM appointments ORDER BY created_at DESC"); 
    if($res) {
        while($r = $res->fetch_assoc()) {
            $apps[] = $r;
            $total_apps++;
            if(isset($r['booking_source']) && stripos($r['booking_source'], 'Offer') !== false) {
                $offer_apps++;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Pearls Shine Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <style>
        body { font-family: 'Inter', sans-serif; }
        .nav-item.active { background-color: #1e293b; color: white; border-right: 4px solid #3b82f6; }
        .nav-item { color: #94a3b8; transition: all 0.2s; }
        .nav-item:hover { color: white; background: #1e293b; }
        .input-text { width: 100%; border: 1px solid #e2e8f0; padding: 10px; border-radius: 8px; font-size: 14px; transition: all 0.2s; }
        .input-text:focus { border-color: #3b82f6; outline: none; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
        .label { display: block; font-size: 12px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; margin-top: 12px; }
        .card { background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #f1f5f9; }
        
        /* Status Colors */
        .status-Pending { background-color: #f1f5f9; color: #64748b; border: 1px solid #cbd5e1; }
        .status-Contacted { background-color: #eff6ff; color: #3b82f6; border: 1px solid #bfdbfe; }
        .status-Interested { background-color: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
        .status-Price { background-color: #fff7ed; color: #ea580c; border: 1px solid #fed7aa; }
        .status-Confirmed { background-color: #166534; color: white; border: 1px solid #14532d; }
        .status-NotInterested { background-color: #fef2f2; color: #ef4444; border: 1px solid #fecaca; }
        .status-Mistake { background-color: #f3f4f6; color: #9ca3af; border: 1px solid #e5e7eb; text-decoration: line-through; }
        .status-NoShow { background-color: #4b5563; color: white; border: 1px solid #374151; }
    </style>
    <script>
        function openTab(id,btn){document.querySelectorAll('.tab-content').forEach(e=>e.classList.add('hidden'));document.getElementById(id).classList.remove('hidden');document.querySelectorAll('.nav-item').forEach(e=>e.classList.remove('active'));btn.classList.add('active');}
        function updateStatus(selectEl) {
            selectEl.className = 'text-xs font-bold rounded-full px-3 py-1.5 cursor-pointer status-' + selectEl.value.replace(/[^a-zA-Z]/g, "");
            selectEl.form.submit();
        }
    </script>
</head>
<body class="bg-slate-50 text-slate-800">

<?php if (!$is_logged_in): ?>
    <div class="flex items-center justify-center h-screen bg-slate-100">
        <form method="POST" class="bg-white p-10 rounded-2xl shadow-xl w-96 space-y-6">
            <div class="text-center">
                <span class="material-symbols-outlined text-5xl text-blue-600 mb-2">dentistry</span>
                <h2 class="text-2xl font-bold text-slate-800">Admin Login</h2>
                <p class="text-slate-400 text-sm">Pearls Shine Dental Care</p>
            </div>
            <input type="hidden" name="action" value="login">
            <div><label class="label">Username</label><input name="user" class="input-text" placeholder="Enter username"></div>
            <div><label class="label">Password</label><input type="password" name="pass" class="input-text" placeholder="Enter password"></div>
            <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-bold transition">Sign In</button>
        </form>
    </div>
<?php else: ?>
    <div class="flex h-screen overflow-hidden">
        
        <aside class="w-64 bg-slate-900 flex flex-col shadow-2xl z-10">
            <div class="p-6 border-b border-slate-800 flex items-center gap-3">
                <span class="material-symbols-outlined text-blue-500">admin_panel_settings</span>
                <span class="font-bold text-lg text-white">Pearls Admin</span>
            </div>
            <nav class="flex-1 py-6 space-y-1">
                <div onclick="openTab('dash',this)" class="nav-item active p-3 px-6 cursor-pointer flex items-center gap-3"><span class="material-symbols-outlined">dashboard</span> Dashboard</div>
                <div onclick="openTab('hero',this)" class="nav-item p-3 px-6 cursor-pointer flex items-center gap-3"><span class="material-symbols-outlined">web</span> Header & Hero</div>
                <div onclick="openTab('about',this)" class="nav-item p-3 px-6 cursor-pointer flex items-center gap-3"><span class="material-symbols-outlined">groups</span> Doctors</div>
                <div onclick="openTab('srv',this)" class="nav-item p-3 px-6 cursor-pointer flex items-center gap-3"><span class="material-symbols-outlined">medical_services</span> Services</div>
                <div onclick="openTab('ba',this)" class="nav-item p-3 px-6 cursor-pointer flex items-center gap-3"><span class="material-symbols-outlined">compare</span> Before/After</div>
                <div onclick="openTab('rev',this)" class="nav-item p-3 px-6 cursor-pointer flex items-center gap-3"><span class="material-symbols-outlined">star</span> Reviews</div>
                <div onclick="openTab('foot',this)" class="nav-item p-3 px-6 cursor-pointer flex items-center gap-3"><span class="material-symbols-outlined">contact_page</span> Footer</div>
            </nav>
            <div class="p-4 border-t border-slate-800">
                <a href="?logout" class="flex items-center gap-2 text-red-400 hover:text-red-300 text-sm font-bold p-2"><span class="material-symbols-outlined text-sm">logout</span> Logout</a>
            </div>
        </aside>

        <main class="flex-1 bg-slate-50 p-8 overflow-y-auto">
            
            <?php 
            if(isset($_SESSION['msg'])) { 
                echo "<div class='bg-green-100 text-green-800 p-4 rounded-lg mb-6 flex items-center gap-2 shadow-sm border border-green-200 animate-fade-in-down'><span class='material-symbols-outlined'>check_circle</span> {$_SESSION['msg']}</div>"; 
                unset($_SESSION['msg']); 
            } 
            ?>
            
            <div id="dash" class="tab-content block">
                <div class="flex justify-between items-end mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-slate-800">Dashboard</h1>
                        <p class="text-slate-500">Lead Management & Appointments</p>
                    </div>
                    <div class="flex gap-4">
                        <div class="bg-white p-3 px-5 rounded-lg border shadow-sm text-center">
                            <div class="text-2xl font-bold text-slate-800"><?php echo $total_apps; ?></div>
                            <div class="text-xs text-slate-500 font-bold uppercase">Total Leads</div>
                        </div>
                        <div class="bg-blue-50 p-3 px-5 rounded-lg border border-blue-100 shadow-sm text-center">
                            <div class="text-2xl font-bold text-blue-600"><?php echo $offer_apps; ?></div>
                            <div class="text-xs text-blue-600 font-bold uppercase">Offer Claims</div>
                        </div>
                    </div>
                </div>

                <div class="card overflow-hidden">
                    <div class="p-4 border-b bg-slate-50 font-bold text-slate-600 flex justify-between">
                        <span>Leads Table</span>
                        <span class="text-xs font-normal text-slate-400">Manage your patient status here</span>
                    </div>
                    <div class="overflow-x-auto min-h-[400px]">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-50 text-slate-500 uppercase font-bold text-xs">
                                <tr>
                                    <th class="p-4 border-b w-32">Status</th>
                                    <th class="p-4 border-b">Source</th>
                                    <th class="p-4 border-b">Patient</th>
                                    <th class="p-4 border-b">Contact</th>
                                    <th class="p-4 border-b">Request</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php foreach($apps as $r): 
                                    $source = isset($r['booking_source']) ? $r['booking_source'] : 'General';
                                    $isOffer = (stripos($source, 'Offer') !== false);
                                    $srcBadge = $isOffer ? "bg-yellow-100 text-yellow-800 border-yellow-200" : "bg-blue-50 text-blue-800 border-blue-100";
                                    $srcIcon = $isOffer ? "verified" : "language";
                                    
                                    $status = isset($r['status']) ? $r['status'] : 'Pending';
                                    $statusClass = "status-" . preg_replace('/[^a-zA-Z]/', '', $status);
                                    if($status == 'Just want to know the price') $statusClass = 'status-Price';
                                    if($status == 'Booked by mistake') $statusClass = 'status-Mistake';
                                    if($status == 'Did not come') $statusClass = 'status-NoShow';
                                    if($status == 'Not Interested') $statusClass = 'status-NotInterested';
                                    if($status == 'Booking Confirmed') $statusClass = 'status-Confirmed';
                                ?>
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="p-4 align-top">
                                        <form method="POST">
                                            <input type="hidden" name="action" value="update_status">
                                            <input type="hidden" name="app_id" value="<?php echo $r['id']; ?>">
                                            <select name="status" onchange="updateStatus(this)" class="text-xs font-bold rounded-full px-3 py-1.5 cursor-pointer appearance-none focus:outline-none w-32 text-center shadow-sm <?php echo $statusClass; ?>">
                                                <option value="Pending" <?php echo $status=='Pending'?'selected':'';?>>Pending</option>
                                                <option value="Contacted" <?php echo $status=='Contacted'?'selected':'';?>>Contacted</option>
                                                <option value="Interested" <?php echo $status=='Interested'?'selected':'';?>>Interested</option>
                                                <option value="Just want to know the price" <?php echo $status=='Just want to know the price'?'selected':'';?>>Price Inquiry</option>
                                                <option value="Booking Confirmed" <?php echo $status=='Booking Confirmed'?'selected':'';?>>Confirmed</option>
                                                <option value="Did not come" <?php echo $status=='Did not come'?'selected':'';?>>No Show</option>
                                                <option value="Not Interested" <?php echo $status=='Not Interested'?'selected':'';?>>Not Interested</option>
                                                <option value="Booked by mistake" <?php echo $status=='Booked by mistake'?'selected':'';?>>Mistake</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td class="p-4 align-top">
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-md text-[10px] font-bold border <?php echo $srcBadge; ?>">
                                            <span class="material-symbols-outlined text-[12px]"><?php echo $srcIcon; ?></span> <?php echo $source; ?>
                                        </span>
                                    </td>
                                    <td class="p-4 align-top">
                                        <div class="font-bold text-slate-800"><?php echo $r['patient_name']; ?></div>
                                        <div class="text-xs text-slate-400 mt-1 flex items-center gap-1"><span class="material-symbols-outlined text-[12px]">calendar_month</span> <?php echo $r['preferred_date']; ?></div>
                                    </td>
                                    <td class="p-4 align-top">
                                        <div class="font-mono text-xs font-bold text-slate-600"><?php echo $r['phone']; ?></div>
                                        <div class="text-xs text-slate-400 mt-0.5 truncate max-w-[150px]" title="<?php echo isset($r['email'])?$r['email']:''; ?>">
                                            <?php echo (isset($r['email']) && $r['email']) ? $r['email'] : '-'; ?>
                                        </div>
                                    </td>
                                    <td class="p-4 align-top">
                                        <div class="text-sm font-bold text-slate-700"><?php echo $r['service_type']; ?></div>
                                        <?php if(!empty($r['reason'])): ?>
                                            <div class="text-xs text-slate-500 italic mt-1 bg-slate-100 p-2 rounded border border-slate-200">
                                                "<?php echo $r['reason']; ?>"
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div id="hero" class="tab-content hidden">
                <form method="POST" enctype="multipart/form-data" class="card p-6 space-y-4">
                    <input type="hidden" name="action" value="update_section"><input type="hidden" name="section_name" value="Header & Hero">
                    <h2 class="font-bold text-xl mb-4 border-b pb-2">Header & Hero Settings</h2>
                    
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="label">Logo</label>
                            <?php if(val('logo')): ?>
                                <div class="bg-slate-200 p-2 rounded-lg mb-2 inline-block"><img src="<?php echo val('logo'); ?>" class="h-8 object-contain"></div>
                            <?php endif; ?>
                            <input type="file" name="logo" class="text-xs w-full">
                        </div>
                        <div><label class="label">Top Bar Phone</label><input name="phone" value="<?php echo val('phone');?>" class="input-text"></div>
                    </div>

                    <div class="grid grid-cols-3 gap-4 border-t border-b py-4 bg-slate-50 p-4 rounded-lg">
                        <div><label class="label">Location 1</label><input name="location_short" value="<?php echo val('location_short');?>" class="input-text"></div>
                        <div><label class="label">Location 2</label><input name="location_2" value="<?php echo val('location_2');?>" class="input-text"></div>
                        <div><label class="label">Location 3</label><input name="location_3" value="<?php echo val('location_3');?>" class="input-text"></div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div><label class="label">Facebook Link</label><input name="link_fb" value="<?php echo val('link_fb');?>" class="input-text"></div>
                        <div><label class="label">Instagram Link</label><input name="link_insta" value="<?php echo val('link_insta');?>" class="input-text"></div>
                        <div><label class="label">YouTube Link</label><input name="link_yt" value="<?php echo val('link_yt');?>" class="input-text"></div>
                    </div>

                    <div class="pt-4 mt-2">
                        <label class="label font-bold text-blue-600 mb-2 border-b pb-1">Main Hero Content</label>
                        <div class="grid grid-cols-2 gap-4 mb-2">
                             <div><label class="label">Top Badge</label><input name="hero_tag" value="<?php echo val('hero_tag');?>" class="input-text"></div>
                             <div><label class="label">Blinking Badge</label><input name="hero_badge" value="<?php echo val('hero_badge');?>" class="input-text"></div>
                        </div>
                        <label class="label">Hero Heading</label><input name="hero_h1" value="<?php echo val('hero_h1');?>" class="input-text mb-2">
                        <label class="label">Hero Sub-Heading</label><input name="hero_sub" value="<?php echo val('hero_sub');?>" class="input-text mb-2">
                        <label class="label">Description</label><textarea name="hero_desc" class="input-text h-20 mb-2"><?php echo val('hero_desc');?></textarea>
                        
                        <label class="label mt-4">Hero Image</label>
                        <?php if(val('hero_main')): ?>
                            <img src="<?php echo val('hero_main'); ?>" class="w-40 h-24 object-cover rounded-lg border border-slate-200 mb-2 shadow-sm">
                        <?php endif; ?>
                        <input type="file" name="hero_main" class="text-xs w-full">
                    </div>

                    <button class="bg-slate-900 text-white px-6 py-3 rounded-lg mt-4 w-full hover:bg-slate-800 font-bold shadow-lg">Save Changes</button>
                </form>
            </div>

            <div id="about" class="tab-content hidden">
                <form method="POST" enctype="multipart/form-data" class="card p-6 space-y-4">
                    <input type="hidden" name="action" value="update_section"><input type="hidden" name="section_name" value="Doctors">
                    <h2 class="font-bold text-xl mb-4 border-b pb-2">Doctors Section</h2>
                    
                    <div class="grid grid-cols-2 gap-6">
                        <div class="border p-4 rounded-xl bg-slate-50">
                            <h3 class="font-bold text-slate-700 mb-3">Doctor 1</h3>
                            <label class="label">Image</label>
                            <?php if(val('doctor_1')): ?>
                                <img src="<?php echo val('doctor_1'); ?>" class="w-16 h-16 object-cover rounded-full border border-slate-300 mb-2 shadow-sm">
                            <?php endif; ?>
                            <input type="file" name="doctor_1" class="text-xs mb-2 w-full">
                            <label class="label">Name</label><input name="doc1_name" value="<?php echo val('doc1_name');?>" class="input-text mb-2">
                            <label class="label">Title</label><input name="doc1_title" value="<?php echo val('doc1_title');?>" class="input-text">
                        </div>
                        <div class="border p-4 rounded-xl bg-slate-50">
                            <h3 class="font-bold text-slate-700 mb-3">Doctor 2</h3>
                            <label class="label">Image</label>
                            <?php if(val('doctor_2')): ?>
                                <img src="<?php echo val('doctor_2'); ?>" class="w-16 h-16 object-cover rounded-full border border-slate-300 mb-2 shadow-sm">
                            <?php endif; ?>
                            <input type="file" name="doctor_2" class="text-xs mb-2 w-full">
                            <label class="label">Name</label><input name="doc2_name" value="<?php echo val('doc2_name');?>" class="input-text mb-2">
                            <label class="label">Title</label><input name="doc2_title" value="<?php echo val('doc2_title');?>" class="input-text">
                        </div>
                    </div>
                    <label class="label">Middle Bio Text</label><textarea name="about_bio" class="input-text h-32"><?php echo val('about_bio');?></textarea>
                    <button class="bg-slate-900 text-white px-6 py-3 rounded-lg mt-4 w-full hover:bg-slate-800 font-bold">Save Changes</button>
                </form>
            </div>

            <div id="srv" class="tab-content hidden">
                <form method="POST" enctype="multipart/form-data" class="card p-6 rounded shadow">
                    <input type="hidden" name="action" value="update_section"><input type="hidden" name="section_name" value="Services">
                    <h2 class="font-bold text-xl mb-6 border-b pb-2">Service Details</h2>

                    <?php 
                    global $services_list; 
                    foreach($services_list as $k => $info): 
                        $label = $info[0];
                    ?>
                    <div class="mb-8 border border-slate-200 rounded-xl p-6 bg-slate-50">
                        <label class="label font-bold text-blue-600 text-lg mb-4 block flex items-center gap-2">
                            <span class="material-symbols-outlined"><?php echo $info[1]; ?></span> <?php echo $label; ?>
                        </label>
                        
                        <div class="flex flex-col md:flex-row gap-6">
                            <div class="w-full md:w-1/4">
                                <label class="label">Service Image</label>
                                <?php if(val('img_'.$k)): ?>
                                    <img src="<?php echo val('img_'.$k); ?>" class="w-full h-32 object-cover rounded-lg border border-slate-200 mb-2">
                                <?php endif; ?>
                                <input type="file" name="img_<?php echo $k;?>" class="text-xs w-full">
                            </div>

                            <div class="w-full md:w-3/4">
                                <label class="label">Description</label>
                                <textarea name="desc_<?php echo $k;?>" class="input-text w-full h-20 mb-3 bg-white"><?php echo val('desc_'.$k);?></textarea>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div><label class="label text-green-600">Badge 1</label><input name="badge1_<?php echo $k;?>" value="<?php echo val('badge1_'.$k);?>" class="input-text bg-white"></div>
                                    <div><label class="label text-green-600">Badge 2</label><input name="badge2_<?php echo $k;?>" value="<?php echo val('badge2_'.$k);?>" class="input-text bg-white"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    
                    <button class="bg-slate-900 text-white px-6 py-3 rounded-lg w-full font-bold shadow-lg hover:bg-slate-800">Save All Services</button>
                </form>
            </div>

            <div id="ba" class="tab-content hidden">
                <form method="POST" enctype="multipart/form-data" class="card p-6 rounded shadow">
                    <input type="hidden" name="action" value="update_section"><input type="hidden" name="section_name" value="Gallery">
                    <h2 class="font-bold text-xl mb-4 border-b pb-2">Before & After Gallery</h2>
                    <p class="text-xs text-slate-500 mb-4">Required Image Size: 800px x 300px (8:3 Aspect Ratio)</p>
                    
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                    <?php 
                    // UPDATED LIST: Replaced 'cleaning' with 'smile' => 'Smile Designing'
                    $cats=['implant'=>'Implants','veneer'=>'Veneers','smile'=>'Smile Designing','fmr'=>'Full Mouth','restoration'=>'Restoration','gbt'=>'GBT']; 
                    foreach($cats as $c => $label): ?>
                        <div class="border p-4 rounded-xl bg-slate-50">
                            <label class="label font-bold text-blue-600 text-center mb-3 text-lg"><?php echo $label; ?></label>
                            
                            <label class="label">Before Image</label>
                            <div class="bg-slate-200 rounded-lg overflow-hidden border border-slate-300 mb-2 w-full aspect-[8/3]">
                                <?php if(val('ba_'.$c.'_b')): ?>
                                    <img src="<?php echo val('ba_'.$c.'_b'); ?>" class="w-full h-full object-contain">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center text-slate-400 text-xs">No Image</div>
                                <?php endif; ?>
                            </div>
                            <input type="file" name="ba_<?php echo $c;?>_b" class="text-xs mb-4 w-full">
                            
                            <label class="label">After Image</label>
                            <div class="bg-slate-200 rounded-lg overflow-hidden border border-slate-300 mb-2 w-full aspect-[8/3]">
                                <?php if(val('ba_'.$c.'_a')): ?>
                                    <img src="<?php echo val('ba_'.$c.'_a'); ?>" class="w-full h-full object-contain">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center text-slate-400 text-xs">No Image</div>
                                <?php endif; ?>
                            </div>
                            <input type="file" name="ba_<?php echo $c;?>_a" class="text-xs w-full">
                        </div>
                    <?php endforeach; ?>
                    </div>
                    <button class="bg-slate-900 text-white px-6 py-3 rounded-lg mt-6 w-full font-bold">Save Gallery</button>
                </form>
            </div>
            <div id="rev" class="tab-content hidden">
                <form method="POST" class="card p-6 space-y-4">
                    <input type="hidden" name="action" value="update_section"><input type="hidden" name="section_name" value="Reviews">
                    <h2 class="font-bold text-xl mb-4 border-b pb-2">Reviews Management</h2>
                    
                    <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 mb-6">
                        <label class="label text-blue-800 font-bold mb-2">Google Review Links</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div><label class="label">New Alipore Link</label><input name="google_rev_alipore" value="<?php echo val('google_rev_alipore');?>" class="input-text bg-white"></div>
                            <div><label class="label">Budge Budge Link</label><input name="google_rev_budge" value="<?php echo val('google_rev_budge');?>" class="input-text bg-white"></div>
                        </div>
                    </div>

                    <h3 class="font-bold text-slate-700">Patient Testimonials</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php for($i=1;$i<=4;$i++): ?>
                        <div class="border p-4 rounded-xl bg-slate-50 relative">
                            <span class="absolute top-2 right-2 text-xs font-bold text-slate-300">#<?php echo $i; ?></span>
                            <label class="label">Name & Loc</label><input name="rev<?php echo $i;?>_name" value="<?php echo val('rev'.$i.'_name');?>" class="input-text font-bold mb-2 bg-white">
                            <label class="label">Review</label><textarea name="rev<?php echo $i;?>_text" class="input-text h-20 bg-white"><?php echo val('rev'.$i.'_text');?></textarea>
                        </div>
                        <?php endfor; ?>
                    </div>
                    <button class="bg-slate-900 text-white px-6 py-3 rounded-lg w-full hover:bg-slate-800 font-bold">Save Reviews</button>
                </form>
            </div>

            <div id="foot" class="tab-content hidden">
                <form method="POST" class="card p-6 space-y-4">
                    <input type="hidden" name="action" value="update_section"><input type="hidden" name="section_name" value="Footer">
                    <h2 class="font-bold text-xl mb-4 border-b pb-2">Footer & Contact</h2>
                    
                    <label class="label">Full Address</label><textarea name="address_full" class="input-text h-20"><?php echo val('address_full');?></textarea>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="label">WhatsApp Number</label><input name="whatsapp" value="<?php echo val('whatsapp');?>" class="input-text"></div>
                        <div><label class="label">Short Location</label><input name="location_short" value="<?php echo val('location_short');?>" class="input-text"></div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="label">Morning Hours</label><input name="hours_morn" value="<?php echo val('hours_morn');?>" class="input-text"></div>
                        <div><label class="label">Evening Hours</label><input name="hours_eve" value="<?php echo val('hours_eve');?>" class="input-text"></div>
                    </div>
                    <label class="label">Google Maps Embed URL</label><input name="map_url" value="<?php echo val('map_url');?>" class="input-text">
                    <button class="bg-slate-900 text-white px-6 py-3 rounded-lg w-full hover:bg-slate-800 font-bold">Save Footer</button>
                </form>
            </div>

        </main>
    </div>
<?php endif; ?>
</body>
</html>