# design.md — Locked Design System for the Watch E-commerce Website

**Document type:** Design-system specification + Antigravity IDE implementation brief  
**Version:** 1.0  
**Status:** Proposed baseline to implement consistently  
**Scope:** Storefront, product discovery, product details, cart, checkout, customer account, support pages, and admin dashboard

> This document is the single source of truth for the website's visual language. Implement the design tokens and reusable components before building individual pages. All screens must inherit the same rules. Page-specific variation is allowed for content, imagery, and layout composition—not for inventing new colors, spacing, typography, radii, or component styles.

---

# Part I — Design System Specification

## 1. Design Direction

### 1.1 Visual concept: Modern Editorial Luxury

The website should feel refined, calm, precise, tactile, and product-led. Use strong product photography, intentional typography, alignment, and whitespace instead of decorative effects.

Core characteristics:
- Warm neutral surfaces rather than stark white everywhere.
- Deep charcoal text with restrained champagne-bronze accents.
- Editorial imagery balanced with practical shopping UI.
- Consistent product photography and predictable card layouts.
- Thin borders, subtle shadows, and controlled corner radii.
- Clear typography hierarchy and purposeful spacing.
- Minimal animation that never distracts from shopping.

Avoid:
- Random gradients, glassmorphism, glow effects, decorative blobs, and excessive animation.
- A different palette for every category.
- Different button shapes or card styles on individual pages.
- Arbitrary spacing and font sizes.
- Excessive uppercase text, letter spacing, shadows, or pills.
- Emoji as interface icons.
- Fabricated reviews, ratings, scarcity claims, or product specifications.
- Unrelated photography styles mixed without a deliberate art direction.

## 2. Locked Color Tokens — Midnight Sapphire & Champagne Gold

Implement these as semantic design tokens. Components must reference token names, not scattered hard-coded color values.

| Token | Hex | Intended usage |
|---|---|---|
| `color.brand.950` | `#0A1128` | Primary midnight navy brand surface, footer, primary CTA |
| `color.brand.900` | `#0F1A36` | Dark header bars, hover state for dark buttons |
| `color.brand.800` | `#162447` | Secondary dark royal navy surfaces |
| `color.accent.700` | `#997328` | Deep antique gold for high-contrast text/borders |
| `color.accent.600` | `#C59B3F` | Luminous Swiss gold for primary active states, prices & ratings |
| `color.accent.500` | `#DFB75C` | Brushed champagne gold for borders and highlights |
| `color.accent.200` | `#FAECD0` | Soft warm champagne badge backgrounds |
| `color.accent.100` | `#FEF8EC` | Subtle gold shimmer tint |
| `color.surface.0` | `#FFFFFF` | Cards, inputs, modal surfaces |
| `color.surface.50` | `#F5F7FB` | Main crisp cool alabaster background |
| `color.surface.100` | `#EBF0F7` | Secondary sections and subtle contrast blocks |
| `color.surface.200` | `#DCE4EF` | Dividers and subtle borders |
| `color.text.primary` | `#0D1527` | Primary midnight charcoal text |
| `color.text.secondary` | `#4A5568` | Slate blue descriptions and secondary information |
| `color.text.muted` | `#718096` | Helper text, quiet metadata, and SKU tags |
| `color.text.inverse` | `#FFFFFF` | Text on dark navy surfaces |
| `color.border.default` | `#E1E7F0` | Standard borders |
| `color.border.strong` | `#C3CFDF` | Emphasized borders |
| `color.state.success` | `#1E6B48` | Success feedback |
| `color.state.success-bg` | `#EAF6F0` | Success background |
| `color.state.warning` | `#9E6716` | Warning feedback |
| `color.state.warning-bg` | `#FEF7EA` | Warning background |
| `color.state.error` | `#B8281E` | Errors and destructive actions |
| `color.state.error-bg` | `#FDEEED` | Error background |
| `color.state.info` | `#1B4965` | Informational feedback |
| `color.state.info-bg` | `#E8F1F5` | Information background |

### Color enforcement
1. Default page background: `surface.50`.
2. Product cards, forms, and elevated content: `surface.0`.
3. Primary text: `text.primary`; avoid pure black as the default.
4. Bronze is a restrained accent, not a dominant second brand color.
5. Primary CTA: `brand.950` with white text.
6. Use `accent.700` for text on light surfaces when bronze is needed; check actual contrast.
7. State colors are functional and must not be used decoratively.
8. Do not add new colors without updating this document and the central tokens.

## 3. Typography System

### 3.1 Font families
- **UI/body:** Inter, with Arial/system sans-serif fallback.
- **Editorial display:** DM Serif Display, with Georgia/serif fallback.

