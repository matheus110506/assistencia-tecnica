# Dicionário de Dados: Banco de Dados `DB_Empresa2`

## Introdução
Este documento descreve detalhadamente a estrutura e o propósito de cada tabela e coluna do banco de dados `DB_Empresa2`, utilizado no Sistema de Assistência Técnica. Ele serve como documentação técnica complementar para desenvolvedores e administradores de banco de dados.

---

## 1. Tabela: CHAMADO
**Descrição:** Tabela central que registra a abertura e o acompanhamento de um chamado de assistência técnica.

| Coluna | Tipo de Dado | Tamanho | Nulo? | Chave? | Padrão | Descrição |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **id** | int | - | Não | PK | Identity | ID único e autoincrementado do chamado. |
| **id_equipamento**| int | - | Sim | FK | - | Referência ao equipamento (`EQUIPAMENTO.id`). |
| **id_cliente** | int | - | Sim | FK | - | Referência ao cliente (`CLIENTES.id`). |
| **id_tecnico** | int | - | Sim | FK | - | Referência ao técnico atribuído (`TECNICO.id`). Pode ser nulo na abertura. |
| **descricao_problema**| varchar | 255 | Sim | - | - | Relato do problema apresentado. |
| **status_chamado**| varchar | 30 | Sim | - | - | Estado do chamado (ex: "Aberto", "Em Análise", "Concluído"). |
| **data_chamado** | date | - | Sim | - | - | Data em que o chamado foi aberto. |

---

## 2. Tabela: CHAMADO_PECA
**Descrição:** Tabela associativa que vincula peças utilizadas a um chamado específico (Relacionamento N:N entre `CHAMADO` e `PECA`).

| Coluna | Tipo de Dado | Tamanho | Nulo? | Chave? | Padrão | Descrição |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **id** | int | - | Não | PK | Identity | ID único e autoincrementado da associação. |
| **id_chamado** | int | - | Sim | FK | - | Referência ao chamado (`CHAMADO.id`). |
| **id_peca** | int | - | Sim | FK | - | Referência à peça (`PECA.id`). |
| **quantidade** | int | - | Sim | - | - | Quantidade da peça utilizada no chamado. |

---

## 3. Tabela: CHAMADO_SERVICO
**Descrição:** Tabela associativa que vincula serviços realizados a um chamado específico (Relacionamento N:N entre `CHAMADO` e `SERVICO`).

| Coluna | Tipo de Dado | Tamanho | Nulo? | Chave? | Padrão | Descrição |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **id** | int | - | Não | PK | Identity | ID único e autoincrementado da associação. |
| **id_chamado** | int | - | Sim | FK | - | Referência ao chamado (`CHAMADO.id`). |
| **id_servico** | int | - | Sim | FK | - | Referência ao serviço (`SERVICO.id`). |

---

## 4. Tabela: CLIENTES
**Descrição:** Armazena as informações cadastrais dos clientes.

| Coluna | Tipo de Dado | Tamanho | Nulo? | Chave? | Padrão | Descrição |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **id** | int | - | Não | PK | Identity | ID único e autoincrementado do cliente. |
| **nome** | varchar | 255 | Sim | - | - | Nome completo do cliente. |
| **email** | varchar | 255 | Sim | - | - | Endereço de e-mail (único na aplicação). |
| **telefone** | varchar | 15 | Sim | - | - | Número de telefone (ex: (99) 99999-9999). |
| **cpf** | varchar | 14 | Sim | - | - | CPF (ex: 999.999.999-99). |
| **estado** | char | 2 | Sim | - | - | Sigla do estado (ex: SP, RJ, MG). |
| **senha** | varchar | 255 | Sim | - | - | Senha criptografada (hash) para acesso. |
| **ativo** | bit | - | Sim | - | 1 | 1=Ativo (Padrão), 0=Inativo (Soft Delete). |

---

## 5. Tabela: EQUIPAMENTO
**Descrição:** Armazena os dados dos equipamentos dos clientes.

| Coluna | Tipo de Dado | Tamanho | Nulo? | Chave? | Padrão | Descrição |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **id** | int | - | Não | PK | Identity | ID único e autoincrementado do equipamento. |
| **modelo** | varchar | 255 | Sim | - | - | Modelo específico do equipamento. |
| **marca** | varchar | 255 | Sim | - | - | Fabricante do equipamento. |
| **tipo** | varchar | 50 | Sim | - | - | Categoria do equipamento (ex: Smartphone, TV). |
| **numero_serie** | varchar | 15 | Sim | - | - | Número de série único do fabricante. |
| **ativo** | bit | - | Sim | - | 1 | 1=Ativo (Padrão), 0=Inativo (Soft Delete). |

---

## 6. Tabela: PECA
**Descrição:** Armazena os dados das peças disponíveis em estoque.

| Coluna | Tipo de Dado | Tamanho | Nulo? | Chave? | Padrão | Descrição |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **id** | int | - | Não | PK | Identity | ID único e autoincrementado da peça. |
| **modelo** | varchar | 255 | Sim | - | - | Modelo específico da peça. |
| **marca** | varchar | 255 | Sim | - | - | Fabricante da peça. |
| **tipo** | varchar | 255 | Sim | - | - | Categoria da peça (ex: Tela, Bateria). |
| **preco** | decimal | 10,2 | Sim | - | - | Preço unitário da peça. |
| **estoque** | int | - | Sim | - | - | Quantidade atual em estoque. |

---

## 7. Tabela: SERVICO
**Descrição:** Armazena os tipos de serviços que podem ser realizados.

| Coluna | Tipo de Dado | Tamanho | Nulo? | Chave? | Padrão | Descrição |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **id** | int | - | Não | PK | Identity | ID único e autoincrementado do serviço. |
| **descricao** | varchar | 255 | Sim | - | - | Descrição detalhada do serviço. |
| **valor** | decimal | 10,2 | Sim | - | - | Valor cobrado pelo serviço. |
| **inicio** | date | - | Sim | - | - | Data de início da vigência do serviço (opcional). |
| **fim** | date | - | Sim | - | - | Data de fim da vigência do serviço (opcional). |
| **status_servico**| varchar | 30 | Sim | - | - | Status (ex: "Ativo", "Obsoleto"). |

---

## 8. Tabela: TECNICO
**Descrição:** Armazena as informações cadastrais dos técnicos.

| Coluna | Tipo de Dado | Tamanho | Nulo? | Chave? | Padrão | Descrição |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **id** | int | - | Não | PK | Identity | ID único e autoincrementado do técnico. |
| **nome** | varchar | 255 | Sim | - | - | Nome completo do técnico. |
| **email** | varchar | 255 | Sim | - | - | Endereço de e-mail (único na aplicação). |
| **telefone** | varchar | 15 | Sim | - | - | Número de telefone (ex: (99) 99999-9999). |
| **cpf** | varchar | 14 | Sim | - | - | CPF (ex: 999.999.999-99). |
| **senha** | varchar | 255 | Sim | - | - | Senha criptografada (hash) para acesso. |

### Legenda e Convenções

*   **PK:** Chave Primária (Identificador único).
*   **FK:** Chave Estrangeira (Vincula a outra tabela).
*   **Identity:** Valor gerado automaticamente pelo banco (SQL Server).
*   **varchar(N):** Texto de tamanho variável, até N caracteres.
*   **decimal(10,2):** Número decimal com 10 dígitos totais e 2 casas decimais.
*   **bit:** Valor booleano (1=Sim/Verdadeiro, 0=Não/Falso).