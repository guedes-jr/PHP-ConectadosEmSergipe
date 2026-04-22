# Data Model Notes

## usuarios
Admin-only authentication for MVP.

## categorias
Friendly slug required for category URLs.

## anuncios
Main business table. Should keep `slug`, `cidade`, `status`, `destaque` indexed.

## imagens
One-to-many relationship with anuncios.
