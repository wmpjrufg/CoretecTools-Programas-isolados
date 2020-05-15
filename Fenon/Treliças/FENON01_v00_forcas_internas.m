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
% ------------------------------------------------------------------------
%
% Vetores
% ------------------------------------------------------------------------
% forcasInternas        - Vetor que armazena as for�as internas nos elementos analisados
% tensoes               - Vetor que armazena as tens�es nos elementos
% ------------------------------------------------------------------------
%
% Matrizes
% ------------------------------------------------------------------------
% elementos             - Matriz de elementos e suas conectividades [No1 No2 TipoMaterial TipoSecao]
% rotacao               - Matriz de rota��o do elemento
% ------------------------------------------------------------------------
%
% <<<<<>>>>>

function [forcasInternas]=FENON01_v00_forcas_internas(nGrauLibTotal,nElem,tensoes,area,comprimento,cossenoDiretor,senoDiretor,elementos,nGrauLiberdadePorNo,nNosPorElemento)
%%
%
%
%=========================================================================%
% STEP 1: CRIA��O DO VETOR DE FOR�AS INTERNAS DA ESTRUTURA
%=========================================================================%
%
%
% Step 1.1: Inicializa��o das vari�veis do m�todo
forcasInternas=zeros(nGrauLibTotal,1);


% Step 1.2.1: La�o para determinar contabilizar a for�a por grau de liberdade do elemento
for el=1:nElem
    
    areaElemento    = area(el);
    compElemento    = comprimento(el);
    tensaoElemento  = tensoes(el);
    vetorB          = [-1/compElemento 0 1/compElemento 0];
    
    % Step 1.2.3.2: Montagem da matriz de rota��o e vetor B em eixos globais
    coss                = cossenoDiretor(el);
    senn                = senoDiretor(el);
    [rotacaoElemento]   = MatrizRotacao(coss,senn);
    vetorBGlobal        = vetorB*rotacaoElemento;
    
    % Step 1.2.2.3: Graus globais
    [grauElementoGlobal]    = FENON01_v00_correspondencia_grau_liberdade(el,elementos,nGrauLiberdadePorNo,nNosPorElemento);
        
    % Step 1.2.2.1: Graus locais
    grauElementoLocal       = [1 2 3 4];
    
    % Step 1.2.2.4: For�as internas do elemento
    forcasInternasElemento  = areaElemento*compElemento*tensaoElemento*vetorBGlobal;
    
    % Step 1.2.2.5: For�as internas do elemento em um vetor global
    forcasInternas(grauElementoGlobal,1) = forcasInternas(grauElementoGlobal,1) + forcasInternasElemento(grauElementoLocal)';
    
end


end