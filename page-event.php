<?php
/**
 * Template Name: Event Page
 * Description: Reusable event template with customizable color scheme.
 *              Uses native WordPress post meta — NO PLUGIN REQUIRED.
 */

get_header();

$post_id = get_the_ID();
$prefix = '_roanoke_event_';

// ─── HELPER: Get meta value ───
function roanoke_event_meta($key, $default = '') {
    global $post_id, $prefix;
    $val = get_post_meta($post_id, $prefix . $key, true);
    return $val !== '' ? $val : $default;
}

function roanoke_event_meta_array($key) {
    global $post_id, $prefix;
    $val = get_post_meta($post_id, $prefix . $key, true);
    return is_array($val) ? $val : [];
}

// ─── COLOR SCHEMES ───
$scheme = roanoke_event_meta('color_scheme', 'custom');
$presets = [
    'fireworks' => ['primary' => '#E26C41', 'secondary' => '#19405D', 'accent' => '#F26047', 'background' => '#FFFFFF', 'dark_text' => '#1F2937', 'optional_accent' => '#FCD34D'],
    'celebrate' => ['primary' => '#F26047', 'secondary' => '#8A5156', 'accent' => '#E26C41', 'background' => '#FEF6D5', 'dark_text' => '#000000', 'optional_accent' => '#FCD34D'],
    'taste'     => ['primary' => '#19405D', 'secondary' => '#8A5156', 'accent' => '#E26C41', 'background' => '#FEF6D5', 'dark_text' => '#000000', 'optional_accent' => '#3B82F6'],
    'holiday'   => ['primary' => '#8A5156', 'secondary' => '#19405D', 'accent' => '#F26047', 'background' => '#FEF6D5', 'dark_text' => '#000000', 'optional_accent' => '#FCD34D'],
    'hometown'  => ['primary' => '#E26C41', 'secondary' => '#000000', 'accent' => '#F26047', 'background' => '#FFFFFF', 'dark_text' => '#19405D', 'optional_accent' => '#3B82F6'],
];

if ($scheme !== 'custom' && isset($presets[$scheme])) {
    $p = $presets[$scheme];
    $color_primary   = $p['primary'];
    $color_secondary = $p['secondary'];
    $color_accent    = $p['accent'];
    $color_background= $p['background'];
    $color_dark_text = $p['dark_text'];
    $color_optional_accent = $p['optional_accent'];
} else {
    $color_primary   = roanoke_event_meta('color_primary', '#D97706');
    $color_secondary = roanoke_event_meta('color_secondary', '#7C2D12');
    $color_accent    = roanoke_event_meta('color_accent', '#FCD34D');
    $color_background= roanoke_event_meta('color_background', '#FFFFFF');
    $color_dark_text = roanoke_event_meta('color_dark_text', '#1F2937');
    $color_optional_accent = roanoke_event_meta('color_optional_accent', '#3B82F6');
}

// ─── FIELD FETCH ───
$event_logo_id     = roanoke_event_meta('logo_id');
$tagline           = roanoke_event_meta('tagline', 'Mark Your Calendar!');
$hero_image_id     = roanoke_event_meta('hero_image_id');
$hero_video        = roanoke_event_meta('hero_video');
$short_desc        = roanoke_event_meta('short_desc');
$event_date_raw    = roanoke_event_meta('date');
$event_time        = roanoke_event_meta('time', '5:00 PM');
$event_end_time    = roanoke_event_meta('end_time');
$event_location    = roanoke_event_meta('location', 'Downtown Roanoke');
$event_address     = roanoke_event_meta('address');
$event_cost        = roanoke_event_meta('cost', 'FREE');
$countdown_target  = roanoke_event_meta('countdown');
$long_desc         = roanoke_event_meta('long_desc');
$schedule          = roanoke_event_meta_array('schedule');
$bring_items       = roanoke_event_meta_array('bring');
$leave_items       = roanoke_event_meta_array('leave');
$parking_info      = roanoke_event_meta('parking');
$map_embed         = roanoke_event_meta('map_embed');
$map_image_id      = roanoke_event_meta('map_image_id');
$gallery_ids       = roanoke_event_meta('gallery_ids');
$faq_items         = roanoke_event_meta_array('faq');
$awards_text       = roanoke_event_meta('awards');
$merch_text        = roanoke_event_meta('merch_text');
$merch_link        = roanoke_event_meta('merch_link');
$cta_text          = roanoke_event_meta('cta_text', 'Get Tickets');
$cta_link          = roanoke_event_meta('cta_link');
$cta_secondary_text= roanoke_event_meta('cta_secondary_text', 'Learn More');
$cta_secondary_link= roanoke_event_meta('cta_secondary_link');

// Format date
$event_date_obj = $event_date_raw ? DateTime::createFromFormat('Y-m-d', $event_date_raw) : null;
$event_date_display = $event_date_obj ? $event_date_obj->format('F j, Y') : '';
$event_day   = $event_date_obj ? $event_date_obj->format('j') : '';
$event_month = $event_date_obj ? $event_date_obj->format('M') : '';

// Gallery images
$gallery_images = [];
if ($gallery_ids) {
    foreach (explode(',', $gallery_ids) as $gid) {
        $gid = intval(trim($gid));
        if ($gid) {
            $img = wp_get_attachment_image_src($gid, 'medium_large');
            if ($img) {
                $gallery_images[] = [
                    'url' => $img[0],
                    'alt' => get_post_meta($gid, '_wp_attachment_image_alt', true),
                    'caption' => get_post($gid)->post_excerpt,
                ];
            }
        }
    }
}

// Awards
$awards = [];
if ($awards_text) {
    foreach (preg_split('/\r\n|\r|\n/', $awards_text) as $line) {
        $line = trim($line);
        if ($line) $awards[] = $line;
    }
}

// Sponsors
$sponsors = roanoke_event_meta_array('sponsors');
$clean_sponsors = array_filter($sponsors, function($s) {
    return !empty($s['name']);
});

// Participate cards
$participate_cards = roanoke_event_meta_array('participate');
?>

<style>
:root {
    --event-primary:   <?php echo esc_attr($color_primary); ?>;
    --event-secondary: <?php echo esc_attr($color_secondary); ?>;
    --event-accent:    <?php echo esc_attr($color_accent); ?>;
    --event-background:<?php echo esc_attr($color_background); ?>;
    --event-dark-text: <?php echo esc_attr($color_dark_text); ?>;
    --event-optional:  <?php echo esc_attr($color_optional_accent); ?>;
    --event-white:     #FFFFFF;
}

/* ════════════════════════════════════════
   EVENT PAGE BASE
   ════════════════════════════════════════ */
.event-page {
    font-family: inherit;
    color: var(--event-dark-text);
    overflow-x: hidden;
}
.event-page section { position: relative; }

.event-headline {
    font-family: var(--font-headline, 'futura-pt', 'Futura', sans-serif);
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    line-height: 1.05;
}
.event-body {
    font-family: var(--font-body, 'source-sans-pro', 'Source Sans Pro', sans-serif);
    line-height: 1.7;
}

/* ─── Buttons ─── */
.event-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem 2.5rem;
    font-family: var(--font-headline);
    font-weight: 700;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    border: 3px solid var(--event-primary);
    background: var(--event-primary);
    color: var(--event-white);
    cursor: pointer;
    transition: all 0.25s ease;
    text-decoration: none;
}
.event-btn:hover {
    background: transparent;
    color: var(--event-primary);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}
.event-btn-outline {
    background: transparent;
    color: var(--event-white);
    border-color: var(--event-white);
}
.event-btn-outline:hover {
    background: var(--event-white);
    color: var(--event-primary);
}
.event-btn-secondary {
    border-color: var(--event-secondary);
    background: var(--event-secondary);
}
.event-btn-secondary:hover { color: var(--event-secondary); }

/* ─── Section Headers ─── */
.event-section-label {
    display: inline-block;
    font-family: var(--font-headline);
    font-size: 0.75rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.2em;
    color: var(--event-accent);
    margin-bottom: 0.75rem;
    padding: 0.35rem 1rem;
    background: var(--event-dark-text);
}
.event-section-title {
    font-family: var(--font-headline);
    font-size: clamp(2rem, 4vw, 3.5rem);
    font-weight: 800;
    text-transform: uppercase;
    line-height: 1.05;
    margin-bottom: 1rem;
}

/* ════════════════════════════════════════
   HERO
   ════════════════════════════════════════ */
.event-hero {
    padding: 1rem 0 10rem 0;
    position: relative;
    min-height: 90vh;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    background: var(--event-dark-text);
}
.event-hero__media {
    position: absolute;
    inset: 0;
    z-index: 1;
}
.event-hero__media img,
.event-hero__media video {
    width: 100%;
    height: 100%;
    object-fit: cover;
    opacity: 0.55;
}
.event-hero__media video {
    object-fit: cover;
    min-width: 100%;
    min-height: 100%;
}
.event-hero__youtube-wrapper {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    background: var(--event-dark-text);
}
.event-hero__overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, var(--event-primary) 0%, transparent 60%, var(--event-secondary) 100%);
    opacity: 0.75;
    z-index: 2;
    mix-blend-mode: multiply;
}
.event-hero__pattern {
    position: absolute;
    inset: 0;
    z-index: 3;
    opacity: 0.08;
    background-image: repeating-linear-gradient(45deg, transparent, transparent 35px, rgba(255,255,255,0.1) 35px, rgba(255,255,255,0.1) 70px);
}
.event-hero__content {
    position: relative;
    z-index: 10;
    text-align: center;
    padding: 2rem;
    max-width: 900px;
}
.event-hero__logo {
    max-width: 280px;
    margin: 0 auto 1.5rem;
    filter: drop-shadow(0 4px 20px rgba(0,0,0,0.3));
}
.event-hero__logo img { width: 100%; height: auto; }
.event-hero__badge {
    display: inline-block;
    font-family: var(--font-headline);
    font-size: 0.8rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.15em;
    color: var(--event-accent);
    background: rgba(0,0,0,0.4);
    padding: 0.5rem 1.5rem;
    margin-bottom: 1.5rem;
    border-left: 4px solid var(--event-accent);
}
.event-hero__title {
    font-family: var(--font-headline);
    font-size: clamp(3rem, 7vw, 6rem);
    font-weight: 900;
    text-transform: uppercase;
    color: var(--event-white);
    line-height: 0.95;
    margin-bottom: 1rem;
    text-shadow: 0 4px 30px rgba(0,0,0,0.4);
}
.event-hero__subtitle {
    font-family: var(--font-body);
    font-size: clamp(1.1rem, 2vw, 1.5rem);
    color: rgba(255,255,255,0.9);
    max-width: 600px;
    margin: 0 auto 2rem;
    line-height: 1.5;
}
.event-hero__dateblock {
    display: inline-flex;
    align-items: center;
    gap: 1.5rem;
    background: rgba(0,0,0,0.5);
    backdrop-filter: blur(10px);
    padding: 1rem 2.5rem;
    margin-bottom: 2rem;
    border: 2px solid var(--event-accent);
}
.event-hero__datebox { text-align: center; line-height: 1; }
.event-hero__datebox .day {
    font-family: var(--font-headline);
    font-size: 3rem;
    font-weight: 900;
    color: var(--event-accent);
    display: block;
}
.event-hero__datebox .month {
    font-family: var(--font-headline);
    font-size: 0.9rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: var(--event-white);
}
.event-hero__dateinfo {
    text-align: left;
    border-left: 2px solid rgba(255,255,255,0.3);
    padding-left: 1.5rem;
}
.event-hero__dateinfo .time {
    font-family: var(--font-headline);
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--event-white);
    display: block;
    margin-bottom: 0.25rem;
}
.event-hero__dateinfo .location {
    font-family: var(--font-body);
    font-size: 0.95rem;
    color: rgba(255,255,255,0.8);
}
.event-hero__actions {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    justify-content: center;
    margin-top: 1rem;
}
.event-hero__scroll {
    position: absolute;
    bottom: 2rem;
    left: 50%;
    transform: translateX(-50%);
    z-index: 10;
    animation: eventBounce 2s infinite;
    margin-bottom: 2rem;
}
.event-hero__scroll span {
    display: block;
    width: 24px;
    height: 40px;
    border: 2px solid rgba(255,255,255,0.6);
    border-radius: 12px;
    position: relative;
}
.event-hero__scroll span::before {
    content: '';
    position: absolute;
    top: 6px;
    left: 50%;
    transform: translateX(-50%);
    width: 4px;
    height: 8px;
    background: var(--event-accent);
    border-radius: 2px;
    animation: eventScrollDot 2s infinite;
}
@keyframes eventBounce {
    0%, 20%, 50%, 80%, 100% { transform: translateX(-50%) translateY(0); }
    40% { transform: translateX(-50%) translateY(-10px); }
    60% { transform: translateX(-50%) translateY(-5px); }
}
@keyframes eventScrollDot {
    0% { opacity: 1; top: 6px; }
    100% { opacity: 0; top: 20px; }
}

/* ════════════════════════════════════════
   QUICK INFO BAR
   ════════════════════════════════════════ */
.event-infobar {
    background: transparent;
    padding: 0;
    position: relative;
    z-index: 20;
    margin-top: -35px;
    pointer-events: none;
    padding-bottom: 2rem;
}
.event-infobar__grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
    gap: 0.75rem;
    pointer-events: none;
}
@media (min-width: 768px) {
    .event-infobar__grid {
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        padding: 0 2rem;
    }
}
.event-infobar__item {
    background: rgba(255, 255, 255, 0.92);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    padding: 2rem 1rem 1.25rem;
    text-align: center;
    border-radius: 16px;
    border: 1px solid rgba(255, 255, 255, 0.3);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    pointer-events: auto;
    position: relative;
    overflow: visible;
}
.event-infobar__item:hover {
    transform: translateY(-4px);
    background: rgba(255, 255, 255, 0.98);
    border: 1px solid var(--event-accent);
    box-shadow: 0 1px 15px var(--event-accent);
}
.event-infobar__icon-wrap {
    width: 72px;
    height: 72px;
    margin: 0 auto 0.75rem;
    margin-top: -52px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--event-primary);
    border-radius: 50%;
    padding: 14px;
    transition: all 0.4s ease;
    border: 3px solid rgba(255, 255, 255, 0.8);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
    position: relative;
    z-index: 2;
}
@media (min-width: 768px) {
    .event-infobar__icon-wrap {
        width: 80px;
        height: 80px;
        margin-top: -56px;
        padding: 16px;
    }
}
.event-infobar__item:hover .event-infobar__icon-wrap {
    transform: translateY(-6px) scale(1.15);
    border-color: var(--event-accent);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.2), 0 0 40px rgba(255, 215, 0, 0.15);
}
.event-infobar__icon {
    width: 100%;
    height: 100%;
    color: var(--event-white);
    stroke: currentColor;
    stroke-width: 1.5;
    fill: none;
    transition: all 0.4s ease;
}
.event-infobar__item:hover .event-infobar__icon {
    /* color: var(--event-accent); */
    transform: scale(1.05);
}
.event-infobar__label {
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.15em;
    color: rgba(0, 0, 0, 0.4);
    margin-bottom: 0.3rem;
    font-family: var(--font-headline);
    margin-top: 0.5rem;
}
.event-infobar__value {
    font-family: var(--font-headline);
    font-size: 0.95rem;
    font-weight: 700;
    color: var(--event-dark-text);
    line-height: 1.3;
}
@media (min-width: 768px) {
    .event-infobar__value { font-size: 1.1rem; }
}

/* ════════════════════════════════════════
   COLOR-BLOCKED SECTIONS
   ════════════════════════════════════════ */
