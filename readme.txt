=== LLMs.txt Generator ===
Contributors: jesusfa
Tags: llms, ai, metadata, seo, prestashop
Requires at least: 1.7
Tested up to: 8.1
Stable tag: 1.0.0
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Generates an optimized llms.txt file for language models with shop metadata, excluding noindex content.

== Description ==
LLMs.txt Generator creates an `llms.txt` file in your PrestaShop root directory, providing a structured representation of your shop's content for AI models. It includes metadata such as shop name, description, URLs by language, social media, contact details, CMS pages, products, and categories, respecting PrestaShop's native SEO settings (noindex).

Features:
- Configurable settings in the backoffice under Modules > LLMs.txt Generator.
- Support for social media, phone, email, and contact page URLs.
- Automatic exclusion of noindex content.
- Compatible with PrestaShop's CMS, products, and categories.
- UTF-8 encoding with BOM for special characters.

== Installation ==
1. Download the module zip from GitHub or the PrestaShop Addons Marketplace.
2. Upload it via Modules > Module Manager > Upload a module in your PrestaShop backoffice.
3. Install and configure the module from Modules > LLMs.txt Generator.
4. Save settings and generate the llms.txt file manually if needed.

== Frequently Asked Questions ==
= What does the llms.txt file do? =
It provides a structured summary of your shop for AI models to improve understanding and indexing.

= Does it respect SEO settings? =
Yes, it excludes content marked as noindex in PrestaShop's SEO configuration.

= Can I customize the URLs? =
Yes, you can set URLs by language, social media, and more in the module settings.

== Screenshots ==
1. Configuration panel in Modules > LLMs.txt Generator.
2. Example llms.txt file output.

== Changelog ==
= 1.0.0 =
* Initial release with full functionality from WordPress adaptation.
* Added support for CMS pages, products, categories, and metadata.
* Included manual generation and deletion options.

== License ==
This module is licensed under the GNU General Public License version 2 (GPLv2) or later. You may modify and distribute it freely under the same terms. See <https://www.gnu.org/licenses/gpl-2.0.html> for details.
