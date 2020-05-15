% ######################################################################
% UNIVERSIDADE FEDERAL DE CATALÃO(UFCat)
% FACULDADE DE ENGENHARIA
% Autores:
% Wanderlei Malaquias Pereira Junior
% Danilo Cavalcanti Borges
% Sylvia Regina Mesquita de Almeida
% Colaboradores
%
%
% ######################################################################

% <<<<<>>>>>            FENON (Finite Element Non Linear Analysis)
% Versão:               v00
% Notas
% 01-05-20              - Versão inicial implementada por W M Pereira Junior e D C Borges
% <<<<<>>>>>

% <<<<<>>>>>            Descrição            <<<<<>>>>> %
%
% Determina as deformações nos elementos de uma treliça de duas dimensões
%
% <<<<<>>>>>                                 <<<<<>>>>> %

% <<<<<>>>>>            Variáveis            <<<<<>>>>> %
%
% Escalares:
% ------------------------------------------------------------------------
% nElem                 - Quantidade de elementos da estrutura
% nGrauLiberdadePorNo   - Total de graus de liberdade por nó
% nNosPorElemento       - Total de graus de liberdade por elemento
% el                    - Contador interno para cada elemento da estrutura
% L                     - Comprimento do elemento (el)
% coss                  - Valor do cosseno diretor para um detemrinado elemento
% senn                  - Valor do seno diretor para um detemrinado elemento
% ------------------------------------------------------------------------
%
% Vetores
% ------------------------------------------------------------------------
%
% deslocamentoNodais	- Vetor de deslocamentos nodais da estrutura
% comprimento           - Vetor que armazena o comprimento dos elementos da estrutura
% cossenoDiretor    	- Vetor que armazena o cos diretor dos elementos da estrutura
% senoDiretor        	- Vetor que armazena o sin diretor dos elementos da estrutura
% deformacoes           - Vetor que armazena as deformação locais dos elementos analisados
% deslocLocal           - Vetor que armazena os deslocamentos locais em posições locais
% grauElementoLocal 	- Vetor que armazena os graus de liberdade locais
% grauElementoGlobal 	- Vetor que armazena os graus de liberdade locais
% vetorB                - Vetor genérico de deformações do elemento
% cossenoDiretor        - Vetor que armazena o cos diretor dos elementos da estrutura
% senoDiretor           - Vetor que armazena o sin diretor dos elementos da estrutura
% ------------------------------------------------------------------------
%
% Matrizes
% ------------------------------------------------------------------------
% elementos             - Matriz de elementos e suas conectividades [No1 No2 TipoMaterial TipoSecao]
% rotacao               - Matriz de rotação do elemento
% ------------------------------------------------------------------------
%
% <<<<<>>>>>

function [deformacoes]=FENON01_v00_deformacoes_barras(nElem,deslocamentoNodais,elementos,nGrauLiberdadePorNo,nNosPorElemento,comprimento,cossenoDiretor,senoDiretor)
%%
%
%
%=========================================================================%
% STEP 1: CRIAÇÃO DO VETOR DE DEFORMAÇÕES DA ESTRUTURA
%=========================================================================%
%
%
% Step 1.1: Inicialização das variáveis do método
deformacoes=zeros(nElem,1);

% Step 1.2: Laço para determinar o valor das deformações por elemento
for el=1:nElem
    
    % Step 1.2.1: Inicializando vetor de deslocamentos nodais em um estado local
    deslocLocal = zeros(4,1);
    
    % Step 1.2.2: Criando correspondência entre vetor graus de liberdade locais e globais
    
    % Step 1.2.2.1: Graus locais
    grauElementoLocal       = [1 2 3 4];
    
    % Step 1.2.2.3: Graus globais
    [grauElementoGlobal]    = FENON01_v00_correspondencia_grau_liberdade(el,elementos,nGrauLiberdadePorNo,nNosPorElemento);
    
    % Step 1.2.2.4: Atribuição da correspondência de grau de liberdade
    deslocLocal(grauElementoLocal) = deslocamentoNodais(grauElementoGlobal);
    
    % Step 1.2.3: Determinações do vetor de deformações dos elementos
    
    % Step 1.2.3.1: Montagem do vetor B de deformações
    L       = comprimento(el);
    vetorB  = [-1/L 0 1/L 0];
    
    % Step 1.2.3.2: Montagem do vetor de deformações
    coss            = cossenoDiretor(el);
    senn            = senoDiretor(el);
    [rotacao]       = MatrizRotacao(coss,senn);
    deformacoes(el) = vetorB*rotacao*deslocLocal;
end

end