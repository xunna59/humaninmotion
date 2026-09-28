<?php

namespace Database\Seeders;

use App\Models\HomepageSection;
use App\Models\JournalArticle;
use App\Models\Page;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->pages();
        $this->journal();
        $this->homepage();
    }

    private function pages(): void
    {
        $pages = [
            ['title' => 'Our Story', 'slug' => 'our-story', 'content' => '<p>Human In Motion began in East London with a simple question: why does most menswear ask you to choose between style and functionality?</p><p>The answer is a wardrobe built for movement. Garments with weight, shape and purpose, designed to stay with you through the everyday — from the first commute of the morning to the last walk home at night.</p><h2>Designed around how you move</h2><p>Every piece starts with proportion, weight and line. Our signature oversized fits are engineered, not sacked out: boxy through the body, measured through the drop, finished with a drape that keeps its shape. Heavyweight fabrics carry the construction and settle as they are worn, so a Human In Motion piece looks better on year two than on day one.</p><h2>Made to stay with you</h2><p>We build in small runs and we do not chase seasons for the sake of it. The Signature Collection is the backbone; Limited Edition pieces come and go and never restock. What links them is the same conviction: clothing should move with you, and last beyond the season it was bought in.</p><p>Human In Motion is for people who move differently — on foot, on wheels, between cities. If that sounds like you, you are already part of the story.</p>'],
            ['title' => 'Delivery', 'slug' => 'delivery', 'content' => '<p>Orders are processed and dispatched from our London warehouse within 1–2 working days, and you will receive tracking by email as soon as your order ships.</p><h2>United Kingdom</h2><ul><li>Standard delivery £3.95 — arrives in 2–4 working days. Free on orders over £100.</li><li>Express delivery £6.95 — arrives in 1–2 working days.</li></ul><h2>Europe</h2><ul><li>European delivery £12.00 — arrives in 5–9 working days. Free on orders over £200.</li></ul><p>We deliver to Ireland, France, Germany, Spain, Italy, the Netherlands, Belgium, Austria and Portugal.</p><h2>International</h2><ul><li>International delivery £18.00 — arrives in 7–14 working days. Free on orders over £250.</li></ul><p>We currently deliver to the United States, Canada, Australia and the United Arab Emirates.</p><h2>Customs and duties</h2><p>Orders delivered outside the UK may be subject to customs duties or local taxes on arrival. These are the responsibility of the recipient and are not included in the price shown at checkout.</p><h2>Questions</h2><p>If your tracking has not updated within two working days of dispatch, contact us at <a href="mailto:hello@humaninmotion.co.uk">hello@humaninmotion.co.uk</a> and we will look into it.</p>'],
            ['title' => 'Returns', 'slug' => 'returns', 'content' => '<h2>How to return an item</h2><p>You have 30 days from delivery to return any item in its original condition with tags attached. To start a return, email <a href="mailto:hello@humaninmotion.co.uk">hello@humaninmotion.co.uk</a> with your order number and the items you wish to return, and we will send you the instructions.</p><h2>Good condition</h2><p>Items must be unworn, unwashed and in their original packaging, unless the item is faulty. For hygiene reasons we cannot accept returns on underwear and socks unless they are faulty.</p><h2>Costs and refunds</h2><p>Returns are free within the UK. Returns from outside the UK are arranged by email and are at the customer\'s cost.</p><p>Once we receive and inspect your return, we refund to your original payment method within 5 working days and email you to confirm. See our <a href="/pages/refund-policy">Refund Policy</a> for the full process.</p><h2>Faulty or incorrect items</h2><p>If an item arrives faulty or is not what you ordered, contact us at <a href="mailto:hello@humaninmotion.co.uk">hello@humaninmotion.co.uk</a> and we will arrange a replacement or a refund, including any postage costs.</p>'],
            ['title' => 'Size Guide', 'slug' => 'size-guide', 'content' => '<p>Our garments are designed to a modern fit. The signature oversized fits wear one size up from your regular size.</p><p>See product pages for specific measurements before purchasing.</p>'],
            ['title' => 'FAQ', 'slug' => 'faq', 'content' => '<h3>How do I track my order?</h3><p>You will receive tracking by email once your order ships.</p><h3>Can I return a sale item?</h3><p>Yes, all items excluding underwear and socks are returnable within 30 days.</p><h3>Where is my order dispatched from?</h3><p>All orders are dispatched from our London warehouse.</p>'],
            ['title' => 'Careers', 'slug' => 'careers', 'content' => '<p>We are always looking for people who move differently. Working at Human In Motion means building a wardrobe for movement with the same care, whether you are in the studio, the warehouse or behind the screen.</p><h2>What we look for</h2><ul><li>People who care about the product, not just the process</li><li>Designers who understand fit, weight and construction</li><li>Specialists in ecommerce, brand and customer care</li><li>Anyone who moves through the city on their own terms</li></ul><h2>Open roles</h2><p>We hire across design, ecommerce, retail and operations, and we advertise current openings here and on our social channels. If there is no role listed, send us a short note anyway — the right person rarely fits a job description.</p><h2>How to apply</h2><p>Email your CV and a few lines about yourself to <a href="mailto:careers@humaninmotion.co.uk">careers@humaninmotion.co.uk</a>. We read every application and reply to every applicant.</p>'],
            ['title' => 'Stockists', 'slug' => 'stockists', 'content' => '<p>Select Human In Motion pieces are available at a growing group of partner retailers across the UK and Europe. Our stockists carry the same pieces you will find online — Signature Collection staples and selected Limited Edition drops.</p><h2>Where to find us</h2><ul><li>London, United Kingdom</li><li>Manchester, United Kingdom</li><li>Amsterdam, Netherlands</li></ul><h2>Wholesale and press</h2><p>Interested in stocking Human In Motion or working with us on press? Get in touch at <a href="mailto:wholesale@humaninmotion.co.uk">wholesale@humaninmotion.co.uk</a> and a member of the team will come back to you.</p>'],
            ['title' => 'Privacy Policy', 'slug' => 'privacy-policy', 'content' => '<h2>Who we are</h2><p>Human In Motion (Company House registration GB123456789) is the data controller responsible for the personal data collected on this website. Registered address: Human In Motion, 12 Carnaby Street, London, W1F 9PW. You can contact our team at <a href="mailto:hello@humaninmotion.co.uk">hello@humaninmotion.co.uk</a>.</p><h2>What we collect</h2><p>We only collect the personal data we need to run the business:</p><ul><li>Contact and delivery details you provide when placing an order or registering</li><li>Order history, including payment method type and authorisation code</li><li>Your email address if you join the newsletter</li><li>Information collected through cookies, such as the pages you visit</li></ul><p>We do not store your card number. Card payments are processed securely by Stripe.</p><h2>How we use your data</h2><ul><li>To fulfil and deliver orders, and to handle returns and refunds</li><li>To provide customer support and answer enquiries</li><li>To send order updates and, if you have opted in, marketing emails</li><li>To improve our website and understand how it is used</li><li>To meet our legal and accounting obligations</li></ul><h2>Legal bases</h2><p>We process data on the following grounds: the performance of a contract with you; our legitimate interests in running and improving the business; your consent, which you can withdraw at any time; and compliance with legal obligations.</p><h2>Cookies</h2><p>Our website uses strictly necessary cookies to function and an optional consent cookie. Your choices are recorded in our <a href="/pages/cookie-policy">Cookie Policy</a>.</p><h2>Who we share data with</h2><p>We share personal data only where necessary: Stripe for payment processing, delivery couriers to get your order to you, and email and analytics providers that act on our behalf. We never sell your data.</p><h2>How long we keep it</h2><p>Order records are kept as long as required by law for accounting and tax purposes. Newsletter data is kept until you unsubscribe or ask us to delete it.</p><h2>Your rights</h2><p>You have the right to access, correct, delete and port your personal data, to object to or restrict its processing, and to withdraw consent. To exercise any of these rights, email <a href="mailto:hello@humaninmotion.co.uk">hello@humaninmotion.co.uk</a>. You also have the right to complain to the Information Commissioner\'s Office.</p><h2>Changes to this policy</h2><p>We may update this policy from time to time. The latest version will always be available on this page.</p>'],
            ['title' => 'Terms & Conditions', 'slug' => 'terms-conditions', 'content' => '<h2>About these terms</h2><p>These terms and conditions govern your use of the Human In Motion website and any orders you place with us. By placing an order you agree to be bound by them. They do not affect your statutory rights.</p><h2>Who we are</h2><p>Human In Motion, 12 Carnaby Street, London, W1F 9PW. For any question about an order, email <a href="mailto:hello@humaninmotion.co.uk">hello@humaninmotion.co.uk</a>.</p><h2>Placing an order</h2><p>When you place an order, we send a confirmation email. A contract is formed when we accept your order and take payment. We may refuse an order where stock is unavailable or payment is not authorised.</p><h2>Prices and payment</h2><p>All prices are in GBP and include VAT. Payment is taken securely via Stripe when your order is placed. We reserve the right to change prices at any time, but changes will not affect orders already placed.</p><h2>Delivery</h2><p>We aim to dispatch all orders within our published delivery times. Standard delivery is 2–4 working days and express delivery 1–2 working days. See our <a href="/pages/delivery">Delivery</a> page for full details and costs. Risk passes to you once your order is delivered.</p><h2>Returns and refunds</h2><p>You have 30 days from delivery to return any item in original condition with tags attached. See our <a href="/pages/returns">Returns</a> and <a href="/pages/refund-policy">Refund Policy</a> pages for the full process.</p><h2>Ownership of goods</h2><p>Goods remain our property until the full purchase price has been paid. Title passes to you once payment is complete.</p><h2>Our liability</h2><p>We are liable for loss or damage caused by our negligence and for defects in the goods we supply. To the extent permitted by law, we are not liable for indirect or consequential loss arising from your use of the site.</p><h2>Intellectual property</h2><p>All content on this website, including text, images and logos, is owned by or licensed to Human In Motion. You may not reproduce it without our written permission.</p><h2>Governing law</h2><p>These terms are governed by the laws of England and Wales, and any disputes are subject to the exclusive jurisdiction of the English courts.</p>'],
            ['title' => 'Cookie Policy', 'slug' => 'cookie-policy', 'content' => '<p>This policy explains how Human In Motion uses cookies and similar technologies on this website.</p><h2>What are cookies?</h2><p>Cookies are small text files stored on your device when you visit a website. They help the site work, remember your choices and understand how visitors use it.</p><h2>Strictly necessary cookies</h2><p>These cookies are essential for the site to function and cannot be switched off. They include a session cookie that keeps your shopping basket and login, and a security token cookie that protects your visit.</p><h2>Your consent</h2><p>When you first visit the site we display a consent banner. We place the strictly necessary cookies automatically. Analytics or marketing cookies are only activated if you accept optional cookies.</p><p>Your choice is stored in a consent cookie called <strong>hm_cookie_consent</strong>. Selecting “Accept all” records the value <strong>all</strong>; selecting “Reject” records <strong>necessary</strong>. It lasts for one year, after which we ask again.</p><h2>Managing cookies</h2><p>You can change your mind at any time using the “Cookie preferences” link in the footer, which reopens the consent banner. You can also delete cookies through your browser settings.</p><h2>Contact</h2><p>Questions about this policy? Email <a href="mailto:hello@humaninmotion.co.uk">hello@humaninmotion.co.uk</a>.</p>'],
            ['title' => 'Accessibility', 'slug' => 'accessibility', 'content' => '<p>Human In Motion is committed to making our website accessible to everyone, including people with disabilities. We work to the Web Content Accessibility Guidelines (WCAG) 2.1 level AA as our standard.</p><h2>What we aim to provide</h2><ul><li>Full keyboard navigation across all pages and checkout</li><li>Clear heading structure and meaningful alternatives for images</li><li>Sufficient colour contrast and readable font sizes throughout</li><li>Forms and error messages that work with screen readers</li><li>Responsive layouts that scale correctly on any device</li></ul><h2>How to report an issue</h2><p>We are always working to improve. If you encounter a page or feature you cannot use, please email <a href="mailto:hello@humaninmotion.co.uk">hello@humaninmotion.co.uk</a> and tell us:</p><ul><li>the page or feature you had trouble with</li><li>what happened and what you expected instead</li><li>the browser, device and assistive technology you were using</li></ul><h2>Third-party content</h2><p>Some content, such as embedded media and payment services provided by Stripe, is supplied by third parties and may not be fully under our control. We encourage those providers to maintain the same standards.</p><h2>Statement review</h2><p>This statement was last reviewed on 28 September 2026 and will be reviewed again as part of our ongoing accessibility work.</p>'],
            ['title' => 'Refund Policy', 'slug' => 'refund-policy', 'content' => '<p>Refunds are issued to the original payment method within 5 working days of a return being received and inspected.</p>'],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(['slug' => $page['slug']], $page + [
                'meta_title' => $page['title'].' | Human In Motion',
                'meta_description' => 'Human In Motion — '.$page['title'].'.',
                'is_active' => true,
            ]);
        }
    }

    private function journal(): void
    {
        $articles = [
            ['title' => 'The Modern Bomber Returns', 'slug' => 'the-modern-bomber-returns', 'category' => 'Campaign Stories', 'author' => 'HIM Editorial', 'excerpt' => 'Why the varsity bomber is the statement piece of the season, and how to wear it without looking retro.', 'content' => '<p>For a season that asks for confidence, the bomber is back with purpose.</p><h2>Styled with intent</h2><p>Wear it oversized over a heavyweight tee, or sharpened over a knit for evenings.</p>'],
            ['title' => 'A Field Guide to Cargo Trousers', 'slug' => 'a-field-guide-to-cargo-trousers', 'category' => 'Styling Guides', 'author' => 'HIM Editorial', 'excerpt' => 'The cargo trouser was made for movement. Here is how to build around it.', 'content' => '<p>The cargo trouser is utility done right.</p><h2>The build</h2><p>Pair a relaxed cargo with a boxy tee and a clean sneaker for an everyday uniform.</p>'],
            ['title' => 'Designed for Movement', 'slug' => 'designed-for-movement', 'category' => 'Brand Stories', 'author' => 'HIM Editorial', 'excerpt' => 'Inside the thinking behind the Signature Collection and the proportion that defines it.', 'content' => '<p>Every piece in the Signature Collection begins with a single question: how does this move?</p>'],
            ['title' => 'Wardrobe Essentials, Considered', 'slug' => 'wardrobe-essentials-considered', 'category' => 'Fashion Articles', 'author' => 'HIM Editorial', 'excerpt' => 'What makes a truly essential wardrobe, and why weight in fabric matters.', 'content' => '<p>The best essentials are the ones you stop thinking about.</p><h2>Weight</h2><p>Heavier fabrics hold their shape. They drape, they settle, they last.</p>'],
            ['title' => 'Interview: The Fit That Rated First', 'slug' => 'interview-fit-first', 'category' => 'Interviews', 'author' => 'HIM Editorial', 'excerpt' => 'We sat down with our head of design to talk oversized fits and measured proportions.', 'content' => '<p>Oversized is not sack-like. Oversized is engineered line and drape.</p>'],
        ];

        foreach ($articles as $i => $article) {
            JournalArticle::updateOrCreate(['slug' => $article['slug']], $article + [
                'status' => 'published',
                'meta_title' => $article['title'].' | Human In Motion Journal',
                'meta_description' => $article['excerpt'],
                'published_at' => now()->subDays($i * 9),
                'gallery' => [],
            ]);
        }
    }

    private function homepage(): void
    {
        $sections = [
            [
                'type' => 'hero',
                'title' => 'HUMAN IN MOTION',
                'description' => 'Designed for those who move differently.',
                'button_text' => 'SHOP NEW IN',
                'button_url' => '/new-in',
                'button_two_text' => 'EXPLORE COLLECTION',
                'button_two_url' => '/collections/signature',
                'text_position' => 'left',
                'overlay_strength' => 55,
                'sort_order' => 1,
                'content' => ['subtext' => 'NEW SEASON — SIGNATURE COLLECTION'],
            ],
            [
                'type' => 'product_carousel',
                'title' => 'NEW ARRIVALS',
                'description' => 'Fresh drops, delivered first.',
                'sort_order' => 2,
                'content' => ['tag' => 'new'],
            ],
            [
                'type' => 'category_grid',
                'title' => 'SHOP THE COLLECTION',
                'sort_order' => 3,
            ],
            [
                'type' => 'product_carousel',
                'title' => 'BEST SELLERS',
                'description' => 'The pieces in permanent rotation.',
                'sort_order' => 4,
                'content' => ['tag' => 'bestsellers'],
            ],
            [
                'type' => 'editorial',
                'title' => 'MOVEMENT IS THE DESIGN',
                'description' => 'A campaign in three parts. Shot across London, the Signature Collection is built around proportion, weight and line.',
                'button_text' => 'READ THE STORY',
                'button_url' => '/journal/designed-for-movement',
                'image_desktop' => '/placeholder/campaign-editorial.svg',
                'text_position' => 'left',
                'sort_order' => 5,
            ],
            [
                'type' => 'collection_tiles',
                'title' => 'SHOP BY COLLECTION',
                'sort_order' => 6,
                'content' => ['collections' => ['signature', 'limited-edition', 'essentials']],
            ],
            [
                'type' => 'promotional_banner',
                'title' => 'LIMITED EDITION',
                'description' => 'Small runs. Never restocked. When they are gone, they are gone.',
                'button_text' => 'SHOP LIMITED',
                'button_url' => '/collections/limited-edition',
                'sort_order' => 7,
            ],
            [
                'type' => 'image_banner',
                'title' => 'SALE',
                'description' => 'Selected styles for the season.',
                'button_text' => 'SHOP SALE',
                'button_url' => '/sale',
                'sort_order' => 8,
            ],
            [
                'type' => 'newsletter',
                'title' => 'JOIN HUMAN IN MOTION',
                'description' => 'Be first to discover new collections, exclusive releases and private offers.',
                'sort_order' => 9,
            ],
        ];

        foreach ($sections as $section) {
            HomepageSection::updateOrCreate(
                ['type' => $section['type'], 'sort_order' => $section['sort_order']],
                $section + ['is_active' => true]
            );
        }
    }
}
