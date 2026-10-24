# TechByte Soluções - Projeto Integrador Acadêmico

- **Aluno(a):** João Felipe Hildebtande
- **Curso:** Análise e Desenvolvimento de Sistemas
- **Link do Repositório GitHub:** https://github.com/joaoHildebrande/techbyte-php-system
- **Link do Site no Ar (Servidor Livre):** https://techbyte.rf.gd (InfinityFree)

---

## 📌 1. Visão Geral do Projeto

Website dinâmico desenvolvido em **PHP puro** e **MySQL**, com interface moderna responsiva utilizando **Bootstrap 5**, atendendo integralmente à folha de avaliação de Sprints nas disciplinas:

1. **Modelagem e Banco de Dados** (100% de conformidade com DER, PKs, FKs e N:N).
2. **Sistemas Operacionais e Redes de Computadores** (Porta 8080, IP Fixo, DNS local, máquinas isoladas e proteção .htaccess).
3. **Desenvolvimento Web Moderna** (Layout dinâmico, templates PHP com header/navbar/footer, conexão PDO e comandos PHP).
4. **Tech Forge** (Arrays estruturados, modularização de funções com parâmetros/return, validação condicional com if/else e busca).

---

## 🚀 2. Como Rodar o Projeto

### Opção A: Com Docker (Recomendado para Redes & SO)

```bash
# 1. Clonar o repositório
git clone https://github.com/leticiacsc1/techbyte-php-system.git
cd techbyte-php-system

# 2. Subir os contêineres na porta 8080 e IP fixo 172.20.0.10
docker compose up -d

# 3. Configurar DNS local no /etc/hosts ou Windows hosts:
# 127.0.0.1 techbyte.local

# 4. Acessar no navegador:
# http://techbyte.local:8080
```

### Opção B: Com XAMPP / WampServer Local

1. Copie a pasta do projeto para `C:\xampp\htdocs\techbyte`.
2. Acesse o phpMyAdmin (`http://localhost/phpmyadmin`).
3. Crie o banco `techbyte_db` e importe o arquivo `database/schema.sql` e depois `database/seed.sql`.
4. Ajuste as credenciais no `config/database.php` se necessário.
5. Inicie o servidor PHP embutido na porta 8080:
   ```bash
   php -S 127.0.0.1:8080
   ```
6. Acesse `http://localhost:8080`.