Load fonts locally or from an approved font provider. Ensure fallback fonts do not cause broken layouts.

### 3.2 Typography tokens

| Token | Font | Size / line height | Weight | Usage |
|---|---|---:|---:|---|
| `type.display-xl` | DM Serif Display | 64px / 1.05 | 400 | Wide-screen home hero |
| `type.display-lg` | DM Serif Display | 52px / 1.08 | 400 | Major campaign headline |
| `type.display-md` | DM Serif Display | 40px / 1.12 | 400 | Editorial section headline |
| `type.heading-xl` | Inter | 36px / 1.2 | 600 | Main page heading |
| `type.heading-lg` | Inter | 30px / 1.25 | 600 | Major section heading |
| `type.heading-md` | Inter | 24px / 1.3 | 600 | Subsection heading |
| `type.heading-sm` | Inter | 20px / 1.35 | 600 | Small section heading |
| `type.title` | Inter | 16px / 1.4 | 600 | Product/component title |
| `type.body-lg` | Inter | 18px / 1.65 | 400 | Introductory copy |
| `type.body` | Inter | 16px / 1.6 | 400 | Default body |
| `type.body-sm` | Inter | 14px / 1.5 | 400 | Secondary descriptions |
| `type.caption` | Inter | 12px / 1.45 | 400 | Metadata/helper text |
| `type.label` | Inter | 12px / 1.4 | 600 | Form labels and compact labels |
| `type.button` | Inter | 14px / 1.2 | 600 | Button labels |
| `type.price` | Inter | 16px / 1.4 | 600 | Product price |
| `type.price-lg` | Inter | 24px / 1.3 | 600 | Product detail price |

### Typography rules
- Use DM Serif Display for selected editorial headlines, not every heading.
- Use Inter for navigation, product titles, prices, filters, forms, buttons, specifications, and body text.
- Use sentence case by default.
- Use uppercase only for short overlines/metadata and with modest tracking.
- Do not add more font families without approval.
- Body copy should normally be at least 14px.
- Scale display sizes responsively on mobile.
- Use consistent currency formatting and tabular numerals where supported.

## 4. Spacing Scale

Use an 8-point rhythm with 4px half-steps for compact alignment. Do not invent arbitrary spacing values.

| Token | Value | Typical usage |
|---|---:|---|
| `space.0` | 0px | Reset |
| `space.1` | 4px | Icon/text micro-gap |
| `space.2` | 8px | Tight control spacing |
| `space.3` | 12px | Compact field/card spacing |
| `space.4` | 16px | Default component gap |
| `space.5` | 24px | Card padding and related groups |
| `space.6` | 32px | Between content groups |
| `space.7` | 40px | Compact section spacing |
| `space.8` | 48px | Standard mobile section spacing |
| `space.9` | 64px | Standard desktop section spacing |
| `space.10` | 80px | Large editorial section spacing |
| `space.11` | 96px | Hero/major section separation |
| `space.12` | 120px | Rare wide-screen editorial separation |

### Spacing rules
- Desktop section padding: usually 64–96px vertically.
- Mobile section padding: usually 40–56px vertically.
- Standard card padding: 16–24px, depending on density.
- Form field vertical gaps: 16px.
- Label-to-input gap: 8px.
- Heading-to-description gap: 12–16px.
- Description-to-CTA gap: 24px.
- Product grid gap: 24px desktop and 16px mobile.
- Dense checkout/admin screens may be more compact, but must still use these tokens.
- If a new spacing value is needed, update the system first and reuse it consistently.

## 5. Layout and Grid

### Container widths
- `container.max`: 1440px.
- `container.standard`: 1280px.
- `container.reading`: 760px.
- `container.form`: 560px.
- Horizontal page gutter: 24px desktop, 20px tablet, 16px mobile.

Center content within its container. Do not stretch paragraphs or product grids across the entire screen without a clear reason.

### Grid rules
- Wide desktop product grid: 4 columns.
- Small desktop/large tablet: 3 columns.
- Tablet: 2–3 columns depending on available width.
- Mobile product grid: 2 columns by default, unless the product/accessible layout needs one.
- Homepage category cards: 4 across desktop, 2 across mobile.
- Product cards in a row must align consistently.
- Use approved spacing tokens for row and column gaps.

### Responsive breakpoints
- `breakpoint.sm`: 480px.
- `breakpoint.md`: 768px.
- `breakpoint.lg`: 1024px.
- `breakpoint.xl`: 1280px.
- `breakpoint.2xl`: 1440px.

Prefer content-driven responsive behavior over exact-device assumptions. Add breakpoints only when the layout requires them.

