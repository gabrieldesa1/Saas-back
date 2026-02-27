# StockFlow API 🚀

API REST desenvolvida para gerenciamento de estoque, categorias e produtos.

## 📌 Objetivo

Fornecer uma API estruturada para controle de produtos e categorias, permitindo operações completas de CRUD e organização de estoque.

---

## 🛠 Tecnologias Utilizadas

- PHP
- MySQL
- Insomnia
- TypeScript
- Banco de dados relacional
- Arquitetura em camadas (routes, controllers, services)

---

## ⚙️ Funcionalidades

### 📦 Produtos
- Criar produto
- Listar produtos
- Atualizar produto
- Deletar produto
- Controle de quantidade
- Cálculo de custo unitário

### 🗂 Categorias
- Criar categoria
- Listar categorias
- Atualizar categoria
- Deletar categoria

---

## 🔗 Estrutura da API

### Produtos
GET /products  
POST /products  
PUT /products/:id  
DELETE /products/:id  

### Categorias
GET /categories  
POST /categories  
PUT /categories/:id  
DELETE /categories/:id  

---

## 🧠 Regras de Negócio

- Produto pertence a uma categoria
- Controle de estoque baseado em quantidade
- Cálculo automático de custo unitário baseado em custo total ÷ quantidade

---

## 🚀 Próximas melhorias

- Relacionamento por categoryId (melhor modelagem)
- Autenticação JWT
- Validação com Zod
- Logs estruturados
- Deploy em ambiente cloud

---

## 👨‍💻 Desenvolvido por

Gabriel de Sá