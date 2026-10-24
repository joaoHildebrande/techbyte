<?php
/**
 * TechByte - Componente Navbar (Bootstrap 5)
 * Garante mais de 5 links exigidos no enunciado do projeto:
 * 1. Início (index.php)
 * 2. Produtos & Serviços (produtos.php)
 * 3. Simulador de Frete & Descontos (simulador.php)
 * 4. Sobre a Empresa (sobre.php)
 * 5. Contato & Orçamento (contato.php)
 * 6. Diagrama DER & Banco (banco.php)
 * 7. Infraestrutura de Redes (infra.php)
 */
$currentPage = basename($_SERVER['PHP_SELF'] ?? '');
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm py-3">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
            <span class="p-2 bg-primary text-white rounded-3 d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                <i class="bi bi-cpu-fill"></i>
            </span>
            <span>Tech<span class="text-primary">Byte</span></span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTechByte" aria-controls="navbarTechByte" aria-expanded="false" aria-label="Alternar navegação">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarTechByte">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= ($currentPage === 'index.php') ? 'active fw-bold' : ''; ?>" href="index.php">
                        <i class="bi bi-house me-1"></i> Início
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($currentPage === 'produtos.php') ? 'active fw-bold' : ''; ?>" href="produtos.php">
                        <i class="bi bi-grid me-1"></i> Produtos & Serviços
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($currentPage === 'simulador.php') ? 'active fw-bold' : ''; ?>" href="simulador.php">
                        <i class="bi bi-calculator me-1"></i> Simulador & Frete
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($currentPage === 'sobre.php') ? 'active fw-bold' : ''; ?>" href="sobre.php">
                        <i class="bi bi-info-circle me-1"></i> Sobre Nós
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($currentPage === 'contato.php') ? 'active fw-bold' : ''; ?>" href="contato.php">
                        <i class="bi bi-envelope me-1"></i> Contato
                    </a>
                </li>
                <!-- Links Acadêmicos para avaliação do professor -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-warning" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-mortarboard-fill me-1"></i> Avaliação Sprint
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <li><a class="dropdown-item" href="banco.php"><i class="bi bi-diagram-3 me-2"></i>Diagrama DER & Banco (3 tabelas + N:N)</a></li>
                        <li><a class="dropdown-item" href="infra.php"><i class="bi bi-hdd-network me-2"></i>Redes & SO (Porta 8080, IP Fixo, DNS)</a></li>
                    </ul>
                </li>
            </ul>

            <form class="d-flex" action="produtos.php" method="GET">
                <div class="input-group">
                    <input class="form-control form-control-sm" type="search" name="busca" placeholder="Buscar peça ou serviço..." aria-label="Buscar">
                    <button class="btn btn-sm btn-primary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</nav>