## 6. Radius, Borders, and Shadows

### Radius tokens
| Token | Value | Usage |
|---|---:|---|
| `radius.xs` | 4px | Small tags/compact controls |
| `radius.sm` | 6px | Buttons and inputs |
| `radius.md` | 10px | Product cards, dropdowns, popovers |
| `radius.lg` | 16px | Feature panels and modals |
| `radius.xl` | 24px | Rare campaign blocks |
| `radius.pill` | 999px | Pills only where semantically appropriate |

Buttons use 6px radius by default. Avoid making every element pill-shaped. Use one consistent product card treatment.

### Borders
- Standard: `1px solid color.border.default`.
- Emphasized: `1px solid color.border.strong`.
- Dividers: `1px solid color.surface.200`.
- Avoid thick decorative outlines.
- Prefer borders and whitespace over shadows for most cards.

### Shadows
- `shadow.none`: none.
- `shadow.card`: `0 2px 8px rgba(32, 37, 34, 0.05)`.
- `shadow.popover`: `0 8px 24px rgba(32, 37, 34, 0.12)`.
- `shadow.modal`: `0 16px 48px rgba(32, 37, 34, 0.18)`.

Use shadows sparingly. Default product cards should rely on photography and spacing rather than heavy elevation.

## 7. Iconography

- Use one consistent icon set, preferably Lucide outline icons.
- Standard sizes: 16px, 20px, and 24px.
- Use 20px for common header actions and 16px for compact controls.
- Icon-only controls need accessible names and visible focus states.
- Do not mix emoji, filled icons, and unrelated icon families.
- Avoid decorative icons that do not improve comprehension.

## 8. Photography and Image Rules

### Art direction
- Product photography is the primary visual asset.
- Keep product identity accurate: do not alter the watch face, hands, dial markings, logo, strap, color, finish, or shape.
- Use consistent lighting and color treatment within a collection.
- Lifestyle photography may be atmospheric; product images must remain faithful to the actual item.

### Image ratios
- Product listing cards: 4:5.
- Product gallery primary image: 4:5.
- Homepage hero: 16:9 or wide editorial crop.
- Category campaign banners: 16:7 or 3:1 according to layout.
- Category tiles: 4:5.
- Editorial imagery: 3:2 or 4:3.

### Image behavior
- Use `object-fit: contain` for isolated product images on neutral backgrounds.
- Use `object-fit: cover` for lifestyle and campaign photography.
- Reserve image-frame dimensions to prevent layout shift.
- Never crop out the watch face, strap, or other key product details in listing cards.
- Use meaningful alt text for informative images and empty alt text for decorative images.
- Avoid reusing one hero image across every category unless intentionally part of a campaign.

## 9. Reusable Component Library

All pages must use shared components. Build each once and configure it through props/data rather than duplicating styles.

### 9.1 Announcement bar
- Content-driven height, normally 32–40px.
- Background: `color.brand.950`.
- Text: `color.text.inverse`.
- Short message and optional link.
- Admin-controlled visibility and scheduling.
- Hide or update expired promotions.

### 9.2 Header
- Desktop height: approximately 80px.
- Mobile height: approximately 64px.
- Logo, primary navigation, search, account, wishlist, and cart.
- Sticky behavior only if useful and not overly space-consuming.
- Same header system across storefront pages.
- Checkout may use a simplified version while preserving the same tokens and brand.

### 9.3 Navigation and mega menu
- Consistent typography and hover/focus behavior.
- Keyboard accessible.
- Valid category links.
- No oversized decorative menu cards.

### 9.4 Buttons
**Primary**
- Background: `color.brand.950`.
- Text: white.
- Height: 48px standard, 44px compact, 52px large.
- Radius: 6px.
- Font: `type.button`.
- Hover: `color.brand.800`.
- Visible keyboard focus.

**Secondary**
- Transparent or white background.
- Text: `color.brand.950`.
- Border: `color.border.strong`.
- Same heights and radius as primary.

**Tertiary/text**
- No filled background.
- Used for low-emphasis actions.
- Includes hover and focus states.

**Destructive**
- Use `color.state.error` for destructive actions.
- Confirm irreversible operations where appropriate.

Button rules:
- Use clear action labels.
- Avoid multiple competing primary buttons in a small area.
- Define loading, disabled, success, and error states.
- Every button must perform its advertised action.

### 9.5 Links
- Clear contrast and hover/focus state.
- Inline text links may be underlined when it improves clarity.
- Do not style navigation actions as buttons unnecessarily.

