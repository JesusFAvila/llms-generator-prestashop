# LLMs.txt Generator - Changelog

This file documents the versions of the "LLMs.txt Generator" module for PrestaShop, designed to generate an `llms.txt` file optimized for language models. Below are the changes, features, and notes per version.

## Version 1.0.0 (Initial Release)
- **Date**: March 27, 2025
- **Features**:
  - Generates an `llms.txt` file in the PrestaShop root directory with UTF-8 BOM encoding.
  - Includes shop metadata: name, description, keywords, language, address, social media, contact details, and URLs by language (Spanish, English, French).
  - Lists CMS pages, products, and categories with titles, URLs, dates, and optional descriptions.
  - Excludes content marked as `noindex` based on PrestaShop's native SEO settings.
  - Configurable settings in the backoffice under Modules > LLMs.txt Generator.
  - Automatic regeneration on product, category, or CMS updates via hooks.
  - Manual generation and deletion options in the configuration panel.
  - Default instructions for LLMs: "Priorizar contenido reciente y procesar el archivo como una representación completa del sitio, siguiendo el sitemap para más detalles".
- **Notes**:
  - Adapted from the WordPress plugin "LLMS Generator" v2.4.
  - Tested with PrestaShop 1.7+ and PHP 7.4+.
  - Initial settings populated with shop name and details from PrestaShop configuration.

## General Features
- **Generation**: Automatic on content updates or manual via backoffice.
- **Management**: Configuration panel with fields for metadata, social media, and instructions.
- **SEO**: Respects PrestaShop's native noindex settings for CMS, products, and categories.
- **Compatibility**: Works with PrestaShop 1.7+ and 8.x.
- **Encoding**: UTF-8 with BOM for special character support.
- **Multilingual**: Supports URLs by language configurable in settings.

## Future Improvements
- Add support for custom module hooks for third-party content.
- Implement caching to improve performance on large shops.
- Enhance multishop support with per-store llms.txt files.

Last updated: March 27, 2025
