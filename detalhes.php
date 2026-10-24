<?php
/**
 * TechByte - Detalhes do Produto & Simulador de Frete (detalhes.php)
 */

declare(strict_types=1);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$pdo = getDatabaseConnection();

$id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare('
    SELECT p.*, c.nome AS categoria_nome 
    FROM produtos_servicos p
    INNER JOIN categorias c ON p.categoria_id = c.id
    WHERE p.id = :id
');
$stmt->execute([':id' => $id]);
$produto = $stmt->fetch();

if (!$produto) {
    header('Location: produtos.php');
    exit;
}

// Processar simulação de frete se submetido via POST
$freteCalculado = null;
$regiaoSelecionada = 'sudeste';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['calcular_frete'])) {
    $regiaoSelecionada = (string)($_POST['regiao'] ?? 'sudeste');
    // Chamada à função de processamento com passagem de parâmetros e recebimento por retorno
    $freteCalculado = calcularFrete(
        (float)$produto['preco'],
        $regiaoSelecionada,
        (float)($produto['peso_kg'] ?? 1.0)
    );
}

// Calcular plano de parcelamento através da função
$parcelas = calcularParcelamento((float)$produto['preco'], 10);

$pageTitle = $produto['nome'];
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<main class="py-5">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Início</a></li>
                <li class="breadcrumb-item"><a href="produtos.php">Catálogo</a></li>
                <li class="breadcrumb-item active"><?= htmlspecialchars($produto['nome']); ?></li>
            </ol>
        </nav>

        <div class="row g-5">
            <!-- Imagem e Badges -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm overflow-hidden rounded-4">
                    <img src="<?= htmlspecialchars($produto['imagem']); ?>" class="img-fluid w-100" alt="<?= htmlspecialchars($produto['nome']); ?>" style="max-height: 440px; object-fit: cover;">
                </div>
            </div>

            <!-- Dados e Ações de Compra -->
            <div class="col-lg-6">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge <?= $produto['tipo'] === 'servico' ? 'badge-servico' : 'badge-produto'; ?> text-white">
                        <?= strtoupper($produto['tipo']); ?>
                    </span>
                    <span class="badge bg-light text-secondary border"><?= htmlspecialchars($produto['categoria_nome']); ?></span>
                </div>

                <h1 class="fw-bold mb-3"><?= htmlspecialchars($produto['nome']); ?></h1>
                <p class="text-muted lead fs-6"><?= htmlspecialchars($produto['descricao']); ?></p>

                <div class="p-4 bg-light rounded-3 my-4 border">
                    <div class="d-flex align-items-baseline gap-3">
                        <span class="fs-2 fw-bold text-primary">R$ <?= number_format((float)$produto['preco'], 2, ',', '.'); ?></span>
                        <span class="text-success small fw-bold"><i class="bi bi-shield-check me-1"></i>Garantia com Nota Fiscal</span>
                    </div>
                    <small class="text-muted d-block mt-1">Em até 10x sem juros de R$ <?= number_format(((float)$produto['preco'] / 10), 2, ',', '.'); ?></small>
                </div>

                <!-- Formulário de Simulação de Frete (Demonstração da Função de Processamento) -->
                <div class="card p-3 mb-4 border-primary border-opacity-25 bg-white shadow-sm">
                    <h6 class="fw-bold text-dark mb-2"><i class="bi bi-truck me-2 text-primary"></i>Simular Frete & Prazo (Função PHP)</h6>
                    <form method="POST" action="detalhes.php?id=<?= $produto['id']; ?>" class="row g-2 align-items-center">
                        <input type="hidden" name="calcular_frete" value="1">
                        <div class="col-sm-8">
                            <select name="regiao" class="form-select form-select-sm">
                                <option value="sudeste" <?= $regiaoSelecionada === 'sudeste' ? 'selected' : ''; ?>>Região Sudeste (SP, RJ, MG, ES)</option>
                                <option value="sul" <?= $regiaoSelecionada === 'sul' ? 'selected' : ''; ?>>Região Sul (PR, SC, RS)</option>
                                <option value="centro-oeste" <?= $regiaoSelecionada === 'centro-oeste' ? 'selected' : ''; ?>>Centro-Oeste (DF, GO, MT, MS)</option>
                                <option value="nordeste" <?= $regiaoSelecionada === 'nordeste' ? 'selected' : ''; ?>>Região Nordeste (BA, PE, CE, etc.)</option>
                                <option value="norte" <?= $regiaoSelecionada === 'norte' ? 'selected' : ''; ?>>Região Norte (AM, PA, etc.)</option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <button type="submit" class="btn btn-outline-primary btn-sm w-100">Calcular Frete</button>
                        </div>
                    </form>

                    <?php if ($freteCalculado !== null): ?>
                        <div class="alert alert-success mt-3 mb-0 py-2 small">
                            <strong>Resultado do Frete:</strong>
                            <?php if ($freteCalculado === 0.00): ?>
                                <span class="badge bg-success ms-2">FRETE GRÁTIS!</span> (Regra: Compras acima de R$ 500 no Sudeste)
                            <?php else: ?>
                                R$ <?= number_format($freteCalculado, 2, ',', '.'); ?> para a região <?= strtoupper($regiaoSelecionada); ?>.
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Botões de Ação -->
                <div class="d-flex gap-3">
                    <a href="contato.php?produto_id=<?= $produto['id']; ?>" class="btn btn-primary btn-lg flex-grow-1">
                        <i class="bi bi-send-check me-2"></i>Solicitar Orçamento / Comprar
                    </a>
                    <a href="simulador.php" class="btn btn-outline-secondary btn-lg">
                        <i class="bi bi-sliders me-1"></i>Simulador
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