### 9.6 Product card
- Image ratio: 4:5.
- Product image on white or approved neutral surface.
- Title: `type.title`, with consistent line clamping.
- Price: `type.price`.
- Original price and discount are secondary.
- Wishlist action in a consistent image-corner position.
- Optional color swatches.
- Subtle hover behavior only.
- Quick Add only when variant selection is clear.
- Accurate availability.
- Identical internal spacing for cards in the same grid.

### 9.7 Product grid
- Shared responsive grid and spacing.
- Consistent alignment for product count and sort.
- Filter drawer on small screens.
- Empty state with clear filter reset.
- Skeletons match final card dimensions.

### 9.8 Filters
- Desktop: sidebar or popover based on catalog complexity.
- Mobile: drawer with Apply and Clear actions.
- Selected filters must be visible and removable.
- Checkboxes for multi-select; radio/select for single-select.
- Only show filters supported by actual catalog data.

### 9.9 Sort dropdown
- Shared select/dropdown style.
- Options: Recommended, Newest, Price low-to-high, Price high-to-low.
- Best-selling and Top-rated only if supported by reliable sales/review data.
- Preserve selection when filters change.

### 9.10 Search
- Shared search field and results behavior.
- Accessible suggestions.
- Clear input and no-results states.
- Search relevant product names, SKUs, categories, and attributes.

### 9.11 Form fields
- Label above input.
- Standard height: 48px; compact: 44px.
- Border: `color.border.default`.
- Radius: 6px.
- Visible focus outline.
- Helper/error text directly below.
- Placeholder must not be the only label.
- Inline validation; preserve valid input after errors.

### 9.12 Badges/status labels
- Compact and restrained.
- Default background: `surface.100`; text: `text.primary`.
- Use semantic state colors only for meaningful states.
- Do not create a badge for every attribute.

### 9.13 Breadcrumbs
- Small, subdued text.
- Current page is not a link.
- Wrap cleanly on mobile.
- Add structured data where appropriate.

### 9.14 Section heading
- Optional overline.
- One clear heading.
- Optional short description.
- Optional single CTA.
- Consistent spacing between heading, description, and content.

### 9.15 Hero/campaign banner
- Reusable image, headline, body, and CTA slots.
- Desktop/mobile image crops defined.
- Verify text contrast over imagery.
- Constrain text width.
- Do not embed text in images unless a campaign asset specifically requires it.
- Page-specific content is allowed; new style rules are not.

### 9.16 Product gallery
- Main image with consistent frame and zoom behavior.
- Thumbnail selection state.
- Variant changes update the correct image.
- Keyboard and touch interactions.
- Keep the entire product visible.

### 9.17 Price display
- Selling price is most prominent.
- Original/MRP is secondary and struck through only when accurate.
- Discounts calculated from real values.
- Consistent currency format.
- No false discounts.

### 9.18 Cart line item
- Thumbnail, product name, selected variant, price, quantity, and remove action.
- Responsive without horizontal overflow.
- Quantity changes validate stock and totals.

### 9.19 Cart summary
- Subtotal, discounts, shipping, taxes, and total as applicable.
- Clear checkout CTA.
- Disclose applicable fees at the appropriate point in the journey.

### 9.20 Modal/drawer
- Shared overlay, spacing, close button, and focus management.
- Escape/outside-click behavior where appropriate.
- Restore focus after closing.
- Keep dialogs focused and reasonably sized.

### 9.21 Toast/alert
- Success, error, warning, and information variants.
- Short actionable messages.
- Do not communicate state by color alone.
- Important errors remain visible until understood or corrected.

### 9.22 Accordion/FAQ
- Consistent title and disclosure icon.
- Accessible keyboard interaction.
- Choose a consistent open-panel behavior within each context.

### 9.23 Review card
- Genuine review data only.
- Consistent rating display.
- Verified-purchase labels only when supported by data.
- No fabricated reviews.

### 9.24 Footer
- Shared across public storefront pages.
- Consistent columns, typography, link states, newsletter, social and legal links.
- Compact stacked groups or accordions on mobile.

### 9.25 Pagination / Load More
- Consistent button and active-page styling.
- Preserve filters and sorting.
- Accessible labels.

### 9.26 Skeleton, empty, and error states
- Skeletons match final dimensions.
- Empty states explain the situation and offer a next action.
- Errors are clear and never expose technical stack traces.
- Provide retry where useful.

## 10. Page Composition Rules

Pages may vary in content, imagery, and section order but must not create new design systems.

### Home
Recommended order:
1. Hero.
2. Shop by category.
3. Featured products.
4. New arrivals.
5. Brand story.
6. Shop by style.
7. Offers.
8. Trust and genuine reviews.
9. Social gallery/newsletter.
10. Footer.

