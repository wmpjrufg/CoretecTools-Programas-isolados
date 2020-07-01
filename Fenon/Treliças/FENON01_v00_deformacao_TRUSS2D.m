% ######################################################################
% UNIVERSIDADE FEDERAL DE CATAL�O(UFCat)
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
% Vers�o:               v00
% Notas
% 01-05-20              - Vers�o inicial implementada por W M Pereira Junior e D C Borges
% <<<<<>>>>>

% <<<<<>>>>>            Descri��o            <<<<<>>>>> %
%
% Determina a deforma��o em um elemento de barra do tipo TRUSS2D
%
% <<<<<>>>>>                                 <<<<<>>>>> %

% <<<<<>>>>>            Vari�veis            <<<<<>>>>> %
%
% Escalares:
% ------------------------------------------------------------------------
% el                    - Contador interno para cada elemento da estrutura
% L                     - Comprimento do elemento (el)
% coss                  - Valor do cosseno diretor para um detemrinado elemento
% senn                  - Valor do seno diretor para um detemrinado elemento
% nElem                 - Quantidade de elementos da estrutura
% nGrauLiberdadePorNo   - Total de graus de liberdade por n�
% nNosPorElemento       - Total de graus de liberdade por elemento
% ------------------------------------------------------------------------
%
% Vetores
% ------------------------------------------------------------------------
% deslocamentoNodais	- Vetor de deslocamentos nodais da estrutura
% comprimento           - Vetor que armazena o comprimento dos elementos da estrutura
% cossenoDiretor    	- Vetor que armazena o cos diretor dos elementos da estrutura
% senoDiretor        	- Vetor que armazena o sin diretor dos elementos da estrutura
% deformacoes           - Vetor que armazena as deforma��o locais dos elementos analisados
% deslocLocal           - Vetor que armazena os deslocamentos locais em posi��es locais
% grauElementoLocal 	- Vetor que armazena os graus de liberdade locais
% grauElementoGlobal 	- Vetor que armazena os graus de liberdade locais
% vetorB                - Vetor gen�rico de deforma��es do elemento
% cossenoDiretor        - Vetor que armazena o cos diretor dos elementos da estrutura
% senoDiretor           - Vetor que armazena o sin diretor dos elementos da estrutura
% ------------------------------------------------------------------------
%
% Matrizes
% ------------------------------------------------------------------------
% elementos             - Matriz de elementos e suas conectividades [No1 No2 TipoMaterial TipoSecao]
% rotacao               - Matriz de rota��o do elemento
% ------------------------------------------------------------------------
%
% <<<<<>>>>>
function [deformacao]=FENON01_v00_deformacao_TRUSS2D(el,elementos,nGrauLiberdadePorNo,nNosPorElemento,deslocamentoNodais,comprimento,cossenoDiretor,senoDiretor,tipoElemento)
%%
%
%
%=========================================================================%
% STEP 1:   DETERMINA��O DA DEFORMA��O DE UM ELEMENTO DA ESTRUTURA ELEMENTO 
%           TRUSS2D1
%=========================================================================%
%
%
% Step 1.1: Modelo de deforma��o nos elementos para o EF TRUSS2D1
if strcmp(tipoElemento,'TRUSS2D1')
    
% Step 1.1.1: Inicializando vetor de deslocamentos nodais em um estado local
deslocLocal                     = zeros(4,1);

% Step 1.1.2: Graus locais do elemento TRUSS2D1
grauElementoLocal               = [1 2 3 4];

% Step 1.1.3: Correspond�ncia do vetor de graus locais no sistema global de refer�ncias
[grauElementoGlobal]            = FENON01_v00_correspondencia_grau_liberdade(el,elementos,nGrauLiberdadePorNo,nNosPorElemento);

% Step 1.1.4: Atribui��o da correspond�ncia de grau de liberdade
deslocLocal(grauElementoLocal)  = deslocamentoNodais(grauElementoGlobal);

% Step 1.1.5: Montagem do vetor B de deforma��es
L                               = comprimento(el);
vetorB                          = [-1/L 0 1/L 0];

% Step 1.1.6: Montagem do vetor de deforma��es
coss                            = cossenoDiretor(el);
senn                            = senoDiretor(el);
[rotacao]                       = MatrizRotacao(coss,senn);%%%%%%%%%%%%%%
deformacao                      = vetorB*rotacao*deslocLocal;

end

end
