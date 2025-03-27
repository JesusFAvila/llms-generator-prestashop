# llmstxt - PrestaShop Module

## Overview
llmstxt is a PrestaShop module designed to generate a structured `llms.txt` file optimized for language models (LLMs). It provides essential information about an online store, including business details, web data, social media, key categories, and dynamic content like categories, products, CMS pages, and blog posts (if applicable). The module aims to facilitate navigation and promote sales by offering a clear, machine-readable format.

## Features
- Generates a `llms.txt` file at the root of your PrestaShop installation.
- Customizable fields for business info, contact details, social media, and featured categories.
- Automatically includes store categories, products, CMS pages, and blog posts (if the blog module is installed).
- UTF-8 encoding with BOM for proper display of special characters (e.g., accents, ñ).
- Two-column configuration interface in the PrestaShop backoffice for easy setup.
- Ignores URLs with meta 'no-index' to respect SEO settings.

## Installation
1. Upload the `llmstxt` folder to `/modules/` in your PrestaShop installation.
2. Go to **Modules > Module Manager** in the PrestaShop backoffice.
3. Search for "LLMs.txt Generator" and click **Install**.
4. After installation, click **Configure** to set up the module.

## Configuration
- Access the module configuration via **Modules > LLMs.txt Generator > Configure**.
- Fill in the fields in the two-column form:
  - **Column 1**: Business name, description, keywords, addresses, local business URLs, phones, emails.
  - **Column 2**: Policy URLs, social media links, and up to 6 featured categories.
- Save the settings and click "Generar llms.txt" to create or update the file.

## Output
The generated `llms.txt` file is saved at `[your-store-root]/llms.txt`. Example URL: `https://www.staring.es/llms.txt`.

## File Structure
The `llms.txt` file is organized as follows:
