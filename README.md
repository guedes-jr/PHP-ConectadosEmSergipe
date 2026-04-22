# Guia de Serviços Locais

Plataforma web leve para divulgação de prestadores de serviços e produtos locais, com painel administrativo simples para cadastro e gestão de anúncios.

## Stack
- PHP 8+
- MySQL / MariaDB
- HTML5 + CSS3 + JS leve
- Apache with mod_rewrite
- Compatível com Hostinger

## Estrutura
- `public/` - páginas públicas (home, categoria, busca, anúncio)
- `admin/` - autenticação e CRUD
- `includes/` - conexão, autenticação, helpers e config
- `database/` - schema SQL
- `uploads/` - imagens dos anúncios
- `assets/` - CSS, JS e imagens estáticas
- `ia/` - contexto, prompts e playbooks para agentes
- `router.php` - rotas amigáveis

## Configuração Inicial

1. Importar `database/schema.sql` no MySQL
2. Copiar `includes/config.php` e ajustar credenciais
3. Acessar `/admin` e fazer login (seed: admin / admin123)
4. Configurar .htaccess para URL amigável

## Requisitos mínimos
- PHP 8.0 ou superior
- PDO MySQL habilitado
- mod_rewrite habilitado
- Permissão de escrita em `uploads/anuncios/`

## Segurança
- PDO prepared statements em todas consultas
- CSRF protection em todos forms admin
- Password hashing com password_hash()
- Upload protegido com .htaccess
- Sessões seguras com regenerate_id

## Comandos Make

```bash
# Desenvolvimento
make start           # Iniciar servidor PHP em localhost:8000
make logs           # Acompanhar logs em tempo real
make logs-err       # Ver apenas erros
make logs-access    # Ver logs HTTP
make clean          # Limpar cache

# Git (use apenas msg)
make commit-feat    msg='nova funcionalidade'
make commit-fix     msg='correção de bug'
make commit-style   msg='ajuste visual'
make commit-refactor msg='refatoração'
make commit-perf    msg='otimização'
make commit-chore   msg='manutenção'
make commit-docs    msg='documentação'
make commit-test   msg='testes'
make push           # Enviar para remoto
make pull          # Baixar do remoto
make status        # Ver status
```

### Exemplos

```bash
make commit-feat msg='adicionar busca por categoria'
make commit-fix msg='corrigir filtro de anúncios'
make commit-style msg='ajustar css do header'
make commit-perf msg='otimizar query de busca'
```

## Próximas implementações
- Upload múltiplo de imagens
- Busca avançaada
- SEO (meta tags, sitemap)
