<?php
session_start();
require 'db.php';

// Verifica se está logado
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

// Inicializa variáveis
$leads = [];
$total_leads = 0;
$today_leads = 0;
$sim_count = 0;

// Paginação
$limit = 30;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
if ($page < 1)
    $page = 1;
$offset = ($page - 1) * $limit;
$total_pages = 0;

if ($pdo) {
    try {
        // 1. Contagem Total de Leads
        $stmtTotal = $pdo->query("SELECT COUNT(*) FROM leads");
        $total_leads = $stmtTotal->fetchColumn();
        $total_pages = ceil($total_leads / $limit);

        // 2. Contagem de Leads de Hoje
        // Usando CURDATE() do MySQL
        $stmtToday = $pdo->query("SELECT COUNT(*) FROM leads WHERE DATE(data_registro) = CURDATE()");
        $today_leads = $stmtToday->fetchColumn();

        // 3. Contagem para Conversão (FGTS Sim)
        $stmtSim = $pdo->query("SELECT COUNT(*) FROM leads WHERE app_fgts = 'sim'");
        $sim_count = $stmtSim->fetchColumn();

        // 4. Busca os leads da página atual
        $stmt = $pdo->prepare("SELECT * FROM leads ORDER BY data_registro DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $leads = $stmt->fetchAll();

    } catch (PDOException $e) {
        $error = "Erro ao buscar dados.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Premium - BMP</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="BMP/images/logo.png">
</head>

<body>
    <div class="dashboard-container">

        <!-- Header -->
        <div class="header-actions">
            <div class="header-brand">
                <img src="BMP/images/logo.png" alt="Logo">
                <div>
                    <h2>Painel de Controle</h2>
                    <span>Bem-vindo, <?php echo htmlspecialchars($_SESSION['admin_user']); ?></span>
                </div>
            </div>

            <div class="header-controls">
                <a href="#" class="btn-export" onclick="alert('Funcionalidade de exportação em breve!'); return false;">
                    <span>&#128196;</span> Exportar CSV
                </a>
                <a href="logout.php" class="btn-logout">Sair</a>
            </div>
        </div>

        <!-- Menu de Acesso Rápido -->
        <div class="quick-menu">
            <a href="gerador_links.php" class="quick-menu-item" target="_blank">
                <span class="quick-icon">🔗</span>
                <span>Gerar Links</span>
            </a>
            <a href="pixel_config.js" class="quick-menu-item" target="_blank">
                <span class="quick-icon">📊</span>
                <span>Config. Pixels</span>
            </a>
            <a href="index.php" class="quick-menu-item" target="_blank">
                <span class="quick-icon">📄</span>
                <span>Ver Formulário</span>
            </a>
            <a href="add_tracking_columns.php" class="quick-menu-item" target="_blank">
                <span class="quick-icon">🔧</span>
                <span>Config. Banco</span>
            </a>
        </div>
        <div class="stats-grid">
            <div class="stat-card">
                <span class="stat-title">Total de Leads</span>
                <span class="stat-number"><?php echo number_format($total_leads, 0, ',', '.'); ?></span>
            </div>
            <div class="stat-card">
                <span class="stat-title">Novos Hoje</span>
                <span class="stat-number"><?php echo number_format($today_leads, 0, ',', '.'); ?></span>
                <?php if ($today_leads > 0): ?>
                    <span class="stat-badge success">+ Novos registros</span>
                <?php endif; ?>
            </div>
            <div class="stat-card highlight-card">
                <span class="stat-title highlight-text">Conversão (FGTS Sim)</span>
                <span class="stat-number highlight-text">
                    <?php
                    echo $total_leads > 0 ? round(($sim_count / $total_leads) * 100) . '%' : '0%';
                    ?>
                </span>
            </div>
        </div>

        <!-- Tabela -->
        <h3 class="section-title">Registros Recentes</h3>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th style="text-align: center; width: 60px;">Status</th>
                        <th>Data/Hora</th>
                        <th>Nome</th>
                        <th>Telefone</th>
                        <th>CPF</th>
                        <th>RG</th>
                        <th>Nascimento</th>
                        <th>CEP</th>
                        <th>FGTS App</th>
                        <th>Origem</th>
                        <th>Ref</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($leads)): ?>
                        <tr>
                            <td colspan="12" style="text-align: center; padding: 40px; color: rgba(255,255,255,0.4);">
                                Nenhum lead registrado até o momento.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($leads as $lead): ?>
                            <tr>
                                <td style="text-align: center;">
                                    <button class="btn-check <?php echo ($lead['atendido'] ?? 0) == 1 ? 'active' : ''; ?>"
                                        onclick="toggleAtendido(<?php echo $lead['id']; ?>, this)"
                                        title="<?php echo ($lead['atendido'] ?? 0) == 1 ? 'Marcar como Pendente' : 'Marcar como Atendido'; ?>">
                                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor"
                                            stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                    </button>
                                </td>
                                <td style="color: rgba(255,255,255,0.7); font-size: 0.85rem;">
                                    <?php echo date('d/m/Y', strtotime($lead['data_registro'])); ?>
                                    <span style="font-size: 0.75rem; color: rgba(255,255,255,0.3); margin-left: 5px;">
                                        <?php echo date('H:i', strtotime($lead['data_registro'])); ?>
                                    </span>
                                </td>
                                <td style="font-weight: 600; color: #fff;"><?php echo htmlspecialchars($lead['nome']); ?></td>
                                <td style="font-family: monospace; letter-spacing: 0.5px;">
                                    <?php
                                    $clean_phone = preg_replace('/\D/', '', $lead['telefone']);
                                    $wa_url = "https://wa.me/+55" . $clean_phone;
                                    ?>
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <a href="<?php echo $wa_url; ?>" target="_blank" title="Chamar no WhatsApp"
                                            style="text-decoration: none; display: flex; align-items: center; justify-content: center; background-color: rgba(37, 211, 102, 0.15); width: 28px; height: 28px; border-radius: 50%; transition: all 0.2s;">
                                            <svg viewBox="0 0 24 24" width="16" height="16" fill="#25D366">
                                                <path
                                                    d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c1.009.55 2.114.846 3.256.847h.001c3.181 0 5.767-2.586 5.768-5.766.001-3.182-2.585-5.769-5.768-5.769zm10.749 5.767c0 5.578-4.524 10.103-10.104 10.104-1.691 0-3.35-.417-4.831-1.206l-5.32 1.396 1.421-5.186c-.71-1.421-1.087-3.003-1.087-4.639C2.868 6.474 7.391 1.948 12.969 1.948c5.579 0 10.103 4.526 10.103 10.103M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.008-.57-.008-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347" />
                                            </svg>
                                        </a>
                                        <?php echo htmlspecialchars($lead['telefone'] ?? ''); ?>
                                    </div>
                                </td>
                                <td style="font-family: monospace; letter-spacing: 0.5px;">
                                    <?php echo htmlspecialchars($lead['cpf'] ?? ''); ?>
                                </td>
                                <td style="font-family: monospace; letter-spacing: 0.5px;">
                                    <?php echo htmlspecialchars($lead['rg'] ?? ''); ?>
                                </td>
                                <td>
                                    <?php
                                    if (!empty($lead['data_nascimento'])) {
                                        echo date('d/m/Y', strtotime($lead['data_nascimento']));
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>
                                <td style="font-family: monospace; letter-spacing: 0.5px;">
                                    <?php
                                    $cep = $lead['cep'] ?? '';
                                    if (!empty($cep) && strlen($cep) == 8) {
                                        echo substr($cep, 0, 5) . '-' . substr($cep, 5);
                                    } else {
                                        echo htmlspecialchars($cep) ?: '-';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    $isSim = strtolower($lead['app_fgts'] ?? '') === 'sim';
                                    echo '<span class="badge ' . ($isSim ? 'badge-sim' : 'badge-nao') . '">' .
                                        ($isSim ? 'SIM' : 'NÃO') . '</span>';
                                    ?>
                                </td>
                                <td style="font-size: 0.85rem; color: rgba(255,255,255,0.7);">
                                    <?php
                                    if (empty($lead['tempo_trabalho'])) {
                                        echo '-';
                                    } else {
                                        $tempo = str_replace(['_'], ' ', $lead['tempo_trabalho']);
                                        echo ucwords($tempo);
                                    }
                                    ?>
                                </td>
                                <td style="font-size: 0.85rem;">
                                    <?php
                                    $origem = $lead['utm_source'] ?? '';
                                    if (!empty($origem)) {
                                        $origemColors = [
                                            'facebook' => '#1877f2',
                                            'instagram' => '#e4405f',
                                            'kwai' => '#ff6b35',
                                            'tiktok' => '#000000',
                                            'google' => '#4285f4',
                                            'whatsapp' => '#25d366',
                                        ];
                                        $color = $origemColors[strtolower($origem)] ?? '#FDB931';
                                        echo '<span style="background: ' . $color . '22; color: ' . $color . '; padding: 4px 10px; border-radius: 12px; font-weight: 600; text-transform: capitalize; border: 1px solid ' . $color . '44;">' . htmlspecialchars($origem) . '</span>';
                                    } else {
                                        echo '<span style="color: rgba(255,255,255,0.3);">-</span>';
                                    }
                                    ?>
                                </td>
                                <td style="font-size: 0.85rem; font-weight: 600; color: #4ade80;">
                                    <?php echo htmlspecialchars($lead['referrer'] ?? '') ?: '<span style="color: rgba(255,255,255,0.3);">-</span>'; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Paginação -->
        <?php if ($total_pages > 1): ?>
            <div class="pagination-container">
                <div class="pagination-info">
                    Página <strong><?php echo $page; ?></strong> de <strong><?php echo $total_pages; ?></strong>
                    <span>(Total: <?php echo number_format($total_leads, 0, ',', '.'); ?> registros)</span>
                </div>
                <div class="pagination-controls">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?php echo $page - 1; ?>" class="btn-page prev">&laquo; Anterior</a>
                    <?php endif; ?>

                    <?php if ($page < $total_pages): ?>
                        <a href="?page=<?php echo $page + 1; ?>" class="btn-page next">Próxima &raquo;</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <script>
        function toggleAtendido(id, btn) {
            // Verifica o estado atual pela classe
            const isCurrentlyActive = btn.classList.contains('active');
            const newStatus = isCurrentlyActive ? 0 : 1;

            // Feedback visual imediato (otimista)
            if (newStatus === 1) {
                btn.classList.add('active');
                btn.title = "Marcar como Pendente";
            } else {
                btn.classList.remove('active');
                btn.title = "Marcar como Atendido";
            }

            // Envia para o backend
            fetch('toggle_atendido.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: id, status: newStatus })
            })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        // Reverte se der erro
                        alert('Erro ao atualizar status: ' + (data.message || 'Erro desconhecido'));
                        if (isCurrentlyActive) {
                            btn.classList.add('active');
                        } else {
                            btn.classList.remove('active');
                        }
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    // Reverte se der erro de rede
                    if (isCurrentlyActive) {
                        btn.classList.add('active');
                    } else {
                        btn.classList.remove('active');
                    }
                });
        }
    </script>

    <!-- Sistema de Notificação de Novos Leads -->
    <script>
        // Configurações
        const CHECK_INTERVAL = 10000; // Verificar a cada 10 segundos
        let lastKnownLeadId = <?php echo $leads[0]['id'] ?? 0; ?>;
        let notificationEnabled = true;

        // Cria o contexto de áudio
        let audioContext = null;

        function initAudio() {
            if (!audioContext) {
                audioContext = new (window.AudioContext || window.webkitAudioContext)();
            }
        }

        // Som de notificação usando Web Audio API
        function playNotificationSound() {
            initAudio();

            const ctx = audioContext;
            const now = ctx.currentTime;

            // Cria osciladores para um som agradável
            const frequencies = [523.25, 659.25, 783.99]; // C5, E5, G5 (acorde maior)

            frequencies.forEach((freq, index) => {
                const oscillator = ctx.createOscillator();
                const gainNode = ctx.createGain();

                oscillator.connect(gainNode);
                gainNode.connect(ctx.destination);

                oscillator.frequency.value = freq;
                oscillator.type = 'sine';

                // Envelope do som
                const startTime = now + (index * 0.1);
                gainNode.gain.setValueAtTime(0, startTime);
                gainNode.gain.linearRampToValueAtTime(0.3, startTime + 0.05);
                gainNode.gain.exponentialRampToValueAtTime(0.01, startTime + 0.5);

                oscillator.start(startTime);
                oscillator.stop(startTime + 0.6);
            });

            // Segunda nota (mais aguda) para "ding-dong"
            setTimeout(() => {
                const oscillator2 = ctx.createOscillator();
                const gainNode2 = ctx.createGain();

                oscillator2.connect(gainNode2);
                gainNode2.connect(ctx.destination);

                oscillator2.frequency.value = 1046.50; // C6
                oscillator2.type = 'sine';

                gainNode2.gain.setValueAtTime(0, ctx.currentTime);
                gainNode2.gain.linearRampToValueAtTime(0.25, ctx.currentTime + 0.05);
                gainNode2.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.8);

                oscillator2.start();
                oscillator2.stop(ctx.currentTime + 1);
            }, 300);
        }

        // Verifica novos leads
        async function checkNewLeads() {
            if (!notificationEnabled) return;

            try {
                const response = await fetch('check_new_leads.php');
                const data = await response.json();

                if (data.success && data.last_id > lastKnownLeadId) {
                    // Novo lead detectado!
                    playNotificationSound();

                    // Mostra notificação visual
                    showNewLeadToast(data.last_name);

                    // Atualiza o ID conhecido
                    lastKnownLeadId = data.last_id;

                    // Atualiza a página após 2 segundos
                    setTimeout(() => {
                        location.reload();
                    }, 2500);
                }
            } catch (error) {
                console.error('Erro ao verificar novos leads:', error);
            }
        }

        // Toast de novo lead
        function showNewLeadToast(nome) {
            // Remove toast anterior se existir
            const existingToast = document.querySelector('.new-lead-toast');
            if (existingToast) existingToast.remove();

            const toast = document.createElement('div');
            toast.className = 'new-lead-toast';
            toast.innerHTML = `
                <span class="toast-icon">🔔</span>
                <div class="toast-content">
                    <strong>Novo Lead!</strong>
                    <span>${nome || 'Novo cadastro recebido'}</span>
                </div>
            `;
            document.body.appendChild(toast);

            // Anima entrada
            setTimeout(() => toast.classList.add('show'), 10);

            // Remove após 3 segundos
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        // Ativa o áudio no primeiro clique (necessário para browsers modernos)
        document.addEventListener('click', function initAudioOnClick() {
            initAudio();
            if (audioContext && audioContext.state === 'suspended') {
                audioContext.resume();
            }
            document.removeEventListener('click', initAudioOnClick);
        }, { once: true });

        // Inicia a verificação periódica
        setInterval(checkNewLeads, CHECK_INTERVAL);
        console.log('🔔 Sistema de notificação de leads ativo (verificando a cada ' + (CHECK_INTERVAL / 1000) + 's)');
    </script>

    <style>
        .new-lead-toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, #4ade80, #22c55e);
            color: #064e3b;
            padding: 16px 24px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 10px 40px rgba(34, 197, 94, 0.4);
            transform: translateX(120%);
            transition: transform 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            z-index: 9999;
        }

        .new-lead-toast.show {
            transform: translateX(0);
        }

        .toast-icon {
            font-size: 1.5rem;
            animation: shake 0.5s ease-in-out infinite;
        }

        .toast-content {
            display: flex;
            flex-direction: column;
        }

        .toast-content strong {
            font-size: 0.95rem;
            margin-bottom: 2px;
        }

        .toast-content span {
            font-size: 0.85rem;
            opacity: 0.8;
        }

        @keyframes shake {

            0%,
            100% {
                transform: rotate(-5deg);
            }

            50% {
                transform: rotate(5deg);
            }
        }
    </style>
</body>

</html>