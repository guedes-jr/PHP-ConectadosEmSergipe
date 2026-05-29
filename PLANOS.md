# Plano de Implementação — Página de Planos

## 1. Análise do Estado Atual

### 1.1 Estrutura existente
| Item | Status | Arquivo |
|------|--------|---------|
| `public/planos.php` | ✅ Existe, mas com **inline styles** e 3 planos antigos (Grátis, Premium R$49, Empresarial) | `public/planos.php` |
| Rota `/planos` no router | ❌ **Não existe** — clicar em "Planos" no nav retorna 404 | `router.php` |
| Link no header | ✅ Já presente no `nav-center` e no mobile menu | `includes/layout.php:34,46` |
| Link no footer | ❌ **Não existe** — footer "Institucional" não lista Planos | `includes/layout.php:71` |
| CSS de planos | ❌ **Não existe** — página usa inline styles | `assets/css/style.css` |

### 1.2 Padrões de estilo do projeto (a seguir)
- **Container**: `width: min(1200px, 100% - 4rem); margin-inline: auto`
- **Seções**: `<section class="section">` com `.section-head`, `.section-label`
- **Cards**: `border-radius: 1.5rem` (ou `1.25rem`), `border: 1px solid var(--border)`, `background: var(--card)`, sombras via `var(--shadow-md)`
- **Botões**: `.btn.btn-primary` (bg azul, white, radius 9999px) e `.btn.btn-outline`
- **Badges**: `.pill` para tags, `border-radius: 9999px`
- **Cores**: Variáveis CSS (`--primary`, `--card`, `--border`, `--muted-foreground`, etc.)
- **Tipografia**: Inter, pesos 400/500/600/700/800
- **Ícones**: Lucide via `<i data-lucide="nome"></i>`
- **Animações**: `transition: all 0.3s ease`, `box-shadow` em hover

---

## 2. O que precisa ser implementado

### 2.1 Novos planos (substituir os atuais)
```
Plano Básico — R$ 25,00/mês
Perfil com fotos, descrição, contatos e link para compartilhamento.

Plano Avançado — R$ 35,00/mês
Tudo do Básico + destaque no carrossel da página inicial +
mais visibilidade e chances de novos contatos.
```

### 2.2 Arquivos para modificar

| Ordem | Arquivo | Ação |
|-------|---------|------|
| 1 | `router.php` | Adicionar rota `/planos` |
| 2 | `assets/css/style.css` | Adicionar classes CSS `.plans-grid`, `.plan-card`, `.plan-badge`, `.plan-price`, `.plan-features`, `.plan-cta`, `.plan-popular` |
| 3 | `public/planos.php` | Reescrever conteúdo com classes CSS e novos planos |
| 4 | `includes/layout.php` | Adicionar link "Planos" no footer (coluna Institucional) |

---

## 3. Especificação Detalhada

### 3.1 `router.php` — Adicionar rota

**Localização**: antes do bloco de rotas admin (após `privacidade`, linha ~43)

```php
if ($path === '/planos') {
    require __DIR__ . '/public/planos.php';
    return;
}
```

### 3.2 `assets/css/style.css` — Novos estilos (inserir antes do primeiro media query)

