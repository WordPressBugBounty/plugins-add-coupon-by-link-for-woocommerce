=== Advance coupon for WooCommerce ===
Contributors: rajeshsingh520
Donate link: https://piwebsolution.com
Tags: coupons, url coupons, discount rules, qrcode, woocommerce coupon
Requires at least: 3.0.1
Tested up to: 6.9
License: GPLv2 or later
Stable tag: 1.2.31
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add coupons by URL, restrict coupons by product attribute, a WooCommerce coupon plugin

== Description ==

WooCommerce URL coupons let you give your customers a coupon link to apply a coupon. Using URL coupons your customers can apply coupons via visiting a link.

Use WooCommerce URL coupons on buttons, images, and text. Show them on your sidebar, ads, email marketing, blog posts – basically wherever you can put a link, you can put a URL coupon!

Also allow multiple conditions to control coupon usage. You can restrict the coupon by product attribute, user role, user email ID, payment methods.

= It's working =

All the WooCommerce coupons can be applied by your customers by visiting a link with the coupon code embedded in the URL

`
Example link: http://abc.com/?apply_coupon=[coupon code]
`

* You can change the URL coupon key **?apply_coupon=** in the plugin settings
* You can also disable the WooCommerce default coupon insertion field present in the cart and checkout pages
* You can set a different message for the coupon from the message tab in the coupon, or you can use the global message set in the plugin setting
* You can set a message that will be shown when the user first lands on your website with the URL, and the coupon is not yet applied as it's a conditional coupon
* Set a message to inform the customer that the coupon is added, but since the conditions of the coupon are not yet satisfied, it is not applied. Once the conditions of the coupon are satisfied, the coupon is applied. You can even describe the conditions of the coupon in this message, as you can set different messages for different coupons.
* This URL coupons plugin will work even for guest users.
* This URL coupons plugin will work for conditional coupons as well.
* Specify a product to auto-add to the cart when a URL coupon is clicked
* You can specify a different set of auto-add products for different coupons
* The plugin also supports coupon QR codes. It will generate a QR code for the coupon.
* Option to apply the coupon when a specific product is added to the cart
* You can assign a coupon to a category, so when a product from that category is added to the cart the coupon will be applied automatically
* Auto-apply coupon to the user's cart when the conditions are satisfied
* Auto-apply coupon based on specific country, product, product category, past purchases, login status, user role, user email ID, and more.
* Control what payment methods are available based on the coupon applied
* Offer shipping discounts by applying a coupon. You can set the shipping discount amount in the coupon settings
* Manage coupons by categories, so you can group coupons by categories and filter them easily in the coupon list page 


