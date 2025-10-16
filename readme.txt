==========================
Módulo llmstxt para PrestaShop
==========================

Nombre del módulo: llmstxt
Versión: 1.1.0
Compatibilidad: PrestaShop 1.7.0.0 – 8.99.99
Autor: Jesús Fernández Ávila
Licencia: MIT 

Descripción
-----------
Este módulo genera un archivo llms.txt en la raíz de tu tienda con una lista de URLs seleccionadas de tu catálogo. Sirve para compartir inventario de enlaces con herramientas externas o procesos internos, con control sobre qué URLs se incluyen o excluyen.

Novedades clave en 1.1.0
------------------------
- Renombrada la clase principal a `llmstxt` para que coincida exactamente con el nombre del módulo y evitar errores de instalación.
- Declaración de compatibilidad actualizada a 1.7.0.0–8.99.99.
- Métodos `install()` y `uninstall()` reforzados para devolver errores de forma más clara y evitar falsos negativos en la instalación.
- Documentación revisada y pasos de solución de problemas ampliados.

Requisitos
----------
- PHP 7.2 o superior (recomendado PHP 8.x).
- Extensiones: cURL, JSON, mbstring.
- Permisos de escritura en la raíz de la tienda para poder crear/actualizar `llms.txt`.

Estructura del módulo
---------------------
modules/
└── llmstxt/
    ├── llmstxt.php        (archivo principal, clase: llmstxt)
    ├── readme.txt         (este archivo)
    └── changelog.md

Instalación
-----------
1) Comprueba que la carpeta se llame exactamente `llmstxt` y que el archivo principal
   sea `llmstxt.php`.
2) Comprime el contenido de la carpeta (sin doble anidado) o súbelo por FTP a
   `/modules/llmstxt/`.
3) Entra en el back-office de PrestaShop:
   - Módulos > Manager de módulos > Subir un módulo (o buscar “llmstxt” y “Instalar”).
4) Tras instalar, accede a **Configurar** para ajustar las opciones del módulo.

Configuración
-------------
- Selección de tipos de URL: elige qué entidades (categorías, productos, CMS, etc.)
  se incluirán en el llms.txt.
- Exclusiones automáticas:
  - Páginas con `noindex` (meta robots) se ignoran.
  - URLs deshabilitadas o fuera de stock (si así se configura) no se incluirán.
- Codificación y formato:
  - El archivo se genera con codificación UTF-8 (con BOM) para compatibilidad universal.
  - Cada URL se escribe en una línea, sin parámetros innecesarios.

Uso
---
1) Ve a **Módulos > llmstxt > Configurar**.
2) Ajusta tus preferencias (qué URLs incluir/excluir).
3) Pulsa **Generar llms.txt**.
4) Verifica que se ha creado/actualizado en la raíz de la tienda:
   `https://tudominio.com/llms.txt`

Permisos de archivo
-------------------
- Asegúrate de que el usuario con el que corre PHP/PrestaShop tiene permisos de escritura
  en el directorio raíz de la tienda.
- En entornos con permisos restrictivos, puede ser necesario un `chmod 644` para el archivo
  y `chmod 755` para la carpeta, o los equivalentes en tu hosting.

Buenas prácticas
----------------
- Evita regenerar el archivo en cada carga pública (solo bajo demanda desde el BO).
- Controla el volumen: si tu catálogo es muy grande, filtra por criterios razonables
  (p. ej. solo categorías activas y productos en estado activo).
- Mantén el módulo y PrestaShop actualizados dentro del rango de compatibilidad indicado.

Solución de problemas
---------------------
- “No se puede instalar / módulo inválido”:
  - Verifica que la clase del archivo principal es `class llmstxt extends Module`.
  - Comprueba que la carpeta es `modules/llmstxt/` y el archivo se llama `llmstxt.php`.
  - Limpia cachés (Parámetros Avanzados > Rendimiento) y vuelve a intentar.
- “No se puede escribir el archivo llms.txt”:
  - Revisa permisos de la raíz y que no haya reglas del servidor que impidan escribir.
  - Genera el archivo desde el BO con un usuario con permisos suficientes.
- “Faltan URLs esperadas”:
  - Asegúrate de que esas páginas no tengan `noindex` o estén desactivadas.
  - Revisa el filtro de entidades configurado en el módulo.

Desinstalación
--------------
- Desde el manager de módulos, pulsa **Desinstalar**. El módulo elimina su configuración
  interna (`LLMSTXT_CONFIG`). Si deseas borrar `llms.txt`, hazlo manualmente para evitar
  borrar archivos creados/gestionados por procesos externos.

Soporte
-------
- Incidencias y soporte: [tu_email@dominio.com]
- Documentación y actualizaciones: [URL de tu repositorio o web]

Créditos
--------
- Desarrollo: [Tu nombre o agencia]
- Agradecimientos: comunidad PrestaShop
