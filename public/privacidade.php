<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/layout.php';

render_header($pdo, 'Política de Privacidade - Conectado em Sergipe', 'Saiba como tratamos seus dados pessoais.');
?>

<div class="container section animate-fade-in" style="max-width: 800px; margin: 0 auto; padding: 4rem 1rem;">
    <h1 style="font-size: 2.5rem; margin-bottom: 2rem; color: var(--foreground); line-height: 1.1;">POLÍTICA DE PRIVACIDADE – CONECTADO EM SERGIPE</h1>
    
    <p style="font-weight: 700; color: var(--primary); margin-bottom: 2rem; text-transform: uppercase; letter-spacing: 0.05em;">Vigência: A partir de <?php echo date('d/m/Y'); ?></p>

    <div class="content-text" style="line-height: 1.8; color: var(--foreground); font-size: 1.1rem; text-align: justify;">
        <p style="margin-bottom: 2rem;">A sua privacidade é muito importante para nós. Esta Política de Privacidade explica como a plataforma <strong>Conectado em Sergipe</strong> coleta, utiliza, armazena e protege os dados pessoais dos usuários, em conformidade com a <strong>Lei Geral de Proteção de Dados (Lei nº 13.709/2018 - LGPD).</strong></p>

        <h3 style="margin-top: 2.5rem; margin-bottom: 1rem; color: var(--primary); font-size: 1.4rem;">1. COLETA DE DADOS PESSOAIS</h3>
        <p>Para oferecer nossos serviços de divulgação e prestação de serviços dos usuários no site e conectar você aos melhores profissionais da região, coletamos as seguintes informações:</p>
        <ul style="margin: 1rem 0; padding-left: 1.5rem; list-style-type: disc;">
            <li><strong>Fotos do serviço:</strong> Nome, e-mail e foto de perfil (conforme autorizado pelo prestador).</li>
            <li><strong>Dados Principais:</strong> Nome completo, e-mail, número de telefone.</li>
            <li><strong>Dados Complementares:</strong> Cidade de atuação/residência, profissão ou tipo de serviço prestado e categoria de perfil.</li>
        </ul>

        <h3 style="margin-top: 2.5rem; margin-bottom: 1rem; color: var(--primary); font-size: 1.4rem;">2. FINALIDADE DO USO DOS DADOS</h3>
        <p>Os dados coletados pelo Conectados em Sergipe possuem as seguintes finalidades:</p>
        <ul style="margin: 1rem 0; padding-left: 1.5rem; list-style-type: disc;">
            <li><strong>Identificação e Segurança:</strong> Garantir que os usuários e anunciantes sejam pessoas reais, prevenindo fraudes.</li>
            <li><strong>Exibição de Anúncios:</strong> Filtrar e exibir serviços e produtos prioritariamente na cidade informada pelo usuário.</li>
            <li><strong>Comunicação:</strong> Permitir que clientes entrem em contato com prestadores de serviços e lojistas via telefone ou e-mail.</li>
            <li><strong>Melhoria do Serviço:</strong> Analisar o uso da plataforma para otimizar a experiência do usuário.</li>
        </ul>

        <h3 style="margin-top: 2.5rem; margin-bottom: 1rem; color: var(--primary); font-size: 1.4rem;">3. COMPARTILHAMENTO DE DADOS</h3>
        <ul style="margin: 1rem 0; padding-left: 1.5rem; list-style-type: disc;">
            <li><strong>Entre Usuários:</strong> Ao anunciar um serviço ou produto, o seu nome e telefone (ou e-mail) ficarão visíveis para que outros usuários possam entrar em contato e realizar negócios.</li>
            <li><strong>Terceiros:</strong> Não vendemos nem alugamos seus dados pessoais para terceiros.</li>
            <li><strong>Autoridades:</strong> Podemos compartilhar seus dados pessoais para órgãos governamentais ou autoridades mediante solicitação oficial ou ordem judicial.</li>
        </ul>

        <h3 style="margin-top: 2.5rem; margin-bottom: 1rem; color: var(--primary); font-size: 1.4rem;">4. ARMAZENAMENTO E SEGURANÇA</h3>
        <p>Todos os dados são armazenados em servidores seguros, com medidas técnicas de proteção para evitar acessos não autorizados, perda ou alteração das informações. O armazenamento ocorre enquanto o anúncio do usuário estiver ativo em nosso site.</p>

        <h3 style="margin-top: 2.5rem; margin-bottom: 1rem; color: var(--primary); font-size: 1.4rem;">5. SEUS DIREITOS (LGPD)</h3>
        <p>Como titular dos dados, você tem o direito de, a qualquer momento solicitar:</p>
        <ul style="margin: 1rem 0; padding-left: 1.5rem; list-style-type: disc;">
            <li>Confirmar a existência do tratamento de seus dados.</li>
            <li>Acessar, corrigir ou atualizar seus dados pessoais.</li>
            <li>Solicitar a exclusão definitiva de seu perfil de anúncio e de todos os seus dados da nossa base de dados.</li>
        </ul>

        <h3 style="margin-top: 2.5rem; margin-bottom: 1rem; color: var(--primary); font-size: 1.4rem;">6. COOKIES E TECNOLOGIAS DE RASTREAMENTO</h3>
        <p>Podemos utilizar cookies para reconhecer seu navegador e lembrar suas preferências (como a cidade selecionada), facilitando sua navegação futura.</p>

        <h3 style="margin-top: 2.5rem; margin-bottom: 1rem; color: var(--primary); font-size: 1.4rem;">8. PARA ENTRAR EM CONTATO CONOSCO</h3>
        <p>Caso tenha alguma dúvida sobre o Serviço, entre em contato conosco pelo nosso site ou pelos canais oficiais.</p>
        <p style="margin-top: 1rem;">Se tiver alguma dúvida, comentário ou sugestão sobre assuntos relacionados aos seus Dados Pessoais ou a esta Política de Privacidade, ou solicitação de uso de seus direitos e escolhas, entre em contato com o nosso Encarregado pelo Tratamento de Dados Pessoais ("Data Protection Officer").</p>
        
        <div style="margin-top: 2rem; padding: 1.5rem; background: var(--muted-bg); border-radius: 1rem; border: 1px solid var(--border);">
            <p style="margin-bottom: 0.5rem;"><strong>DPO (encarregado):</strong> Anderson Pereira</p>
            <p style="margin-bottom: 0.5rem;"><strong>E-mail:</strong> <a href="mailto:conectadoemsergipe@gmail.com" style="color: var(--primary); text-decoration: none;">conectadoemsergipe@gmail.com</a></p>
        </div>
    </div>
</div>

<?php render_footer(); ?>
