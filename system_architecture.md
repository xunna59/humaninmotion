# HUMAN IN MOTION — PREMIUM FASHION ECOMMERCE PLATFORM

## ROLE

You are a senior full-stack ecommerce engineer, UI/UX designer, product architect, fashion-commerce specialist, and technical lead.

You are responsible for designing and building a complete, production-ready ecommerce platform for:

**Human In Motion**

Human In Motion is a high-end fashion and apparel brand based in the United Kingdom.

The platform must feel like a serious, established, premium fashion retailer rather than a generic ecommerce template.

The visual and UX benchmark is:

https://www.boohooman.com/

Use BoohooMAN as a **design and ecommerce UX reference**, particularly for:

- overall ecommerce structure
- fashion-oriented visual hierarchy
- navigation
- promotional bars
- category navigation
- product discovery
- product grids
- product cards
- filtering and sorting
- collection pages
- product detail pages
- shopping bag
- checkout flow
- account area
- responsive behaviour
- mobile shopping experience
- merchandising sections
- editorial fashion presentation

DO NOT copy BoohooMAN's branding, logo, text, imagery, source code, proprietary assets, or exact design.

Human In Motion must have its own premium visual identity.

The finished website should communicate:

**Luxury. Confidence. Movement. Modern British fashion. Exclusivity. Culture. Quality.**

---

# 1. PRIMARY OBJECTIVE

Build a complete ecommerce platform where customers can:

1. Discover Human In Motion collections.
2. Browse products by category.
3. Browse products by collection.
4. Browse new arrivals.
5. Browse best sellers.
6. Browse sale items.
7. Search for products.
8. Filter products.
9. Sort products.
10. View detailed product information.
11. Select size and colour.
12. Add products to bag.
13. Update bag quantities.
14. Save products to wishlist.
15. Create an account.
16. Log in.
17. Manage their profile.
18. Manage saved addresses.
19. View order history.
20. Track orders.
21. Checkout securely.
22. Make payments.
23. Receive order confirmation.
24. Receive transactional emails.
25. Subscribe to marketing/newsletter communications.

The system must also provide a powerful administration platform where Human In Motion staff can manage:

- products
- categories
- collections
- inventory
- orders
- customers
- discounts
- coupons
- promotions
- banners
- homepage sections
- product images
- sizes
- colours
- shipping
- payments
- returns
- reviews
- newsletters
- analytics
- content

---

# 2. DESIGN DIRECTION

The site should feel inspired by contemporary UK fashion ecommerce.

Reference:

https://www.boohooman.com/

However, Human In Motion should feel **more premium, refined and editorial**.

Do NOT make the website look like a cheap fast-fashion marketplace.

The target visual impression should be:

- premium
- minimalist
- editorial
- bold
- fashion-forward
- sophisticated
- masculine/unisex depending on the actual collection
- modern
- confident
- high contrast
- image-led
- spacious where appropriate
- highly polished

Think:

**Luxury fashion editorial + modern UK ecommerce + high-conversion shopping experience.**

---

# 3. BRAND VISUAL SYSTEM

Create a flexible design system for Human In Motion.

Do not hard-code styling independently throughout the application.

Create reusable:

- typography system
- colour tokens
- spacing system
- border radius system
- shadows
- buttons
- badges
- cards
- forms
- modals
- drawers
- navigation
- product components
- notification components
- loading states
- empty states

The system must be easy to modify later.

## Colour Direction

Use a predominantly premium neutral palette.

Suggested foundation:

- Deep Black
- Soft Black
- White
- Off-white
- Warm Grey
- Charcoal
- Light Grey

Use the Human In Motion brand accent colour sparingly.

Avoid excessive gradients.

Avoid excessive rounded cards.

Avoid generic SaaS styling.

Avoid overly colourful ecommerce UI.

The products and photography should be the primary visual focus.

---

# 4. TYPOGRAPHY

Typography must feel fashion/editorial.

Use a strong modern sans-serif as the primary UI font.

Use a refined display font where appropriate for:

- campaign headlines
- editorial sections
- collection titles
- major promotional messaging

Maintain excellent readability.

Typography hierarchy should clearly distinguish:

- announcement
- navigation
- campaign headline
- collection heading
- product title
- product price
- promotional price
- body copy
- metadata
- buttons
- labels

Do not overuse oversized typography.

---

# 5. GLOBAL HEADER

Create a sophisticated ecommerce header inspired by modern fashion retailers.

Desktop structure:

### Top announcement bar

Example:

"FREE UK DELIVERY OVER £100"

This content must be manageable from the admin panel.

### Main header

Left:

- Human In Motion logo

Center/left:

- SHOP
- NEW IN
- COLLECTIONS
- CLOTHING
- ACCESSORIES
- SALE

Right:

- Search
- Account
- Wishlist
- Shopping Bag

The exact navigation categories must be configurable from the admin panel.

