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
                <div class="plan-card-badges">
                    <span class="pill pill-green">ECONOMIZE 50%</span>
                </div>
                <h2 class="plan-name">Plano Básico</h2>
                <p class="plan-desc">Presença digital completa para seu negócio.</p>
                <div class="plan-price">
                    <span class="plan-price-old">R$ 50</span>
                    <span class="plan-price-amount">R$ 25</span>
                    <span class="plan-price-period">/mês</span>
                </div>
                <span class="plan-save-badge">Você economiza <strong>R$ 25/mês</strong></span>
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
                        <i data-lucide="check" class="feat-icon check"></i>
                        Gestão simplificada do seu anúncio
                    </li>
                </ul>
                <a href="https://wa.me/557996327084?text=Olá, tenho interesse no Plano Básico de R$ 25/mês do Conectado em Sergipe" target="_blank" class="btn btn-primary plan-cta">Quero o Plano Básico</a>
            </div>

            <!-- Plano Avançado (Destaque) -->
            <div class="plan-card plan-popular">
                <div class="plan-card-badges">
                    <span class="plan-badge-top">MAIS VISIBILIDADE</span>
                    <span class="pill pill-green">ECONOMIZE 50%</span>
                </div>
                <h2 class="plan-name">Plano Avançado</h2>
                <p class="plan-desc">Tudo do Básico + destaque em evidência no site.</p>
                <div class="plan-price">
                    <span class="plan-price-old">R$ 70</span>
                    <span class="plan-price-amount">R$ 35</span>
                    <span class="plan-price-period">/mês</span>
                </div>
                <span class="plan-save-badge">Você economiza <strong>R$ 35/mês</strong></span>
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
                        <i data-lucide="check" class="feat-icon check"></i>
                        Gestão simplificada do seu anúncio
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
                <a href="https://wa.me/557996327084?text=Olá, tenho interesse no Plano Avançado de R$ 35/mês do Conectado em Sergipe" target="_blank" class="btn btn-primary plan-cta">Quero o Plano Avançado</a>
            </div>
        </div>

        <!-- Comparativo Rápido -->
        <div class="plan-compare">
            <h3>Comparativo dos planos</h3>
            <div class="compare-table">
                <div class="compare-row compare-header">
                    <span class="compare-feat">Recursos</span>
                    <span class="compare-cell">Básico</span>
                    <span class="compare-cell highlight">Avançado</span>
                </div>
                <div class="compare-row">
                    <span class="compare-feat">Perfil com fotos</span>
                    <span class="compare-cell"><i data-lucide="check" class="feat-icon check"></i></span>
                    <span class="compare-cell highlight"><i data-lucide="check" class="feat-icon check"></i></span>
                </div>
                <div class="compare-row">
                    <span class="compare-feat">Contatos e link compartilhável</span>
                    <span class="compare-cell"><i data-lucide="check" class="feat-icon check"></i></span>
                    <span class="compare-cell highlight"><i data-lucide="check" class="feat-icon check"></i></span>
                </div>
                <div class="compare-row">
                    <span class="compare-feat">Destaque no carrossel da home</span>
                    <span class="compare-cell"><i data-lucide="x" class="feat-icon x"></i></span>
                    <span class="compare-cell highlight"><i data-lucide="check" class="feat-icon check"></i></span>
                </div>
                <div class="compare-row">
                    <span class="compare-feat">Maior visibilidade para clientes</span>
                    <span class="compare-cell"><i data-lucide="x" class="feat-icon x"></i></span>
                    <span class="compare-cell highlight"><i data-lucide="check" class="feat-icon check"></i></span>
                </div>
                <div class="compare-row">
                    <span class="compare-feat">Preço</span>
                    <span class="compare-cell"><strong>R$ 25/mês</strong></span>
                    <span class="compare-cell highlight"><strong>R$ 35/mês</strong></span>
                </div>
            </div>
        </div>

        <!-- FAQ Accordion -->
        <div class="plan-faq-section">
            <div class="faq-header">
                <span class="section-label">PERGUNTAS FREQUENTES</span>
                <h2>Por que anunciar no Conectado em Sergipe?</h2>
                <p style="color: var(--muted-foreground); margin-top: 0.75rem; max-width: 600px; margin-left: auto; margin-right: auto; text-align: center;">
                    Tire suas dúvidas e descubra como podemos ajudar seu negócio a crescer.
                </p>
            </div>
            <div class="faq-list">
                <details class="faq-item" open>
                    <summary class="faq-question">
                        <span>Por que anunciar no Conectado em Sergipe?</span>
                        <i data-lucide="chevron-down" class="faq-arrow"></i>
                    </summary>
                    <div class="faq-answer">
                        <p>O Conectado em Sergipe é a maior vitrine de serviços do estado. Milhares de pessoas acessam o site todos os meses em busca de profissionais confiáveis. Anunciar aqui significa que seu negócio será visto por quem realmente precisa dos seus serviços, aumentando suas chances de fechar novos contratos.</p>
                    </div>
                </details>
                <details class="faq-item">
                    <summary class="faq-question">
                        <span>Qual a diferença entre o Plano Básico e o Avançado?</span>
                        <i data-lucide="chevron-down" class="faq-arrow"></i>
                    </summary>
                    <div class="faq-answer">
                        <p>Os dois planos incluem perfil completo com fotos, descrição, contatos e link para compartilhar. A diferença é que o <strong>Plano Avançado</strong> também coloca seu negócio em destaque no carrossel em movimento da página inicial, garantindo visibilidade extra para todo mundo que acessa o site.</p>
                    </div>
                </details>
                <details class="faq-item">
                    <summary class="faq-question">
                        <span>Como funciona o destaque no carrossel?</span>
                        <i data-lucide="chevron-down" class="faq-arrow"></i>
                    </summary>
                    <div class="faq-answer">
                        <p>Ao contratar o Plano Avançado, sua marca ou serviço entra em um carrossel em movimento na página inicial do site. Isso significa que, sempre que alguém acessar o Conectado em Sergipe, vai se deparar com o seu negócio em evidência — aumentando o reconhecimento da sua marca e as chances de novos contatos.</p>
                    </div>
                </details>
                <details class="faq-item">
                    <summary class="faq-question">
                        <span>Posso mudar de plano depois?</span>
                        <i data-lucide="chevron-down" class="faq-arrow"></i>
                    </summary>
                    <div class="faq-answer">
                        <p>Sim! Você pode fazer upgrade do Plano Básico para o Avançado ou downgrade a qualquer momento. A diferença é ajustada proporcionalmente no período de faturamento.</p>
                    </div>
                </details>
                <details class="faq-item">
                    <summary class="faq-question">
                        <span>Há período de teste ou fidelidade?</span>
                        <i data-lucide="chevron-down" class="faq-arrow"></i>
                    </summary>
                    <div class="faq-answer">
                        <p>Oferecemos 7 dias de teste grátis para novos anunciantes. Além disso, não há fidelidade — você pode cancelar quando quiser, sem multa ou burocracia.</p>
                    </div>
                </details>
                <details class="faq-item">
                    <summary class="faq-question">
                        <span>Como faço para contratar?</span>
                        <i data-lucide="chevron-down" class="faq-arrow"></i>
                    </summary>
                    <div class="faq-answer">
                        <p>Basta clicar em "Anunciar agora" no plano desejado e preencher os dados do seu negócio. Se preferir, entre em contato pelo WhatsApp e nossa equipe te ajuda em todo o processo.</p>
                    </div>
                </details>
            </div>
        </div>

        <!-- WhatsApp CTA -->
        <div class="plan-faq">
            <h3>Ainda tem dúvidas?</h3>
            <p>Fale conosco pelo WhatsApp e descubra a melhor opção para seu negócio.</p>
            <a href="https://wa.me/5579999999999?text=Olá, gostaria de saber mais sobre os planos" target="_blank" class="btn btn-primary">
                <i data-lucide="message-circle" style="width: 16px; height: 16px;"></i>
                Conversar no WhatsApp
            </a>
        </div>
    </div>
</section>

<?php render_footer(); ?>
