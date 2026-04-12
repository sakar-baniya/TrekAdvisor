/* ============ DESIGN TOKENS & CSS CUSTOM PROPERTIES ============ */
/* 
   This file documents all CSS custom properties (variables) used in TrekAdvisor.
   You can customize the entire design by modifying these values in public/css/base.css
*/

:root {
    /* =============== COLORS =============== */
    
    /* Primary Brand Colors */
    --color-primary: #228B22;              /* Forest Green - Main brand color */
    --color-primary-dark: #1a6b1a;         /* Darker green for hover/active states */
    --color-primary-light: #2eac2e;        /* Lighter green for subtle backgrounds */
    
    /* Secondary Colors */
    --color-accent: #87CEEB;               /* Sky Blue - Secondary actions */
    --color-accent-dark: #5bb8e0;          /* Darker sky blue for hover */
    --color-cta: #FF4500;                  /* Sunset Orange - Call-to-action buttons */
    --color-cta-dark: #d93a00;             /* Darker orange for hover */
    
    /* Neutral Colors */
    --color-background: #F5F5F5;           /* Off-White - Main background */
    --color-surface: #ffffff;              /* White - Card/panel backgrounds */
    --color-surface-muted: #EFF7EF;        /* Tinted off-white - Subtle backgrounds */
    
    /* Text Colors */
    --color-text-primary: #2F4F4F;         /* Dark Slate Gray - Primary text */
    --color-text-secondary: #5a7a7a;       /* Lighter slate - Secondary text */
    
    /* Utility Colors */
    --color-border: #d4e8d4;               /* Soft green border */
    --color-success: #16a34a;              /* Green for success messages */
    --color-warning: #f59e0b;              /* Amber for warnings */
    --color-error: #ef4444;                /* Red for errors */
    --color-info: #0ea5e9;                 /* Blue for info messages */
    --color-gold: #f59e0b;                 /* Gold for premium/special */
    
    /* =============== TYPOGRAPHY =============== */
    
    /* Font Families */
    --font-display: 'Poppins', sans-serif; /* Headlines and prominent text */
    --font-body: 'Inter', sans-serif;      /* Body text and UI elements */
    
    /* Font Sizes (use in components) */
    --font-size-h1: clamp(1.875rem, 5vw, 3.5rem);
    --font-size-h2: clamp(1.5rem, 4vw, 2.5rem);
    --font-size-h3: clamp(1.25rem, 3vw, 1.875rem);
    --font-size-h4: 1.125rem;
    --font-size-base: 1rem;
    --font-size-sm: 0.875rem;
    --font-size-xs: 0.75rem;
    
    /* Font Weights */
    --font-weight-normal: 400;
    --font-weight-medium: 500;
    --font-weight-semibold: 600;
    --font-weight-bold: 700;
    --font-weight-extrabold: 800;
    --font-weight-black: 900;
    
    /* Line Heights */
    --line-height-tight: 1.2;
    --line-height-normal: 1.5;
    --line-height-relaxed: 1.75;
    --line-height-loose: 2;
    
    /* =============== SPACING =============== */
    
    /* Space Scale (multiples of 8px) */
    --space-1: 0.25rem;    /* 4px */
    --space-2: 0.5rem;     /* 8px */
    --space-3: 0.75rem;    /* 12px */
    --space-4: 1rem;       /* 16px */
    --space-5: 1.25rem;    /* 20px */
    --space-6: 1.5rem;     /* 24px */
    --space-8: 2rem;       /* 32px */
    --space-10: 2.5rem;    /* 40px */
    --space-12: 3rem;      /* 48px */
    --space-16: 4rem;      /* 64px */
    --space-20: 5rem;      /* 80px */
    
    /* =============== SHADOWS =============== */
    
    /* Shadow Elevations */
    --shadow-sm: 0 1px 3px rgba(34, 139, 34, 0.10);
    --shadow-md: 0 4px 16px rgba(34, 139, 34, 0.12);
    --shadow-lg: 0 10px 30px rgba(34, 139, 34, 0.14);
    
    /* Alternative Shadows */
    --shadow-cta: 0 4px 12px rgba(255, 69, 0, 0.25);
    --shadow-cta-hover: 0 8px 20px rgba(255, 69, 0, 0.35);
    
    /* =============== BORDER RADIUS =============== */
    
    /* Radius Scale */
    --radius-sm: 8px;
    --radius-md: 12px;
    --radius-lg: 18px;
    --radius-xl: 24px;
    --radius-2xl: 32px;
    --radius-full: 999px;
    
    /* =============== BREAKPOINTS =============== */
    
    /* Responsive Design Breakpoints */
    /* Used in media queries:
       @media (max-width: 768px) { ... }
       @media (max-width: 1024px) { ... }
       @media (max-width: 1440px) { ... }
    */
    --breakpoint-sm: 640px;
    --breakpoint-md: 768px;
    --breakpoint-lg: 1024px;
    --breakpoint-xl: 1280px;
    --breakpoint-2xl: 1536px;
    
    /* =============== TRANSITIONS =============== */
    
    /* Animation Durations */
    --transition-fast: 0.15s;
    --transition-base: 0.2s;
    --transition-slow: 0.3s;
    --transition-slower: 0.5s;
    
    /* Animation Easing Functions */
    --ease-in-out: cubic-bezier(0.4, 0, 0.2, 1);
    --ease-out: cubic-bezier(0, 0, 0.2, 1);
    --ease-in: cubic-bezier(0.4, 0, 1, 1);
    --ease-linear: linear;
}

