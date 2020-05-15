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
% Determina as deforma��es nos elementos de uma treli�a de duas dimens�es
%
% <<<<<>>>>>                                 <<<<<>>>>> %

% <<<<<>>>>>            Vari�veis            <<<<<>>>>> %
%
% Escalares:
% ------------------------------------------------------------------------
% nElem                 - Quantidade de elementos da estrutura
% nGrauLiberdadePorNo   - Total de graus de liberdade por n�
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

function [deformacoes]=FENON01_v00_deformacoes_barras(nElem,deslocamentoNodais,elementos,nGrauLiberdadePorNo,nNosPorElemento,comprimento,cossenoDiretor,senoDiretor)
%%
%
%
%=========================================================================%
% STEP 1: CRIA��O DO VETOR DE DEFORMA��ES DA ESTRUTURA
%=========================================================================%
%
%
% Step 1.1: Inicializa��o das vari�veis do m�todo
deformacoes=zeros(nElem,1);

% Step 1.2: La�o para determinar o valor das deforma��es por elemento
for el=1:nElem
    
    % Step 1.2.1: Inicializando vetor de deslocamentos nodais em um estado local
    deslocLocal = zeros(4,1);
    
    % Step 1.2.2: Criando correspond�ncia entre vetor graus de liberdade locais e globais
    
    % Step 1.2.2.1: Graus locais
    grauElementoLocal       = [1 2 3 4];
    
    % Step 1.2.2.3: Graus globais
    [grauElementoGlobal]    = FENON01_v00_correspondencia_grau_liberdade(el,elementos,nGrauLiberdadePorNo,nNosPorElemento);
    
    % Step 1.2.2.4: Atribui��o da correspond�ncia de grau de liberdade
    deslocLocal(grauElementoLocal) = deslocamentoNodais(grauElementoGlobal);
    
    % Step 1.2.3: Determina��es do vetor de deforma��es dos elementos
    
    % Step 1.2.3.1: Montagem do vetor B de deforma��es
    L       = comprimento(el);
    vetorB  = [-1/L 0 1/L 0];
    
    % Step 1.2.3.2: Montagem do vetor de deforma��es
    coss            = cossenoDiretor(el);
    senn            = senoDiretor(el);
    [rotacao]       = MatrizRotacao(coss,senn);
    deformacoes(el) = vetorB*rotacao*deslocLocal;
end

end