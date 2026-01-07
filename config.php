<?php
// 1. SECURE SESSION HANDLING
$sess_folder = __DIR__ . '/my_sessions';
if (!file_exists($sess_folder)) { mkdir($sess_folder, 0777, true); }
ini_set('session.save_path', $sess_folder);
ini_set('session.gc_maxlifetime', 86400);
session_set_cookie_params(86400);
session_start();

require 'db_connect.php';

// 2. DEFINE SERVICES LIST (Master List)
// Format: 'key' => ['Display Name', 'Material Icon Name']
$services_list = [
    'laser'     => ['Laser Surgery', 'light_mode'],
    'implant'   => ['Digital Navigation Implants', 'architecture'],
    'rct'       => ['Automated Root Canal', 'dentistry'],
    'kids'      => ['Routine & Pediatric', 'child_care'],
    'fmr'       => ['Full Mouth Rehab', 'medical_services'],
    'gbt'       => ['GBT (Biofilm Therapy)', 'water_drop'],
    'micro'     => ['Micro Dentistry', 'zoom_in'],
    'crowns'    => ['Crowns & Bridges', 'crown'],
    'dentures'  => ['Imported Dentures', 'calendar_view_day'],
    'smile'     => ['Smile Designing', 'sentiment_satisfied'],
    'aligners'  => ['Aligners & Braces', 'health_and_safety']
];

