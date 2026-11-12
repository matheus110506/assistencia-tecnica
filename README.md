# Sistema de Assistência Técnica

## 1. Objetivo do Sistema
O objetivo deste sistema é gerenciar chamados de manutenção para uma oficina de assistência técnica de eletrônicos.

## 2. Descrição do Problema
O sistema foi desenvolvido para facilitar a gestão de uma empresa de assistência técnica que precisa lidar com múltiplos clientes, equipamentos e chamados, buscando evitar problemas como a dificuldade em rastrear peças/status de equipamentos e perda de informações de clientes.

## 3. Tecnologias Utilizadas
*   **Back-end:** PHP 8.1
*   **Banco de Dados:** SQL Server
*   **Front-end:** HTML5, CSS3 (Tailwind CSS via CDN), JavaScript (para manipulação de DOM e redirecionamento).

## 4. Requisitos
* Cadastrar cliente.
* Cadastrar equipamento.
* Excluir cliente (Soft Delete).
* Abrir chamado
* Atribuir cliente a equipamento
* Atribuir técnico a chamado
* Mudar status de chamado

## 5. Arquitetura da Aplicação
* Monolítica - todos os arquivos ficam no mesmo repositório
* Client-Server (cliente-servidor) – cliente (front-end) conversa com o servidor (back-end), com conexão via PDO

## 6. Estrutura do Banco de Dados
A aplicação utiliza as seguintes tabelas principais:
*   `CLIENTES`: Armazena os dados dos clientes.
*   `TECNICO`: Armazena os dados dos técnicos.
*   `EQUIPAMENTO`: Armazena os dados dos equipamentos dos chamados.
*   `CHAMADO`: Tabela principal que vincula cliente, equipamento e técnico, e armazena a descrição do problema.
*   `SERVICO`: Armazena a descrição do serviço efetuado.
*   `CHAMADO_SERVICO`: Tabela de vínculo (N:N) entre Chamados e Serviços.

O diagrama Entidade-Relacionamento (DER) pode ser encontrado na pasta `/documentacao`.

## 7. Como Instalar
1.  Clone o repositório:
    ```bash
    git clone [https://github.com/seu-usuario/assistencia-tecnica.git](https://github.com/seu-usuario/assistencia-tecnica.git)
    ```
2.  Importe o arquivo `banco.sql` para o seu servidor SQL Server.

## 8. Como Configurar
1. Abra o arquivo conexao.php na raiz do projeto.
2. Altere as variáveis de conexão de acordo com o seu SQL Server local.
3. Salve o arquivo.

## 9. Como Executar
1. Certifique-se de que o Apache e o SQL Server estejam rodando (ex: via Painel de Controle do XAMPP).
2. Abra um navegador web de sua preferência.
3. Digite o endereço: http://localhost/assistencia-tecnica
4. O sistema será carregado e estará pronto para uso.

## 10. Funcionalidades
A aplicação permite:
- [x] Cadastro, Edição e Exclusão (Soft Delete) de Clientes.
- [ ] Cadastro de Técnicos (Melhoria Futura).
- [x] Abertura de Chamados vinculando Cliente e Equipamento.
- [x] Edição de Chamados para atribuição de Técnico e descrição do Serviço Efetuado.
- [ ] Geração de Relatórios de Chamados Concluídos (Melhoria Futura).

## 11. Como realizar os testes

Esta aplicação foi validada através de testes manuais. Para verificar o funcionamento do sistema, siga o roteiro de teste abaixo acessando a aplicação via navegador (ex: `http://localhost/assistencia-tecnica`).

### Roteiro de Teste Manual Simplificado

| ID | Cenário | Passos para Execução | Resultado Esperado |
| :--- | :--- | :--- | :--- |
| **CT01** | **Cadastro com Sucesso** | 1. Acesse `cadastrar_cliente.php`.<br>2. Preencha todos os campos obrigatórios (*Nome, E-mail, CPF, Senha*) com dados válidos.<br>3. Clique em "Guardar Cliente". | O sistema deve exibir uma mensagem de sucesso e, após 2 segundos, redirecionar para `clientes.php` e uma nova linha ser adicionada à tabela. |
| **CT02** | **Validação de Campo Obrigatório** | 1. Acesse `cadastrar_cliente.php`.<br>2. Deixe o campo "Nome" vazio e preencha os outros.<br>3. Clique em "Guardar Cliente". | O formulário não deve ser enviado e o navegador deve apontar o erro no campo "Nome" (devido ao atributo `required`). |
| **CT03** | **Validação de E-mail Inválido** | 1. Acesse `cadastrar_cliente.php`.<br>2. No campo "E-mail", digite `email_errado_sem_arroba`.<br>3. Clique em "Guardar Cliente". | O formulário não deve ser enviado. |
| **CT04** | **Edição com Sucesso** | 1. Acesse `clientes.php` e clique em "Editar" em um cliente existente.<br>2. Altere o nome do cliente.<br>3. Clique em "Salvar Alterações". | O sistema deve exibir uma mensagem de sucesso, o usuário deve ser redirecionado de volta para `clientes.php` após 2 segundos e o nome alterado deve aparecer na lista. |
| **CT05** | **Exclusão (Soft Delete)** | 1. Acesse `clientes.php` e clique em "Excluir" em um cliente.<br>2. Confirme a exclusão no alerta do navegador. | O cliente deve desaparecer da lista principal (`clientes.php`), mas seu registro deve permanecer no banco de dados com a coluna `ativo` definida como `0`. |
| **CT06** | **Atribuição de Técnico** | 1. Acesse `editar_chamado.php?id=1` (ou ID existente).<br>2. No campo "Técnico Responsável", selecione um técnico.<br>3. Clique em "Salvar Alterações". | O sistema deve exibir mensagem de sucesso, o usuário deve ser redirecionado a `chamados.php` após 2 segundos e o técnico selecionado deve aparecer lá. |

---

> **Observação:** Para uma documentação detalhada de todos os 10+ casos de teste realizados, incluindo dados utilizados, evidências e lista de problemas, consulte o documento `planilha_testes.docx` (ou `.xlsx`) na pasta `/docs/testes` deste repositório.

## 12. Limitações Conhecidas
1. O sistema não gera relatórios de chamados concluídos.
2. As senhas de usuários são armazenadas em texto simples no banco de dados.
3. Incapacidade de reverter pela interface a exclusão de um item, necessitando alterar diretamente no banco de dados a coluna ativo de 0 para 1.
4. Ausência de um controle de acesso (login) para administradores e técnicos.

## 13. Melhorias Futuras
1. Módulo para gerar relatórios de chamados concluídos.
2. Criptografia hash das senhas.
3. Interface para reveter exclusões.
4. Controle de acesso do sistema.