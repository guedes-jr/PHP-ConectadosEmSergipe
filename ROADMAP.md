# 🚀 Roadmap de Desenvolvimento — Conectado em Sergipe

Este documento descreve as etapas de modernização, funcionalidades implementadas e o planejamento futuro para a plataforma.

## 🟢 Fase 1: Infraestrutura e Design Base (Concluído)
- [x] Modelagem do Banco de Dados (Anúncios, Categorias, Imagens, Horários)
- [x] Sistema de Autenticação Administrativa
- [x] Redesign Premium do Painel Administrativo (Estilo Lovable/Moderno)
- [x] Componentização do Layout (Sidebar, Header, Footer Reutilizáveis)
- [x] Sistema de Temas (Claro/Escuro) com persistência
- [x] Página Pública de Detalhes do Anúncio (Vitrines Dinâmicas)
- [x] Gestão de Clientes/Profissionais no Admin
- [x] Fluxo de Criação de Anúncios com múltiplos uploads e horários

## 🟡 Fase 2: Dinamização e Personalização (Em Andamento)
- [x] **Módulo de Configurações Gerais:**
    - [x] Criar tabela `configuracoes` para textos e links globais.
    - [x] Interface no Admin para editar WhatsApp, Redes Sociais e SEO do site.
- [x] **Home Page Totalmente Dinâmica:**
    - [x] Dinamizar o Hero Slider (Imagens e Textos via Admin).
    - [x] Vincular Filtros de Busca e "Pills" às categorias reais do banco.
    - [x] Implementar lógica de busca funcional no cabeçalho da Home.
- [x] **Gestão de Categorias no Admin:**
    - [x] Interface para Adicionar/Editar categorias e escolher ícones SVG.
- [x] **SEO Avançado:**
    - [x] Implementação de Meta Tags dinâmicas e OpenGraph para compartilhamento social.

## 🔵 Fase 3: Funcionalidades Avançadas (Futuro)
- [ ] **Área do Prestador:**
    - [ ] Login independente para que o profissional edite seu próprio perfil.
- [ ] **Sistema de Prova Social:**
    - [ ] Módulo de Avaliações e Notas (Rating) para os anúncios.
    - [ ] Moderação de comentários pelo administrador.
- [ ] ** Monetização e Destaques:**
    - [ ] Sistema de selos "Verificado" e anúncios "Premium".
    - [ ] Integração de pagamentos para destaques (opcional).
- [ ] **Geolocalização:**
    - [ ] Busca por proximidade e visualização em Mapa.

---

## 📈 Sugestões de Melhoria Técnica
- **Performance:** Implementar Lazy Loading em todas as imagens da galeria.
- **Segurança:** Adicionar Rate Limiting nas rotas de login para prevenir brute-force.
- **UX:** Adicionar Skeleton Screens no carregamento de listas pesadas.
- **Manutenibilidade:** Migrar lógica de banco para uma camada de Repositório (OPCIONAL).

---
*Última atualização: 02 de Maio de 2026*
