<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/layout.php';

render_header($pdo, 'Política de Privacidade - Conectado em Sergipe', 'Saiba como tratamos seus dados pessoais.');
?>

<div class="container section animate-fade-in" style="max-width: 800px; margin: 0 auto; padding: 4rem 1rem;">
    <h1 style="font-size: 2.5rem; margin-bottom: 2rem; color: var(--foreground);">Política de Privacidade</h1>
    
    <p style="font-weight: 600; color: var(--primary); margin-bottom: 2rem;">Vigência a partir de: <?php echo date('d/m/Y'); ?></p>

    <div class="content-text" style="line-height: 1.8; color: var(--foreground); font-size: 1.1rem;">
        <p>A sua privacidade é muito importante para nós. Esta Política de Privacidade explica como a plataforma <strong>Conectado em Sergipe</strong> coleta, utiliza, armazena e protege os dados pessoais dos usuários, em conformidade com a <strong>Lei Geral de Proteção de Dados (LGPD).</strong></p>

        <h3 style="margin-top: 2.5rem;">1. COLETA DE DADOS PESSOAIS</h3>
        <p>Coletamos informações via Login Google (Nome, e-mail, foto) ou Cadastro Direto (CPF/CNPJ, telefone, cidade, profissão). Esses dados são essenciais para conectar você aos melhores profissionais da região.</p>

        <h3 style="margin-top: 2.5rem;">2. FINALIDADE DO USO DOS DADOS</h3>
        <ul style="margin-left: 1.5rem; margin-top: 1rem;">
            <li>Identificação e Segurança: Prevenção de fraudes.</li>
            <li>Exibição de Anúncios: Filtro regional por cidade.</li>
            <li>Comunicação: Contato direto entre clientes e prestadores.</li>
            <li>Melhoria do Serviço: Análise de uso da plataforma.</li>
        </ul>

        <h3 style="margin-top: 2.5rem;">3. COMPARTILHAMENTO DE DADOS</h3>
        <p>Seus dados de contato (nome, telefone/e-mail) ficam visíveis apenas quando você anuncia. Dados como CPF e CNPJ são mantidos em sigilo para fins de validação interna. Não vendemos dados para terceiros.</p>

        <h3 style="margin-top: 2.5rem;">4. ARMAZENAMENTO E SEGURANÇA</h3>
        <p>Os dados são armazenados em servidores seguros com medidas técnicas de proteção. Mantemos as informações enquanto sua conta estiver ativa.</p>

        <h3 style="margin-top: 2.5rem;">5. SEUS DIREITOS (LGPD)</h3>
        <p>Você tem o direito de confirmar o tratamento, acessar, corrigir ou solicitar a exclusão definitiva de seus dados a qualquer momento.</p>

        <h3 style="margin-top: 2.5rem;">6. COOKIES</h3>
        <p>Utilizamos cookies para reconhecer seu navegador e lembrar suas preferências, facilitando sua navegação futura.</p>

        <h3 style="margin-top: 2.5rem;">7. ALTERAÇÕES</h3>
        <p>Esta política pode ser atualizada periodicamente. Mudanças significativas serão avisadas via plataforma.</p>

        <h3 style="margin-top: 2.5rem;">8. CONTATO</h3>
        <p>Para dúvidas, entre em contato com o controlador:<br>
        <strong>ANERSON PEREIRA DA SILVA</strong></p>
    </div>
</div>

<?php render_footer(); ?>
