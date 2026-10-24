<?php
/**
 * TechByte - Componente Rodapé (Footer)
 */
?>
<footer class="bg-dark text-light pt-5 pb-4 mt-auto border-top border-secondary">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <h5 class="fw-bold text-white d-flex align-items-center gap-2">
                    <span class="p-1 bg-primary text-white rounded d-inline-flex"><i class="bi bi-cpu-fill"></i></span>
                    TechByte Soluções
                </h5>
                <p class="text-secondary small mt-3">
                    Soluções em TI, venda de hardware de alto desempenho e prestação de serviços corporativos em infraestrutura e redes.
                </p>
                <div class="d-flex gap-2">
                    <span class="badge bg-secondary">PHP 8.2+</span>
                    <span class="badge bg-secondary">MySQL 8</span>
                    <span class="badge bg-secondary">Bootstrap 5.3</span>
                    <span class="badge bg-secondary">Docker 8080</span>
                </div>
            </div>

            <div class="col-lg-2 col-md-6">
                <h6 class="text-white fw-bold mb-3">Navegação</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="index.php" class="text-secondary text-decoration-none hover-white">Início</a></li>
                    <li class="mb-2"><a href="produtos.php" class="text-secondary text-decoration-none hover-white">Produtos</a></li>
                    <li class="mb-2"><a href="produtos.php?tipo=servico" class="text-secondary text-decoration-none hover-white">Serviços</a></li>
                    <li class="mb-2"><a href="simulador.php" class="text-secondary text-decoration-none hover-white">Simulador</a></li>
                    <li class="mb-2"><a href="sobre.php" class="text-secondary text-decoration-none hover-white">Sobre Nós</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6">
                <h6 class="text-white fw-bold mb-3">Requisitos Acadêmicos</h6>
                <ul class="list-unstyled small text-secondary">
                    <li class="mb-1"><i class="bi bi-check2-circle text-success me-1"></i> Banco MySQL com N:N</li>
                    <li class="mb-1"><i class="bi bi-check2-circle text-success me-1"></i> Diagrama DER incluso</li>
                    <li class="mb-1"><i class="bi bi-check2-circle text-success me-1"></i> Porta 8080 & IP Fixo</li>
                    <li class="mb-1"><i class="bi bi-check2-circle text-success me-1"></i> Listagem de pastas proibida</li>
                    <li class="mb-1"><i class="bi bi-check2-circle text-success me-1"></i> Funções com retorno e arrays</li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6">
                <h6 class="text-white fw-bold mb-3">Atendimento</h6>
                <p class="text-secondary small mb-1"><i class="bi bi-geo-alt me-2"></i> Av. da Tecnologia, 1024 - Tech City</p>
                <p class="text-secondary small mb-1"><i class="bi bi-telephone me-2"></i> (11) 4002-8922</p>
                <p class="text-secondary small"><i class="bi bi-envelope me-2"></i> contato@techbyte.local</p>
            </div>
        </div>

        <hr class="border-secondary my-4">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center text-secondary small">
            <p class="mb-0">&copy; <?= date('Y'); ?> TechByte Soluções. Projeto acadêmico para avaliação de Sprint.</p>
            <p class="mb-0">Hospedagem em servidor livre com suporte a PHP e MySQL.</p>
        </div>
    </div>
</footer>

<!-- Bootstrap 5 Bundle JS com Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
