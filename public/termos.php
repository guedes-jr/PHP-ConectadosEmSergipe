<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/layout.php';

render_header($pdo, 'Termos de Uso - Conectado em Sergipe', 'Leia nossos termos e condições de uso da plataforma.');
?>

<div class="container section animate-fade-in" style="max-width: 800px; margin: 0 auto; padding: 4rem 1rem;">
    <h1 style="font-size: 2.5rem; margin-bottom: 2rem; color: var(--foreground); line-height: 1.1;">TERMOS DE USO – CONECTADO EM SERGIPE</h1>
    
    <p style="font-weight: 700; color: var(--primary); margin-bottom: 2rem; text-transform: uppercase; letter-spacing: 0.05em;">Vigência: A partir de 11 de maio de 2026</p>
    
    <div class="content-text" style="line-height: 1.8; color: var(--foreground); font-size: 1.1rem;">
        <p style="margin-bottom: 1.5rem; font-size: 1.2rem; font-weight: 500;">Seja bem-vindo ao Conectado em Sergipe. Agradecemos por utilizar nossa plataforma.</p>
        
        <p style="margin-bottom: 2rem;">Os presentes Termos de Uso regem a utilização do site e dos serviços oferecidos pelo Conectado em Sergipe. Ao acessar ou utilizar nossa plataforma e canais de comunicação, você manifesta sua total concordância com as regras aqui estabelecidas.</p>

        <h3 style="margin-top: 2.5rem; margin-bottom: 1rem; color: var(--primary); font-size: 1.4rem;">1. QUEM SOMOS E NOSSA MISSÃO</h3>
        <p>O Conectado em Sergipe é uma plataforma independente de divulgação que atua por meio de website e redes sociais (Instagram e WhatsApp). Nossa missão é conectar prestadores de serviço a potenciais clientes, facilitando a visibilidade do trabalho local.</p>

        <h3 style="margin-top: 2.5rem; margin-bottom: 1rem; color: var(--primary); font-size: 1.4rem;">2. LIMITAÇÃO DE RESPONSABILIDADE</h3>
        <p><strong>Intermediação:</strong> O Conectado em Sergipe atua estritamente como um canal de publicidade. Não intermediamos pagamentos, não recebemos comissões sobre vendas e não participamos da negociação entre as partes.</p>
        <p><strong>Isenção de Vínculo:</strong> O contato é feito diretamente entre o interessado e o anunciante via WhatsApp ou outros meios informados. Portanto, não nos responsabilizamos pela qualidade, entrega, garantia ou conduta ética dos prestadores de serviço listados.</p>

        <h3 style="margin-top: 2.5rem; margin-bottom: 1rem; color: var(--primary); font-size: 1.4rem;">3. CONDIÇÕES DE ACESSO</h3>
        <p><strong>Idade Mínima:</strong> Para utilizar o site e entrar em contato com os anunciantes, o usuário deve ter, no mínimo, 16 anos de idade.</p>

        <h3 style="margin-top: 2.5rem; margin-bottom: 1rem; color: var(--primary); font-size: 1.4rem;">4. MODERAÇÃO E INTERRUPÇÃO DE SERVIÇOS</h3>
        <p>Reservamo-nos o direito de remover anúncios e suspender o acesso de prestadores de serviço nas seguintes hipóteses:</p>
        <ul style="margin: 1rem 0; padding-left: 1.5rem; list-style-type: disc;">
            <li>Recebimento de denúncias fundamentadas de usuários;</li>
            <li>Confirmação de inadimplência ou má-fé por parte do anunciante;</li>
            <li>Identificação de serviços irregulares ou ilícitos.</li>
        </ul>
        <p>Em tais casos, o anúncio só poderá ser reativado após a devida resolução do conflito e análise da administração.</p>

        <h3 style="margin-top: 2.5rem; margin-bottom: 1rem; color: var(--primary); font-size: 1.4rem;">5. PROPRIEDADE INTELECTUAL E DIREITOS AUTORAIS</h3>
        <p>Respeitamos os direitos de propriedade intelectual. Se você identificar que qualquer conteúdo publicado viola seus direitos autorais ou marca registrada, por favor, envie-nos uma mensagem imediatamente para que possamos analisar e tomar as providências cabíveis.</p>

        <h3 style="margin-top: 2.5rem; margin-bottom: 1rem; color: var(--primary); font-size: 1.4rem;">6. DISPOSIÇÕES GERAIS</h3>
        <p>Ao publicar ou utilizar o portal, você declara ser o único responsável pelas informações fornecidas e pelas interações realizadas.</p>
    </div>
</div>

<?php render_footer(); ?>