---

# 6. MEGA MENU

Implement a sophisticated desktop mega menu.

Hovering/clicking a main category should reveal:

### SHOP

- New In
- Best Sellers
- Clothing
- Accessories
- Collections
- Sale

### CLOTHING

- T-Shirts
- Shirts
- Hoodies
- Sweatshirts
- Knitwear
- Jackets
- Coats
- Trousers
- Jeans
- Shorts
- Tracksuits
- Sets

### COLLECTIONS

Dynamic collections managed through admin.

Example:

- Core Collection
- Signature Collection
- Essentials
- Limited Edition
- Seasonal Collection
- New Season

Do not assume these collections are permanent.

The administrator must be able to create, edit, reorder and remove navigation items.

---

# 7. MOBILE HEADER

Mobile navigation must be excellent.

Header:

- hamburger
- Human In Motion logo
- search
- bag

Use a full-height mobile navigation drawer.

Navigation should support expandable nested categories.

Mobile shopping should feel first-class, not like a reduced desktop website.

---

# 8. HOMEPAGE

The homepage is extremely important.

It should feel like a fashion campaign.

## HERO SECTION

Use a full-width/high-impact campaign image or video.

Overlay:

**HUMAN IN MOTION**

Supporting message:

"Designed for those who move differently."

Primary CTA:

**SHOP NEW IN**

Secondary CTA:

**EXPLORE COLLECTION**

Allow administrators to change:

- desktop image
- mobile image
- video
- headline
- description
- buttons
- links
- text position
- overlay strength

---

# 9. HOMEPAGE CONTENT STRUCTURE

Build the homepage using configurable sections.

Suggested order:

### HERO

Large campaign visual.

### NEW ARRIVALS

Horizontal product carousel.

### SHOP THE COLLECTION

Large editorial tiles.

Example:

- CLOTHING
- ESSENTIALS
- ACCESSORIES

### FEATURED COLLECTION

Large image + editorial text.

### BEST SELLERS

Product carousel/grid.

### CAMPAIGN EDITORIAL

Large visual storytelling section.

### SHOP BY CATEGORY

Visual category cards.

### LIMITED EDITION

High-impact promotional section.

### SOCIAL / COMMUNITY

Instagram-style editorial/social section.

### NEWSLETTER

Premium newsletter signup.

Example:

"JOIN HUMAN IN MOTION"

"Be first to discover new collections, exclusive releases and private offers."

---

# 10. PRODUCT LISTING PAGE

Create a premium PLP similar in usability to leading fashion ecommerce websites.

Example:

`/shop`

`/category/t-shirts`

`/category/hoodies`

`/collection/signature`

`/sale`

`/new-in`

---

# 11. PRODUCT GRID

Desktop:

4 products per row where appropriate.

Large desktop:

4–5 products depending on viewport.

Tablet:

3 products.

Mobile:

2 products per row.

Product cards must contain:

- primary image
- secondary hover image
- product name
- price
- sale price
- colour
- available colour swatches
- badges
- wishlist button
- quick add
- quick view where appropriate

Example badges:

- NEW
- BESTSELLER
- LIMITED
- EXCLUSIVE
- SALE
- LOW STOCK

Badges must be configurable.

---

# 12. PRODUCT IMAGE BEHAVIOUR

Fashion photography is critical.

Product images should:

- use consistent aspect ratios
- have clean presentation
- support multiple images
- support hover image switching
- support zoom
- support video where available

Do not distort product images.

Use optimized responsive images.

Implement lazy loading.

Use modern image formats where supported.

---

# 13. PRODUCT FILTERING

Implement a powerful filter system.

Filters should include:

- category
- collection
- size
- colour
- price
- availability
- product type
- fit
- material
- style
- sale
- new arrivals

Filters must dynamically adapt depending on the category.

For example, trousers may expose:

- waist
- leg length
- fit

while T-shirts may expose:

- size
- fit
- colour.

---

# 14. SORTING

Support:

- Featured
- Newest
- Best Selling
- Price Low to High
- Price High to Low
- Recommended

Do not implement fake recommendation logic.

If "Recommended" is used, define the actual ranking logic.

---

# 15. PRODUCT SEARCH

Implement fast site-wide search.

Search should support:

- product name
- SKU
- category
- collection
- tags
- product description

Search UI should show:

- recent searches
- popular searches
- suggested products
- categories
- collections

Example:

User types:

`hoodie`

Display:

**Products**

- Signature Oversized Hoodie
- Essential Heavyweight Hoodie

**Categories**

- Hoodies
- Sweatshirts

---

# 16. PRODUCT DETAIL PAGE

Create a premium fashion product detail experience.

Desktop layout:

LEFT:

Large image gallery.

RIGHT:

- product name
- rating
- price
- sale price if applicable
- payment information
- colour
- colour swatches
- size selector
- size guide
- stock status
- quantity
- Add to Bag
- Wishlist