Avoid stacking full-width banners without clear hierarchy.

### Men's, Women's, Kids & Youth, Unisex & Minimalist
- Use one shared collection template.
- Vary heading, copy, imagery, and relevant filters.
- Reuse product cards, grid, sorting, filters, breadcrumbs, and pagination.
- Do not assign a new color palette to each category.

### New Arrivals
- Shared collection template.
- Newest-first default or configured launch rules.
- Show a “New” label only while the product qualifies.

### Offers
- Shared collection template and price components.
- Approved campaign treatment only.
- Accurate prices, terms, and expiry dates.

### About Us
- Editorial typography and imagery.
- Same spacing scale, containers, buttons, and footer.
- Long-form text uses the reading container.

### Product details
- Desktop: balanced gallery and purchase panel columns.
- Mobile order: gallery, title, price, variants, purchase actions, delivery, specifications, reviews, recommendations.
- Reuse price, button, variant, accordion, and review components.

### Cart and checkout
- Prioritize clarity and task completion.
- Simplified header where useful.
- Same form, button, alert, price, and spacing tokens.
- Avoid promotional interruptions that obstruct checkout.

### Customer account
- Shared forms, alerts, typography, and spacing.
- Clear order status and next actions.
- Protect customer data.

### Admin
- Same tokens and type system, with denser information layout.
- Tables, sidebar, filters, and admin-specific components may be added.
- Do not create an unrelated color system.
- Prioritize data density, accessibility, status clarity, and safe destructive actions.

## 11. Motion and Interaction

### Timing tokens
- `motion.fast`: 120ms.
- `motion.standard`: 180ms.
- `motion.slow`: 260ms.
- Standard easing: `cubic-bezier(0.2, 0, 0, 1)`.

Rules:
- Buttons/links: subtle color or opacity changes.
- Product hover: alternate image or subtle scale, not exaggerated movement.
- Drawers/modals: short transitions.
- No constant floating, pulsing, parallax, or scroll animation by default.
- Respect `prefers-reduced-motion`.
- Motion must not delay navigation, cart actions, or checkout.

## 12. Accessibility

Target WCAG 2.2 AA.
- Adequate text/background contrast.
- Visible keyboard focus.
- Semantic heading hierarchy.
- Labels for every input.
- Accessible names for icon-only controls.
- Keyboard-operable menus, dialogs, filters, and galleries.
- Never use color as the only status signal.
- Meaningful alt text for product imagery.
- Reduced-motion support.
- Touch targets approximately 44 × 44px where practical.
- Errors identify the field and explain correction.

## 13. Central Design Tokens — CSS Example

Create a single central token source. Add the remaining type sizes, line heights, shadows, breakpoints, and component tokens in the same theme/configuration.

```css
:root {
  /* Brand */
  --color-brand-950: #202522;
  --color-brand-900: #292f2b;
  --color-brand-800: #39423b;
  --color-accent-700: #856744;
  --color-accent-600: #9a7950;
  --color-accent-200: #e5d7c2;

  /* Surfaces */
  --color-surface-0: #ffffff;
  --color-surface-50: #f8f6f1;
  --color-surface-100: #f0ede6;
  --color-surface-200: #e5e0d6;

  /* Text and borders */
  --color-text-primary: #242824;
  --color-text-secondary: #626760;
  --color-text-muted: #777d75;
  --color-text-inverse: #ffffff;
  --color-border-default: #ddd9d0;
  --color-border-strong: #beb8ac;

  /* Feedback */
  --color-success: #2f6b4f;
  --color-success-bg: #eaf3ed;
  --color-warning: #8a5b19;
  --color-warning-bg: #fff3db;
  --color-error: #b42318;
  --color-error-bg: #fdecea;
  --color-info: #285f83;
  --color-info-bg: #eaf3f8;

  /* Typography */
  --font-ui: "Inter", Arial, sans-serif;
  --font-display: "DM Serif Display", Georgia, serif;

  /* Spacing */
  --space-1: 4px;
  --space-2: 8px;
  --space-3: 12px;
  --space-4: 16px;
  --space-5: 24px;
  --space-6: 32px;
  --space-7: 40px;
  --space-8: 48px;
  --space-9: 64px;
  --space-10: 80px;
  --space-11: 96px;
  --space-12: 120px;

  /* Radius */
  --radius-xs: 4px;
  --radius-sm: 6px;
  --radius-md: 10px;
  --radius-lg: 16px;
  --radius-xl: 24px;
  --radius-pill: 999px;

  /* Layout */
  --container-standard: 1280px;
  --container-max: 1440px;
  --container-reading: 760px;
  --container-form: 560px;
  --page-gutter-desktop: 24px;
  --page-gutter-tablet: 20px;
  --page-gutter-mobile: 16px;

  /* Motion */
  --motion-fast: 120ms;
  --motion-standard: 180ms;
  --motion-slow: 260ms;
  --ease-standard: cubic-bezier(0.2, 0, 0, 1);
}
```

