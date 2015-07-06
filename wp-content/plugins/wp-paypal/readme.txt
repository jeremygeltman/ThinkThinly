=== WordPress PayPal ===
Contributors: naa986
Donate link: http://wphowto.net/
Tags: paypal, button, e-commerce, ecommerce, commerce, sell, sales, shipping, shop, shopping, store, tax, wordpress ecommerce, cart, checkout, donation, donations, payment, subscription, subscriptions
Requires at least: 3.0
Tested up to: 4.2
Stable tag: 1.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easily accept payments in WordPress by adding PayPal buttons to your website

== Description ==

[WP PayPal](http://wphowto.net/wordpress-paypal-plugin-732) plugin allows you to easily create PayPal Buy Now, Add to Cart, Donation or Subscription type buttons. It generates dynamic buttons using shortcodes that enable PayPal checkout on your WordPress site.

= Features =

* Create PayPal buttons on the fly in a post/page using shortcodes
* Accept once off payments or recurring payments
* Accept donations from users
* Allow users to add multiple items to the shopping cart and checkout
* Manage orders received via PayPal buttons from your WordPrss admin dashboard
* Quick settings configurations
* Enable debug to troubleshoot various issues (e.g. orders not getting updated)
* Accept subscriptions on a daily, weekly, monthly or yearly basis
* Sell items with different variation options (e.g. size, color, price)
* Switch your store to PayPal sandbox mode for testing

= Usage =

Once you have installed this plugin you need to go to the settings menu to configure some default options (WP PayPal -> Settings).

* PayPal Email: Your PayPal email address
* Currency Code: The default currency code

In order to create a button insert the shortcode like the following:

= PayPal Buy Now =

Buy Now buttons are for single item purchases. In order to create a buy button you need to specify it in the button parameter of the shortcode.

`[wp_paypal button="buynow" name="My product" amount="1.00"]`

= PayPal Add to Cart =

Add To Cart buttons let users add multiple items to their PayPal shopping cart and checkout.

`[wp_paypal button="cart" name="My product" amount="1.00"]`

= PayPal Donation =

Donation buttons let you accept donations from your users.

`[wp_paypal button="donate" name="My product" amount="1.00"]`

= PayPal Subscription =

Subscribe buttons let you set up payment subscriptions. 

`[wp_paypal button="subscribe" name="My product" amount="1.00" recurrence="4" period="M"]`

= Button Parameters =

You can use additional parameters to customize your PayPal buttons.

* **type** - The type of button to render (e.g. "buynow", "cart", "donate" or "subscribe")
* **name** - Description of the item.
* **number** - The number of the item (Also known as SKU. e.g. number="16").
* **amount**- The price of the item (e.g. amount="4.95").
* **currency** - The currency of the item (e.g. currency="USD").
* **quantity** - Quantity of items to purchase (e.g. quantity="2").
* **shipping** - The cost of shipping this item. (e.g. shipping="0.75").
* **tax** - Transaction-based tax override variable (e.g. tax="2.99").
* **locale** - The desired locale of the PayPal site (e.g. locale="GB"). This feature is useful if you want to render the payment page in a specific language.
* **return** - The URL where the user will be redirected to after the payment (e.g. return="http://example.com/thank-you").

For detailed documentation please check out the [WP PayPal Plugin](http://wphowto.net/wordpress-paypal-plugin-732) page.

== Installation ==

1. Go to the Add New plugins screen in your WordPress Dashboard
1. Click the upload tab
1. Browse for the plugin file (wp-paypal.zip) on your computer
1. Click "Install Now" and then hit the activate button

== Frequently Asked Questions ==

= Can I accept PayPal payments in WordPress using this plugin? =

Yes.

== Screenshots ==

1. PayPal Button Demo
2. PayPal Orders

== Upgrade Notice ==
none

== Changelog ==

= 1.0.1 =
* First commit