Below:

- product description
- details
- materials
- fit
- care instructions
- shipping information
- returns information
- reviews
- related products

---

# 17. SIZE SELECTION

Size selection should be visually clear.

Example:

XS
S
M
L
XL
XXL

Unavailable sizes should be disabled.

Do not allow customers to add a product to the cart without selecting a required variant.

Add:

**Size Guide**

as a modal/drawer.

Size charts must be manageable through admin.

---

# 18. COLOUR VARIANTS

Products should support multiple colour variants.

Example:

BLACK
WHITE
CHARCOAL
STONE
NAVY

Use visual colour swatches where possible.

Selecting a colour should update the product images and SKU/variant.

---

# 19. INVENTORY

Inventory must operate at variant level.

Example:

Product:

Human In Motion Essential Hoodie

Variants:

Black / S
Black / M
Black / L
White / S
White / M
White / L

Each variant must have:

- SKU
- stock quantity
- low stock threshold
- availability
- optional barcode

---

# 20. SHOPPING BAG

Build a polished slide-out shopping bag.

When adding a product:

Open a bag drawer.

Show:

- product image
- product name
- selected size
- selected colour
- quantity
- price
- remove
- wishlist/save option

Also display:

- subtotal
- estimated shipping
- promotional discount
- total

CTA:

**CHECKOUT**

Secondary:

**VIEW BAG**

---

# 21. CART UPSELL

Implement intelligent merchandising.

Example:

"COMPLETE YOUR LOOK"

Show:

- matching trousers
- matching hoodie
- matching accessories

Only show products that actually exist and are in stock.

---

# 22. CHECKOUT

Create a clean, distraction-free checkout.

Steps:

1. Contact information
2. Delivery address
3. Shipping method
4. Payment
5. Order review
6. Confirmation

Support guest checkout.

Do not force account creation.

Allow users to optionally create an account after checkout.

---

# 23. PAYMENT

Design the payment system so payment providers can be swapped.

For UK ecommerce, structure the architecture to support appropriate providers such as:

- Stripe
- PayPal
- Apple Pay
- Google Pay
- Klarna or other BNPL provider where commercially appropriate

Do not hard-code payment provider logic into controllers/components.

Use a payment abstraction/service layer.

Never store raw card details.

---

# 24. SHIPPING

Create a configurable shipping engine.

Support:

- UK standard delivery
- UK express delivery
- free delivery thresholds
- international delivery
- shipping zones
- shipping rates
- estimated delivery times

Administrators should be able to configure:

- country
- region
- shipping method
- price
- free shipping threshold
- delivery estimate
- active/inactive

---

# 25. CUSTOMER ACCOUNT

Create:

`/account`

Sections:

- Dashboard
- Orders
- Order details
- Wishlist
- Addresses
- Profile
- Password
- Preferences

Order page:

- order number
- date
- products
- status
- payment status
- delivery status
- tracking
- total

---

# 26. WISHLIST

Allow customers to:

- add product
- remove product
- move product to bag
- see availability
- see price changes

Wishlist should work for authenticated users.

If guest wishlist support is implemented, use local storage/session safely.

---

# 27. PROMOTIONS

Create a flexible promotions system.

Support:

- percentage discounts
- fixed discounts
- product discounts
- category discounts
- collection discounts
- buy X get Y
- minimum order amount
- first-order discounts
- limited-time campaigns
- coupon codes

Example:

`WELCOME10`

10% off first order.

The system must validate:

- expiry
- usage limit
- customer eligibility
- minimum spend
- applicable products
- applicable categories

---

# 28. SALE SYSTEM

Create a dedicated sale experience.

Example:

**SALE**

- Sale Clothing
- Sale T-Shirts
- Sale Hoodies
- Sale Trousers
- Sale Accessories

Product cards should clearly display:

Original price
Sale price
Discount percentage

Sale pages must support filtering.

---

# 29. NEW IN

Create a strong:

`/new-in`

experience.

Show:

- newest products
- recent drops
- latest collections
- newest campaign imagery

Admin should control what qualifies as "New In".

---

# 30. COLLECTION PAGES

Collections should be first-class entities.

Example:

**THE SIGNATURE COLLECTION**

Collection page:

- campaign hero
- editorial description
- collection imagery
- product grid
- related categories

Admins can create:

- collection name
- slug
- description
- hero image
- mobile hero image
- campaign video
- SEO title
- SEO description
- products
- display order
- start date
- end date
- status

---

# 31. EDITORIAL / JOURNAL

Create a fashion editorial section.

Route:

`/journal`

Content types:

- campaign stories
- styling guides
- collection stories
- brand stories
- interviews
- fashion articles

Each article can have:

- title
- slug
- cover image
- gallery
- content
- author
- publish date
- SEO metadata

This should help establish Human In Motion as a fashion brand rather than simply an online shop.

