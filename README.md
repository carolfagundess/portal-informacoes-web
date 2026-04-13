# Portal de Informações Web (Sistema de Pesquisa IMC)

Um sistema web baseado em PHP para coleta, gerenciamento e análise de dados relacionados a Índices de Massa Corporal (IMC), idade e peso. O projeto funciona como um portal para registros investigativos, possuindo operações completas de CRUD (Create, Read, Update, Delete), interação com banco de dados e uma interface administrativa fácil de navegar utilizando Bootstrap 5.

## Funcionalidades Principais

- **Formulário de Coleta:** Permite a inserção de novos registros de forma rápida.
- **Painel Administrativo:** Dashboard modernizada para acesso rápido às principais áreas.
- **Listagem de Registros:** Tabela que permite visualizar, editar e remover os dados inseridos.
- **Análise de IMC:** Visualização de dados focados nos Índices de Massa Corporal.
- **Análise de Idade:** Métricas e resumos com base nas idades registradas no sistema.
- **Análise de Peso:** Estatísticas e visualização de dados sobre os pesos coletados.
- **Logs de Operações:** Funcionalidade que grava em arquivo texto (`log_operacoes.txt`) qualquer alteração feita no banco de dados, oferecendo rastreabilidade completa.

## Tecnologias

- **Front-End:** HTML5, CSS3, Javascript, Bootstrap 5.
- **Back-End:** PHP.
- **Banco de Dados:** MySQL.

## Estrutura de Arquivos

- `formulario.php`: Interface principal para submissão dos dados pelo usuário.
- `painelAdmin.php`: Index administrativo com botões de acesso às estatísticas.
- `tabelaRegistros.php`: Exibição tabular abrangente dos registros.
- `dadosImc.php`, `dadosIdade.php`, `dadosPeso.php`: Relatórios segmentados.
- `bd/`: Camada de manipulação de banco de dados (ex: `funcoes-bd.php`).
- `html/`: Componentes visuais da estrutura padrão da página (`cabecalho.php`, `rodape.php`).
- `processamento-de-dados/`: Scripts de tratamento de formulários e processamento de regras de negócios.

## Instalação / Uso

1. Importe o banco de dados necessário.
2. Certifique-se de configurar as credenciais no arquivo do diretório `bd`.
3. Utilize um servidor local com suporte a PHP (como XAMPP, WAMPP, Apache) e coloque a pasta do projeto dentro de `htdocs` ou `www`.
4. Acesse via `localhost/portal-informacoes-web/formulario.php`.
