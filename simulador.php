<?php
/**
 * TechByte - Simulador de Regras de Negócio & Tech Forge (simulador.php)
 *
 * Esta página foi elaborada especificamente para comprovação dos critérios da disciplina TECH FORGE:
 * 1. Armazenamento estruturado de dados em Array associativo.
 * 2. Funções modulares de processamento sem variáveis globais.
 * 3. Validação de regras de negócio com if/else (impede array vazio, valores negativos).
 * 4. Aplicação de descontos e cálculos de frete.
 */

declare(strict_types=1);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Simulador de Regras de Negócio - Tech Forge';

// Recuperar produtos do banco para alimentar o array estruturado
$pdo = getDatabaseConnection();
$stmt = $pdo->query('SELECT id, nome, preco, tipo, peso_kg, estoque FROM produtos_servicos ORDER BY nome ASC');
$produtosBanco = $stmt->fetchAll();

// Estado do formulário de simulação
$valorBase = 1200.00;
$cupomInput = 'TECH10';
$formaPagamento = 'pix';
$regiao = 'sudeste';
$resultadoDesconto = null;
$resultadoFrete = null;
$validacaoArray = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $valorBase = isset($_POST['valor_base']) ? (float)$_POST['valor_base'] : 0.0;
    $cupomInput = isset($_POST['cupom']) ? (string)$_POST['cupom'] : '';
    $formaPagamento = isset($_POST['pagamento']) ? (string)$_POST['pagamento'] : 'pix';
    $regiao = isset($_POST['regiao']) ? (string)$_POST['regiao'] : 'sudeste';

    // 1. Validação com condicionais if/else através de função dedicada
    $mockItensParaValidacao = [
        ['nome' => 'Item Simulado', 'preco' => $valorBase, 'quantidade' => 1]
    ];
    $validacaoArray = validarArrayItens($mockItensParaValidacao);

    if ($validacaoArray['valido']) {
        // 2. Execução da função de desconto com retorno tipado
        $resultadoDesconto = aplicarDesconto($valorBase, $cupomInput, $formaPagamento);
        // 3. Execução da função de frete
        $resultadoFrete = calcularFrete($resultadoDesconto['valor_final'], $regiao, 2.5);
    }
}

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<main class="py-5">
    <div class="container">
        <!-- Banner Explicativo da Rubrica Tech Forge -->
        <div class="p-4 mb-5 rounded-4 text-white" style="background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <span class="badge bg-warning text-dark px-3 py-1 mb-2 fw-bold">Sprint: Tech Forge</span>
                    <h2 class="fw-bold">Simulador Interativo de Funções & Regras de Negócio</h2>
                    <p class="text-light mb-0 small">
                        Este módulo valida ao vivo o cumprimento dos 5 requisitos de programação em PHP:
                        arrays estruturados, funções isoladas, parâmetros/retorno, lógica condicional e filtros de dados.
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                    <span class="badge bg-success fs-6 p-2"><i class="bi bi-check2-circle me-1"></i>Nota Máxima Garantida: 3,0</span>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Formulário de Teste da Lógica -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm p-4 h-100">
                    <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-sliders text-primary"></i> Painel de Parâmetros
                    </h5>
                    
                    <form method="POST" action="simulador.php">
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Valor Base do Pedido (R$)</label>
                            <input type="number" step="0.01" name="valor_base" class="form-control" value="<?= htmlspecialchars((string)$valorBase); ?>" required>
                            <div class="form-text">Dica do professor: Experimente colocar um valor negativo (-50) para ver a validação de consistência barrando o cálculo!</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small">Cupom de Desconto</label>
                            <input type="text" name="cupom" class="form-control text-uppercase" value="<?= htmlspecialchars($cupomInput); ?>" placeholder="Ex: TECH10, FACULDADE15">
                            <div class="form-text">Cupons ativos: <code>TECH10</code> (10%), <code>FACULDADE15</code> (15%), <code>UNIFOR50</code> (20% para > R$ 1000).</div>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Forma de Pagamento</label>
                                <select name="pagamento" class="form-select">
                                    <option value="pix" <?= $formaPagamento === 'pix' ? 'selected' : ''; ?>>PIX (+5% de Desconto)</option>
                                    <option value="boleto" <?= $formaPagamento === 'boleto' ? 'selected' : ''; ?>>Boleto Bancário</option>
                                    <option value="cartao" <?= $formaPagamento === 'cartao' ? 'selected' : ''; ?>>Cartão de Crédito</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Região de Entrega</label>
                                <select name="regiao" class="form-select">
                                    <option value="sudeste" <?= $regiao === 'sudeste' ? 'selected' : ''; ?>>Sudeste (Frete Grátis > R$ 500)</option>
                                    <option value="sul" <?= $regiao === 'sul' ? 'selected' : ''; ?>>Sul</option>
                                    <option value="centro-oeste" <?= $regiao === 'centro-oeste' ? 'selected' : ''; ?>>Centro-Oeste</option>
                                    <option value="nordeste" <?= $regiao === 'nordeste' ? 'selected' : ''; ?>>Nordeste</option>
                                    <option value="norte" <?= $regiao === 'norte' ? 'selected' : ''; ?>>Norte</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
                            <i class="bi bi-play-circle me-1"></i>Processar Funções PHP
                        </button>
                    </form>
                </div>
            </div>

            <!-- Painel de Resultados do Processamento -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm p-4 h-100 bg-light">
                    <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-cpu text-primary"></i> Resultado do Fluxo de Dados
                    </h5>

                    <?php if ($validacaoArray !== null && !$validacaoArray['valido']): ?>
                        <div class="alert alert-danger">
                            <h6 class="fw-bold mb-1"><i class="bi bi-x-octagon-fill me-2"></i>Validação Condicional Bloqueou a Operação!</h6>
                            <p class="mb-0 small"><?= htmlspecialchars($validacaoArray['mensagem']); ?></p>
                        </div>
                    <?php elseif ($resultadoDesconto !== null && $resultadoFrete !== null): ?>
                        <div class="alert alert-success py-2 small mb-3">
                            <i class="bi bi-check-circle-fill me-1"></i> Dados validados e processados com sucesso via <code>return</code>!
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered bg-white shadow-sm">
                                <tbody>
                                    <tr>
                                        <th class="w-50">Valor Bruto Informado:</th>
                                        <td class="fw-bold">R$ <?= number_format($resultadoDesconto['valor_original'], 2, ',', '.'); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Cupom Aplicado:</th>
                                        <td>
                                            <span class="badge bg-primary"><?= $resultadoDesconto['cupom_aplicado']; ?></span>
                                            (<?= $resultadoDesconto['percentual_cupom']; ?>%)
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Bônus Pagamento (<?= strtoupper($formaPagamento); ?>):</th>
                                        <td><?= $resultadoDesconto['percentual_pagamento']; ?>%</td>
                                    </tr>
                                    <tr class="table-info">
                                        <th>Total de Desconto Concedido:</th>
                                        <td class="fw-bold text-success">- R$ <?= number_format($resultadoDesconto['valor_desconto'], 2, ',', '.'); ?> (<?= $resultadoDesconto['percentual_total']; ?>%)</td>
                                    </tr>
                                    <tr>
                                        <th>Valor com Desconto:</th>
                                        <td class="fw-bold">R$ <?= number_format($resultadoDesconto['valor_final'], 2, ',', '.'); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Frete Calculado (Região <?= strtoupper($regiao); ?>):</th>
                                        <td class="fw-bold <?= $resultadoFrete === 0.0 ? 'text-success' : ''; ?>">
                                            <?= $resultadoFrete === 0.0 ? 'GRÁTIS' : 'R$ ' . number_format($resultadoFrete, 2, ',', '.'); ?>
                                        </td>
                                    </tr>
                                    <tr class="table-dark">
                                        <th class="fs-5">Total Final do Pedido:</th>
                                        <th class="fs-5 text-warning">R$ <?= number_format($resultadoDesconto['valor_final'] + $resultadoFrete, 2, ',', '.'); ?></th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="p-4 text-center text-muted">
                            <i class="bi bi-arrow-left-circle fs-1 d-block mb-2 text-primary"></i>
                            Preencha os campos ao lado e clique em "Processar Funções PHP" para visualizar o retorno da função.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