---

# 32. FOOTER

Create a sophisticated multi-column footer.

Columns:

### SHOP

- New In
- Clothing
- Collections
- Best Sellers
- Sale

### HELP

- Contact
- Delivery
- Returns
- Size Guide
- FAQ

### ABOUT

- Our Story
- Journal
- Careers
- Stockists

### LEGAL

- Privacy
- Terms
- Cookies
- Accessibility

### FOLLOW

Social media icons.

Newsletter signup.

Payment method icons where appropriate.

---

# 33. ADMIN DASHBOARD

Build a complete admin application.

Admin navigation:

### Dashboard

Display:

- revenue
- orders
- customers
- average order value
- products sold
- low-stock products
- recent orders
- recent customers
- sales chart

---

# 34. PRODUCT MANAGEMENT

Admin must be able to:

Create product.

Edit product.

Delete/archive product.

Duplicate product.

Manage:

- name
- slug
- description
- short description
- price
- compare-at price
- SKU
- category
- collection
- brand
- images
- video
- variants
- sizes
- colours
- materials
- care instructions
- fit
- inventory
- tags
- SEO
- status

Statuses:

- Draft
- Active
- Archived

---

# 35. BULK PRODUCT MANAGEMENT

Support:

- bulk price updates
- bulk inventory updates
- bulk category assignment
- bulk collection assignment
- CSV import
- CSV export

Validate CSV imports before committing changes.

---

# 36. CATEGORY MANAGEMENT

Admin can:

- create category
- edit category
- delete/archive category
- reorder categories
- create nested categories
- assign products
- assign SEO metadata
- upload category image
- upload category banner

---

# 37. COLLECTION MANAGEMENT

Admin can create campaigns and collections.

Fields:

- name
- slug
- description
- hero image
- mobile hero
- promotional text
- CTA
- products
- active period
- SEO
- status

---

# 38. HOMEPAGE BUILDER

This is important.

Do NOT hard-code the homepage.

Create a configurable content management system.

Admins should be able to create/reorder sections:

- hero
- image banner
- product carousel
- product grid
- collection tiles
- editorial section
- promotional banner
- category grid
- newsletter
- custom HTML/content if safely supported

Allow:

- desktop image
- mobile image
- title
- description
- button
- link
- alignment
- active/inactive
- display order

---

# 39. ORDER MANAGEMENT

Admin order management must show:

- order number
- customer
- products
- quantities
- subtotal
- discounts
- shipping
- total
- payment status
- fulfilment status
- shipping address
- billing address
- tracking number
- notes
- timestamps

Order statuses:

- Pending
- Confirmed
- Processing
- Packed
- Shipped
- Delivered
- Cancelled
- Refunded
- Partially Refunded

---

# 40. RETURNS

Create a returns workflow.

Customer can request a return.

Capture:

- order
- product
- reason
- quantity
- comments
- status

Admin statuses:

- Requested
- Approved
- Rejected
- Received
- Inspected
- Refunded
- Completed

Design this so the return process can later integrate with a UK shipping/returns provider.

---

# 41. CUSTOMER MANAGEMENT

Admin can:

- search customers
- view customer profile
- view orders
- view lifetime spending
- view addresses
- view wishlist where applicable
- disable account
- restore account

Do not expose sensitive payment information.

---

# 42. REVIEWS

Implement product reviews.

Customer can review purchased products.

Review fields:

- rating
- title
- comment
- images if enabled
- verified purchase

Admin moderation:

- pending
- approved
- rejected

Never automatically publish unmoderated user-generated content unless explicitly configured.

---

# 43. EMAIL SYSTEM

Create transactional email architecture.

Emails:

- welcome
- account verification
- password reset
- order confirmation
- payment confirmation
- order processing
- order shipped
- order delivered
- return requested
- return approved
- refund confirmation

Marketing emails should be separated from transactional emails.

---

# 44. SEO

SEO must be built into the architecture.

Every relevant page must support:

- SEO title
- meta description
- canonical URL
- Open Graph image
- structured data
- clean URL
- sitemap
- robots.txt

Product structured data should include appropriate:

- name
- image
- description
- SKU
- price
- availability
- brand

Implement breadcrumbs.

---

# 45. PERFORMANCE

This is a production ecommerce site.

Performance is critical.

Implement:

- image optimization
- lazy loading
- responsive images
- code splitting
- caching
- efficient database queries
- pagination
- debounced search
- optimized API calls
- CDN-ready architecture

Avoid unnecessary JavaScript.

Do not load large libraries for trivial functionality.

---

# 46. RESPONSIVE DESIGN

The website must be excellent on:

- 1440px+
- 1280px
- 1024px
- 768px
- 480px
- 390px
- 375px

Do not simply shrink the desktop version.

Mobile layouts should be intentionally designed.

Pay particular attention to:

- product grids
- navigation
- filter drawer
- product gallery
- sticky add-to-bag
- checkout
- forms
- image cropping
- typography

---

# 47. MOBILE PRODUCT EXPERIENCE

On mobile:

Product page should provide:

- swipeable image gallery
- sticky product purchase area
- easy size selection
- expandable product information
- easy wishlist
- fast add-to-bag

The Add to Bag button should remain easy to access.

---

# 48. ACCESSIBILITY

Follow modern accessibility practices.

Implement:

- semantic HTML
- keyboard navigation
- visible focus states
- accessible forms
- ARIA where appropriate
- sufficient contrast
- alt text
- accessible modals
- screen-reader-friendly navigation

Do not rely on colour alone to communicate state.

---

# 49. SECURITY

Treat this as a production ecommerce application.

Implement:

- secure authentication
- password hashing
- CSRF protection where applicable
- rate limiting
- secure sessions/tokens
- input validation
- output escaping
- authorization
- admin role protection
- secure file uploads
- payment webhook verification
- audit logging
- secure environment variables

Never expose secrets in frontend code.

---

# 50. DATABASE ARCHITECTURE

Design a normalized ecommerce database.

Core entities should include approximately:

### Users

- id
- name
- email
- password
- phone
- role
- status
- timestamps

### Addresses

- user_id
- type
- name
- address
- city
- county
- postcode
- country

### Products

- id
- name
- slug
- description
- short_description
- sku
- price
- compare_price
- status
- category_id
- metadata
- timestamps

### Product Variants

- product_id
- sku
- size
- colour
- price
- stock
- low_stock_threshold

### Product Images

- product_id
- variant_id
- image
- alt_text
- position

### Categories

- id
- parent_id
- name
- slug
- description
- image
- SEO fields

### Collections

- id
- name
- slug
- description
- hero
- mobile_hero
- status

### Orders

- id
- user_id
- order_number
- subtotal
- discount
- shipping
- total
- payment_status
- fulfilment_status

### Order Items

- order_id
- product_id
- variant_id
- quantity
- price

### Carts

### Cart Items

### Wishlists

### Wishlist Items

### Coupons

### Promotions

### Reviews

### Returns

### Return Items

### Payments

### Shipments

### Newsletter Subscribers

### Homepage Sections

### Pages

### Journal Articles

### Audit Logs

Design relationships properly.

Use indexes where required.

---

# 51. ARCHITECTURE

Use a clean architecture.

Separate:

- presentation
- business logic
- data access
- integrations
- authentication
- payments
- shipping
- notifications
- admin functionality

Do not put business logic directly inside UI components.

Do not create massive controllers.

Do not duplicate logic.

Use reusable services.

---

# 52. API DESIGN

Use a clean REST API or the project's chosen API architecture.

Organize endpoints logically.

Example:

`/api/products`

`/api/categories`

`/api/collections`

`/api/cart`

`/api/wishlist`

`/api/orders`

`/api/checkout`

`/api/customers`

`/api/reviews`

`/api/admin/products`

`/api/admin/orders`

Use:

- validation
- pagination
- consistent response format
- appropriate HTTP status codes
- authentication
- authorization

---

# 53. ADMIN AUTHORIZATION

Implement role-based access.

At minimum:

### Super Admin

Full access.

### Admin

Operational management.

### Catalogue Manager

Products/categories/collections.

### Order Manager

Orders/shipping/returns.

### Content Manager

Homepage/editorial/content.

### Customer Support

Customers/orders/returns.

Permissions should be granular enough to expand later.

---

# 54. IMAGE MANAGEMENT

Fashion ecommerce requires serious media management.

Support:

- product images
- campaign images
- collection images
- editorial images
- category images
- banners

Images should support:

- upload
- reorder
- delete
- alt text
- crop where appropriate
- desktop/mobile versions

Use object storage/CDN architecture rather than storing large image binaries in the database.

---

# 55. ANALYTICS

Design analytics events for:

- product viewed
- product added to bag
- product removed
- checkout started
- checkout completed
- search performed
- wishlist added
- wishlist removed
- category viewed
- collection viewed
- coupon applied

Make the analytics implementation provider-independent.

---

# 56. MARKETING FEATURES

Prepare the system for:

- newsletter signup
- abandoned cart
- promotional banners
- coupon campaigns
- product recommendations
- recently viewed products
- recently purchased products
- campaign landing pages

Do not build fake AI recommendation functionality.

Start with deterministic merchandising rules.

---

# 57. RECENTLY VIEWED

Implement recently viewed products.

For guests:

Use browser/local storage where appropriate.

For authenticated customers:

Persist where useful.

Do not store unnecessary personal information.

---

# 58. PRODUCT RECOMMENDATIONS

Support:

### Related Products

Same category/collection.

### Complete The Look

Manually configured products.

### You May Also Like

Rule-based recommendations.

### Recently Viewed

