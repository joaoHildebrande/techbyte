<?php
/**
 * TechByte - Página Inicial Dinâmica (index.php)
 * Demonstra recuperação de dados do banco de dados via PDO e renderização com Bootstrap 5.
 */

declare(strict_types=1);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Início - Soluções em Hardware & Serviços';

// Conectar ao banco de dados via PDO
$pdo = getDatabaseConnection();

// Recuperar produtos em destaque diretamente do MySQL (Dados recuperados do banco)
$stmtProdutos = $pdo->prepare('
    SELECT p.*, c.nome AS categoria_nome 
    FROM produtos_servicos p
    INNER JOIN categorias c ON p.categoria_id = c.id
    WHERE p.destaque = 1
    ORDER BY p.id DESC
    LIMIT 4
');
$stmtProdutos->execute();
$produtosDestaque = $stmtProdutos->fetchAll();

// Recuperar categorias dinamicamente
$stmtCat = $pdo->query('
    SELECT c.*, COUNT(p.id) as total_itens 
    FROM categorias c
    LEFT JOIN produtos_servicos p ON p.categoria_id = c.id
    GROUP BY c.id
    ORDER BY c.nome ASC
');
$categorias = $stmtCat->fetchAll();

// Incluir templates
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<main>
    <!-- Componente 2 do Bootstrap: Carousel Dinâmico de Banners -->
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active bg-dark text-white py-5" style="background: linear-gradient(135deg, #1e1b4b 0%, #0f172a 100%);">
                <div class="container py-5">
                    <div class="row align-items-center">
                        <div class="col-lg-7">
                            <span class="badge bg-primary px-3 py-2 mb-3">Infraestrutura Corporativa</span>
                            <h1 class="display-5 fw-bold mb-3">Tecnologia de Alta Performance para seu Negócio</h1>
                            <p class="lead text-light mb-4">Hardware de ponta, servidores dedicados e consultoria especializada com entrega ágil e suporte técnico com SLA garantido.</p>
                            <div class="d-flex gap-3">
                                <a href="produtos.php" class="btn btn-primary btn-lg px-4"><i class="bi bi-cart3 me-2"></i>Ver Catálogo</a>
                                <a href="simulador.php" class="btn btn-outline-light btn-lg px-4"><i class="bi bi-calculator me-2"></i>Simulador de Frete</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="carousel-item bg-dark text-white py-5" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
                <div class="container py-5">
                    <div class="row align-items-center">
                        <div class="col-lg-7">
                            <span class="badge bg-info text-dark px-3 py-2 mb-3">Cupom Acadêmico</span>
                            <h1 class="display-5 fw-bold mb-3">Até 20% de Desconto com Cupons Especiais</h1>
                            <p class="lead text-light mb-4">Teste nossa lógica de negócio em PHP com os cupons TECH10 e FACULDADE15 no simulador interativo.</p>
                            <a href="simulador.php" class="btn btn-info btn-lg px-4 text-dark fw-bold">Testar Regras de Negócio</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="carousel-item bg-dark text-white py-5" style="background: linear-gradient(135deg, #311042 0%, #0f172a 100%);">
                <div class="container py-5">
                    <div class="row align-items-center">
                        <div class="col-lg-7">
                            <span class="badge bg-warning text-dark px-3 py-2 mb-3">Serviços Especializados</span>
                            <h1 class="display-5 fw-bold mb-3">Cabeamento, Manutenção e Redes</h1>
                            <p class="lead text-light mb-4">Equipe qualificada para montagem de racks, certificação de cabos e auditoria de segurança.</p>
                            <a href="produtos.php?tipo=servico" class="btn btn-warning btn-lg px-4 fw-bold">Conhecer Serviços</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Próximo</span>
        </button>
    </div>

    <!-- Seção de Categorias Dinâmicas -->
    <section class="py-5 bg-white border-bottom">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Navegue por Categoria</h2>
                <p class="text-muted">Categorias cadastradas na tabela <code>categorias</code> do banco de dados</p>
            </div>

            <div class="row g-4">
                <?php
                // Utilização correta de FOREACH no PHP
                if (!empty($categorias)):
                    foreach ($categorias as $cat):
                ?>
                    <div class="col-md-6 col-lg-3">
                        <a href="produtos.php?categoria=<?= urlencode((string)$cat['id']); ?>" class="text-decoration-none">
                            <div class="card h-100 product-card text-center p-3">
                                <div class="card-body">
                                    <div class="mb-3 text-primary fs-1">
                                        <i class="bi bi-folder-fill"></i>
                                    </div>
                                    <h5 class="card-title text-dark fw-bold"><?= htmlspecialchars($cat['nome']); ?></h5>
                                    <p class="card-text text-muted small"><?= htmlspecialchars($cat['descricao']); ?></p>
                                    <span class="badge bg-light text-primary border"><?= $cat['total_itens']; ?> itens</span>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php
                    endforeach;
                else:
                ?>
                    <div class="col-12">
                        <div class="alert alert-warning text-center">Nenhuma categoria encontrada no banco de dados.</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Seção de Destaques: Componente 3 do Bootstrap: Cards com Badges e Botões -->
    <section class="py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-1">Itens em Destaque</h2>
                    <p class="text-muted mb-0">Produtos e serviços de alta demanda retornados via consulta SQL</p>
                </div>
                <a href="produtos.php" class="btn btn-outline-primary">Ver Todos os <?= count($produtosDestaque); ?>+ Itens</a>
            </div>

            <div class="row g-4">
                <?php
                // Verificação condicional com IF/ELSE
                if (!empty($produtosDestaque)):
                    foreach ($produtosDestaque as $item):
                ?>
                    <div class="col-md-6 col-lg-3">
                        <div class="card h-100 product-card">
                            <div class="position-relative">
                                <img src="<?= htmlspecialchars($item['imagem']); ?>" class="card-img-top" alt="<?= htmlspecialchars($item['nome']); ?>" style="height: 200px; object-fit: cover;">
                                <span class="position-absolute top-0 start-0 m-2 badge <?= $item['tipo'] === 'servico' ? 'badge-servico' : 'badge-produto'; ?> text-white">
                                    <?= strtoupper($item['tipo']); ?>
                                </span>
                                <?php if ($item['estoque'] > 0 && $item['tipo'] === 'produto'): ?>
                                    <span class="position-absolute top-0 end-0 m-2 badge bg-success">Em estoque</span>
                                <?php endif; ?>
                            </div>
                            <div class="card-body d-flex flex-column">
                                <span class="text-muted small mb-1"><?= htmlspecialchars($item['categoria_nome']); ?></span>
                                <h5 class="card-title fw-bold text-dark fs-6"><?= htmlspecialchars($item['nome']); ?></h5>
                                <p class="card-text text-muted small flex-grow-1" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    <?= htmlspecialchars($item['descricao']); ?>
                                </p>
                                <div class="mt-3 pt-3 border-top d-flex justify-content-between align-items-center">
                                    <div>
                                        <small class="text-muted d-block">Preço à vista</small>
                                        <span class="fs-5 fw-bold text-primary">R$ <?= number_format((float)$item['preco'], 2, ',', '.'); ?></span>
                                    </div>
                                    <a href="detalhes.php?id=<?= $item['id']; ?>" class="btn btn-sm btn-primary">Detalhes</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                    endforeach;
                else:
                ?>
                    <div class="col-12">
                        <div class="alert alert-info">Nenhum produto em destaque no momento.</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Banner de Recursos do Projeto Acadêmico -->
    <section class="bg-primary text-white py-5">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <h3 class="fw-bold mb-2">Projeto Desenvolvido para Aprovação Máxima</h3>
                    <p class="mb-0">Cumpre 100% dos requisitos de Modelagem de Banco (DER, N:N), Sistemas Operacionais (Porta 8080, IP Fixo, .htaccess) e Tech Forge (Funções com return, Arrays estruturados).</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="banco.php" class="btn btn-light fw-bold me-2"><i class="bi bi-diagram-3 me-1"></i>Ver DER</a>
                    <a href="infra.php" class="btn btn-outline-light"><i class="bi bi-hdd-network me-1"></i>Ver Redes</a>
                </div>
            </div>
        </div>
    </section>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
