# Agent Operating Guide

## Objective
Build and maintain a local services directory platform using PHP, MySQL and a lightweight frontend, optimized for shared hosting.

## Operating principles
1. Build in chronological modules.
2. Do not skip data modeling.
3. Keep the public area and admin area separated.
4. Prefer clear server-side rendering.
5. Avoid adding dependencies without a strong reason.
6. Always preserve shared-hosting compatibility.

## Recommended implementation order
1. Database schema
2. Core includes
3. Public pages
4. Admin auth
5. Admin CRUD
6. Uploads
7. Search
8. SEO and friendly URLs
9. Security review
10. Deploy preparation

## Response format for the agent
- Files changed
- Why they changed
- Code
- Validation checklist

## Never do this by default
- Add frameworks
- Add Docker-only assumptions
- Add Node/Vite/Webpack
- Replace procedural PHP with a full custom MVC unless explicitly asked

## Always consider
- Simplicity
- Shared hosting limits
- Performance on cheap plans
- Low token usage
