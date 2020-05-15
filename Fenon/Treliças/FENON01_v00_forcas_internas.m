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
% ------------------------------------------------------------------------
%
% Vetores
% ------------------------------------------------------------------------
% forcasInternas        - Vetor que armazena as forças internas nos elementos analisados
% tensoes               - Vetor que armazena as tensões nos elementos
% ------------------------------------------------------------------------
%
% Matrizes
% ------------------------------------------------------------------------
% elementos             - Matriz de elementos e suas conectividades [No1 No2 TipoMaterial TipoSecao]
% rotacao               - Matriz de rotação do elemento
% ------------------------------------------------------------------------
%
% <<<<<>>>>>

function [forcasInternas]=FENON01_v00_forcas_internas(nGrauLibTotal,nElem,tensoes,area,comprimento,cossenoDiretor,senoDiretor,elementos,nGrauLiberdadePorNo,nNosPorElemento)
%%
%
%
%=========================================================================%
% STEP 1: CRIAÇÃO DO VETOR DE FORÇAS INTERNAS DA ESTRUTURA
%=========================================================================%
%
%
% Step 1.1: Inicialização das variáveis do método
forcasInternas=zeros(nGrauLibTotal,1);


% Step 1.2.1: Laço para determinar contabilizar a força por grau de liberdade do elemento
for el=1:nElem
    
    areaElemento    = area(el);
    compElemento    = comprimento(el);
    tensaoElemento  = tensoes(el);
    vetorB          = [-1/compElemento 0 1/compElemento 0];
    
    % Step 1.2.3.2: Montagem da matriz de rotação e vetor B em eixos globais
    coss                = cossenoDiretor(el);
    senn                = senoDiretor(el);
    [rotacaoElemento]   = MatrizRotacao(coss,senn);
    vetorBGlobal        = vetorB*rotacaoElemento;
    
    % Step 1.2.2.3: Graus globais
    [grauElementoGlobal]    = FENON01_v00_correspondencia_grau_liberdade(el,elementos,nGrauLiberdadePorNo,nNosPorElemento);
        
    % Step 1.2.2.1: Graus locais
    grauElementoLocal       = [1 2 3 4];
    
    % Step 1.2.2.4: Forças internas do elemento
    forcasInternasElemento  = areaElemento*compElemento*tensaoElemento*vetorBGlobal;
    
    % Step 1.2.2.5: Forças internas do elemento em um vetor global
    forcasInternas(grauElementoGlobal,1) = forcasInternas(grauElementoGlobal,1) + forcasInternasElemento(grauElementoLocal)';
    
end


end