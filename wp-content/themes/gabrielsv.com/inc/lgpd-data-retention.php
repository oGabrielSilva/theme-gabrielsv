<?php
/**
 * LGPD/GDPR Data Retention Policy
 *
 * Política de Retenção de Dados conforme:
 * - LGPD (Lei 13.709/2018)
 * - Marco Civil da Internet (Lei 12.965/2014)
 *
 * @package gabrielsv.com
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Configurações de Retenção de Dados
 */
define('THEME_LGPD_VERIFICATION_CODE_RETENTION', 7); // dias - tempo para manter código visível
define('THEME_LGPD_IP_RETENTION', 180); // dias (6 meses) - Marco Civil Art. 15
define('THEME_LGPD_FULL_DELETE', 365); // dias (12 meses) - delete completo

/**
 * Cleanup automático de dados de verificação de email
 * Executa em 3 camadas para conformidade LGPD
 */
function theme_cleanup_expired_verification_codes()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'email_verification_codes';

    // ✅ CAMADA 1: Mascarar códigos após 7 dias (Art. 6º, III - Minimização)
    $masked = $wpdb->query(
        $wpdb->prepare(
            "UPDATE {$table_name}
             SET verification_code = '******'
             WHERE created_at < DATE_SUB(NOW(), INTERVAL %d DAY)
             AND verification_code != '******'",
            THEME_LGPD_VERIFICATION_CODE_RETENTION
        )
    );

    if ($masked > 0) {
        error_log(sprintf(
            'LGPD CLEANUP: Masked %d verification codes older than %d days',
            $masked,
            THEME_LGPD_VERIFICATION_CODE_RETENTION
        ));
    }

    // ✅ CAMADA 2: Anonimizar IPs após 6 meses (Marco Civil Art. 15 + LGPD Art. 16)
    // Mantém apenas prefixo para análise estatística (ex: 192.168.1.0)
    $anonymized = $wpdb->query(
        $wpdb->prepare(
            "UPDATE {$table_name}
             SET
                ip_address = CONCAT(SUBSTRING_INDEX(ip_address, '.', 3), '.0'),
                user_agent = 'anonymized'
             WHERE created_at < DATE_SUB(NOW(), INTERVAL %d DAY)
             AND ip_address NOT LIKE '%%.0'",
            THEME_LGPD_IP_RETENTION
        )
    );

    if ($anonymized > 0) {
        error_log(sprintf(
            'LGPD CLEANUP: Anonymized %d IP addresses older than %d days',
            $anonymized,
            THEME_LGPD_IP_RETENTION
        ));
    }

    // ✅ CAMADA 3: Deletar completamente após 12 meses (Art. 16 - Término do tratamento)
    $deleted = $wpdb->query(
        $wpdb->prepare(
            "DELETE FROM {$table_name}
             WHERE created_at < DATE_SUB(NOW(), INTERVAL %d DAY)",
            THEME_LGPD_FULL_DELETE
        )
    );

    if ($deleted > 0) {
        error_log(sprintf(
            'LGPD CLEANUP: Deleted %d email verification records older than %d days',
            $deleted,
            THEME_LGPD_FULL_DELETE
        ));
    }

    // ✅ CAMADA 4: Marcar como expirados (imediato)
    $expired = $wpdb->query(
        "UPDATE {$table_name}
         SET status = 'expired'
         WHERE status = 'pending'
         AND expires_at < NOW()"
    );

    // Retornar estatísticas para monitoramento
    return array(
        'masked' => $masked,
        'anonymized' => $anonymized,
        'deleted' => $deleted,
        'expired' => $expired,
        'timestamp' => current_time('mysql')
    );
}
add_action('theme_cleanup_expired_verification_codes', 'theme_cleanup_expired_verification_codes');

/**
 * Exportar dados de verificação de um usuário (LGPD Art. 18, II - Portabilidade)
 *
 * @param int $user_id ID do usuário
 * @return array Dados em formato estruturado
 */
function theme_export_user_verification_data($user_id)
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'email_verification_codes';

    $data = $wpdb->get_results($wpdb->prepare(
        "SELECT
            id as 'ID do Registro',
            DATE_FORMAT(created_at, '%%d/%%m/%%Y %%H:%%i:%%s') as 'Data/Hora da Solicitação',
            old_email as 'Email Anterior',
            new_email as 'Novo Email Solicitado',
            status as 'Status',
            CASE
                WHEN ip_address LIKE '%%.0' THEN CONCAT(ip_address, ' (anonimizado)')
                ELSE CONCAT(SUBSTRING(ip_address, 1, 12), '...')
            END as 'Endereço IP',
            CASE
                WHEN user_agent = 'anonymized' THEN 'Anonimizado'
                ELSE LEFT(user_agent, 50)
            END as 'Dispositivo',
            attempts as 'Tentativas de Validação',
            DATE_FORMAT(verified_at, '%%d/%%m/%%Y %%H:%%i:%%s') as 'Verificado em'
         FROM {$table_name}
         WHERE user_id = %d
         ORDER BY created_at DESC",
        $user_id
    ), ARRAY_A);

    // Log da exportação (auditoria)
    error_log(sprintf(
        'LGPD EXPORT: User %d requested email verification data export (%d records)',
        $user_id,
        count($data)
    ));

    return $data;
}