## 14. Design Drift Prevention Rules

Mandatory:
1. All tokens live in one central theme/token file.
2. Common UI is implemented as shared components.
3. No arbitrary hex colors in page-level styles.
4. No arbitrary spacing values.
5. No ad hoc font families or font sizes.
6. Check for an existing component before creating another.
7. Page differences come from content and composition, not a new visual language.
8. Shared interaction states are defined once.
9. Every component works on mobile, tablet, and desktop.
10. Accessibility is part of component implementation, not a later optional task.
11. Use real product data and genuine reviews.
12. Compare new screens against existing screens before declaring them complete.
13. Approved token changes are made centrally, then propagated across the website.
14. Avoid one-off CSS overrides that silently break shared rules.
15. Reuse templates for category, product, account, and checkout screens.

### Definition of done for each screen
- Uses the approved tokens.
- Uses shared components wherever applicable.
- Has consistent typography and spacing.
- Works on mobile, tablet, and desktop.
- Includes relevant loading, empty, error, disabled, and focus states.
- Has accessible labels and keyboard behavior.
- Has no broken production links.
- Uses accurate product data and functional actions.
- Has been checked against another page using the same components.

## 15. Suggested Code Organization

Adapt this structure to the project's existing framework; do not force a rewrite solely to match these names.

```text
src/
├── design-system/
│   ├── tokens.css
│   ├── typography.css
│   ├── breakpoints.css
│   ├── motion.css
│   └── README.md
├── components/
│   ├── layout/
│   │   ├── AnnouncementBar
│   │   ├── Header
│   │   ├── MegaMenu
│   │   └── Footer
│   ├── product/
│   │   ├── ProductCard
│   │   ├── ProductGrid
│   │   ├── ProductGallery
│   │   ├── PriceDisplay
│   │   └── VariantSelector
│   ├── commerce/
│   │   ├── CartItem
│   │   ├── CartSummary
│   │   └── CheckoutForm
│   ├── forms/
│   ├── feedback/
│   ├── navigation/
│   └── ui/
├── layouts/
├── pages/
└── ...
```

---

# Part II — Instructions for Antigravity IDE

## 16. How Antigravity Must Use This Document

Treat `design.md` as the authoritative design-system contract for the watch e-commerce website.

### Required workflow
1. Inspect the existing repository, framework, folder structure, styles, components, and current routes before changing files.
2. Read `map.md` if present. Use it as the website sitemap and functional page-scope reference. Use this `design.md` for the visual system.
3. Identify existing styles/components that can be reused. Do not replace a working stack or rewrite unrelated features without a clear reason.
4. Create the central design tokens and typography rules first.
5. Build or refactor the shared UI primitives and storefront components.
6. Build shared layouts and templates before individual page content.
7. Apply the same tokens/components to all eight main pages and supporting shopping/account/admin screens.
8. Test responsive behavior and interaction states.
9. Run the project's existing lint, type-check, tests, and build commands where available.
10. Report what changed, what was tested, and any remaining gaps.

### Implementation constraints
- Preserve the existing framework, routing approach, backend, database, and integrations unless a change is required and approved.
- Do not delete existing content, routes, product data, or features merely to simplify the UI.
- Do not replace functional pages with static mockups.
- Do not fabricate product inventory, prices, ratings, reviews, shipping promises, warranty terms, or business claims.
- Use existing product data where available. If data is missing, use clearly labeled development placeholders only in non-production/demo contexts.
- Keep secrets and credentials out of source code.
- Keep accessibility, security, SEO, performance, and mobile usability intact.
- Do not claim that tests passed unless they were actually run and passed.
- If `map.md` is not present, continue with the design-system implementation and report that the sitemap file was unavailable; do not invent unseen project requirements.

### Architecture rules
- Store design tokens centrally.
- Use reusable components with clear, consistent props.
- Avoid duplicated page-specific CSS.
- Prefer semantic HTML and the existing project's established patterns.
- Use a consistent icon library.
- Avoid unnecessary new dependencies; justify any added dependency.
- Use data-driven product cards and category templates.
- Keep admin UI on the same design tokens while allowing denser data layouts.
- Keep component variants explicit (for example, primary/secondary button) rather than arbitrary custom styling.