User-specific.

Administrators should be able to override automatic recommendations.

---

# 59. COOKIE CONSENT

Implement a UK/EU-conscious cookie consent system.

Categorize cookies into:

- Necessary
- Preferences
- Analytics
- Marketing

Do not activate optional tracking before appropriate consent.

---

# 60. LEGAL PAGES

Create editable pages for:

- Privacy Policy
- Terms & Conditions
- Cookie Policy
- Delivery
- Returns
- Refund Policy
- Size Guide
- Contact
- FAQ

These should be manageable through admin.

---

# 61. ERROR STATES

Design premium error experiences.

Examples:

404:

**LOOKS LIKE THIS PIECE HAS MOVED.**

CTA:

**CONTINUE SHOPPING**

Other states:

- product unavailable
- out of stock
- network error
- payment failed
- invalid coupon
- empty bag
- empty wishlist
- no search results

Never show raw technical errors to customers.

---

# 62. LOADING STATES

Use elegant skeleton loaders.

Do not make the interface feel frozen.

Examples:

- product skeleton
- product image skeleton
- collection skeleton
- cart skeleton

---

# 63. EMPTY STATES

Create intentional empty experiences.

Empty Bag:

"YOUR BAG IS WAITING."

"Discover the latest Human In Motion pieces."

CTA:

**SHOP NEW IN**

Empty Wishlist:

"NOTHING SAVED YET."

CTA:

**EXPLORE COLLECTIONS**

---

# 64. MICROINTERACTIONS

Use subtle animation.

Examples:

- image hover
- button feedback
- drawer transitions
- product added confirmation
- wishlist animation
- menu transitions
- modal transitions
- page transitions where appropriate

Animation should feel premium.

Do not overanimate.

Prefer fast, subtle transitions.

---

# 65. FASHION PHOTOGRAPHY

The website must be designed around large, high-quality fashion imagery.

Do not use random stock photography in the final production experience.

During development, create clearly labelled placeholders if actual Human In Motion photography is unavailable.

Structure the CMS so real campaign/product photography can be uploaded later without changing the UI.

---

# 66. CONTENT SHOULD BE DATA-DRIVEN

Do not hard-code:

- product names
- prices
- categories
- campaigns
- promotional messages
- banners
- collections
- navigation
- homepage content

All commercial content should come from the database/CMS.

Use seed data only for development.

Clearly distinguish seed/demo content from production content.

---

# 67. URL STRUCTURE

Create clean ecommerce URLs.

Examples:

`/`

`/shop`

`/new-in`

`/sale`

`/collections/signature`

`/collections/essentials`

`/category/t-shirts`

`/category/hoodies`

`/product/signature-oversized-hoodie`

`/journal`

`/journal/article-slug`

`/account`

`/bag`

`/checkout`

---

# 68. ADMIN URL

Use a protected admin area.

Example:

`/admin`

Do not expose administrative functionality to normal customers.

---

# 69. DEVELOPMENT PROCESS

Before writing substantial code:

1. Inspect the existing repository.
2. Determine the existing framework.
3. Determine package manager.
4. Determine database.
5. Determine authentication architecture.
6. Determine existing design system.
7. Determine existing reusable components.
8. Determine environment variables.
9. Determine existing routes.
10. Determine existing API architecture.

DO NOT unnecessarily rewrite an existing project.

If an existing architecture is sound, extend it.

---

# 70. IMPLEMENTATION ORDER

Build in this order:

## Phase 1 — Foundation

- project structure
- design system
- global layout
- typography
- responsive framework
- header
- navigation
- footer

## Phase 2 — Catalogue

- products
- categories
- collections
- variants
- images
- inventory

## Phase 3 — Shopping

- product listing
- filters
- sorting
- search
- product detail
- wishlist
- cart

## Phase 4 — Checkout

- customer information
- addresses
- shipping
- payment abstraction
- order creation
- confirmation

## Phase 5 — Customer

- registration
- login
- account
- orders
- addresses
- wishlist

## Phase 6 — Admin

- dashboard
- products
- categories
- collections
- orders
- customers
- promotions
- content
- homepage builder

## Phase 7 — Marketing

- newsletter
- coupons
- recommendations
- editorial
- SEO

## Phase 8 — Hardening

- security
- performance
- accessibility
- testing
- error handling
- logging
- deployment preparation

---

# 71. TESTING

Implement appropriate tests.

Test:

### Authentication

- registration
- login
- logout
- password reset

### Products

- product creation
- variants
- inventory
- pricing

### Cart

- add
- remove
- quantity
- variant selection

### Checkout

- address
- shipping
- discount
- payment
- order creation

### Orders

- creation
- status updates
- cancellation
- refund

### Admin

- permissions
- authorization

### Frontend

- responsive layouts
- navigation
- product filtering
- checkout

---

# 72. DATA INTEGRITY

Never trust frontend calculations.

