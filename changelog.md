# llmstxt - Changelog

## Version 1.0.0 (2025-03-27)
- **Initial Release**: Basic module structure with `llms.txt` generation.
- **Features Added**:
  - Custom fields: Name, description, keywords, address, contact URL, return policy, terms URL.
  - Dynamic content: Categories, products, CMS pages, and conditional blog posts (psblog).
  - UTF-8 encoding support with `utf8_encode()`.

## Updates
- **Tilde Fix (2025-03-27)**:
  - Replaced `utf8_encode()` with `mb_convert_encoding()` for proper UTF-8 handling.
  - Added BOM (\xEF\xBB\xBF) to ensure correct display of accents and special characters.
- **Structural Improvements (2025-03-27)**:
  - Added last update date next to "Información General".
  - Updated instructions for LLMs to prioritize categories and products.
  - Included sitemap.xml and robots.txt URLs.
  - Reordered fields in "Información General" (name, description, keywords, etc.).
  - Condensed category/product/page/blog info into single lines with `|` separators.
- **New Fields (2025-03-27)**:
  - Added: Facebook, Instagram, LinkedIn, 5 featured categories, 2 local business URLs, 2 phones, 2 emails.
  - Integrated new fields into "Información General" with updated order.
- **Reorganization (2025-03-27)**:
  - Split into sections: "Información General", "Información Web", "Redes sociales", "Categorías principales".
  - Added 6th featured category.
  - Two-column configuration UI in backoffice for better usability.
- **AI Optimization (2025-03-27)**:
  - Moved instructions to the top.
  - Simplified hierarchy: `#` for file title, `##` for all sections.
  - Renamed sections: "Negocio", "Web", "Redes Sociales", "Categorías Principales", etc.
  - Integrated URLs by language into "Web" section.
  - Standardized format with `Clave: Valor` for consistency.

## Notes
- All changes were iterative based on user feedback to improve functionality and AI readability.
- The module remains at version 1.0.0 as no formal releases have been made beyond initial development.