### Visual QA requirements
Test at minimum:
- Mobile: 375px and 390px widths.
- Tablet: 768px width.
- Desktop: 1280px width.
- Wide desktop: 1440px width.

Check:
- Horizontal overflow.
- Text clipping and awkward wrapping.
- Header/menu behavior.
- Product grid alignment and image consistency.
- Form labels, validation, and focus states.
- Dialog/drawer behavior and keyboard access.
- Cart and checkout layout.
- Empty/loading/error states.
- Color contrast.
- Consistency of spacing, radii, typography, and component styles across pages.

---

# Part III — Copy-Paste Prompt for Antigravity IDE

Use the following prompt in Antigravity IDE after placing `design.md` in the project root. If `map.md` exists, keep it in the root too.

## 17. Antigravity Implementation Prompt

```text
TASK: IMPLEMENT AND ENFORCE THE LOCKED DESIGN SYSTEM FOR THE WATCH E-COMMERCE WEBSITE.

AUTHORITATIVE DOCUMENTS
1. Read ./design.md completely. It is the single source of truth for the design tokens, typography, spacing, layout, components, responsive rules, interaction states, accessibility, and design drift prevention.
2. Read ./map.md if present. It defines the website sitemap and page requirements.
3. Inspect the existing project before editing. The existing framework, routes, backend, database, product data, integrations, and working features must be preserved unless a change is necessary and justified.

PRIMARY OBJECTIVE
Create one coherent, premium, modern editorial-luxury visual system and make every relevant screen inherit it. The result must feel like one intentionally designed watch brand, not a set of independently generated pages.

LOCKED VISUAL SYSTEM
- Main background: warm ivory #F8F6F1.
- Card/form surface: white #FFFFFF.
- Primary brand surface and primary CTA: charcoal green #202522.
- Secondary dark: #292F2B and #39423B.
- Restrained champagne-bronze accent: #9A7950; use #856744 for accent text where appropriate.
- Main text: #242824; secondary text: #626760.
- Borders: #DDD9D0 and #BEB8AC.
- UI/body font: Inter.
- Editorial display font: DM Serif Display.
- Spacing: use the approved 4px/8px-based scale from design.md.
- Standard button/input height: 48px.
- Button/input radius: 6px.
- Standard product-card radius: 10px, unless the existing approved component uses a consistent alternative.
- Product listing image ratio: 4:5.
- Maximum content width: 1440px.
- Desktop section spacing: normally 64–96px.
- Mobile section spacing: normally 40–56px.
- Use one consistent outline icon family, preferably Lucide.
- Motion must be restrained and respect reduced-motion preferences.

PHASE 1 — AUDIT
1. Inspect the current folder structure, framework, routing, existing CSS/theme system, UI library, components, page templates, product data, and scripts.
2. Identify duplicate styles, arbitrary color values, inconsistent spacing, repeated components, and responsive/accessibility issues.
3. Make a concise implementation plan based on the real codebase.
4. Do not stop after producing the plan; proceed with implementation unless a genuinely blocking ambiguity or destructive decision requires approval.

PHASE 2 — CENTRAL TOKENS
1. Create or update a central design-system directory using the project's conventions.
2. Define all color, typography, spacing, radius, shadow, layout, breakpoint, and motion tokens from design.md.
3. Create a consistent global typography/reset layer.
4. Make the tokens the single source of truth.
5. Avoid duplicated literal values in individual pages.
6. If the project uses Tailwind, CSS modules, Sass, styled components, or another system, integrate the tokens into that existing system instead of introducing a competing system.

PHASE 3 — SHARED COMPONENTS
Implement or refactor reusable components according to design.md:
- Announcement bar.
- Storefront header and responsive navigation/mega menu.
- Footer.
- Buttons and links.
- Product card and product grid.
- Product price display.
- Product gallery and variant selector.
- Filters and sorting.
- Search input and search states.
- Form fields and validation states.
- Badges and status labels.
- Breadcrumbs.
- Section heading and hero/campaign banner.
- Cart line item and cart summary.
- Modal and drawer.
- Toasts and alerts.
- FAQ accordion.
- Review card.
- Pagination/load more.
- Skeleton, empty, loading, and error states.

Build each component once and reuse it. Use explicit variants and consistent props. Avoid copy-pasting component markup and CSS across pages.

PHASE 4 — PAGE TEMPLATES
Use shared templates rather than creating independent designs:
- Home page: compose the shared hero, category cards, product grids, editorial sections, offers, trust/review sections, and footer.
- Men's, Women's, Kids & Youth, and Unisex & Minimalist: use one shared collection listing template with relevant content and filters.
- New Arrivals: use the shared collection template with a valid new-arrival rule.
- Offers: use the shared collection template with accurate active promotions and prices.
- About Us: use the editorial content layout and shared type/spacing/buttons.
- Product details: use shared gallery, price, variant, CTA, specification, accordion, review, and related-product components.
- Cart, checkout, customer account, support pages, and admin screens: use the same tokens and applicable shared components.
- The admin dashboard may use denser tables and navigation, but must not introduce a separate arbitrary color/typography system.

PHASE 5 — RESPONSIVE AND ACCESSIBLE BEHAVIOR
- Test mobile widths of 375px and 390px, tablet at 768px, desktop at 1280px, and wide desktop at 1440px.
- Ensure no horizontal overflow, clipped text, or broken image frames.
- Use a 4-column product grid on wide desktop, 3 columns on smaller desktop, and normally 2 columns on mobile.
- Make navigation, mega menus, filters, drawers, dialogs, galleries, and forms keyboard accessible.
- Add visible focus states and accessible labels.
- Use semantic headings and meaningful image alt text.
- Respect prefers-reduced-motion.
- Target WCAG 2.2 AA contrast and interaction accessibility.
- Do not communicate status by color alone.

PHASE 6 — FUNCTIONAL INTEGRITY
- Preserve existing functionality and real product data.
- Ensure links and buttons perform the actions they advertise.
- Do not replace functional commerce behavior with visual-only mockups.
- Do not fabricate prices, reviews, ratings, inventory, warranties, or delivery claims.
- Ensure product variants, filters, sorting, cart, and checkout continue to use the real data flow.
- Keep payment secrets and private configuration out of source control.
- Do not weaken authentication, authorization, validation, or payment verification.
- Do not change database schemas or payment integrations without checking existing dependencies and migration requirements.

PHASE 7 — CLEANUP AND QA
1. Run existing lint, type-check, test, and build commands where available.
2. Fix errors caused by your changes.
3. Inspect the rendered pages at all specified viewport sizes if browser/preview tooling is available.
4. Compare the same component across at least three different pages to verify consistency.
5. Search for stray hard-coded colors, arbitrary spacing, duplicate button/product-card styles, and unapproved fonts.
6. Verify loading, empty, error, disabled, hover, active, and focus states where relevant.
7. Do not claim tests passed unless they were actually executed successfully.

DELIVERABLES
- A central token/theme implementation.
- Shared typography and responsive rules.
- Reusable UI components.
- Updated pages using shared components.
- A short design-system README or equivalent documentation explaining where tokens and components live.
- A concise report listing files changed, pages/components updated, tests actually run, and remaining issues.

NON-NEGOTIABLE DESIGN DRIFT RULE
Do not invent page-specific colors, fonts, spacing, radii, shadows, or button styles. If a new visual token is genuinely necessary, first add it to design.md and the central token source, then reuse it consistently. Page variation should come from content, imagery, product data, and section composition—not from a new visual language.

Start by inspecting the project, then implement the system in small, verifiable steps. Preserve working features and do not perform destructive rewrites.
```

