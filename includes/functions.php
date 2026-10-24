<?php
/**
 * TechByte Soluções - Funções de Processamento & Regras de Negócio
 * Disciplina: Tech Forge & Desenvolvimento Web Moderna
 *
 * REQUISITOS ATENDIDOS:
 * 1. Armazenamento estruturado em Arrays (proibido variáveis soltas $item1, $item2).
 * 2. Funções modulares criadas pelo aluno com escopo isolado.
 * 3. Parâmetros e retorno estritos: SEM uso de variáveis globais ($GLOBALS ou global $var).
 * 4. Lógica de pesquisa e filtro sobre arrays.
 * 5. Validação de regras de negócio com condicionais (if/else) para consistência dos dados.
 */

declare(strict_types=1);

/**
 * Validação de integridade de dados do array antes de processar.
 * Requisito: Não permitir cálculos se o array estiver vazio ou se houver valores negativos.
 *
 * @param array $itens Lista de itens ou produtos
 * @return array ['valido' => bool, 'mensagem' => string]
 */
function validarArrayItens(array $itens): array {
    // Validação 1: Array não pode estar vazio
    if (empty($itens)) {
        return [
            'valido' => false,
            'mensagem' => 'O array de itens está vazio. Nenhum cálculo pode ser realizado.'
        ];
    }

    // Validação 2: Verificar integridade de cada item
    foreach ($itens as $indice => $item) {
        if (!is_array($item)) {
            return [
                'valido' => false,
                'mensagem' => "O elemento no índice {$indice} não é um array associativo estruturado."
            ];
        }

        if (!isset($item['preco']) || !is_numeric($item['preco'])) {
            return [
                'valido' => false,
                'mensagem' => "O item '{$item['nome']}' possui preço indefinido ou inválido."
            ];
        }

        // Não permitir valores negativos
        if ($item['preco'] < 0) {
            return [
                'valido' => false,
                'mensagem' => "O item '{$item['nome']}' contém valor negativo (R$ {$item['preco']}). Operação bloqueada!"
            ];
        }

        // Validação de quantidade se existir
        if (isset($item['quantidade']) && $item['quantidade'] <= 0) {
            return [
                'valido' => false,
                'mensagem' => "A quantidade do item '{$item['nome']}' deve ser maior que zero."
            ];
        }
    }

    return [
        'valido' => true,
        'mensagem' => 'Dados consistentes e validados com sucesso.'
    ];
}

/**
 * Função de Processamento: Calcula o valor do frete com base na região, peso e valor da compra.
 * Requisito: Recebe informações via parâmetros e devolve através de return.
 *
 * @param float $valorPedido Valor total da compra
 * @param string $regiao Região de entrega (sudeste, sul, centro-oeste, nordeste, norte)
 * @param float $pesoKg Peso total estimado em kg
 * @return float Valor do frete calculado
 */
function calcularFrete(float $valorPedido, string $regiao, float $pesoKg = 1.0): float {
    // Regra de Negócio: Frete Grátis para compras acima de R$ 500,00 no Sudeste
    $regiaoNormalizada = strtolower(trim($regiao));

    if ($valorPedido >= 500.00 && $regiaoNormalizada === 'sudeste') {
        return 0.00;
    }

    // Tabela de taxas base por região
    $taxasBase = [
        'sudeste'      => 18.50,
        'sul'          => 28.00,
        'centro-oeste' => 35.00,
        'nordeste'     => 42.00,
        'norte'        => 55.00
    ];

    // Se região informada não for reconhecida, adota taxa padrão
    $taxaBase = $taxasBase[$regiaoNormalizada] ?? 30.00;

    // Adicional por peso (R$ 4,50 por kg excedente a 1kg)
    $adicionalPeso = 0.00;
    if ($pesoKg > 1.0) {
        $adicionalPeso = ($pesoKg - 1.0) * 4.50;
    }

    // Retorno do resultado calculado
    return round($taxaBase + $adicionalPeso, 2);
}

/**
 * Função de Processamento: Aplica desconto comercial e regras de cupom promocional.
 *
 * @param float $valorOriginal Valor bruto
 * @param string $cupom Código do cupom digitado pelo usuário
 * @param string $formaPagamento PIX, Boleto ou Cartão
 * @return array Estrutura detalhada com percentual, desconto em R$ e valor final
 */