The backend must recalculate:

- prices
- discounts
- shipping
- taxes if applicable
- totals

The client must never be able to manipulate the final order amount.

Verify inventory during checkout.

Prevent overselling.

Handle concurrent inventory updates safely.

---

# 73. TAX / VAT

Design the system so UK VAT can be supported correctly.

Do not assume every product/order has the same tax treatment.

Create a tax abstraction/configuration layer.

Tax calculations must be server-side.

---

# 74. CURRENCY

Primary currency:

**GBP (£)**

Design the pricing architecture so additional currencies can be added later.

Do not hard-code currency formatting throughout the application.

---

# 75. INTERNATIONALIZATION

The initial market is the United Kingdom.

However, architect the system so future markets can support:

- currencies
- countries
- shipping zones
- taxes
- languages

Do not overengineer internationalization in the first release.

---

# 76. BRAND TONE

Human In Motion copy should be:

- confident
- concise
- premium
- modern
- understated
- fashion-focused

Avoid generic ecommerce language such as:

"BEST PRODUCTS AT AMAZING PRICES!!!"

Prefer:

**"Built for movement. Designed to stay with you."**

Avoid excessive exclamation marks.

---

# 77. VISUAL QUALITY BAR

The website must NOT look like:

- a generic Shopify clone
- a dashboard
- a SaaS landing page
- a template
- a beginner ecommerce project
- a marketplace
- an overly rounded modern UI kit

It should look like a professionally art-directed UK fashion brand.

Every major page should feel intentional.

---

# 78. BOOHOO MAN REFERENCE

Use BoohooMAN as a UX benchmark for:

- dense fashion navigation
- category organization
- product discovery
- promotional messaging
- product grid behaviour
- sale/new-in merchandising
- filtering
- fashion ecommerce conventions
- responsive shopping experience

The current BoohooMAN experience includes extensive category navigation, new-in and sale merchandising, collection-based discovery, product filtering, and promotional modules.

However:

**DO NOT COPY THE WEBSITE.**

Do not copy:

- logo
- brand identity
- exact colours
- exact text
- photography
- source code
- proprietary assets
- exact layout measurements
- distinctive branded components

Instead, interpret the underlying UX patterns and create a distinct Human In Motion implementation.

---

# 79. HOMEPAGE ART DIRECTION

The homepage should immediately communicate the brand.

Above the fold should contain:

1. Strong campaign imagery/video.
2. Human In Motion identity.
3. Clear campaign headline.
4. Strong CTA.
5. Minimal distractions.

The first impression should be:

**"This is a serious fashion brand."**

not:

**"This is an ecommerce template."**

---

# 80. CONVERSION PRINCIPLES

Design the platform to minimize friction.

Customers should be able to go:

Homepage → Product → Size → Add to Bag → Checkout

with minimal unnecessary steps.

Important CTAs must be visually obvious.

Do not overwhelm the customer with popups.

Avoid unnecessary account requirements.

Keep checkout focused.

---

# 81. ADMIN MERCHANDISING

The admin should allow staff to control merchandising without developers.

For example:

Homepage:

1. Hero
2. New In
3. Featured Collection
4. Best Sellers
5. Editorial
6. Limited Edition
7. Newsletter

Admin can reorder:

1. Hero
2. Limited Edition
3. New In
4. Editorial
5. Best Sellers

without changing code.

---

# 82. PRODUCT MERCHANDISING

Allow admins to define:

- featured products
- best sellers
- new arrivals
- sale
- limited edition
- recommended products

Support manual product ordering.

---

# 83. ADMIN DASHBOARD UX

The admin panel should be clean and professional but does NOT need to mimic the luxury storefront.

Prioritize:

- efficiency
- clarity
- data density
- tables
- bulk actions
- search
- filters
- keyboard-friendly workflows

---

# 84. DATABASE SEEDING

Create realistic development seed data.

Include at least:

20–30 products.

Multiple categories.

Multiple collections.

Multiple colours.

Multiple sizes.

Different prices.

Sale products.

Featured products.

New arrivals.

Customers.

Orders.

Coupons.

Do not use real customer data.

Clearly mark all seeded content as demo content.

---

# 85. SEO-FRIENDLY CONTENT STRUCTURE

Product pages should produce meaningful metadata.

Example:

Title:

Human In Motion Signature Oversized Hoodie | Black

Description:

Discover the Human In Motion Signature Oversized Hoodie in Black. Premium construction, relaxed fit and designed for everyday movement.

Generate structured metadata dynamically.

---

# 86. PERSISTENCE

Shopping cart must persist appropriately.

For authenticated users:

Persist server-side.

For guests:

Use secure guest-cart strategy.

Merge guest cart into account cart after login.

Handle:

- expired products
- price changes
- unavailable variants
- inventory changes

before checkout.

---

# 87. CHECKOUT VALIDATION

Before order creation:

Revalidate:

- product exists
- variant exists
- product active
- inventory available
- current price
- discount validity
- shipping availability
- payment amount

Never trust cart totals sent from the frontend.

---

# 88. PAYMENT WEBHOOKS

Payment confirmation must be webhook-driven where supported.

Never mark an order as paid solely because the frontend says payment succeeded.

Verify:

- webhook signature
- transaction
- amount
- currency
- order reference

before updating payment status.

---

# 89. LOGGING

Implement structured logging.

Log:

- application errors
- failed payments
- webhook events
- authentication events
- admin actions
- order status changes

Do not log:

- passwords
- raw payment information
- sensitive tokens
- unnecessary personal data

---

# 90. FINAL UX REVIEW

Before declaring the project complete, inspect every major page visually.

Check:

- desktop
- tablet
- mobile

Check:

- spacing
- typography
- image proportions
- button hierarchy
- navigation
- loading states
- empty states
- errors
- forms
- checkout

The website must look polished at first load.

---

# 91. FINAL ACCEPTANCE CRITERIA

The project is NOT complete simply because it compiles.

It is complete when:

- storefront works
- navigation works
- search works
- categories work
- collections work
- product listing works
- filtering works
- sorting works
- product detail works
- variants work
- inventory works
- wishlist works
- cart works
- checkout works
- payment architecture works
- orders work
- customer accounts work
- admin works
- promotions work
- CMS works
- homepage is configurable
- SEO works
- responsive layouts work
- accessibility is considered
- security is implemented
- errors are handled
- loading states exist
- database relationships are correct
- production build succeeds

---

# 92. IMPORTANT DEVELOPMENT RULES

DO NOT:

- create placeholder architecture and stop
- leave TODOs for core functionality
- create fake API responses for production functionality
- hard-code ecommerce data
- duplicate business logic
- put secrets in source code
- ignore validation
- ignore mobile
- ignore loading/error states
- create fake payment success
- trust frontend pricing
- create insecure admin endpoints
- overwrite working functionality unnecessarily
- rewrite the entire project without justification

DO:

- inspect first
- plan architecture
- reuse existing code
- create reusable components
- maintain clean separation of concerns
- validate all input
- handle errors
- test important workflows
- keep code maintainable
- optimize for production
- document important architectural decisions

---

# 93. OPEN CODE EXECUTION STRATEGY

Work autonomously through the project in logical stages.

For each major stage:

1. Inspect existing implementation.
2. Identify what already exists.
3. Create a short implementation plan.
4. Implement the feature.
5. Run the appropriate tests/type checks/build.
6. Fix errors.
7. Review the implementation.
8. Continue to the next stage.

Do not stop after generating a plan.

Do not ask for confirmation for ordinary implementation decisions.

Only stop and ask for clarification when a required business decision genuinely cannot be inferred from the requirements.

---

# 94. CODE QUALITY

Write production-quality code.

Prioritize:

- readability
- maintainability
- type safety
- modularity
- testability
- performance
- security

Use meaningful names.

Avoid:

- giant components
- giant controllers
- deeply nested conditional logic
- unnecessary abstractions
- duplicated code
- magic numbers
- hard-coded business rules

---

# 95. DESIGN QUALITY

The most important visual principle is:

**PRODUCTS + PHOTOGRAPHY + TYPOGRAPHY + SPACING = PREMIUM EXPERIENCE**

Do not try to make the site look premium by adding excessive gradients, shadows, animations, rounded cards or decorative effects.

Premium should come from:

- typography
- photography
- layout
- whitespace
- hierarchy
- restraint
- consistency
- interaction quality

---

# 96. DELIVERABLE

At the end of implementation, provide:

1. Complete working storefront.
2. Complete admin dashboard.
3. Database schema.
4. Seed data.
5. Authentication.
6. Product management.
7. Category management.
8. Collection management.
9. Inventory.
10. Cart.
11. Wishlist.
12. Checkout.
13. Orders.
14. Customer accounts.
15. Promotions.
16. CMS/homepage builder.
17. SEO.
18. Responsive UI.
19. Tests.
20. Environment configuration documentation.
21. Deployment documentation.

---

# 97. FINAL INSTRUCTION

Build Human In Motion as if you were the lead engineer and digital product designer for a real UK luxury fashion company preparing to launch its official ecommerce platform.

Do not produce a generic ecommerce template.

Do not merely reproduce BoohooMAN.

Study the interaction patterns and information architecture of modern fashion ecommerce, particularly the referenced BoohooMAN experience, then create a distinctive Human In Motion interpretation.

The final result should feel:

**Premium. Fashion-led. Modern. Fast. Confident. Editorial. Commercially effective.**

The customer should immediately understand:

**what Human In Motion sells, what is new, what is popular, what the brand represents, and how to buy.**

Start by inspecting the repository and existing application architecture before making implementation decisions.