.event-section { padding: 5rem 1.5rem; }
@media (min-width: 768px) { .event-section { padding: 6rem 2rem; } }

.event-section--primary   { background: var(--event-primary); color: var(--event-white); }
.event-section--primary .event-section-title { color: var(--event-white); }
.event-section--secondary { background: var(--event-secondary); color: var(--event-white); }
.event-section--secondary .event-section-title { color: var(--event-white); }
.event-section--light     { background: var(--event-background); color: var(--event-dark-text); }
.event-section--light .event-section-title { color: var(--event-dark-text); }
.event-section--dark      { background: var(--event-dark-text); color: var(--event-white); }
.event-section--dark .event-section-title { color: var(--event-white); }
.event-section--white     { background: var(--event-white); color: var(--event-dark-text); }
.event-section--accent    { background: var(--event-accent); color: var(--event-dark-text); }
.event-section--accent .event-section-title { color: var(--event-dark-text); }

/* ════════════════════════════════════════
   ABOUT
   ════════════════════════════════════════ */
.event-about__grid {
    display: grid;
    gap: 3rem;
    max-width: 1200px;
    margin: 0 auto;
    align-items: center;
}
@media (min-width: 768px) {
    .event-about__grid { grid-template-columns: 1fr 1fr; gap: 4rem; }
}
.event-about__image { position: relative; }
.event-about__image img {
    width: 100%;
    height: auto;
    display: block;
}
.event-about__image::before {
    content: '';
    position: absolute;
    top: -15px;
    left: -15px;
    right: 15px;
    bottom: 15px;
    border: 4px solid var(--event-accent);
    z-index: -1;
}
.event-about__image::after {
    content: '';
    position: absolute;
    top: 15px;
    left: 15px;
    right: -15px;
    bottom: -15px;
    background: var(--event-primary);
    opacity: 0.2;
    z-index: -1;
}
.event-about__text { font-size: 1.1rem; line-height: 1.8; }
.event-about__text p + p { margin-top: 1.25rem; }

/* ════════════════════════════════════════
   SCHEDULE
   ════════════════════════════════════════ */
   .event-schedule__block {
    margin-bottom: 3rem;
}

.event-schedule__block:last-child {
    margin-bottom: 0;
}

.event-schedule__block-header {
    margin-bottom: 1rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid rgba(255,255,255,0.2);
}

.event-schedule__block-header h3 {
    font-family: var(--font-headline);
    font-size: 1.5rem;
    font-weight: 800;
    margin: 0;
    color: inherit;
}

.event-schedule__location {
    font-size: 0.95rem;
    opacity: 0.75;
    margin: 0;
}
.event-schedule__wrap { max-width: 900px; margin: 0 auto; }
.event-schedule__item {
    display: grid;
    grid-template-columns: 120px 1fr;
    gap: 1.5rem;
    padding: 1.5rem;
    border-left: 4px solid var(--event-accent);
    background: rgba(255,255,255,0.05);
    margin-bottom: 1rem;
    transition: all 0.2s ease;
}
.event-schedule__item:hover {
    background: rgba(255,255,255,0.1);
    transform: translateX(8px);
}
.event-schedule__time {
    font-family: var(--font-headline);
    font-size: 1rem;
    font-weight: 800;
    color: var(--event-accent);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}
.event-schedule__details h4 {
    font-family: var(--font-headline);
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 0.35rem;
    color: inherit;
}
.event-schedule__details p {
    font-size: 0.95rem;
    opacity: 0.85;
    margin: 0;
}

/* ════════════════════════════════════════
   BRING / LEAVE
   ════════════════════════════════════════ */
.event-rules__grid {
    display: grid;
    gap: 2rem;
    max-width: 1000px;
    margin: 0 auto;
}
@media (min-width: 768px) {
    .event-rules__grid { grid-template-columns: 1fr 1fr; gap: 3rem; }
}
.event-rules__box { padding: 2.5rem; position: relative; }
.event-rules__box--bring { background: var(--event-primary); color: var(--event-white); }
.event-rules__box--leave {
    background: var(--event-dark-text);
    color: var(--event-white);
    border: 3px solid var(--event-accent);
}
.event-rules__box h3 {
    font-family: var(--font-headline);
    font-size: 1.5rem;
    font-weight: 800;
    text-transform: uppercase;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}
.event-rules__box h3 .icon { width: 36px; height: 36px; flex-shrink: 0; }
.event-rules__box ul { list-style: none; padding: 0; margin: 0; }
.event-rules__box li {
    padding: 0.6rem 0;
    padding-left: 1.75rem;
    position: relative;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    font-size: 1rem;
}
.event-rules__box li::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 8px;
    height: 8px;
    background: var(--event-accent);
}
.event-rules__box--bring li::before { clip-path: polygon(50% 0%, 100% 50%, 50% 100%, 0% 50%); }
.event-rules__box--leave li::before { clip-path: polygon(20% 0%, 0% 20%, 30% 50%, 0% 80%, 20% 100%, 50% 70%, 80% 100%, 100% 80%, 70% 50%, 100% 20%, 80% 0%, 50% 30%); }

/* ════════════════════════════════════════
   COUNTDOWN
   ════════════════════════════════════════ */
.event-countdown {
    display: flex;
    justify-content: center;
    gap: 1rem;
    flex-wrap: wrap;
    margin-top: 2rem;
}
.event-countdown__box {
    background: rgba(0,0,0,0.4);
    border: 2px solid var(--event-accent);
    padding: 1.25rem 1.5rem;
    text-align: center;
    min-width: 90px;
}
.event-countdown__number {
    font-family: var(--font-headline);
    font-size: 2.5rem;
    font-weight: 900;
    color: var(--event-accent);
    line-height: 1;
    display: block;
}
.event-countdown__label {
    font-family: var(--font-headline);
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: rgba(255,255,255,0.8);
    margin-top: 0.5rem;
    display: block;
}

/* ════════════════════════════════════════
   MAP
   ════════════════════════════════════════ */
.event-map__wrap {
    position: relative;
    background: var(--event-secondary);
    padding: 2rem;
}
@media (min-width: 768px) { .event-map__wrap { padding: 3rem; } }
.event-map__wrap::before {
    content: '';
    position: absolute;
    top: -20px;
    left: -20px;
    right: 20px;
    bottom: 20px;
    border: 4px solid var(--event-accent);
    z-index: 0;
    pointer-events: none;
}
.event-map__wrap iframe,
.event-map__wrap embed,
.event-map__wrap object,
.event-map__frame {
    position: relative;
    z-index: 1;
    width: 100%;
    min-height: 400px;
    border: none;
    display: block;
}
.event-map__wrap iframe[src*="google.com/maps"],
.event-map__wrap iframe[src*="googleusercontent"],
.event-map__wrap iframe[src*="mapbox"],
.event-map__wrap iframe[src*="openstreetmap"] {
    filter: grayscale(30%) contrast(1.1);
}
.event-map__wrap iframe:hover {
    filter: none;
}

/* ════════════════════════════════════════
   PARTICIPATE CARDS
   ════════════════════════════════════════ */
.event-cta-grid {
    display: grid;
    gap: 1.5rem;
    max-width: 1000px;
    margin: 0 auto;
}
@media (min-width: 768px) { .event-cta-grid { grid-template-columns: repeat(2, 1fr); } }
.event-cta-card {
    padding: 2.5rem 2rem;
    text-align: center;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}
.event-cta-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 6px;
    background: var(--event-accent);
}
.event-cta-card--primary { background: var(--event-primary); color: var(--event-white); }
.event-cta-card--secondary { background: var(--event-secondary); color: var(--event-white); }
.event-cta-card--accent { background: var(--event-accent); color: var(--event-dark-text); }
.event-cta-card--background { background: var(--event-background); color: var(--event-dark-text); }
.event-cta-card--dark_text { background: var(--event-dark-text); color: var(--event-white); }
.event-cta-card--optional_accent { background: var(--event-optional); color: var(--event-white); }
.event-cta-card--white { background: var(--event-white); color: var(--event-dark-text); border: 2px solid var(--event-primary); }

