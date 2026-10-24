<?php
/**
 * TechByte - Sobre a Empresa (sobre.php)
 */

declare(strict_types=1);

$pageTitle = 'Sobre Nós - TechByte Soluções';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<main class="py-5">
    <div class="container">
        <!-- Hero Institucional -->
        <div class="row align-items-center g-5 mb-5">
            <div class="col-lg-6">
                <span class="badge bg-primary px-3 py-2 mb-3">Quem Somos</span>
                <h1 class="fw-bold display-5 mb-4">Especialistas em Hardware & Redes Corporativas</h1>
                <p class="text-muted lead">
                    Fundada por estudantes de Analise e Desenvolvimento de Sistemas, a TechByte nasceu com a missão de fornecer produtos de alta performance e serviços de infraestrutura sem burocracia.
                </p>
                <p class="text-muted">
                    Atendemos desde profissionais liberais até médias empresas, oferecendo consultoria de redes com equipamentos gerenciáveis, montagem de estações de trabalho e contratos mensais de suporte preventivo.
                </p>
            </div>
            <div class="col-lg-6">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=800&q=80" class="img-fluid" alt="Equipe TechByte">
                </div>
            </div>
        </div>

        <!-- Pilares e Valores -->
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm p-4 text-center">
                    <div class="fs-1 text-primary mb-3"><i class="bi bi-shield-lock-fill"></i></div>
                    <h5 class="fw-bold">Segurança & Confiabilidade</h5>
                    <p class="text-muted small">Todos os nossos servidores e redes são implementados seguindo as melhores práticas de isolamento e regras de firewall.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm p-4 text-center">
                    <div class="fs-1 text-primary mb-3"><i class="bi bi-speedometer2"></i></div>
                    <h5 class="fw-bold">Performance Extrema</h5>
                    <p class="text-muted small">Hardware de última geração e unidades NVMe Gen4 selecionadas a dedo para desenvolvedores e servidores.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm p-4 text-center">
                    <div class="fs-1 text-primary mb-3"><i class="bi bi-code-slash"></i></div>
                    <h5 class="fw-bold">Código Limpo & Aberto</h5>
                    <p class="text-muted small">Arquitetura modular em PHP moderno, orientada a templates reutilizáveis e código auditado.</p>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
