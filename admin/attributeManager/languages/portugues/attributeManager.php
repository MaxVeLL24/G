<?php
/*
  $Id: attributeManager.php,v 1.0 21/02/06 Sam West$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Released under the GNU General Public License
  
  Traduзгo para Portuguкs do Brasil de AJAX-AttributeManager-V2.7
  
  por Valmy Gomes (Atualizado em 28/01/2010)
  Conheзa a LEGALLOJA, uma OsCommerce integrada com Mercado Livre, Toda Oferta, Twitter e SMS
  http://www.legalloja.com.br
  valmygomes@legalzona.com.br
  */

//attributeManagerPrompts.inc.php

define('AM_AJAX_YES', 'Sim');
define('AM_AJAX_NO', 'Nгo');
define('AM_AJAX_UPDATE', 'Atualizar');
define('AM_AJAX_CANCEL', 'Cancelar');
define('AM_AJAX_OK', 'OK');

define('AM_AJAX_SORT', 'Ordenar:');
define('AM_AJAX_TRACK_STOCK', 'Seguir estoque?');
define('AM_AJAX_TRACK_STOCK_IMGALT', 'Seguir estoque deste atributo?');

define('AM_AJAX_ENTER_NEW_OPTION_NAME', 'Novo atributo');
define('AM_AJAX_ENTER_NEW_OPTION_VALUE_NAME', 'Novo valor');
define('AM_AJAX_ENTER_NEW_OPTION_VALUE_NAME_TO_ADD_TO', 'Novo nome do valor para adicionar a %s');

define('AM_AJAX_PROMPT_REMOVE_OPTION_AND_ALL_VALUES', 'Tem certeza que deseja apagar os atributos de %s e todos seus valores para este produto?');
define('AM_AJAX_PROMPT_REMOVE_OPTION', 'Tem certeza que deseja apagar %s deste produto?');
define('AM_AJAX_PROMPT_STOCK_COMBINATION', 'Tem certeza que deseja remover esta combinaзгo de estoque deste produto?');

define('AM_AJAX_PROMPT_LOAD_TEMPLATE', 'Tem certeza que deseja carregar %s do gabarito? <br />Isto sobrescreverб as opзхes atuais do produto. Esta operaзгo nгo pode ser desfeita!');
define('AM_AJAX_NEW_TEMPLATE_NAME_HEADER', 'Digite o nome do novo Gabarito. Ou...');
define('AM_AJAX_NEW_NAME', 'Novo nome:');
define('AM_AJAX_CHOOSE_EXISTING_TEMPLATE_TO_OVERWRITE', ' ...<br /> ... escolha um que jб existe para sobrescrevк-lo');
define('AM_AJAX_CHOOSE_EXISTING_TEMPLATE_TITLE', 'Jб existe:'); 
define('AM_AJAX_RENAME_TEMPLATE_ENTER_NEW_NAME', 'Digite o novo nome para o Gabarito %s');
define('AM_AJAX_PROMPT_DELETE_TEMPLATE', 'Tem certeza que deseja apagaro Gabarito %s?<br>Esta operaзгo nгo pode ser desfeita!');

//attributeManager.php