```css
/* ========== PÁGINA DE PLANOS ========== */
.plans-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 2rem;
    margin-top: 3rem;
    align-items: start;
}

.plan-card {
    border: 1px solid var(--border);
    border-radius: 1.5rem;
    padding: 2.5rem 2rem;
    background: var(--card);
    position: relative;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
}

.plan-card:hover {
    box-shadow: var(--shadow-lg);
    transform: translateY(-4px);
}

.plan-card.plan-popular {
    border: 2px solid var(--primary);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
}

.plan-card.plan-popular:hover {
    box-shadow: 0 12px 32px rgba(37, 99, 235, 0.15);
}

.plan-badge {
    position: absolute;
    top: -0.875rem;
    left: 50%;
    transform: translateX(-50%);
    background: var(--primary);
    color: white;
    padding: 0.375rem 1.25rem;
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    white-space: nowrap;
}

.plan-name {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: var(--foreground);
}

.plan-desc {
    color: var(--muted-foreground);
    font-size: 0.95rem;
    margin-bottom: 1.5rem;
    line-height: 1.5;
}

.plan-price {
    margin-bottom: 2rem;
    display: flex;
    align-items: baseline;
    gap: 0.25rem;
}

.plan-price-amount {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--foreground);
    line-height: 1;
}

.plan-price-period {
    color: var(--muted-foreground);
    font-size: 0.9rem;
}

.plan-price-label {
    font-size: 0.85rem;
    color: var(--muted-foreground);
    display: block;
    margin-top: 0.25rem;
}

.plan-features {
    list-style: none;
    margin-bottom: 2rem;
    flex: 1;
}

.plan-features li {
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    font-size: 0.9rem;
    color: var(--muted-foreground);
    line-height: 1.4;
}

.plan-features li:last-child {
    border-bottom: none;
}

.plan-features .feat-icon {
    flex-shrink: 0;
    width: 18px;
    height: 18px;
    margin-top: 0.15rem;
}

.plan-features .feat-icon.check {
    color: #16a34a;
}

.plan-features .feat-icon.x {
    color: var(--muted);
}

.plan-cta {
    width: 100%;
    text-align: center;
    justify-content: center;
}

.plan-faq {
    margin-top: 4rem;
    padding: 2.5rem;
    background: var(--muted-bg);
    border-radius: 1.5rem;
    text-align: center;
}

.plan-faq h3 {
    font-size: 1.25rem;
    margin-bottom: 0.75rem;
    color: var(--foreground);
}

.plan-faq p {
    color: var(--muted-foreground);
    margin-bottom: 1.5rem;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
}

@media (max-width: 768px) {
    .plans-grid {
        grid-template-columns: 1fr;
        gap: 2rem;
    }

    .plan-card {
        padding: 2rem 1.5rem;
    }

    .plan-price-amount {
        font-size: 2rem;
    }
}
```

### 3.3 `public/planos.php` — Novo conteúdo

Substituir completamente o arquivo atual por:

```php
<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/layout.php';
require_once __DIR__ . '/../includes/seo.php';

render_header($pdo, seo_title('Planos'), 'Escolha o plano ideal para seu negócio em Sergipe.');
?>

<section class="section">
    <div class="container">
        <div class="section-head">
            <div>
                <span class="section-label">NOSSOS PLANOS</span>
                <h1>Impulsione seu negócio em Sergipe</h1>
                <p style="color: var(--muted-foreground); margin-top: 1rem; max-width: 600px;">
                    Escolha o plano que melhor se encaixa nos seus objetivos e aumente sua presença digital.
                </p>
            </div>
        </div>

        <div class="plans-grid">
            <!-- Plano Básico -->
            <div class="plan-card">
                <h2 class="plan-name">Plano Básico</h2>
                <p class="plan-desc">Perfeito para quem quer começar com presença digital.</p>
                <div class="plan-price">
                    <span class="plan-price-amount">R$ 25</span>
                    <span class="plan-price-period">/mês</span>
                </div>
                <ul class="plan-features">
                    <li>
                        <i data-lucide="check" class="feat-icon check"></i>
                        Perfil completo com fotos
                    </li>
                    <li>
                        <i data-lucide="check" class="feat-icon check"></i>
                        Descrição detalhada do seu serviço
                    </li>
                    <li>
                        <i data-lucide="check" class="feat-icon check"></i>
                        Contatos visíveis (WhatsApp, telefone, e-mail)
                    </li>
                    <li>
                        <i data-lucide="check" class="feat-icon check"></i>
                        Link exclusivo para compartilhar seu perfil
                    </li>
                </ul>
                <a href="/admin/criar" class="btn btn-primary plan-cta">Anunciar agora</a>
            </div>

            <!-- Plano Avançado (Destaque) -->
            <div class="plan-card plan-popular">
                <span class="plan-badge">MAIS VISIBILIDADE</span>
                <h2 class="plan-name">Plano Avançado</h2>
                <p class="plan-desc">Tudo do Plano Básico + destaque em evidência no site.</p>
                <div class="plan-price">
                    <span class="plan-price-amount">R$ 35</span>
                    <span class="plan-price-period">/mês</span>
                </div>
                <ul class="plan-features">
                    <li>
                        <i data-lucide="check" class="feat-icon check"></i>
                        Perfil completo com fotos
                    </li>
                    <li>
                        <i data-lucide="check" class="feat-icon check"></i>
                        Descrição detalhada do seu serviço
                    </li>
                    <li>
                        <i data-lucide="check" class="feat-icon check"></i>
                        Contatos visíveis (WhatsApp, telefone, e-mail)
                    </li>
                    <li>
                        <i data-lucide="check" class="feat-icon check"></i>
                        Link exclusivo para compartilhar seu perfil
                    </li>
                    <li>
                        <i data-lucide="star" class="feat-icon check"></i>
                        <strong>Destaque no carrossel</strong> da página inicial
                    </li>
                    <li>
                        <i data-lucide="trending-up" class="feat-icon check"></i>
                        Sua marca em evidência para todos os visitantes
                    </li>
                    <li>
                        <i data-lucide="users" class="feat-icon check"></i>
                        Mais visibilidade e chances de novos clientes
                    </li>
                </ul>
                <a href="/admin/criar" class="btn btn-primary plan-cta">Anunciar agora</a>
            </div>
        </div>

        <div class="plan-faq">
            <h3>Dúvidas sobre nossos planos?</h3>
            <p>Fale conosco pelo WhatsApp e descubra a melhor opção para seu negócio.</p>
            <a href="https://wa.me/5579999999999?text=Olá, gostaria de saber mais sobre os planos" target="_blank" class="btn btn-primary">
                <i data-lucide="message-circle" style="width: 16px; height: 16px;"></i>
                Conversar no WhatsApp
            </a>
        </div>
    </div>
</section>

<?php render_footer(); ?>
```

### 3.4 `includes/layout.php` — Adicionar link no footer

No footer, coluna "Institucional" (linha 71), adicionar antes do fechamento do `<nav>`:

```html
<a href="<?php echo url('/planos'); ?>">Planos</a>
```

---

## 4. Checklist de Validação

- [ ] Rota `/planos` registrada em `router.php` antes do bloco admin
- [ ] CSS responsivo testado em mobile (grid vira 1 coluna)
- [ ] Dark theme funcionando (tudo usa `var(--*)`)
- [ ] Preços exibidos corretamente: R$ 25/mês e R$ 35/mês
- [ ] Ícones Lucide renderizando (`lucide.createIcons()` roda no footer)
- [ ] Links WhatsApp com número correto
- [ ] Link "Planos" no nav leva para a página (não dá 404)
- [ ] Link "Planos" aparece no footer
- [ ] `<?php echo e(...) ?>` em todos os outputs dinâmicos (se houver)
- [ ] Sem inline styles no HTML (tudo via classes CSS)
- [ ] `section.section` com `container` para consistência visual

---

## 5. Diferenças do estado atual para o novo

| Aspecto | Atual (inline styles) | Novo (classes CSS) |
|---------|----------------------|--------------------|
| Estilo | Inline `style="..."` | Classes CSS no `style.css` |
| Planos | Grátis / Premium R$49 / Empresarial | Básico R$25 / Avançado R$35 |
| Cards | `border-radius: 0.75rem` | `border-radius: 1.5rem` (padrão do site) |
| Hover | Nenhum | Sobe 4px + sombra |
| Ícones | Texto "✓" e "✗" | Lucide icons (`check`, `star`, `trending-up`, `users`) |
| Destaque | `box-shadow` + borda azul | Mesmo + badge "MAIS VISIBILIDADE" |
| Responsivo | Não tem | Media query 768px |
