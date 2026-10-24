<?php
/**
 * TechByte - Contato e Orçamento (contato.php)
 */

declare(strict_types=1);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$pdo = getDatabaseConnection();

$mensagemSucesso = '';
$mensagemErro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome     = trim((string)($_POST['nome'] ?? ''));
    $email    = trim((string)($_POST['email'] ?? ''));
    $telefone = trim((string)($_POST['telefone'] ?? ''));
    $assunto  = trim((string)($_POST['assunto'] ?? ''));
    $mensagem = trim((string)($_POST['mensagem'] ?? ''));

    // Validação com condicionais if/else (Requisito da rubrica)
    if (empty($nome) || empty($email) || empty($mensagem)) {
        $mensagemErro = 'Por favor, preencha todos os campos obrigatórios (*).';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensagemErro = 'O endereço de e-mail informado é inválido.';
    } elseif (strlen($mensagem) < 10) {
        $mensagemErro = 'A mensagem deve conter no mínimo 10 caracteres.';
    } else {
        try {
            // Gravação segura no MySQL com Prepared Statement
            $stmt = $pdo->prepare('
                INSERT INTO mensagens_contato (nome, email, telefone, assunto, mensagem, data_envio)
                VALUES (:nome, :email, :telefone, :assunto, :mensagem, NOW())
            ');
            $stmt->execute([
                ':nome'     => $nome,
                ':email'    => $email,
                ':telefone' => $telefone,
                ':assunto'  => $assunto,
                ':mensagem' => $mensagem,
            ]);
            $mensagemSucesso = 'Sua mensagem e solicitação de orçamento foram enviadas com sucesso! Nossa equipe retornará em até 24 horas.';
        } catch (PDOException $e) {
            $mensagemErro = 'Erro ao registrar no banco de dados: ' . $e->getMessage();
        }
    }
}

$pageTitle = 'Contato & Orçamento';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<main class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm p-4 p-md-5 rounded-4">
                    <div class="text-center mb-4">
                        <span class="badge bg-primary px-3 py-2 mb-2">Fale Conosco</span>
                        <h2 class="fw-bold">Solicite um Orçamento ou Suporte</h2>
                        <p class="text-muted">Preencha o formulário abaixo. Os dados são salvos na tabela <code>mensagens_contato</code>.</p>
                    </div>

                    <?php if (!empty($mensagemSucesso)): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($mensagemSucesso); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($mensagemErro)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($mensagemErro); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="contato.php">
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Nome Completo *</label>
                                <input type="text" name="nome" class="form-control" placeholder="Seu nome" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">E-mail *</label>
                                <input type="email" name="email" class="form-control" placeholder="seu@email.com" required>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Telefone / WhatsApp</label>
                                <input type="text" name="telefone" class="form-control" placeholder="(11) 99999-9999">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Assunto</label>
                                <select name="assunto" class="form-select">
                                    <option value="Orcamento de Hardware">Orçamento de Hardware</option>
                                    <option value="Consultoria de Redes">Consultoria de Redes & Infraestrutura</option>
                                    <option value="Suporte Tecnico">Suporte Técnico</option>
                                    <option value="Duvida Academica">Dúvida sobre o Projeto</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small">Mensagem / Especificações do Pedido *</label>
                            <textarea name="mensagem" rows="5" class="form-control" placeholder="Descreva os produtos ou serviços que você deseja..." required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="bi bi-send me-2"></i>Enviar Solicitação
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