define('AM_AJAX_ADDS_ATTRIBUTE_TO_OPTION', 'Adiciona o atributo selecionado na esquerda para a opзгo %s');
define('AM_AJAX_ADDS_NEW_VALUE_TO_OPTION', 'Adiciona um novo valor para a opзгo %s');
define('AM_AJAX_PRODUCT_REMOVES_OPTION_AND_ITS_VALUES', 'Remover a opзгo %1$s e o(s) valor(s) %2$d  abaixo deste produto');
define('AM_AJAX_CHANGES', 'Modificar'); 
define('AM_AJAX_LOADS_SELECTED_TEMPLATE', 'Carregar o Gabarito selecionado');
define('AM_AJAX_SAVES_ATTRIBUTES_AS_A_NEW_TEMPLATE', 'Salvar os atributos atuais como um novo Gabarito');
define('AM_AJAX_RENAMES_THE_SELECTED_TEMPLATE', 'Renomear o gabarito selecionado');
define('AM_AJAX_DELETES_THE_SELECTED_TEMPLATE', 'Apagar o gabarito selecionado');
define('AM_AJAX_NAME', 'Nome do Atributo<a class="ajuda" href="#"><img src="layout/help1.gif" width="24" height="16" border="0"><span><font color="#FF0000">Atenзгo:</font><BR>
            Aqui vocк pode criar atributos para os Produtos e dar-lhes opзхes e valores diferentes. Os atributos servem para que vocк cadastre variedade de um mesmo produto. EX:<BR> "Opзгo" Tamanho = <font color="#FF0000">"Valores" P, M e G</font><BR>"Opзгo" Cor = <font color="#FF0000">"Valores" Azul, Preta, Rosa e Amarela</font><BR>"Opзгo" Voltagem = <font color="#FF0000">"Valores" 110v e 220v</font><BR>Os valores dos atributos tambйm podem ter preзos diferentes, para isto utilize o prefix + ou - e preencha o campo "Diferenзa de Preзo".<BR>Use os "GABARITOS" para salvar suas combinaзхes de atributos e inserн-las rapidamente mais tarde. </span></a>
            ');
define('AM_AJAX_ACTION', 'Aзгo');
define('AM_AJAX_QT_PRO', 'Quantidades em estoque');
define('AM_AJAX_PRODUCT_REMOVES_VALUE_FROM_OPTION', 'Remover %1$s de %2$s, deste produto');
define('AM_AJAX_MOVES_OPTION_UP', 'Mover opзгo para cima');
define('AM_AJAX_MOVES_OPTION_DOWN', 'Mover opзгo para baixo');
define('AM_AJAX_MOVES_VALUE_UP', 'Mover valor para cima');
define('AM_AJAX_MOVES_VALUE_DOWN', 'Mover valor para baixo');
define('AM_AJAX_ADDS_NEW_OPTION', 'Adicionar uma nova opзгo na lista');
define('AM_AJAX_OPTION', 'Opзгo:');
define('AM_AJAX_VALUE', 'Valor:');
define('AM_AJAX_PREFIX', 'Prefixo:');
define('AM_AJAX_PRICE', 'Diferenзa de Preзo:');
define('AM_AJAX_WEIGHT_PREFIX', 'Unidade Peso:');
define('AM_AJAX_WEIGHT', 'Peso:');
define('AM_AJAX_SORT', 'Ordem:');
define('AM_AJAX_ADDS_NEW_OPTION_VALUE', 'Adicionar uma nova opзгo de valor na lista');
define('AM_AJAX_ADDS_ATTRIBUTE_TO_PRODUCT', 'Adicionar este atributo ao produto atual');
define('AM_AJAX_DELETES_ATTRIBUTE_FROM_PRODUCT', 'Excluir o atributo ou combinaзгo de atributos do produto atual');
define('AM_AJAX_QUANTITY', 'Quantidade');
define('AM_AJAX_PRODUCT_REMOVE_ATTRIBUTE_COMBINATION_AND_STOCK', 'Remover esta combinaзгo de atributos e estoque deste produto');
define('AM_AJAX_UPDATE_OR_INSERT_ATTRIBUTE_COMBINATIONBY_QUANTITY', 'Atualizar ou inserir a combinaзгo de atributo com a determinada quantidade');
define('AM_AJAX_UPDATE_PRODUCT_QUANTITY', 'Definir a quantidade indicada para o produto atual');

//attributeManager.class.php
define('AM_AJAX_TEMPLATES', '-- Gabaritos --');

//----------------------------
// Change: download attributes for AM
//
// author: mytool
//-----------------------------
define('AM_AJAX_FILENAME', 'Nome do Arquivo');
define('AM_AJAX_FILE_DAYS', 'Dias para baixar');
define('AM_AJAX_FILE_COUNT', 'Mбximo de Downloads');
define('AM_AJAX_DOWLNOAD_EDIT', 'Editar Opзхes de Download');
define('AM_AJAX_DOWLNOAD_ADD_NEW', 'Adicionar Opзхes de Download');
define('AM_AJAX_DOWLNOAD_DELETE', 'Apagar Opзхes de Download');
define('AM_AJAX_HEADER_DOWLNOAD_ADD_NEW', 'Adicionar Opзхes de Download a \"%s\"');
define('AM_AJAX_HEADER_DOWLNOAD_EDIT', 'Editar Opзхes de Download de \"%s\"');
define('AM_AJAX_HEADER_DOWLNOAD_DELETE', 'Apagar Opзхes de Download \"%s\"');
define('AM_AJAX_FIRST_SAVE', 'Salvar produto antes de adicionar opзхes');

//----------------------------
// EOF Change: download attributes for AM
//-----------------------------

define('AM_AJAX_OPTION_NEW_PANEL','Nova Opзгo:');
?>