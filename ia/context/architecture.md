# Architecture

## Style
Server-rendered PHP with reusable includes.

## Areas
- Public pages in `/public`
- Admin pages in `/admin`
- Shared logic in `/includes`
- Static assets in `/assets`
- Uploaded media in `/uploads`
- SQL in `/database`

## Routing
Apache mod_rewrite rewrites friendly URLs into PHP pages with query parameters.

## Data access
PDO only, explicit queries, prepared statements.