.event-cta-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.2);
}
.event-cta-card h3 {
    font-family: var(--font-headline);
    font-size: 1.5rem;
    font-weight: 800;
    text-transform: uppercase;
    margin-bottom: 1rem;
}
.event-cta-card p {
    font-size: 0.95rem;
    opacity: 0.9;
    margin-bottom: 1.5rem;
    line-height: 1.6;
}

/* Button overrides for participate cards */
.event-cta-card--dark_text .event-btn-outline {
    border-color: var(--event-white);
    color: var(--event-white);
}
.event-cta-card--dark_text .event-btn-outline:hover {
    background: var(--event-white);
    color: var(--event-dark-text);
}
.event-cta-card--background .event-btn-outline,
.event-cta-card--white .event-btn-outline {
    border-color: var(--event-primary);
    color: var(--event-primary);
}
.event-cta-card--background .event-btn-outline:hover,
.event-cta-card--white .event-btn-outline:hover {
    background: var(--event-primary);
    color: var(--event-white);
}
.event-cta-card--accent .event-btn-outline {
    border-color: var(--event-dark-text);
    color: var(--event-dark-text);
}
.event-cta-card--accent .event-btn-outline:hover {
    background: var(--event-dark-text);
    color: var(--event-white);
}
.event-cta-card--dark_text h3,
.event-cta-card--dark_text p { color: var(--event-white); }
.event-cta-card--background h3,
.event-cta-card--background p,
.event-cta-card--white h3,
.event-cta-card--white p { color: var(--event-dark-text); }
.event-cta-card--accent h3,
.event-cta-card--accent p { color: var(--event-dark-text); }

/* ════════════════════════════════════════
   GALLERY
   ════════════════════════════════════════ */
.event-gallery__grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.5rem;
    max-width: 1200px;
    margin: 0 auto;
}
@media (min-width: 768px) {
    .event-gallery__grid { grid-template-columns: repeat(4, 1fr); gap: 0.75rem; }
}
.event-gallery__item {
    position: relative;
    overflow: hidden;
    aspect-ratio: 1;
    cursor: pointer;
}
.event-gallery__item:nth-child(3n+1) {
    grid-row: span 2;
    grid-column: span 2;
}
.event-gallery__item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}
.event-gallery__item:hover img { transform: scale(1.08); }
.event-gallery__overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, var(--event-primary), transparent);
    opacity: 0;
    transition: opacity 0.3s ease;
    display: flex;
    align-items: flex-end;
    padding: 1.5rem;
}
.event-gallery__item:hover .event-gallery__overlay { opacity: 0.85; }
.event-gallery__caption {
    font-family: var(--font-headline);
    font-weight: 700;
    color: var(--event-white);
    font-size: 1.1rem;
    text-transform: uppercase;
}

/* ════════════════════════════════════════
   FAQ
   ════════════════════════════════════════ */
.event-faq__item { border-bottom: 2px solid rgba(255,255,255,0.1); padding: 1.5rem 0; }
.event-faq__question {
    font-family: var(--font-headline);
    font-size: 1.15rem;
    font-weight: 700;
    color: inherit;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
}
.event-faq__question::after {
    content: '+';
    font-size: 1.5rem;
    font-weight: 300;
    color: var(--event-accent);
    flex-shrink: 0;
    transition: transform 0.3s ease;
}
.event-faq__item[open] .event-faq__question::after { transform: rotate(45deg); }
.event-faq__answer {
    padding-top: 1rem;
    font-size: 1rem;
    line-height: 1.7;
    opacity: 0.9;
}

/* ════════════════════════════════════════
   AWARDS
   ════════════════════════════════════════ */
.event-awards__list {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 1rem;
    max-width: 800px;
    margin: 0 auto;
}
.event-awards__badge {
    background: rgba(0,0,0,0.3);
    border: 2px solid var(--event-accent);
    padding: 0.75rem 1.5rem;
    font-family: var(--font-headline);
    font-size: 0.85rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--event-white);
}

/* ════════════════════════════════════════
   FOOTER CTA
   ════════════════════════════════════════ */
.event-footercta {
    text-align: center;
    padding: 5rem 2rem;
    background: linear-gradient(135deg, var(--event-primary), var(--event-secondary));
    position: relative;
    overflow: hidden;
}
.event-footercta::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image: repeating-linear-gradient(45deg, transparent, transparent 20px, rgba(255,255,255,0.03) 20px, rgba(255,255,255,0.03) 40px);
}
.event-footercta__title {
    font-family: var(--font-headline);
    font-size: clamp(2rem, 5vw, 3.5rem);
    font-weight: 900;
    text-transform: uppercase;
    color: var(--event-white);
    margin-bottom: 1rem;
    position: relative;
}
.event-footercta__text {
    font-size: 1.15rem;
    color: rgba(255,255,255,0.85);
    max-width: 600px;
    margin: 0 auto 2rem;
    position: relative;
}

