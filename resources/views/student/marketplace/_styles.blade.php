{{-- Reusable CSS styles for the Marketplace feature --}}
<style>
  /* ── Design Tokens (Academic Precision) ────────────────────────── */
  :root {
    --mp-primary:    #253745;
    --mp-primary-lt: rgba(37, 55, 69, 0.08);
    --mp-bg:         #CCD0CF;
    --mp-surface:    #ffffff;
    --mp-surface-lt: #F8F9F9;
    --mp-border:     rgba(6, 20, 27, 0.08);
    --mp-text:       #06141B;
    --mp-text-muted: #4A5568;
    --mp-shadow:     0 4px 20px rgba(6, 20, 27, 0.05);
    --mp-radius:     16px;
  }

  /* ── Marketplace Wrapper ─────────────────────────────────────── */
  .mp-wrap {
    font-family: 'Inter', sans-serif;
    background: var(--mp-bg);
    min-height: 100vh;
    padding-bottom: 90px;
    color: var(--mp-text);
  }

  /* ── Top Nav ─────────────────────────────────────────────────── */
  .mp-nav {
    display: flex;
    align-items: center;
    gap: 0;
    background: #fff;
    border-bottom: 1px solid var(--mp-border);
    overflow-x: auto;
    scrollbar-width: none;
    position: sticky;
    top: 0;
    z-index: 50;
    box-shadow: 0 2px 10px rgba(6, 20, 27, 0.02);
  }
  .mp-nav::-webkit-scrollbar { display: none; }
  .mp-nav a {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 16px 24px;
    font-size: 14px;
    font-weight: 600;
    color: var(--mp-text-muted);
    text-decoration: none;
    border-bottom: 3px solid transparent;
    white-space: nowrap;
    transition: all .2s;
  }
  .mp-nav a:hover { color: var(--mp-primary); background: var(--mp-surface-lt); }
  .mp-nav a.active {
    color: var(--mp-primary);
    border-bottom-color: var(--mp-primary);
    font-weight: 800;
  }
  .mp-nav-badge {
    background: var(--mp-primary);
    color: #CCD0CF;
    font-size: 10px;
    font-weight: 800;
    border-radius: 999px;
    padding: 2px 6px;
    line-height: 1;
  }

  /* ── Page Header ────────────────────────────────────────────── */
  .mp-page-header {
    padding: 30px 20px 20px;
    background: #fff;
    border-bottom: 1px solid var(--mp-border);
  }
  .mp-page-title {
    font-size: 24px;
    font-weight: 800;
    color: var(--mp-text);
    margin-bottom: 4px;
    letter-spacing: -0.5px;
  }
  .mp-page-sub {
    font-size: 14px;
    color: var(--mp-text-muted);
    font-weight: 500;
  }

  /* ── Cards ──────────────────────────────────────────────────── */
  .mp-card {
    background: var(--mp-surface);
    border: 1px solid var(--mp-border);
    border-radius: 24px;
    padding: 20px;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: var(--mp-shadow);
  }
  .mp-card:hover {
    box-shadow: 0 12px 30px rgba(6, 20, 27, 0.08);
    border-color: rgba(37, 55, 69, 0.15);
    transform: translateY(-4px);
  }
  .mp-card-title {
    font-size: 17px;
    font-weight: 800;
    color: var(--mp-text);
    margin-bottom: 8px;
    line-height: 1.4;
    letter-spacing: -0.3px;
  }

  /* ── Badges ─────────────────────────────────────────────────── */
  .mp-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  .mp-badge-normal   { background: #F2F4F3; color: #2D3748; border: 1px solid rgba(0,0,0,0.05); }
  .mp-badge-good     { background: #FEF3C7; color: #92400E; border: 1px solid rgba(0,0,0,0.05); }
  .mp-badge-stretch  { background: #FEE2E2; color: #991B1B; border: 1px solid rgba(0,0,0,0.05); }
  .mp-badge-skill    { background: var(--mp-surface-lt); color: var(--mp-text-muted); border: 1px solid var(--mp-border); }
  .mp-badge-primary  { background: var(--mp-primary); color: #CCD0CF; }
  .mp-badge-green    { background: #D1FAE5; color: #065F46; }
  .mp-badge-cert     { background: #E0E7FF; color: #3730A3; }
  .mp-badge-stipend  { background: #D1FAE5; color: #065F46; }
  .mp-badge-volunteer{ background: #FFEDD5; color: #9A3412; }

  /* ── Chips (multi-select toggles) ───────────────────────────── */
  .mp-chip {
    display: inline-flex;
    align-items: center;
    padding: 10px 18px;
    border-radius: 12px;
    border: 1px solid var(--mp-border);
    background: #fff;
    font-size: 14px;
    font-weight: 600;
    color: var(--mp-text-muted);
    cursor: pointer;
    transition: all .2s;
  }
  .mp-chip:hover, .mp-chip.selected {
    background: var(--mp-primary);
    border-color: var(--mp-primary);
    color: #CCD0CF;
    box-shadow: 0 4px 12px rgba(37, 55, 69, 0.15);
  }

  /* ── Filter Pills ───────────────────────────────────────────── */
  .mp-pill {
    padding: 8px 16px;
    border-radius: 10px;
    border: 1px solid var(--mp-border);
    background: #fff;
    font-size: 13px;
    font-weight: 700;
    color: var(--mp-text-muted);
    cursor: pointer;
    white-space: nowrap;
    transition: all .2s;
  }
  .mp-pill.active, .mp-pill:hover {
    background: var(--mp-primary);
    border-color: var(--mp-primary);
    color: #CCD0CF;
    box-shadow: 0 4px 12px rgba(37, 55, 69, 0.15);
  }

  /* ── Buttons ────────────────────────────────────────────────── */
  .mp-btn-primary {
    width: 100%;
    padding: 16px;
    border-radius: 14px;
    background: var(--mp-primary);
    color: #CCD0CF;
    font-size: 16px;
    font-weight: 800;
    border: none;
    cursor: pointer;
    font-family: inherit;
    transition: all 0.3s;
    box-shadow: 0 4px 15px rgba(37, 55, 69, 0.15);
  }
  .mp-btn-primary:hover   { background: #1a2833; transform: translateY(-2px); box-shadow: 0 8px 20px rgba(37, 55, 69, 0.2); }
  .mp-btn-primary:disabled{ opacity: .5; cursor: not-allowed; transform: none; }

  .mp-btn-ghost {
    width: 100%;
    padding: 14px;
    background: #fff;
    border: 1px solid var(--mp-border);
    border-radius: 12px;
    color: var(--mp-text-muted);
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    font-family: inherit;
    transition: all .2s;
  }
  .mp-btn-ghost:hover { border-color: var(--mp-primary); color: var(--mp-primary); background: var(--mp-surface-lt); }

  .mp-btn-start {
    padding: 8px 18px;
    border-radius: 10px;
    background: var(--mp-surface-lt);
    border: 1px solid var(--mp-border);
    color: var(--mp-primary);
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    font-family: inherit;
    transition: all .2s;
  }
  .mp-btn-start:hover { background: var(--mp-primary); color: #CCD0CF; border-color: var(--mp-primary); }

  .mp-btn-done {
    padding: 8px 18px;
    border-radius: 10px;
    background: #D1FAE5;
    border: 1px solid rgba(6, 95, 70, 0.1);
    color: #065F46;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    font-family: inherit;
    transition: all .2s;
  }
  .mp-btn-done:hover { background: #065F46; color: #fff; }

  /* ── Bottom Sheet Modal ─────────────────────────────────────── */
  .mp-overlay {
    position: fixed;
    inset: 0;
    background: rgba(6, 20, 27, 0.4);
    backdrop-filter: blur(4px);
    z-index: 100;
    display: none;
    animation: fadeIn 0.3s ease;
  }
  @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
  .mp-overlay.open { display: block; }
  .mp-sheet {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: #fff;
    border-radius: 32px 32px 0 0;
    z-index: 101;
    max-height: 90vh;
    overflow-y: auto;
    transform: translateY(100%);
    transition: transform .35s cubic-bezier(.4, 0, 0.2, 1);
    box-shadow: 0 -10px 40px rgba(6, 20, 27, 0.1);
  }
  .mp-sheet.open { transform: translateY(0); }
  .mp-sheet-handle {
    display: flex;
    justify-content: center;
    padding: 16px 0 8px;
  }
  .mp-sheet-handle span {
    width: 48px;
    height: 5px;
    background: var(--mp-border);
    border-radius: 10px;
  }

  /* ── Stat Cards ─────────────────────────────────────────────── */
  .mp-stat {
    flex: 1;
    min-width: 90px;
    background: #fff;
    border: 1px solid var(--mp-border);
    border-radius: 20px;
    padding: 20px 10px;
    text-align: center;
    box-shadow: var(--mp-shadow);
  }
  .mp-stat-num {
    font-size: 32px;
    font-weight: 800;
    line-height: 1;
    margin-bottom: 6px;
    color: var(--mp-primary);
  }
  .mp-stat-label { font-size: 12px; color: var(--mp-text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }

  /* ── Section Label ──────────────────────────────────────────── */
  .mp-section-label {
    font-size: 12px;
    font-weight: 800;
    color: var(--mp-text-muted);
    text-transform: uppercase;
    letter-spacing: 1.2px;
    margin-bottom: 12px;
    padding: 0 20px;
  }

  /* ── Year Card (onboarding) ─────────────────────────────────── */
  .mp-year-card {
    display: flex;
    align-items: center;
    gap: 16px;
    width: 100%;
    padding: 20px 24px;
    border-radius: 16px;
    border: 2px solid var(--mp-border);
    background: #fff;
    cursor: pointer;
    font-family: inherit;
    text-align: left;
    transition: all .2s;
  }
  .mp-year-card:hover, .mp-year-card.selected {
    border-color: var(--mp-primary);
    background: var(--mp-surface-lt);
    box-shadow: 0 4px 15px rgba(37, 55, 69, 0.08);
  }

  /* ── Progress Dots ──────────────────────────────────────────── */
  .mp-dot {
    height: 8px;
    border-radius: 10px;
    background: var(--mp-border);
    transition: all .3s;
  }
  .mp-dot.active {
    width: 32px !important;
    background: var(--mp-primary);
  }

  /* ── Info Banner ────────────────────────────────────────────── */
  .mp-info {
    background: var(--mp-surface-lt);
    border: 1px solid var(--mp-border);
    border-radius: 12px;
    padding: 12px 16px;
    font-size: 13px;
    color: var(--mp-primary);
    font-weight: 600;
  }

  /* ── Empty State ────────────────────────────────────────────── */
  .mp-empty {
    text-align: center;
    padding: 80px 24px;
    color: var(--mp-text-muted);
  }
  .mp-empty-icon { font-size: 60px; margin-bottom: 20px; }
  .mp-empty-title { font-size: 18px; font-weight: 800; color: var(--mp-text); margin-bottom: 8px; }
  .mp-empty-sub   { font-size: 14px; }

  /* ── Section Sub-header in Modal ────────────────────────────── */
  .mp-modal-section-head {
    font-size: 12px;
    font-weight: 800;
    color: var(--mp-text-muted);
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 10px;
    margin-top: 24px;
  }

  /* ── How steps ──────────────────────────────────────────────── */
  .mp-step {
    display: flex;
    gap: 16px;
    margin-bottom: 16px;
  }
  .mp-step-num {
    flex-shrink: 0;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: var(--mp-primary);
    color: #CCD0CF;
    font-size: 13px;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 2px;
  }
  .mp-step p { font-size: 14px; line-height: 1.6; color: var(--mp-text-muted); margin: 0; }

  /* ── Link ───────────────────────────────────────────────────── */
  .mp-resource-link {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 16px;
    background: var(--mp-surface-lt);
    border: 1px solid var(--mp-border);
    border-radius: 12px;
    font-size: 14px;
    color: var(--mp-primary);
    text-decoration: none;
    font-weight: 700;
    margin-bottom: 8px;
    transition: all .2s;
  }
  .mp-resource-link:hover { background: #fff; border-color: var(--mp-primary); box-shadow: 0 4px 10px rgba(37, 55, 69, 0.05); }

  /* ── Gradient Text ──────────────────────────────────────────── */
  .mp-gradient-text {
    color: var(--mp-primary);
    font-weight: 800;
  }

  /* ── Responsive ─────────────────────────────────────────────── */
  @media (min-width: 640px) {
    .mp-wrap { max-width: 640px; margin: 0 auto; border-left: 1px solid var(--mp-border); border-right: 1px solid var(--mp-border); }
  }
</style>
