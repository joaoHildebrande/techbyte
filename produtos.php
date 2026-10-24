<?php
/**
 * TechByte - Catálogo de Produtos e Serviços (produtos.php)
 * Lógica de pesquisa e filtro dinâmico com parâmetros GET e consultas SQL seguras.
 */

declare(strict_types=1);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$pdo = getDatabaseConnection();

// Obter parâmetros de filtro com sanitização
$termoBusca   = isset($_GET['busca']) ? trim((string)$_GET['busca']) : '';
$categoriaId  = isset($_GET['categoria']) && is_numeric($_GET['categoria']) ? (int)$_GET['categoria'] : null;
$tipoFiltro   = isset($_GET['tipo']) && in_array($_GET['tipo'], ['produto', 'servico'], true) ? $_GET['tipo'] : null;
$precoMax     = isset($_GET['preco_max']) && is_numeric($_GET['preco_max']) ? (float)$_GET['preco_max'] : null;

// Construção dinâmica de consulta SQL com Prepared Statements
$sql = '
    SELECT p.*, c.nome AS categoria_nome 
    FROM produtos_servicos p
    INNER JOIN categorias c ON p.categoria_id = c.id
    WHERE 1=1
';
$params = [];

if (!empty($termoBusca)) {
    $sql .= ' AND (p.nome LIKE :busca OR p.descricao LIKE :busca)';
    $params[':busca'] = '%' . $termoBusca . '%';
}

if ($categoriaId !== null) {
    $sql .= ' AND p.categoria_id = :categoria_id';
    $params[':categoria_id'] = $categoriaId;
}

if ($tipoFiltro !== null) {
    $sql .= ' AND p.tipo = :tipo';
    $params[':tipo'] = $tipoFiltro;
}

if ($precoMax !== null && $precoMax > 0) {
    $sql .= ' AND p.preco <= :preco_max';
    $params[':preco_max'] = $precoMax;
}

$sql .= ' ORDER BY p.nome ASC';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$produtos = $stmt->fetchAll();

// Recuperar categorias para o formulário de filtro
$categorias = $pdo->query('SELECT * FROM categorias ORDER BY nome ASC')->fetchAll();

$pageTitle = 'Catálogo de Produtos & Serviços';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<main class="py-5">
    <div class="container">
        <!-- Cabeçalho da Página com Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Início</a></li>
                <li class="breadcrumb-item active" aria-current="page">Catálogo</li>
            </ol>
        </nav>

        <div class="row g-4">
            <!-- Sidebar de Filtros -->
            <div class="col-lg-3">
                <div class="card border-0 shadow-sm p-4 sticky-top" style="top: 90px;">
                    <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-funnel-fill text-primary"></i> Filtrar Catálogo
                    </h5>
                    
                    <form action="produtos.php" method="GET">
                        <!-- Busca por Palavra-Chave -->
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Palavra-Chave</label>
                            <input type="text" name="busca" class="form-control form-control-sm" value="<?= htmlspecialchars($termoBusca); ?>" placeholder="Ex: SSD, Switch, Redes...">
                        </div>

                        <!-- Filtro por Categoria -->
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Categoria</label>
                            <select name="categoria" class="form-select form-select-sm">
                                <option value="">Todas as Categorias</option>
                                <?php foreach ($categorias as $cat): ?>
                                    <option value="<?= $cat['id']; ?>" <?= ($categoriaId === (int)$cat['id']) ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($cat['nome']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Filtro por Tipo -->
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Tipo de Item</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipo" id="tipoTodos" value="" <?= empty($tipoFiltro) ? 'checked' : ''; ?>>
                                <label class="form-check-label small" for="tipoTodos">Todos</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipo" id="tipoProduto" value="produto" <?= ($tipoFiltro === 'produto') ? 'checked' : ''; ?>>
                                <label class="form-check-label small" for="tipoProduto">Apenas Produtos</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipo" id="tipoServico" value="servico" <?= ($tipoFiltro === 'servico') ? 'checked' : ''; ?>>
                                <label class="form-check-label small" for="tipoServico">Apenas Serviços</label>
                            </div>
                        </div>

                        <!-- Filtro por Preço Máximo (Requisito: pesquisa e condição de preço) -->
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Preço Máximo (R$)</label>
                            <input type="number" step="50" min="0" name="preco_max" class="form-control form-control-sm" value="<?= $precoMax ? htmlspecialchars((string)$precoMax) : ''; ?>" placeholder="Ex: 1000">
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search me-1"></i>Aplicar Filtros</button>
                            <a href="produtos.php" class="btn btn-outline-secondary btn-sm">Limpar Filtros</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Lista de Produtos e Resultados -->
            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded shadow-sm">
                    <div>
                        <h4 class="fw-bold mb-0">Produtos & Serviços</h4>
                        <small class="text-muted"><?= count($produtos); ?> itens encontrados no banco de dados</small>
                    </div>
                    <?php if (!empty($termoBusca) || $categoriaId !== null || !empty($tipoFiltro) || $precoMax !== null): ?>
                        <span class="badge bg-primary px-3 py-2">Filtros Ativos</span>
                    <?php endif; ?>
                </div>

                <?php if (empty($produtos)): ?>
                    <div class="alert alert-warning py-4 text-center">
                        <i class="bi bi-exclamation-triangle-fill fs-2 d-block mb-2"></i>
                        <h5>Nenhum item corresponde aos critérios de pesquisa selecionados.</h5>
                        <p class="small text-muted mb-3">Tente alterar os filtros ou pesquisar com termos mais gerais.</p>
                        <a href="produtos.php" class="btn btn-outline-primary btn-sm">Ver Todos os Produtos</a>
                    </div>
                <?php else: ?>
                    <div class="row g-4">
                        <?php foreach ($produtos as $p): ?>
                            <div class="col-md-6 col-xl-4">
                                <div class="card h-100 product-card">
                                    <div class="position-relative">
                                        <img src="<?= htmlspecialchars($p['imagem']); ?>" class="card-img-top" alt="<?= htmlspecialchars($p['nome']); ?>" style="height: 180px; object-fit: cover;">
                                        <span class="position-absolute top-0 start-0 m-2 badge <?= $p['tipo'] === 'servico' ? 'badge-servico' : 'badge-produto'; ?> text-white">
                                            <?= strtoupper($p['tipo']); ?>
                                        </span>
                                    </div>
                                    <div class="card-body d-flex flex-column">
                                        <span class="text-muted small mb-1"><?= htmlspecialchars($p['categoria_nome']); ?></span>
                                        <h6 class="card-title fw-bold text-dark mb-2"><?= htmlspecialchars($p['nome']); ?></h6>
                                        <p class="card-text text-muted small flex-grow-1" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                            <?= htmlspecialchars($p['descricao']); ?>
                                        </p>
                                        
                                        <div class="mt-3 pt-3 border-top">
                                            <div class="d-flex justify-content-between align-items-baseline mb-2">
                                                <span class="fs-5 fw-bold text-primary">R$ <?= number_format((float)$p['preco'], 2, ',', '.'); ?></span>
                                                <small class="text-muted"><?= $p['tipo'] === 'produto' ? "Estoque: {$p['estoque']}" : 'Disponível'; ?></small>
                                            </div>
                                            <div class="d-grid gap-2">
                                                <a href="detalhes.php?id=<?= $p['id']; ?>" class="btn btn-primary btn-sm">
                                                    <i class="bi bi-eye me-1"></i>Ver Detalhes & Frete
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