/**
 * Deletar dados de verificação ao deletar usuário (LGPD Art. 18, VI - Esquecimento)
 *
 * @param int $user_id ID do usuário sendo deletado
 */
function theme_delete_user_verification_data($user_id)
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'email_verification_codes';

    // Contar registros antes de deletar
    $count = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM {$table_name} WHERE user_id = %d",
        $user_id
    ));

    if ($count > 0) {
        // Log antes de deletar (auditoria)
        error_log(sprintf(
            'LGPD DELETE: Deleting %d email verification records for user %d (account deletion)',
            $count,
            $user_id
        ));

        $wpdb->delete(
            $table_name,
            array('user_id' => $user_id),
            array('%d')
        );
    }
}
add_action('delete_user', 'theme_delete_user_verification_data');

/**
 * Endpoint AJAX para usuário solicitar seus dados (LGPD Art. 18)
 */
function theme_ajax_lgpd_export_my_data()
{
    check_ajax_referer('profile_nonce', 'nonce');

    if (!is_user_logged_in()) {
        wp_send_json_error(array(
            'message' => 'Você precisa estar logado.',
        ));
    }

    $user_id = get_current_user_id();
    $data = theme_export_user_verification_data($user_id);

    wp_send_json_success(array(
        'message' => 'Dados exportados com sucesso.',
        'data' => $data,
        'count' => count($data),
    ));
}
add_action('wp_ajax_lgpd_export_verification_data', 'theme_ajax_lgpd_export_my_data');

/**
 * Estatísticas de cleanup para administradores
 */
function theme_lgpd_cleanup_stats()
{
    if (!current_user_can('manage_options')) {
        return;
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'email_verification_codes';

    $stats = $wpdb->get_row("
        SELECT
            COUNT(*) as total,
            SUM(CASE WHEN verification_code = '******' THEN 1 ELSE 0 END) as masked,
            SUM(CASE WHEN ip_address LIKE '%.0' THEN 1 ELSE 0 END) as anonymized,
            SUM(CASE WHEN status = 'verified' THEN 1 ELSE 0 END) as verified,
            SUM(CASE WHEN status = 'expired' THEN 1 ELSE 0 END) as expired,
            MIN(created_at) as oldest,
            MAX(created_at) as newest
        FROM {$table_name}
    ", ARRAY_A);

    return $stats;
}

/**
 * Widget Admin Dashboard com estatísticas LGPD
 */
function theme_lgpd_dashboard_widget()
{
    $stats = theme_lgpd_cleanup_stats();

    if (!$stats) {
        echo '<p>Nenhum dado de verificação de email encontrado.</p>';
        return;
    }

    echo '<table class="widefat">';
    echo '<tr><th>Total de Registros:</th><td>' . number_format($stats['total']) . '</td></tr>';
    echo '<tr><th>Códigos Mascarados:</th><td>' . number_format($stats['masked']) . '</td></tr>';
    echo '<tr><th>IPs Anonimizados:</th><td>' . number_format($stats['anonymized']) . '</td></tr>';
    echo '<tr><th>Verificados:</th><td>' . number_format($stats['verified']) . '</td></tr>';
    echo '<tr><th>Expirados:</th><td>' . number_format($stats['expired']) . '</td></tr>';
    echo '<tr><th>Registro mais antigo:</th><td>' . date('d/m/Y H:i', strtotime($stats['oldest'])) . '</td></tr>';
    echo '</table>';

    echo '<p style="margin-top: 15px;">';
    echo '<strong>Política de Retenção:</strong><br>';
    echo '• Códigos: mascarados após ' . THEME_LGPD_VERIFICATION_CODE_RETENTION . ' dias<br>';
    echo '• IPs: anonimizados após ' . THEME_LGPD_IP_RETENTION . ' dias<br>';
    echo '• Registros: deletados após ' . THEME_LGPD_FULL_DELETE . ' dias';
    echo '</p>';
}

function theme_add_lgpd_dashboard_widget()
{
    wp_add_dashboard_widget(
        'theme_lgpd_widget',
        'LGPD - Verificação de Email',
        'theme_lgpd_dashboard_widget'
    );
}
add_action('wp_dashboard_setup', 'theme_add_lgpd_dashboard_widget');