function aplicarDesconto(float $valorOriginal, string $cupom = '', string $formaPagamento = 'pix'): array {
    if ($valorOriginal <= 0) {
        return [
            'sucesso' => false,
            'mensagem' => 'Valor base deve ser maior que zero.',
            'valor_original' => 0.0,
            'desconto_total' => 0.0,
            'valor_final' => 0.0
        ];
    }

    $percentualCupom = 0.0;
    $cupomNormalizado = strtoupper(trim($cupom));

    // Validação condicional de cupons da faculdade / loja
    if ($cupomNormalizado === 'TECH10') {
        $percentualCupom = 10.0; // 10% de desconto
    } elseif ($cupomNormalizado === 'FACULDADE15') {
        $percentualCupom = 15.0; // 15% para estudantes
    } elseif ($cupomNormalizado === 'UNIFOR50' && $valorOriginal > 1000.00) {
        $percentualCupom = 20.0; // 20% especial para grandes projetos
    }

    // Desconto de modalidade de pagamento (ex: 5% extra no PIX)
    $percentualPagamento = 0.0;
    if (strtolower($formaPagamento) === 'pix') {
        $percentualPagamento = 5.0;
    }

    $percentualTotal = $percentualCupom + $percentualPagamento;
    $valorDesconto = round(($valorOriginal * $percentualTotal) / 100, 2);
    $valorFinal = round($valorOriginal - $valorDesconto, 2);

    return [
        'sucesso' => true,
        'cupom_aplicado' => !empty($cupomNormalizado) ? $cupomNormalizado : 'Nenhum',
        'percentual_cupom' => $percentualCupom,
        'percentual_pagamento' => $percentualPagamento,
        'percentual_total' => $percentualTotal,
        'valor_original' => $valorOriginal,
        'valor_desconto' => $valorDesconto,
        'valor_final' => $valorFinal
    ];
}

/**
 * Função de Processamento: Calcula plano de parcelamento sem juros em até 10x.
 *
 * @param float $valorTotal
 * @param int $maxParcelas
 * @return array Array estruturado de parcelas calculadas
 */
function calcularParcelamento(float $valorTotal, int $maxParcelas = 10): array {
    if ($valorTotal <= 0 || $maxParcelas <= 0) {
        return [];
    }

    $planoParcelas = [];
    for ($i = 1; $i <= $maxParcelas; $i++) {
        $valorParcela = round($valorTotal / $i, 2);
        $planoParcelas[] = [
            'parcela' => $i,
            'valor_parcela' => $valorParcela,
            'descricao' => "{$i}x de R$ " . number_format($valorParcela, 2, ',', '.') . " sem juros"
        ];
    }

    return $planoParcelas;
}

/**
 * Lógica de Pesquisa e Filtro sobre o Array estruturado de dados.
 * Atende ao requisito da rubrica: "busca dentro do array que retorne um dado específico
 * ou uma lista filtrada com base em uma condição (ex: mostrar apenas produtos com preço > 100)".
 *
 * @param array $produtos Lista estruturada de produtos
 * @param string $termoBusca Texto pesquisado pelo usuário
 * @param int|null $categoriaId Filtro de categoria
 * @param float|null $precoMin Preço mínimo
 * @param float|null $precoMax Preço máximo
 * @param string|null $tipo 'produto' ou 'servico'
 * @return array Lista filtrada
 */
function filtrarProdutos(
    array $produtos,
    string $termoBusca = '',
    ?int $categoriaId = null,
    ?float $precoMin = null,
    ?float $precoMax = null,
    ?string $tipo = null
): array {
    // Se o array de entrada estiver vazio, retorna vazio
    if (empty($produtos)) {
        return [];
    }

    $resultados = [];
    $termoLimpo = mb_strtolower(trim($termoBusca), 'UTF-8');

    foreach ($produtos as $item) {
        // Validação condicional 1: Termo de busca no nome ou descrição
        if (!empty($termoLimpo)) {
            $nomeItem = mb_strtolower($item['nome'], 'UTF-8');
            $descItem = mb_strtolower($item['descricao'], 'UTF-8');
            if (strpos($nomeItem, $termoLimpo) === false && strpos($descItem, $termoLimpo) === false) {
                continue; // Não coincide com a busca textual
            }
        }

        // Validação condicional 2: Categoria
        if ($categoriaId !== null && (int)$item['categoria_id'] !== $categoriaId) {
            continue;
        }

        // Validação condicional 3: Tipo (produto ou serviço)
        if ($tipo !== null && $item['tipo'] !== $tipo) {
            continue;
        }

        // Validação condicional 4: Faixa de Preço Mínimo
        if ($precoMin !== null && (float)$item['preco'] < $precoMin) {
            continue;
        }

        // Validação condicional 5: Faixa de Preço Máximo
        if ($precoMax !== null && (float)$item['preco'] > $precoMax) {
            continue;
        }

        // Item atende a todos os critérios
        $resultados[] = $item;
    }

    return $resultados;
}