= Add to cart coupon =
[youtube https://www.youtube.com/watch?v=kqSl8ze3HZI]


= How URL coupons handle conditional coupons =
* When the URL coupon has conditional coupon then it will add the coupon in the user session and notify the customer that coupon is added and it will be applied when coupon conditions are satisfied
* Once the coupon conditions are satisfied, the coupon gets applied automatically

 = Attribute-Based Coupon Restrictions =
 With the Attribute-Based Coupon Restrictions, you can now create more targeted promotions by applying or excluding coupons based on specific product attributes. This feature is perfect for store owners looking to fine-tune their discount strategies and ensure that promotions are applied exactly where they are intended.

**Targeted Promotions:** Apply coupons to products with specific attributes, such as color, size, or any other custom attribute, ensuring your promotions reach the right products.
**Exclusion Control:** Exclude products with certain attributes from coupon eligibility, giving you precise control over your discount campaigns.
**Easy Management:** Easily add, manage, and update attribute restrictions directly from the coupon settings in your WooCommerce dashboard.

== Used in Order: Tracking Feature ==

This feature enhances WooCommerce coupon management by adding a new "Used in order" column in the coupons list view. Each coupon now includes a clickable link that provides insight into its usage history. Upon clicking the link, users can view a detailed list of orders in which the coupon has been applied. This feature offers transparency and enables store owners to track the effectiveness and history of each coupon effortlessly.

== Exclude Email Restrictions for Coupons ==
This feature enhances coupon management by allowing exclusion of specific email addresses or entire domains from coupon application. You can create a list of email IDs that coupons should not apply to, and use wildcard exceptions like *@gmail.com to block coupons for entire domains. For example, you can exclude xyz@ps.com, *@yahoo.com, abc@gmail.com, and more. This feature provides greater control over coupon usage and ensures that discounts are applied only to eligible customers.

== Payment Methods Restriction for Coupons ==
Ability to restrict coupon usage based on specific payment methods. This feature empowers you to tailor promotions by limiting coupon applicability to selected payment methods, ensuring greater control over discount distribution and promotional strategies.

== User Role Restrictions for Coupons ==
Introducing an advanced feature in our plugin, designed to provide precise control over coupon usage based on user roles. Now, you can effectively manage promotions by applying coupons exclusively to selected user roles or excluding them from specific user roles.

== Advanced conditions for coupon restriction ==

We have a large number of conditions to restrict coupon usage. You can restrict the coupon by location, product, past purchases, login status, and you can use and/or conditions to combine the restrictions. This feature allows you to create complex coupon rules that can be tailored to your specific needs.

You can use the auto-apply option with these conditions so that the discount will be auto-applied for your matching customers. This is a great way to reward your loyal customers or to encourage new customers to make a purchase.

A few of the conditions available in the plugin include:

**Billing country**: Restrict coupon usage based on the customer's billing country. e.g., Offer 10% off to customers whose billing country is Canada. Use this for region-specific promotions like Canada Day sales.

**Product category**: 15% off products under the “Winter Jackets” category. Great for end-of-season clearance sales.

**Custom product taxonomy**: Apply a discount on products marked with new-launch in a custom taxonomy. Perfect for spotlighting new arrivals or beta collections.

**Product metadata**: Give a discount on products where _is_featured = yes. Useful for promoting only featured products.

**Cart quantity**: Apply a discount when the cart contains 5 or more items. Boosts cart size and average order value.

**Cart subtotal**: $20 off if cart subtotal exceeds $200. Encourages bigger orders by offering savings at higher thresholds.

**Previous orders by category**: 20% off on “Shoes” if the customer previously bought from “Socks”. Smart cross-selling based on previous category behavior.

**Customer login status**:  5% discount for logged-in users only. Drives registrations and logged-in engagement.

**User role**: 25% off for users with the “wholesale_buyer” role. Segment pricing for wholesale vs retail customers.

**Cart weight (PRO)**: 10% off if total cart weight is under 1 kg. Motivate customers to choose light, low-shipping-cost products.

**Coupon applied (PRO)**: Allow “WELCOME10” only if “NEWYEAR50” isn’t applied. Prevent misuse of overlapping promotions.

**Order count (PRO)**: 50% off on the customer’s 5th order. Reward returning customers and increase repeat sales.

**Total customer spend (PRO)**: 15% discount for customers who’ve spent over $1,000. Loyalty-based rewards for high-value customers.

**Specific product present in cart or Specific product quantity (PRO)**: Buy 3 units of Product X, get a discount. This encourages bulk purchases and upselling.

**Shipping country (PRO)**: Free shipping coupon if shipping destination is the United States. Encourage purchases from US customers by waiving delivery fees.

**User shipping zone (PRO)**: $20 off coupon for users in the "South America" shipping zone. Push regional courier deals or logistics coverage expansion.

**Virtual / Non-virtual product quantity**: 10% off if cart contains 2 or more virtual products. Promote digital goods and services effectively.


== Store Credit Coupon ==
Store Credit coupons offer a versatile and customer-friendly way to manage discounts and returns within your WooCommerce store. This coupon type allows you to issue credit that customers can use for future purchases, providing a seamless shopping experience and encouraging repeat business.

Customers can use part or all of their store credit in a single purchase. If the cart total is less than the store credit amount, the remaining balance can be used for future orders.

Customers can view their remaining store credit balance directly in the cart, enhancing transparency and encouraging further engagement.

= Issue Refunds as Store Credit =
With this feature, you can now issue full or partial refunds as store credit directly from the order edit page. This provides a convenient and flexible way to manage refunds and retain customer loyalty. By issuing refunds as store credit, you can effectively manage returns and keep customers engaged with your store.

= Restrict coupon by day of the week =
You can now restrict the coupon by the day of the week. This feature allows you to create time-sensitive promotions that are only valid on specific days, enhancing your marketing strategies and encouraging customer engagement.
e.g., You can create a coupon that is only valid on weekends or weekdays, providing targeted discounts to your customers based on the day of the week.

= Auto-add product to cart when coupon is applied by URL or QR Code =
You can now specify a set of products to be automatically added to the user's cart when a coupon is applied via URL or QR code. This feature enhances the customer experience by simplifying the checkout process and offering additional value through bundled product promotions. So if you are offering a 10% discount on product A, then you can make product A be auto-added to the cart when the user clicks on the URL coupon link or scans the QR code.

= Auto-add product to cart when the coupon is applied =
You can now specify a set of products to be automatically added to the user's cart when a coupon is applied. This is a bit different from Auto add on URL coupon, as Auto add on URL coupon adds the product even when the coupon is not yet applied to the cart, whereas this adds the product only when the coupon is applied to the cart.

= Boost Conversions with Flexible WooCommerce Shipping Discount Coupons 🚚✨ =
Enhance your WooCommerce store’s promotional power with our Shipping Discount Coupon feature—a game-changer for boosting sales and reducing cart abandonment.

Key Features & Benefits:
✅ Percentage-Based Shipping Discounts: Offer dynamic discounts (e.g., 30% off shipping) to incentivize purchases without sacrificing profit margins.
✅ Exclude Specific Shipping Methods: Prevent discounts from applying to high-cost or premium delivery options, ensuring full control over promotions.
✅ Dynamic Adjustments: Automatically display discounted shipping rates at checkout, creating transparency and urgency.
✅ Compatibility: Works seamlessly with all shipping methods, even with third-party dynamic shipping methods

Example Use Case:
Run a “test” coupon campaign to slash shipping costs by 30%, exclude premium delivery options, and watch conversions soar. You can even restrict the coupon to be available for a specific zone or only once for each customer.

= Advanced Coupon Scheduling by Date Range =
This feature allows you to set multiple date and time ranges for when your coupons will be valid. With this, you can specify start and end dates, along with specific time intervals for each range, ensuring your coupon is only applicable within the predefined periods.

**Key Highlights:**

* **Custom Date and Time Ranges:** Define precise start and end dates, along with time intervals for each range.

* **Multiple Range Flexibility:** Activate your coupon across various intervals to suit different promotional strategies.

* **Seamless Integration:** Integrates smoothly with all existing conditions and restrictions for a hassle-free user experience.

* **Enhanced Control:** Strategically time your discounts to match peak shopping periods, special events, or seasonal sales.

= Recurring Day-Based Scheduling of Coupons (PRO) =
The Pro version of the plugin includes a day-based scheduling feature, allowing you to specify the exact days and time intervals when your coupons will be valid.

**Key Highlights:**

* **Day Selection:** Choose specific days of the week (e.g., Monday, Wednesday, Friday) on which the coupon will be active.

* **Time Intervals:** Set start and end times for each selected day, ensuring the coupon is only valid during these periods.

* **Invalid Days Warning:** Customize a message to inform users when a coupon is not available on a certain day.

**Benefits:**

* **Precise Targeting:** Align coupon availability with your peak sales days or special events.

* **Enhanced Control:** Prevent overuse of coupons by limiting their validity to specific days and times.

* **Improved Customer Experience:** Provide clear communication to customers about when they can use the coupon, reducing confusion and enhancing satisfaction.

= Enhanced Coupon Usage Reset Options (PRO) =
The PRO version of our plugin includes advanced features that allow administrators to reset the coupon usage count and user limit count at various intervals. These intervals include daily, weekly, monthly, and yearly resets. This enhancement provides greater flexibility and control over coupon usage, ensuring that promotional campaigns can be managed more effectively and efficiently.

This feature is ideal for managing recurring promotions and ensuring that your coupon usage aligns with your marketing strategy.

e.g.: Let's say you're running a special promotion where you allow customers to use a discount coupon up to 3 times per month. With the advanced reset options in the PRO version, you can set the usage count and user limit to reset on a monthly basis. Here's how you can use it:

**Step-by-Step Example:**

1. **Create a Coupon:** Go to your WooCommerce dashboard and create a new coupon or edit an existing one.

2. **Set Usage Limits:** In the "Usage limits" section, set the "Usage limit per coupon" to 100 and the "Usage limit per user" to 3.

3. **Enable Reset Options:** Choose the "Monthly" reset option from the dropdown menu. This ensures that both the coupon usage count and the user limit count will reset at the beginning of each month.

4. **Save Changes:** Save the coupon settings.

**How It Works:**

* Each customer can use the coupon up to 3 times within a month.

* The total coupon usage is limited to 100 times within a month.

* At the beginning of the next month, both counts (per user and per coupon) will reset, allowing the coupon to be used afresh for the new month.

== Add product by Coupon ==
Give a coupon code that automatically adds products to the cart either for free or with a special discount.

This feature allows you to create a coupon that, when applied, automatically adds specific products to the user's cart. This is particularly useful for promotional campaigns where you want to encourage customers to purchase additional items or to provide a free gift with a purchase.


== Frequently Asked Questions ==
= Auto-add product to the user's cart when a URL coupon is applied =
Yes, you can specify a different set of products that should be auto-added to the user's cart when a certain URL coupon link is clicked or visited.

= The plugin will generate the QR code of the coupon =
Yes, the QR code will be generated for the coupon; your customer can scan the QR code and the coupon will be applied.

= Can I download the QR Code? =
Yes, you can download the QR code.

= Apply coupon when a specific product is added to the cart? =
Yes, you can configure a product to apply coupon, when that product is added to the cart the coupon will be applied

= Can I assign an add-to-cart coupon to a category? =
Yes, you can assign a coupon to a category; when a product from that category is added to the cart the coupon will be applied automatically.

= HPOS compatible =
Yes plugin support HPOS

= What are Attribute-Based Coupon Restrictions? =
Attribute-Based Coupon Restrictions allow you to apply or exclude coupons based on specific product attributes in your WooCommerce store. This feature gives you greater control over which products are eligible for discounts.

= How do I enable Attribute-Based Coupon Restrictions for a coupon? =
To enable Attribute-Based Coupon Restrictions, go to the coupon edit screen in your WooCommerce dashboard. Navigate to the "Attribute Restrictions" tab, where you can select the attributes you want to include or exclude from the coupon eligibility.

= Can I use multiple attributes for a single coupon? =
Yes, you can select multiple attributes to include or exclude when setting up your coupon restrictions. This allows you to create complex discount rules tailored to your store’s needs.

= What happens if a product has multiple attributes? =
If a product has multiple attributes, the coupon will be applied or excluded based on the attribute conditions you set. For example, if you include the attribute "Color: Red" and exclude "Size: Large", the coupon will apply to products that are red but not large.

= Can I combine Attribute-Based Coupon Restrictions with other coupon restrictions? =
Yes, you can combine Attribute-Based Coupon Restrictions with other coupon settings, such as minimum spend, product categories, and usage limits, to create comprehensive discount rules.

= Will the Attribute-Based Coupon Restrictions work with variable products? =
Yes, the restrictions will work with variable products. You can target specific variations based on their attributes, ensuring that the coupon is applied correctly to the desired variations.

= How do I troubleshoot if the coupon is not applying as expected? =
If the coupon is not applying as expected, double-check the attribute restrictions you have set. Ensure that the products in your cart meet the attribute conditions specified in the coupon settings. Additionally, make sure there are no conflicting restrictions that might prevent the coupon from being applied.

= Is there a limit to the number of attributes I can restrict? =
There is no limit to the number of attributes you can restrict. You can include or exclude as many attributes as needed to meet your promotional goals.

= How can I view which orders a coupon has been used in? =
Navigate to the WooCommerce Coupons section in your WordPress admin panel. Look for the "Used in order" column, which displays a link for each coupon. Clicking this link will reveal a list of orders where the coupon has been applied.

= How do I exclude specific email addresses from using a coupon? =
You can exclude specific email addresses by listing them in the coupon Usage restriction > Exclude email section. Separate multiple email addresses with commas. For example, xyz@example.com, abc@example.com.

= Can I exclude entire domains from using a coupon? =
Yes, you can exclude entire domains by using wildcard exceptions. For instance, *@gmail.com will exclude all email addresses ending with @gmail.com from using the coupon.

= What happens if an excluded email attempts to use the coupon? =
If a customer tries to apply the coupon with an excluded email address or domain, the coupon will not be applied, and an error message will be displayed during checkout. If the user has not yet added their email ID and tries to add the coupon, the coupon will get applied, but when the user adds their email ID and it matches the excluded email ID, the coupon will be set to 0 and will be removed on checkout.

= Can I combine specific email exclusions with domain exceptions? =
Yes, you can combine both specific email exclusions and domain exceptions. This allows for flexible management of coupon restrictions based on customer email addresses. e.g., test@example.com, john@example.com, *@gmail.com, *@yahoo.com.

= Can I restrict a coupon to apply only for specific payment methods? =
Yes, our plugin now allows you to restrict coupon usage to selected payment methods. Simply choose the payment methods you want to include when setting up your coupon.

= Can I exclude certain user roles from using a coupon? =
Yes, you can now restrict coupon usage based on user roles. Choose the user roles you want to include or exclude when setting up your coupon to control who can apply the discount.

= Can I set different messages for different coupons? =
Yes, you can set unique messages for each coupon. This allows you to tailor the messaging based on the specific promotion or discount associated with each coupon.

= What kind of messages can I customize? =
You can customize various types of messages, such as:

* A success message indicating the coupon has been applied in their session when they visit the site using a URL coupon
* A conditional message informing the customer that the coupon has been added but not yet applied due to certain conditions.

= What is a Store Credit coupon? =
Store Credit is a form of virtual currency that can be used as a payment method for future purchases on our store. It acts like a gift card that retains its value until fully used.

= How do I use Store Credit for my purchases? =
During checkout, you can apply your Store Credit by entering the coupon code provided to you. The available balance will be deducted from your total order amount. Any remaining balance can be used for future purchases.

= Can customers use Store Credit partially? =
Yes, you can use Store Credit partially. If your Store Credit balance exceeds the total order amount, the remaining balance will stay on your account for future purchases.

= Can Store Credit expire? =
You can configure an expiry date in the coupon setting; after the expiry date, the store credit will not be usable.

= What happens to unused Store Credit if I return a purchase made with Store Credit? =
If you return a purchase made using Store Credit, the refunded amount will be issued back as Store Credit, which will be added to your existing Store Credit balance.

= Where can the customer check their Store Credit balance? =
If store credit was associated with an email ID, the store credit coupon will be shown in the My Account section of the user with that email ID. If the store credit coupon was general and not linked to any email ID, the store credit coupon will be shown on the cart page when the user applies the coupon.

= How can I issue a refund as store credit? =
To issue a refund as store credit, navigate to the order edit page in your WooCommerce dashboard. From there, select the option to issue a refund. Choose whether you want to issue a full or partial refund and select the store credit option. Enter the amount to be refunded as store credit and confirm the refund.

= How does issuing a refund as store credit benefit my store? =
Issuing refunds as store credit encourages customers to make future purchases, helping you retain customer loyalty. It also simplifies the refund process and enhances the customer experience by providing instant credit for future use.

= Can store credit be used for any product in the store? =
Yes, store credit issued to customers can be used for any product available in your WooCommerce store. Customers can apply their store credit during checkout for their next purchase.

= Can customers request a cash refund instead of store credit? =
As the store administrator, you have the flexibility to choose whether to issue a refund as store credit or through other means such as cash or the original payment method. Customers can request their preferred refund method, and you can decide based on your store policies.

= How will customers know they received store credit as a refund? =
Customers will receive an email notification informing them that their refund has been issued as store credit. The email will include details of the store credit amount and the code.

= Can I send a store credit email manually? =
Yes, you can send a store credit email manually from the coupon edit page. Simply select the option to send a store credit email, and the customer will receive an email notification with the store credit details.

= How to see the store credit of a particular user =
* Admin can go to the coupon list page and search for coupons by email ID (and select the coupon type as store credit); the store credit coupon will be shown in the list.

= Can I make a coupon auto-apply to the user's cart when the conditions are satisfied? =
* Yes, you can make the coupon auto-apply to the user's cart when the conditions are satisfied.

= Offer shipping discount by applying a coupon =
* You can set the shipping discount amount in the coupon settings; you can configure percentage discounts, fixed amount discounts, or change the shipping cost to a fixed amount once the coupon is applied. You can even exclude shipping methods from this discount offer.

= How can I set multiple date and time ranges for my WooCommerce coupons? =
You can easily set multiple date and time ranges using the Advanced Coupon Scheduling feature in our WooCommerce plugin. This allows you to specify start and end dates, along with specific time intervals for each range, ensuring your coupons are only valid within the predefined periods.

= How does day-based scheduling work in the WooCommerce Coupon? =
The Pro version of our plugin allows you to set specific days and time intervals for when your coupons will be valid. You can choose the days of the week and the time periods for each selected day, ensuring your coupons are only valid during these times.

= How can I reset the coupon usage count and user limit count in WooCommerce coupon? =
In the PRO version, navigate to the "Usage limits" section under "Coupon data." Here, you will find options to reset the usage limit per coupon and per user. You can choose to reset these limits on a daily, weekly, monthly, or yearly basis by selecting the appropriate option from the dropdown menu.

= What does the "Add product by Coupon" feature do? =
It automatically adds specific products to the cart when a customer applies a coupon. The added product can be free, discounted, at its original price, or at a new price.


== Screenshots ==

1. Plugin options are present in Woocommerce > Url coupon.
2. Basic settings of Woocommerce coupon link.
3. Auto add product to cart when url coupon gets applied
4. QR code for the WooCommerce coupon link.
5. Woocommerce coupon plugin
6. Add WooCommerce coupon to the product that will get applied when the product will be added to the cart
7. Restrict WooCommerce coupon by product attribute
8. Woocommerce coupon plugin
9. Message shown when the WooCommerce coupon by link is applied to the user session
10. Coupon amount can be made to act like store credit so users can use the same coupon on multiple occasions; say the initial amount is $100 and they used it once to get a discount of $4, then that coupon will be $96 available for the next purchase
11. Make WooCommerce coupon behave like a store credit coupon, so users can use it multiple times
12. You can issue a refund as a store credit coupon directly from the order edit page
13. Email sent to the customer when they receive a refund in the form of a store credit coupon
14. WooCommerce coupon plugin allows you to restrict the coupon by day of the week, payment methods, user role, user email
15. Woocommerce coupon plugin
16. Discount plugin allows you to restrict the coupon by billing country
17. Allows you to restrict the coupon by payment methods
18. Woocommerce coupon plugin
19. Shipping discount coupon, that when applied will give a discount on all the shipping methods (so you can offer 10% discount on shipping, and your Express shipping which was $10 will now be $9)
20. Configure shipping discount coupon; you can apply the offer on all the shipping methods or some shipping methods
21. Schedule the coupon to be valid for specific date and time ranges, so you can set a coupon to be valid from 1st Jan 2024 to 31st Jan 2024 and only between 10:00 AM to 5:00 PM
22. In the Pro version you can make the coupon available by days of the week, so you can set a coupon to be valid on Monday, Wednesday and Friday only between 10:00 AM to 5:00 PM
23. Pro version allows you to reset the coupon usage count and user limit count at various intervals like daily, weekly, monthly, and yearly
24. Advanced conditions for coupons; you can create complex logical conditions to restrict coupon usage.
26. Add product by coupon, so when a user applies a coupon it will automatically add a product to the cart; you can set the product to be free or discounted or at its original price

== Changelog ==

= 1.2.29 =
* [added] Organize coupons by categories feature added

= 1.2.26 =
* [fix] store credit fixed for block based checkout page

= 1.2.24 =
* [fix] Gift4U plugin conflict fixed

= 1.2.23 =
* [modified] Changed the way we get order id in order edit page 

= 1.2.22 =
* virtual and non virtual product quantity condition added for coupon restriction

= 1.2.20 =
* guest customer email now read properly for email restriction condition

= 1.2.14 =
* Tested for WC 10.1.0

= 1.2.13 =
* Deactivate free version when pro version is activated

= 1.2.11 =
* Tested for WC 10.0.2

= 1.2.10 =
* UI improved for WooCommerce coupon plugin

= 1.2.9 =
* Version number recording feature added to WooCommerce coupon plugin
* Add product by coupon feature added to Woocommerce coupon plugin
* Advanced coupon plugin tested for WC 9.9.5

= 1.2.7 =
* WooCommerce coupon module Tested for WC 9.9.3

= 1.2.6 =
* Tested for WC 9.8.0

= 1.2.4 =
* Tested for WP 6.8.0

= 1.2.2 =
* Advance conditions added for coupon restriction

= 1.2.1 =
* change in promotional banner

= 1.2.0 =
* Tested for WC 9.7.0

= 1.1.79 = 
* pro version launched
* date range for coupon restriction added

= 1.1.77 =
* Tested for WC 9.6.0

= 1.1.76 =
* shipping method discount using coupon

= 1.1.74 =
* Auto add product to cart when coupon is applied
* Loops issue fixed when conditional coupon was applied by url or QR code
* Fix issue: url coupon (that was conditional) not getting removed from cart once applied by url coupon

= 1.1.73 =
* Store credit email rectified
* new function to get original amount of the store credit coupon

= 1.1.72 =
* Disable payment method based when the coupon is applied

= 1.1.71 =
* looping issue fixed for auto apply coupon with condition

= 1.1.70 =
* payment method rule now works in the checkout block as well 

= 1.1.69 =
* auto-apply coupon option added for the coupon

= 1.1.67 =
* day-based coupon restriction added

= 1.1.66 =
* Tested for WC 9.4.0

= 1.1.64 =
* Tested for WP 6.7.0

= 1.1.63 =
* Removed apply_coupon code from URL; now you can enable it by this filter `add_filter('pisol_acblw_redirect_to_remove_coupon', '__return_true');`

= 1.1.62 =
* Remove apply_coupon code from URL if the coupon applied has auto add product in it, that way it won't keep adding product to cart on page refresh since URL has changed and now it doesn't have apply_coupon variable  

= 1.1.61 = 
* Tested for WC 9.3.3

= 1.1.60 =
* Tested for WC 9.3.0

= 1.1.49 =
* Tested for WC 9.2.3

= 1.1.47 =
* Tested for WC 9.2.0

= 1.1.44 =
* PHP 8.2 compatible

= 1.1.43 =
* fallback to fixed cart calculation
* Now you can find the store credit coupon by email id in the coupon list page

= 1.1.42 =
* Send store credit emails manually

= 1.1.41 =
* Fixed "Our other plugins" tab

= 1.1.40 =
* Email sent to the customer when they receive a refund in the form of a store credit coupon

= 1.1.39 =
* Issue refund as store credit directly from the order page

= 1.1.37 =
* Store Credit Coupon option provided 

= 1.1.36 =
* Added custom message for each coupon

= 1.1.34 =
* Payment Methods Restriction for Coupons
* User Role Restrictions for Coupons

= 1.1.33 =
* Exclude email ID from using the coupon
* View orders where the coupon has been used


== Privacy ==

If you choose to opt in from the plugin settings, or submit optional feedback during deactivation, this plugin may collect basic technical information, including:

- Plugin version  
- WordPress version  
- WooCommerce version  
- Site URL
- Deactivation reason (if submitted)

This data is used solely to improve plugin quality, compatibility, and features. No personal or user-specific data is collected without consent.