---

# Part IV — Acceptance Checklist

## 18. Design-system acceptance checklist

Mark each item only after verifying it.

- [ ] Central design tokens exist and are used throughout the project.
- [ ] Typography uses only Inter and DM Serif Display, with documented fallbacks.
- [ ] Color palette matches this document.
- [ ] Spacing uses the approved scale.
- [ ] Header, footer, buttons, forms, cards, filters, and alerts are reusable.
- [ ] All four primary watch category pages use the same collection template.
- [ ] New Arrivals and Offers reuse the collection template.
- [ ] Product details use a reusable template.
- [ ] Cart, checkout, account, and admin use the same design tokens.
- [ ] Product image ratios and crops are consistent.
- [ ] No arbitrary page-level color or typography systems remain.
- [ ] Mobile/tablet/desktop layouts have been inspected.
- [ ] Keyboard focus and accessible labels are present.
- [ ] Loading, empty, error, and disabled states are implemented where applicable.
- [ ] Existing business logic and data have been preserved.
- [ ] Tests/build checks were run and results documented accurately.
- [ ] Remaining gaps are listed explicitly.

## 19. Final contract

Treat this file as the locked design-system specification for the watch e-commerce website. All screens must inherit the same colors, typography, spacing, radii, shadows, grid rules, iconography, and component behavior.

Do not redesign the system while implementing individual pages. Build screens by composing approved components and supplying page-specific content, images, product data, and section order. Update the central system first whenever a design change is approved.

The intended result is a coherent, recognizable, premium storefront whose pages feel like one professionally designed brand—not independently generated interfaces.
