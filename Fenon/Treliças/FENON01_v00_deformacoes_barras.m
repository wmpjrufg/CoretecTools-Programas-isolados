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

function [deformacoes]=FENON01_v00_deformacoes_barras(nElem,deslocamentoNodais,elementos,nGrauLiberdadePorNo,nNosPorElemento,comprimento,cossenoDiretor,senoDiretor,tipoElemento)
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
    
    
    [deformacoes(el)]=FENON01_v00_deformacao_TRUSS2D(el,elementos,nGrauLiberdadePorNo,nNosPorElemento,deslocamentoNodais,comprimento,cossenoDiretor,senoDiretor,tipoElemento);
    
end

end

