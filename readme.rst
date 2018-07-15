###################
Desafio
###################
Criar um sistema onde seja possível Listar, Criar e Encerrar registros de incidentes em empresas.
Aqui foi criado uma API REST com essas 3 funções, realizando as devidas validações.
Também uma página web onde é possível de forma simples visualizar esse funcionamento.

************
Guia
************
Por favor veja o `Manual para chamadas da API <https://docs.google.com/document/d/1SmtS32kfHzrgC8A8ru2E7KKVkhW3QZ2RjmKANgHhOBk/edit?usp=sharing>`_

Para ver as chamadas prontas `usando POSTMAN <https://www.getpostman.com/collections/7f857ff072e1f07a49e4>`_

Para usar localmente é importante alterar os dados do banco de dados em Application/config/database se alterar o nome da pasta também deve ser alerado em Application/config/config > base_url

************
Utilizado
************
PHP, HTML 5, Ajax, Javascript, CSS3 e JQuery. Padrão REST para API. Padrão de arquitetura MVC.
Framework Codeigniter com implementação de terceiros `para trabalhar com REST <https://github.com/chriskacerguis/codeigniter-restserver>`_

Somente para ficar mais agradável, visto que não pretendo fazer o teste para Frontend, usei as biliotecas: Bootstrap e Datatable.

************
Possíveis Melhorias
************
Com mais tempo é possível usar sockets para listar em tempo real os novos registros, também seria possível usar paginação com AJAX, para não listar todos os itens a cada chamada.