/* ============ HOW TO USE THESE TOKENS ============ */

/*
   COLORS:
   .button { background: var(--color-cta); }
   .text { color: var(--color-text-primary); }
   
   SPACING:
   .card { padding: var(--space-6); margin: var(--space-4); }
   
   TYPOGRAPHY:
   h1 { font-size: var(--font-size-h1); font-weight: var(--font-weight-bold); }
   
   SHADOWS:
   .card { box-shadow: var(--shadow-md); }
   .button:hover { box-shadow: var(--shadow-lg); }
   
   TRANSITIONS:
   .button { transition: background var(--transition-base) var(--ease-in-out); }
   
   BORDER RADIUS:
   .button { border-radius: var(--radius-md); }
   
   RESPONSIVE:
   @media (max-width: var(--breakpoint-md)) {
       html { font-size: 14px; }
   }
*/

/* ============ DARK MODE SUPPORT ============ */

@media (prefers-color-scheme: dark) {
    :root {
        --color-background: #1a1a1a;
        --color-surface: #2d2d2d;
        --color-surface-muted: #3d3d3d;
        --color-text-primary: #f0f0f0;
        --color-text-secondary: #b0b0b0;
        --color-border: rgba(255, 255, 255, 0.1);
    }
}

/* ============ ACCESSIBILITY: REDUCED MOTION ============ */

@media (prefers-reduced-motion: reduce) {
    *,
    *::before,
    *::after {
        --transition-fast: 0.01ms;
        --transition-base: 0.01ms;
        --transition-slow: 0.01ms;
        --transition-slower: 0.01ms;
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        scroll-behavior: auto !important;
    }
}

/* ============ THEME CUSTOMIZATION EXAMPLES ============ */

/*
   To change the entire color scheme, modify these in public/css/base.css:
   
   EXAMPLE 1: Warm Sunset Theme
   --color-primary: #D97706;     (Amber)
   --color-accent: #F97316;      (Orange)
   --color-cta: #DC2626;         (Red)
   
   EXAMPLE 2: Ocean Theme
   --color-primary: #0369A1;     (Sky Blue)
   --color-accent: #06B6D4;      (Cyan)
   --color-cta: #0D9488;         (Teal)
   
   EXAMPLE 3: Purple Theme
   --color-primary: #7C3AED;     (Violet)
   --color-accent: #A78BFA;      (Purple)
   --color-cta: #D946EF;         (Pink)
   
   The rest of the design will automatically adapt!
*/