// 3. DEFINE DEFAULTS
$defaults = [
    // External Images
    'logo'             => 'https://img.icons8.com/color/48/dental-braces.png',
    'hero_bg'          => 'https://images.unsplash.com/photo-1629909613654-28e377c37b09?auto=format&fit=crop&w=1600&q=80',
    'hero_main'        => 'https://images.unsplash.com/photo-1588776814546-1ffcf47267a5?auto=format&fit=crop&w=800&q=80',
    
    // Doctors
    'doctor_1'         => 'assets/images/doctor.jpg',
    'doctor_2'         => 'https://images.unsplash.com/photo-1622253692010-333f2da6031d?auto=format&fit=crop&w=800&q=80',
    
    // Service Descriptions & Badges
    // 1. Laser
    'desc_laser'       => 'Precision soft tissue procedures using advanced diode lasers. Experience bloodless, painless surgery with faster healing.',
    'badge1_laser'     => 'Advanced Diode Laser', 'badge2_laser' => 'Bloodless & Painless',
    
    // 2. Implant
    'desc_implant'     => 'We use 3D Computer Guided Surgery to place implants with 100% accuracy, ensuring long-term stability.',
    'badge1_implant'   => '3D Guided Precision', 'badge2_implant' => 'Lifetime Warranty',
    
    // 3. RCT
    'desc_rct'         => 'Complete your Root Canal Treatment in a single sitting using flexible rotary files and automated apex locators.',
    'badge1_rct'       => 'Single Sitting', 'badge2_rct' => 'Microscope Enhanced',
    
    // 4. Kids
    'desc_kids'        => 'We make dentistry fun! From fluoride application to sealants, we ensure your child grows up with a healthy smile.',
    'badge1_kids'      => 'No-Tear Dentistry', 'badge2_kids' => 'Fluoride Protection',
    
    // 5. FMR
    'desc_fmr'         => 'Restore function and aesthetics to your entire mouth with a comprehensive rehabilitation plan.',
    'badge1_fmr'       => 'Full Function Restore', 'badge2_fmr' => 'Neuromuscular Balance',
    
    // 6. GBT
    'desc_gbt'         => 'Guided Biofilm Therapy is the new standard in cleaning. It is minimally invasive and highly effective.',
    'badge1_gbt'       => 'Swiss Technology', 'badge2_gbt' => 'Stain Removal',
    
    // 7. Micro
    'desc_micro'       => 'See what others miss. We use dental microscopes for high-precision diagnostics and treatment.',
    'badge1_micro'     => '20x Magnification', 'badge2_micro' => 'Root Canal Specialist',
    
    // 8. Crowns
    'desc_crowns'      => 'High-strength Zirconia and E-max crowns that look and feel exactly like your natural teeth.',
    'badge1_crowns'    => 'Metal Free', 'badge2_crowns' => '5 Year Warranty',
    
    // 9. Dentures
    'desc_dentures'    => 'Lightweight, flexible, and imported BPS dentures that offer superior fit and chewing efficiency.',
    'badge1_dentures'  => 'BPS Certified', 'badge2_dentures' => 'Unbreakable Flexi',
    
    // 10. Smile
    'desc_smile'       => 'Digital Smile Design (DSD) allows you to see your future smile before we even touch your teeth.',
    'badge1_smile'     => 'Digital Mockup', 'badge2_smile' => 'Hollywood Smile',
    
    // 11. Aligners
    'desc_aligners'    => 'Straighten your teeth invisibly with clear aligners. No wires, no metal, just results.',
    'badge1_aligners'  => 'Invisible Braces', 'badge2_aligners' => 'Faster Results',

    // B/A Images (Defaults)
    'ba_implant_b'     => 'https://images.unsplash.com/photo-1571772996211-2f02c9727629?auto=format&fit=crop&w=800&q=80', 
    'ba_implant_a'     => 'https://images.unsplash.com/photo-1606811971618-4486d14f3f99?auto=format&fit=crop&w=800&q=80',
    
    // Contact & Text
    'phone'            => '092313 28309',
    'whatsapp'         => '919231328309',
    'location_short'   => 'Budge Budge, Kolkata',
    'location_2'       => 'Tollygunge, Kolkata',
    'location_3'       => 'Park Street, Kolkata',
    'address_full'     => '15/1/A, AL Daw Rd, Joychandipur, Budge Budge, Kolkata 700137',
    'map_url'          => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3686.866898765432!2d88.345678!3d22.456789!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjLCsDI3JzI0LjQiTiA4OMKwMjAnNDQuNCJF!5e0!3m2!1sen!2sin!4v1600000000000!5m2!1sen!2sin',
    'hours_morn'       => '9:00 AM – 12:30 PM',
    'hours_eve'        => '5:00 PM – 8:30 PM',
    'link_fb' => '#', 'link_insta' => '#', 'link_yt' => '#',
    
    'hero_h1'          => 'Advanced Dentistry',
    'hero_sub'         => 'Reimagined.',
    'hero_desc'        => 'The only clinic in Budge Budge to introduce laser surgery.',
    'hero_tag'         => 'ISO 9001:2015 CERTIFIED',
    'hero_badge'       => 'Micro-Dentistry Enabled',
    'hero_serving'     => 'Serving Budge Budge, New Alipore, and Pujali.',
    
    'doc1_name'        => 'Dr. Shiv Bhusan Pandey', 'doc1_title' => 'Lead Dentist & Implantologist',
    'doc2_name'        => 'Dr. Aditi Sharma',       'doc2_title' => 'Orthodontist & Cosmetic Specialist',
    'about_bio'        => 'Welcome to <strong>Pearls Shine Oral and Dental Care</strong>. We are proud to be the <strong>only clinic</strong> in Budge Budge to introduce revolutionary technologies.',
    
   // GOOGLE REVIEW LINKS (NEW)
    'google_rev_alipore' => 'https://share.google/0H8GiRSeRgRye01aw',
    'google_rev_budge'   => 'https://share.google/gEcHVw8a8GGbPcL2j',

    // REVIEWS (Mixed locations)
    'rev1_name' => 'Rahul S. (New Alipore)', 'rev1_text' => 'The clinic in New Alipore is fantastic. Dr. Pandey explained the implant process perfectly.',
    'rev2_name' => 'Sneha Das (New Alipore)', 'rev2_text' => 'Very hygienic and modern clinic. Best dental experience I have had in Kolkata.',
    'rev3_name' => 'Debarati Roy (Budge Budge)', 'rev3_text' => 'Dr. Pandey immediately diagnosed the issue. Pain gone! Highly recommend the Budge Budge branch.',
    'rev4_name' => 'Krishnapada M. (Budge Budge)', 'rev4_text' => 'My root canal at the Budge Budge clinic was completely painless. Thank you team!',

    // NEW OFFER DEFAULTS (Added separately at the bottom)
    'offer_title' => 'First Visit Privilege',
    'offer_badge' => 'EXCLUSIVE ONLINE OFFER',
    'offer_desc'  => 'New to Pearls Shine? Experience our state-of-the-art care with a comprehensive consultation package including digital X-rays and a personalized treatment plan.',
    'offer_f1'    => 'Comprehensive Exam',
    'offer_f2'    => 'Digital X-Rays',
    'offer_f3'    => 'Expert Consultation'
];

$site = [];
$res = $conn->query("SELECT * FROM site_settings");
if($res) {
    while($row = $res->fetch_assoc()) {
        $site[$row['setting_key']] = $row['setting_value'];
    }
}
$site = array_merge($defaults, $site);

// THIS FUNCTION IS CRITICAL
function val($key) { 
    global $site; 
    return isset($site[$key]) ? $site[$key] : ''; 
}
?>