/* ─── SPONSORS MARQUEE ─── */
.event-sponsors-marquee {
    position: relative;
    width: 100%;
    overflow: hidden;
    mask-image: linear-gradient(to right, transparent, black 8%, black 92%, transparent);
    -webkit-mask-image: linear-gradient(to right, transparent, black 8%, black 92%, transparent);
}
.event-sponsors-track {
    display: inline-flex;
    align-items: center;
    gap: 5rem;
    width: max-content;
    will-change: transform;
}
.event-sponsors-marquee:hover .event-sponsors-track {
    animation-play-state: paused !important;
}
.event-sponsor-item {
    flex: 0 0 auto;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem 2rem;
    background: rgba(255,255,255,0.1);
    border-radius: 10px;
    min-width: 160px;
    height: 100px;
    transition: transform 0.3s ease, background 0.3s ease;
    cursor: pointer;
}
.event-sponsor-item:hover {
    background: rgba(255,255,255,0.25);
    transform: scale(1.08);
}
.event-sponsor-item img {
    max-height: 60px;
    width: auto;
    max-width: 160px;
    object-fit: contain;
    opacity: 0.95;
    pointer-events: none;
}
.event-sponsor-name {
    font-family: var(--font-headline);
    font-size: 1rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--event-white);
    white-space: nowrap;
}
@keyframes eventSponsorScroll {
    0%   { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}

/* ─── SPONSOR MODAL ─── */
.event-sponsor-modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.85);
    backdrop-filter: blur(8px);
    z-index: 9999;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 2rem;
    animation: eventModalFadeIn 0.3s ease;
}
.event-sponsor-modal-overlay.active { display: flex; }
@keyframes eventModalFadeIn {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}
.event-sponsor-modal {
    background: var(--event-white);
    border-radius: 20px;
    max-width: 600px;
    width: 100%;
    max-height: 90vh;
    overflow-y: auto;
    padding: 2.5rem;
    position: relative;
    box-shadow: 0 30px 60px rgba(0, 0, 0, 0.5);
    animation: eventModalSlideUp 0.3s ease;
}
@keyframes eventModalSlideUp {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}
.event-sponsor-modal-close {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: none;
    border: none;
    font-size: 2rem;
    line-height: 1;
    color: var(--event-dark-text);
    cursor: pointer;
    padding: 0.5rem;
    width: 44px;
    height: 44px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    background: rgba(0, 0, 0, 0.05);
}
.event-sponsor-modal-close:hover {
    background: rgba(0, 0, 0, 0.1);
    transform: rotate(90deg);
}
.event-sponsor-modal-logo {
    max-width: 150px;
    margin: 0 auto 1.5rem;
    text-align: center;
}
.event-sponsor-modal-logo img {
    max-width: 100%;
    height: auto;
    max-height: 120px;
    object-fit: contain;
}
.event-sponsor-modal-name {
    font-family: var(--font-headline);
    font-size: 1.75rem;
    font-weight: 800;
    color: var(--event-dark-text);
    text-align: center;
    margin-bottom: 0.5rem;
}
.event-sponsor-modal-bio {
    font-family: var(--font-body);
    font-size: 1rem;
    line-height: 1.7;
    color: var(--event-dark-text);
    opacity: 0.85;
    margin: 1rem 0 1.5rem;
    text-align: center;
}
.event-sponsor-modal-website {
    text-align: center;
    margin-bottom: 1.5rem;
}
.event-sponsor-modal-website a {
    color: var(--event-primary);
    text-decoration: none;
    font-weight: 600;
    font-family: var(--font-body);
    transition: color 0.2s ease;
}
.event-sponsor-modal-website a:hover {
    color: var(--event-secondary);
    text-decoration: underline;
}
.event-sponsor-modal-social {
    display: flex;
    justify-content: center;
    gap: 1rem;
    flex-wrap: wrap;
    padding-top: 1.5rem;
    border-top: 2px solid rgba(0, 0, 0, 0.08);
}
.event-sponsor-modal-social a {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.6rem 1.2rem;
    border-radius: 50px;
    background: var(--event-background);
    color: var(--event-dark-text);
    text-decoration: none;
    font-family: var(--font-body);
    font-size: 0.85rem;
    font-weight: 600;
    transition: all 0.25s ease;
    border: 2px solid transparent;
}
.event-sponsor-modal-social a:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
}
.event-sponsor-modal-social a .social-icon {
    width: 20px;
    height: 20px;
    flex-shrink: 0;
}
.event-sponsor-modal-social a.facebook:hover { background: #1877F2; color: white; border-color: #1877F2; }
.event-sponsor-modal-social a.instagram:hover { background: #E4405F; color: white; border-color: #E4405F; }
.event-sponsor-modal-social a.tiktok:hover { background: #000000; color: white; border-color: #333; }
.event-sponsor-modal-social a.twitter:hover { background: #000000; color: white; border-color: #000; }
.event-sponsor-modal-social a.youtube:hover { background: #FF0000; color: white; border-color: #FF0000; }

/* ════════════════════════════════════════
   ANIMATIONS & RESPONSIVE
   ════════════════════════════════════════ */
.event-reveal {
    opacity: 0;
    transform: translateY(30px);
    transition: opacity 0.6s ease, transform 0.6s ease;
}
.event-reveal.visible { opacity: 1; transform: translateY(0); }

@media (max-width: 640px) {
    .event-hero__dateblock { flex-direction: column; gap: 0.75rem; padding: 1rem 1.5rem; }
    .event-hero__dateinfo { border-left: none; border-top: 2px solid rgba(255,255,255,0.3); padding-left: 0; padding-top: 0.75rem; text-align: center; }
    .event-schedule__item { grid-template-columns: 1fr; gap: 0.5rem; }
    .event-countdown__box { min-width: 70px; padding: 1rem; }
    .event-countdown__number { font-size: 1.75rem; }
    .event-infobar__grid { grid-template-columns: repeat(2, 1fr); }
    .event-infobar__item:nth-child(2) { border-right: none; }
    .event-infobar__item:nth-child(1), .event-infobar__item:nth-child(2) { border-bottom: 1px solid rgba(255,255,255,0.15); }
    .event-rules__grid { grid-template-columns: 1fr; }
    .event-cta-grid { grid-template-columns: 1fr; }
    .event-gallery__grid { grid-template-columns: repeat(2, 1fr); }
    .event-sponsor-modal { padding: 1.5rem; margin: 1rem; }
    .event-sponsor-modal-social { flex-direction: column; align-items: stretch; }
    .event-sponsor-modal-social a { justify-content: center; }
}

@media (max-width: 400px) {
    .event-infobar { margin-top: -20px; }
    .event-infobar__icon-wrap { width: 48px; height: 48px; margin-top: -34px; padding: 8px; }
    .event-infobar__item { background: rgba(255, 255, 255, 0.98); }
}
</style>

<main class="event-page">

<!-- ════════════════════════════════════════
     HERO
     ════════════════════════════════════════ -->
<section class="event-hero">
    <div class="event-hero__media">
        <?php if ($hero_video): ?>
            <?php 
            // Check if it's a YouTube URL
            $youtube_id = '';
            if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $hero_video, $matches)) {
                $youtube_id = $matches[1];
            }
            ?>
            
            <?php if ($youtube_id): ?>
                <!-- YouTube Embed -->
				<div class="event-hero__youtube-wrapper" style="position:absolute; inset:0; overflow:hidden;">
					<iframe 
						src="https://www.youtube.com/embed/<?php echo esc_attr($youtube_id); ?>?autoplay=1&mute=1&loop=1&playlist=<?php echo esc_attr($youtube_id); ?>&controls=0&showinfo=0&rel=0&modestbranding=1&playsinline=1"
						style="
							position: absolute;
							top: 50%;
							left: 50%;
							width: 100vw;
							height: 56.25vw;
							min-height: 100vh;
							min-width: 177.78vh;
							transform: translate(-50%, -50%);
							border: 0;
							pointer-events: none;
						"
						allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
						allowfullscreen
						frameborder="0"
					></iframe>
				</div>
            <?php else: ?>
                <!-- Direct video file -->
                <video autoplay muted loop playsinline poster="<?php echo $hero_image_id ? esc_url(wp_get_attachment_image_url($hero_image_id, 'full')) : ''; ?>">
                    <source src="<?php echo esc_url($hero_video); ?>" type="video/mp4">
                </video>
            <?php endif; ?>
            
        <?php elseif ($hero_image_id): ?>
            <img src="<?php echo esc_url(wp_get_attachment_image_url($hero_image_id, 'full')); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
        <?php else: ?>
            <div style="width:100%;height:100%;background:linear-gradient(135deg,var(--event-primary),var(--event-secondary));"></div>
        <?php endif; ?>
    </div>
    <div class="event-hero__overlay"></div>
    <div class="event-hero__pattern"></div>

    <div class="event-hero__content">
        <?php if ($event_logo_id): ?>
            <div class="event-hero__logo">
                <?php echo wp_get_attachment_image($event_logo_id, 'medium', false, ['alt' => get_the_title()]); ?>
            </div>
        <?php endif; ?>

        <span class="event-hero__badge"><?php echo esc_html($tagline); ?></span>
        <h1 class="event-hero__title"><?php the_title(); ?></h1>

        <?php if ($short_desc): ?>
            <p class="event-hero__subtitle"><?php echo esc_html($short_desc); ?></p>
        <?php endif; ?>

        <?php if ($event_date_raw): ?>
        <div class="event-hero__dateblock">
            <div class="event-hero__datebox">
                <span class="day"><?php echo esc_html($event_day); ?></span>
                <span class="month"><?php echo esc_html($event_month); ?></span>
            </div>
            <div class="event-hero__dateinfo">
                <span class="time"><?php echo esc_html($event_time); ?><?php echo $event_end_time ? ' &ndash; ' . esc_html($event_end_time) : ''; ?></span>
                <span class="location"><?php echo esc_html($event_location); ?></span>
            </div>
        </div>
        <?php endif; ?>

        <div class="event-hero__actions">
            <?php if ($cta_link): ?>
                <a href="<?php echo esc_url($cta_link); ?>" class="event-btn"><?php echo esc_html($cta_text); ?></a>
            <?php endif; ?>
            <?php if ($cta_secondary_link): ?>
                <a href="<?php echo esc_url($cta_secondary_link); ?>" class="event-btn event-btn-outline"><?php echo esc_html($cta_secondary_text); ?></a>
            <?php endif; ?>
        </div>

        <?php if ($countdown_target): ?>
        <div class="event-countdown" data-target="<?php echo esc_attr($countdown_target); ?>">
            <div class="event-countdown__box"><span class="event-countdown__number" data-unit="days">00</span><span class="event-countdown__label">Days</span></div>
            <div class="event-countdown__box"><span class="event-countdown__number" data-unit="hours">00</span><span class="event-countdown__label">Hours</span></div>
            <div class="event-countdown__box"><span class="event-countdown__number" data-unit="minutes">00</span><span class="event-countdown__label">Minutes</span></div>
            <div class="event-countdown__box"><span class="event-countdown__number" data-unit="seconds">00</span><span class="event-countdown__label">Seconds</span></div>
        </div>
        <?php endif; ?>
    </div>

    <div class="event-hero__scroll"><span></span></div>
</section>

<?php if ($event_date_raw || $event_time || $event_location || $event_cost): ?>
<section class="event-infobar">
    <div class="event-infobar__grid">
        <?php if ($event_date_display): ?>
        <div class="event-infobar__item">
            <div class="event-infobar__icon-wrap">
                <svg class="event-infobar__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                    <path d="M8 14h.01"></path>
                    <path d="M12 14h.01"></path>
                    <path d="M16 14h.01"></path>
                    <path d="M8 18h.01"></path>
                    <path d="M12 18h.01"></path>
                    <path d="M16 18h.01"></path>
                </svg>
            </div>
            <div class="event-infobar__label">Date</div>
            <div class="event-infobar__value"><?php echo esc_html($event_date_display); ?></div>
        </div>
        <?php endif; ?>
        <?php if ($event_time): ?>
        <div class="event-infobar__item">
            <div class="event-infobar__icon-wrap">
                <svg class="event-infobar__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
            </div>
            <div class="event-infobar__label">Time</div>
            <div class="event-infobar__value"><?php echo esc_html($event_time); ?><?php echo $event_end_time ? ' &ndash; ' . esc_html($event_end_time) : ''; ?></div>
        </div>
        <?php endif; ?>
        <?php if ($event_location): ?>
        <div class="event-infobar__item">
            <div class="event-infobar__icon-wrap">
                <svg class="event-infobar__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                </svg>
            </div>
            <div class="event-infobar__label">Location</div>
            <div class="event-infobar__value"><?php echo esc_html($event_location); ?></div>
        </div>
        <?php endif; ?>
        <?php if ($event_cost): ?>
        <div class="event-infobar__item">
            <div class="event-infobar__icon-wrap">
                <svg class="event-infobar__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M8 12h8"></path>
                    <path d="M12 8v8"></path>
                </svg>
            </div>
            <div class="event-infobar__label">Admission</div>
            <div class="event-infobar__value"><?php echo esc_html($event_cost); ?></div>
        </div>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<!-- ════════════════════════════════════════
     ABOUT / DESCRIPTION
     ════════════════════════════════════════ -->
<?php if ($long_desc): ?>
<section class="event-section event-section--white">
    <div class="event-about__grid event-reveal">
        <div class="event-about__image">
            <?php if (has_post_thumbnail()): ?>
                <?php echo get_the_post_thumbnail(get_the_ID(), 'large', ['alt' => esc_attr(get_the_title())]); ?>
            <?php endif; ?>
        </div>
        <div>
            <span class="event-section-label">About the Event</span>
            <h2 class="event-section-title" style="color: var(--event-dark-text);">What to Expect</h2>
            <div class="event-about__text event-body">
                <?php echo wp_kses_post($long_desc); ?>
            </div>
            <?php if ($cta_link): ?>
                <a href="<?php echo esc_url($cta_link); ?>" class="event-btn" style="margin-top: 1.5rem;"><?php echo esc_html($cta_text); ?></a>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ════════════════════════════════════════
     SCHEDULE
     ════════════════════════════════════════ -->
<?php if (!empty($schedule)): ?>
<section class="event-section event-section--dark">
    <div class="event-reveal">

        <div style="text-align: center; margin-bottom: 3rem;">
            <span class="event-section-label">Plan Your Day</span>
            <h2 class="event-section-title">Event Schedule</h2>
        </div>

        <div class="event-schedule__wrap">

            <?php foreach ($schedule as $block): ?>

                <?php
                $block_name = $block['name'] ?? '';
                $timeline_items = isset($block['items']) && is_array($block['items'])
                    ? $block['items']
                    : [];

                // Skip completely empty blocks.
                if (empty($block_name) && empty($timeline_items)) {
                    continue;
                }
                ?>

                <div class="event-schedule__block">

                    <?php if ($block_name): ?>
                        <div class="event-schedule__block-header">
                            <h3><?php echo esc_html($block_name); ?></h3>
                        </div>
                    <?php endif; ?>

                    <?php foreach ($timeline_items as $item): ?>

                        <?php
                        $time     = $item['time'] ?? '';
                        $activity = $item['activity'] ?? '';
                        $location = $item['location'] ?? '';

                        // Skip completely empty timeline items.
                        if (empty($time) && empty($activity) && empty($location)) {
                            continue;
                        }
                        ?>

                        <div class="event-schedule__item">

                            <?php if ($time): ?>
                                <div class="event-schedule__time">
                                    <?php echo esc_html($time); ?>
                                </div>
                            <?php endif; ?>

                            <div class="event-schedule__details">

                                <?php if ($activity): ?>
                                    <h4><?php echo esc_html($activity); ?></h4>
                                <?php endif; ?>

                                <?php if ($location): ?>
                                    <p class="event-schedule__location">
                                        <?php echo esc_html($location); ?>
                                    </p>
                                <?php endif; ?>

                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>

            <?php endforeach; ?>

        </div>
    </div>
</section>
<?php endif; ?>

<!-- ════════════════════════════════════════
     BRING / LEAVE
     ════════════════════════════════════════ -->
<?php if (!empty($bring_items) || !empty($leave_items)): ?>
<section class="event-section event-section--white" style="padding-top: 3rem; padding-bottom: 3rem;">
    <div class="event-rules__grid event-reveal">
        <?php if (!empty($bring_items)): ?>
        <div class="event-rules__box event-rules__box--bring">
            <h3>
                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Bring
            </h3>
            <ul>
                <?php foreach ($bring_items as $item): 
                    if (empty($item['item'])) continue;
                ?>
                    <li><?php echo esc_html($item['item']); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>
        <?php if (!empty($leave_items)): ?>
        <div class="event-rules__box event-rules__box--leave">
            <h3>
                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                Leave at Home
            </h3>
            <ul>
                <?php foreach ($leave_items as $item): 
                    if (empty($item['item'])) continue;
                ?>
                    <li><?php echo esc_html($item['item']); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<!-- ════════════════════════════════════════
     PARKING
     ════════════════════════════════════════ -->
<?php if ($parking_info): ?>
<section class="event-section event-section--light">
    <div class="event-reveal" style="max-width: 800px; margin: 0 auto; text-align: center;">
        <span class="event-section-label">Getting Here</span>
        <h2 class="event-section-title" style="color: var(--event-dark-text);">Parking</h2>
        <div class="event-body" style="font-size: 1.1rem;">
            <?php echo wp_kses_post($parking_info); ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ════════════════════════════════════════
     MAP
     ════════════════════════════════════════ -->
    <?php if ($map_embed || $map_image_id): ?>
<section class="event-section event-section--primary" style="padding: 0;">
    <div class="event-map__wrap event-reveal">
        <?php if ($map_embed): ?>
            <?php
            // Allow iframe and common map embed attributes
            $map_allowed = wp_kses_allowed_html('post');
            $map_allowed['iframe'] = [
                'src'             => true,
                'width'           => true,
                'height'          => true,
                'frameborder'     => true,
                'allowfullscreen' => true,
                'allow'           => true,
                'style'           => true,
                'class'           => true,
                'id'              => true,
                'title'           => true,
                'loading'         => true,
                'referrerpolicy'  => true,
                'sandbox'         => true,
                'scrolling'       => true,
            ];
            $map_allowed['script'] = [
                'src'   => true,
                'type'  => true,
                'async' => true,
                'defer' => true,
            ];
            $map_allowed['div'] = [
                'style' => true,
                'class' => true,
                'id'    => true,
            ];
            echo wp_kses($map_embed, $map_allowed);
            ?>
        <?php elseif ($map_image_id): ?>
            <img src="<?php echo esc_url(wp_get_attachment_image_url($map_image_id, 'large')); ?>" alt="Event Map" style="width:100%;height:auto;display:block;">
        <?php endif; ?>
    </div>
</section>
    <?php endif; ?>

<!-- WordPress content area -->
<section id="content" class="py-8 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php
        while ( have_posts() ) : the_post();
            the_content();
        endwhile;
        ?>
    </div>
</section>

<!-- ════════════════════════════════════════
     PARTICIPATE CARDS
     ════════════════════════════════════════ -->
<?php 
$color_classes = [
    'primary'          => 'event-cta-card--primary',
    'secondary'        => 'event-cta-card--secondary',
    'accent'           => 'event-cta-card--accent',
    'background'       => 'event-cta-card--background',
    'dark_text'        => 'event-cta-card--dark_text',
    'optional_accent'  => 'event-cta-card--optional_accent',
    'white'            => 'event-cta-card--white',
];
?>
<?php if (!empty($participate_cards)): ?>
<section class="event-section event-section--secondary">
    <div class="event-reveal">
        <div style="text-align: center; margin-bottom: 3rem;">
            <span class="event-section-label">Get Involved</span>
            <h2 class="event-section-title">Participate</h2>
        </div>
        <div class="event-cta-grid">
            <?php foreach ($participate_cards as $card): 
                if (empty($card['title'])) continue;
                $color_class = isset($color_classes[$card['color_scheme']]) ? $color_classes[$card['color_scheme']] : 'event-cta-card--primary';
            ?>
            <div class="event-cta-card <?php echo esc_attr($color_class); ?>">
                <h3><?php echo esc_html($card['title']); ?></h3>
                <?php if (!empty($card['description'])): ?>
                    <p><?php echo esc_html($card['description']); ?></p>
                <?php endif; ?>
                <?php if (!empty($card['link_url']) && !empty($card['link_text'])): ?>
                    <a href="<?php echo esc_url($card['link_url']); ?>" class="event-btn event-btn-outline" style="font-size: 0.8rem; padding: 0.6rem 1.2rem;"><?php echo esc_html($card['link_text']); ?></a>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ════════════════════════════════════════
     SPONSORS
     ════════════════════════════════════════ -->
<?php if (!empty($clean_sponsors)): ?>
<section class="event-section event-section--primary" style="padding: 4rem 0; overflow: hidden;">
    <div style="text-align: center; margin-bottom: 2.5rem; padding: 0 1.5rem;">
        <span class="event-section-label">Partners</span>
        <h2 class="event-section-title">Our Sponsors</h2>
    </div>
    
    <div class="event-sponsors-marquee">
        <div class="event-sponsors-track" id="sponsorTrack">
            <?php foreach ($clean_sponsors as $sponsor_index => $sponsor): 
                $sponsor_img = !empty($sponsor['image_id']) ? wp_get_attachment_image_url(intval($sponsor['image_id']), 'medium') : '';
                $has_details = !empty($sponsor['bio']) || !empty($sponsor['link']) || !empty($sponsor['facebook']) || !empty($sponsor['instagram']) || !empty($sponsor['tiktok']) || !empty($sponsor['twitter']) || !empty($sponsor['youtube']);
            ?>
                <div class="event-sponsor-item" data-sponsor="<?php echo esc_attr($sponsor_index); ?>" <?php echo $has_details ? 'style="cursor:pointer;"' : ''; ?>>
                    <?php if ($sponsor_img): ?>
                        <img src="<?php echo esc_url($sponsor_img); ?>" alt="<?php echo esc_attr($sponsor['name']); ?>" loading="lazy">
                    <?php else: ?>
                        <span class="event-sponsor-name"><?php echo esc_html($sponsor['name']); ?></span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Sponsor Modal -->
<div class="event-sponsor-modal-overlay" id="sponsorModal">
    <div class="event-sponsor-modal">
        <button class="event-sponsor-modal-close" id="sponsorModalClose">&times;</button>
        <div id="sponsorModalContent">
            <div class="event-sponsor-modal-logo" id="modalLogo"></div>
            <h3 class="event-sponsor-modal-name" id="modalName"></h3>
            <div class="event-sponsor-modal-bio" id="modalBio"></div>
            <div class="event-sponsor-modal-website" id="modalWebsite"></div>
            <div class="event-sponsor-modal-social" id="modalSocial"></div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- ════════════════════════════════════════
     GALLERY
     ════════════════════════════════════════ -->
<?php if (!empty($gallery_images)): ?>
<section class="event-section event-section--white" style="padding: 3rem 0.5rem;">
    <div class="event-reveal">
        <div style="text-align: center; margin-bottom: 2rem; padding: 0 1.5rem;">
            <span class="event-section-label" style="background: var(--event-primary); color: var(--event-white);">Memories</span>
            <h2 class="event-section-title" style="color: var(--event-dark-text);">Photo Gallery</h2>
        </div>
        <div class="event-gallery__grid">
            <?php foreach ($gallery_images as $img): ?>
            <div class="event-gallery__item">
                <img src="<?php echo esc_url($img['url']); ?>" alt="<?php echo esc_attr($img['alt'] ?: 'Event photo'); ?>" loading="lazy">
                <div class="event-gallery__overlay">
                    <span class="event-gallery__caption"><?php echo esc_html($img['caption'] ?: 'View'); ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ════════════════════════════════════════
     FAQ
     ════════════════════════════════════════ -->
<?php if (!empty($faq_items)): ?>
<section class="event-section event-section--dark">
    <div class="event-reveal" style="max-width: 800px; margin: 0 auto;">
        <div style="text-align: center; margin-bottom: 3rem;">
            <span class="event-section-label">Questions?</span>
            <h2 class="event-section-title">FAQ</h2>
        </div>
        <?php foreach ($faq_items as $faq): 
            if (empty($faq['question'])) continue;
        ?>
        <details class="event-faq__item">
            <summary class="event-faq__question"><?php echo esc_html($faq['question']); ?></summary>
            <div class="event-faq__answer event-body"><?php echo wp_kses_post($faq['answer'] ?? ''); ?></div>
        </details>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- ════════════════════════════════════════
     AWARDS
     ════════════════════════════════════════ -->
<?php if (!empty($awards)): ?>
<section class="event-section event-section--accent">
    <div class="event-reveal" style="text-align: center;">
        <span class="event-section-label" style="background: var(--event-dark-text); color: var(--event-white);">Recognition</span>
        <h2 class="event-section-title" style="color: var(--event-dark-text); margin-bottom: 2rem;">Award Winning</h2>
        <div class="event-awards__list">
            <?php foreach ($awards as $award): ?>
                <span class="event-awards__badge"><?php echo esc_html($award); ?></span>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ════════════════════════════════════════
     MERCH
     ════════════════════════════════════════ -->
<?php if ($merch_text): ?>
<section class="event-section event-section--light">
    <div class="event-reveal" style="max-width: 700px; margin: 0 auto; text-align: center;">
        <span class="event-section-label">Gear Up</span>
        <h2 class="event-section-title" style="color: var(--event-dark-text);">Official Merch</h2>
        <p class="event-body" style="font-size: 1.1rem; margin-bottom: 2rem;"><?php echo esc_html($merch_text); ?></p>
        <?php if ($merch_link): ?>
            <a href="<?php echo esc_url($merch_link); ?>" class="event-btn">Shop Now</a>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<!-- ════════════════════════════════════════
     FOOTER CTA
     ════════════════════════════════════════ -->
<?php if ($cta_link): ?>
<section class="event-footercta">
    <div class="event-reveal">
        <h2 class="event-footercta__title"><?php echo esc_html($cta_text); ?></h2>
        <p class="event-footercta__text">Don't miss out on the biggest event of the season. Grab your spot today!</p>
        <div style="display: flex; flex-wrap: wrap; gap: 1rem; justify-content: center; position: relative;">
            <?php if ($cta_link): ?>
                <a href="<?php echo esc_url($cta_link); ?>" class="event-btn" style="background: var(--event-white); color: var(--event-primary); border-color: var(--event-white);"><?php echo esc_html($cta_text); ?></a>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?>

</main><!-- /.event-page -->

<script>
(function() {
    'use strict';

    // Scroll reveal
    var reveals = document.querySelectorAll('.event-reveal');
    if ('IntersectionObserver' in window) {
        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) entry.target.classList.add('visible');
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });
        reveals.forEach(function(el) { observer.observe(el); });
    } else {
        reveals.forEach(function(el) { el.classList.add('visible'); });
    }

    // Countdown timer
    var countdown = document.querySelector('.event-countdown');
    if (countdown) {
        var target = new Date(countdown.dataset.target).getTime();
        var units = countdown.querySelectorAll('.event-countdown__number');
        function updateCountdown() {
            var now = new Date().getTime();
            var diff = target - now;
            if (diff < 0) diff = 0;
            var d = Math.floor(diff / (1000 * 60 * 60 * 24));
            var h = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            var s = Math.floor((diff % (1000 * 60)) / 1000);
            units.forEach(function(u) {
                var unit = u.dataset.unit;
                if (unit === 'days') u.textContent = String(d).padStart(2, '0');
                if (unit === 'hours') u.textContent = String(h).padStart(2, '0');
                if (unit === 'minutes') u.textContent = String(m).padStart(2, '0');
                if (unit === 'seconds') u.textContent = String(s).padStart(2, '0');
            });
        }
        updateCountdown();
        setInterval(updateCountdown, 1000);
    }

    // ─── SPONSOR MARQUEE ───
    var track = document.getElementById('sponsorTrack');
    if (track) {
        var marquee = track.parentElement;
        var originalHTML = track.innerHTML;

        function fillTrack() {
            track.innerHTML = originalHTML;
            var attempts = 0;
            while (track.scrollWidth < marquee.offsetWidth * 2 && attempts < 20) {
                track.innerHTML += originalHTML;
                attempts++;
            }
        }

        function setAnimationDuration() {
            if (track.children.length === 0) track.innerHTML = originalHTML;
            var oneLoopWidth = track.scrollWidth / 2;
            if (oneLoopWidth === 0 || !isFinite(oneLoopWidth)) oneLoopWidth = marquee.offsetWidth || 800;
            var duration = Math.max(oneLoopWidth / 60, 10);
            track.style.animation = 'none';
            void track.offsetHeight;
            track.style.animation = 'eventSponsorScroll ' + duration + 's linear infinite';
        }

        function initMarquee() {
            fillTrack();
            setAnimationDuration();
            attachSponsorClickEvents();
        }

        function waitForImagesAndInit() {
            var images = track.querySelectorAll('img');
            if (images.length === 0) { initMarquee(); return; }
            var loaded = 0, totalImages = images.length;
            function checkAllLoaded() {
                loaded++;
                if (loaded === totalImages) initMarquee();
            }
            images.forEach(function(img) {
                if (img.complete && img.naturalWidth > 0) checkAllLoaded();
                else {
                    img.addEventListener('load', checkAllLoaded);
                    img.addEventListener('error', checkAllLoaded);
                }
            });
            setTimeout(function() {
                if (!track.style.animation || track.style.animation === 'none') initMarquee();
            }, 3000);
        }

        var resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                track.style.animation = 'none';
                initMarquee();
                setTimeout(attachSponsorClickEvents, 100);
            }, 300);
        });

        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                setTimeout(function() {
                    var computedStyle = window.getComputedStyle(track);
                    if (computedStyle.animationName === 'none') initMarquee();
                }, 500);
            }
        });

        waitForImagesAndInit();
    }

    // ─── SPONSOR MODAL ───
    var modal = document.getElementById('sponsorModal');
    var closeBtn = document.getElementById('sponsorModalClose');
    if (!modal || !closeBtn) return;

    var modalLogo = document.getElementById('modalLogo');
    var modalName = document.getElementById('modalName');
    var modalBio = document.getElementById('modalBio');
    var modalWebsite = document.getElementById('modalWebsite');
    var modalSocial = document.getElementById('modalSocial');

    var sponsorsData = <?php 
        $sponsor_data = [];
        foreach ($clean_sponsors as $index => $sponsor) {
            $sponsor_data[$index] = [
                'name' => $sponsor['name'] ?? '',
                'logo' => !empty($sponsor['image_id']) ? wp_get_attachment_image_url(intval($sponsor['image_id']), 'medium') : '',
                'bio' => $sponsor['bio'] ?? '',
                'link' => $sponsor['link'] ?? '',
                'facebook' => $sponsor['facebook'] ?? '',
                'instagram' => $sponsor['instagram'] ?? '',
                'tiktok' => $sponsor['tiktok'] ?? '',
                'twitter' => $sponsor['twitter'] ?? '',
                'youtube' => $sponsor['youtube'] ?? '',
            ];
        }
        echo json_encode($sponsor_data);
    ?>;

    function openSponsorModal(index) {
        var sponsor = sponsorsData[index];
        if (!sponsor) return;
        var hasDetails = sponsor.bio || sponsor.link || sponsor.facebook || sponsor.instagram || sponsor.tiktok || sponsor.twitter || sponsor.youtube;
        if (!hasDetails) return;

        modalLogo.innerHTML = sponsor.logo ? '<img src="' + sponsor.logo + '" alt="' + sponsor.name + '">' : '';
        modalName.textContent = sponsor.name;
        
        if (sponsor.bio) { modalBio.textContent = sponsor.bio; modalBio.style.display = 'block'; }
        else { modalBio.style.display = 'none'; }
        
        if (sponsor.link) {
            modalWebsite.innerHTML = '<a href="' + sponsor.link + '" target="_blank" rel="noopener noreferrer">' + sponsor.link.replace(/^https?:\/\//, '') + '</a>';
            modalWebsite.style.display = 'block';
        } else { modalWebsite.style.display = 'none'; }

        var socialHtml = '';
        var socialLinks = [
            { key: 'facebook', label: 'Facebook', icon: '<svg class="social-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>' },
            { key: 'instagram', label: 'Instagram', icon: '<svg class="social-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>' },
            { key: 'tiktok', label: 'TikTok', icon: '<svg class="social-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>' },
            { key: 'twitter', label: 'X (Twitter)', icon: '<svg class="social-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>' },
            { key: 'youtube', label: 'YouTube', icon: '<svg class="social-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>' },
        ];
        
        var hasSocial = false;
        socialLinks.forEach(function(link) {
            if (sponsor[link.key]) {
                hasSocial = true;
                socialHtml += '<a href="' + sponsor[link.key] + '" target="_blank" rel="noopener noreferrer" class="' + link.key + '">' + link.icon + ' ' + link.label + '</a>';
            }
        });
        
        if (hasSocial) { modalSocial.innerHTML = socialHtml; modalSocial.style.display = 'flex'; }
        else { modalSocial.style.display = 'none'; }

        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeSponsorModal() {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }

    function attachSponsorClickEvents() {
        var trackEl = document.getElementById('sponsorTrack');
        if (!trackEl) return;
        trackEl.removeEventListener('click', handleSponsorClick);
        trackEl.addEventListener('click', handleSponsorClick);
    }

    function handleSponsorClick(e) {
        var target = e.target.closest('.event-sponsor-item[data-sponsor]');
        if (!target) return;
        openSponsorModal(target.getAttribute('data-sponsor'));
    }

    closeBtn.addEventListener('click', closeSponsorModal);
    modal.addEventListener('click', function(e) { if (e.target === this) closeSponsorModal(); });
    document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeSponsorModal(); });

    attachSponsorClickEvents();

    var trackObs = document.getElementById('sponsorTrack');
    if (trackObs) {
        var observer = new MutationObserver(function() { attachSponsorClickEvents(); });
        observer.observe(trackObs, { childList: true, subtree: true });
    }
})();
</script>

<?php get_footer(); ?>