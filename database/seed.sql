-- Inserção de Categorias
INSERT INTO categorias (id, nome, slug, descricao, icone) VALUES
(1, 'Hardware & Peças', 'hardware', 'Processadores, memórias e SSDs', 'bi-cpu'),
(2, 'Computadores & Notebooks', 'computadores', 'Máquinas completas e estações DevPro', 'bi-laptop'),
(3, 'Redes & Infraestrutura', 'redes', 'Switches, roteadores e racks', 'bi-hdd-network'),
(4, 'Serviços Especializados', 'servicos', 'Consultoria em redes, manutenção e infra', 'bi-wrench');

-- Inserção de Produtos e Serviços
INSERT INTO produtos_servicos (id, categoria_id, nome, descricao, tipo, preco, estoque, peso_kg, imagem, destaque) VALUES
(1, 1, 'SSD NVMe M.2 1TB Gen4 HighSpeed', 'Unidade de estado sólido com leitura de 7000MB/s e escrita de 6500MB/s.', 'produto', 489.90, 15, 0.15, 'https://images.unsplash.com/photo-1597872200969-2b65d56bd16b?auto=format&fit=crop&w=600&q=80', 1),
(2, 2, 'Workstation DevPro Core i7 32GB RAM', 'Desktop otimizado para desenvolvedores e compiladores.', 'produto', 4850.00, 5, 8.50, 'https://images.unsplash.com/photo-1587831990711-23ca6441447b?auto=format&fit=crop&w=600&q=80', 1),
(3, 4, 'Consultoria e Configuração de Redes', 'Topologia de rede local, VLANs, roteamento estático e firewall.', 'servico', 750.00, 99, 0.00, 'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?auto=format&fit=crop&w=600&q=80', 1),
(4, 1, 'Memória RAM DDR4 16GB 3200MHz', 'Módulo de memória para estações de trabalho e testes.', 'produto', 229.00, 24, 0.10, 'https://images.unsplash.com/photo-1562976540-1502c2145186?auto=format&fit=crop&w=600&q=80', 0),
(5, 3, 'Switch Gerenciável 24 Portas Gigabit', 'Switch L2+ com 24 portas Gigabit e 4 uplinks SFP para fibra.', 'produto', 1290.00, 8, 3.20, 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?auto=format&fit=crop&w=600&q=80', 1),
(6, 4, 'Manutenção e Higienização de Servidor', 'Checklist de 25 itens, troca de pasta térmica e logs do SO.', 'servico', 380.00, 99, 0.00, 'https://images.unsplash.com/photo-1629654297299-c8506221ca97?auto=format&fit=crop&w=600&q=80', 0),
(7, 2, 'Notebook Corporativo UltraSlim 15.6"', 'Portátil para produtividade com processador Ryzen 7 e 16GB RAM.', 'produto', 3499.00, 7, 1.70, 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&w=600&q=80', 0),
(8, 4, 'Instalação e Certificação Cat6', 'Passagem de cabos, conectorização e certificação de pontos.', 'servico', 590.00, 99, 0.00, 'https://images.unsplash.com/photo-1551703599-6b3e8379aa8c?auto=format&fit=crop&w=600&q=80', 0);

-- Inserção de Exemplo para Teste do Relacionamento N:N
INSERT INTO pedidos_orcamentos (id, cliente_nome, cliente_email, cliente_telefone, status, valor_total, valor_frete, regiao_entrega) VALUES
(1, 'Leticia Silva', 'leticia@faculdade.edu.br', '(11) 98765-4321', 'concluido', 5339.90, 0.00, 'sudeste');

-- Inserção dos Itens do Pedido (Relacionamento N:N)
INSERT INTO item_pedido (pedido_id, produto_id, quantidade, preco_unitario) VALUES
(1, 1, 1, 489.90),
(1, 2, 1, 4850.00);
