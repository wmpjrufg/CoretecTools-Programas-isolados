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
% Determina as tens�es internas nos elementos de uma treli�a de duas dimens�es e tamb�m o m�dulo de elasticidade correspondente
%
% <<<<<>>>>>                                 <<<<<>>>>> %

% <<<<<>>>>>            Vari�veis            <<<<<>>>>> %
%
% Escalares:
% ------------------------------------------------------------------------
% nElem                 - Quantidade de elementos da estrutura
% moduloYoung           - M�dulo de elasticidade inicial do material
% sigmaEscTrac          - Tens�o de escoamento no elasto-pl�stico perfeito em situa��o de tra��o
% epsilonEscTrac        - Deforma��o que correspondente a tens�o de escoamento no elasto-pl�stico perfeito em situa��o de tra��o
% sigmaEscComp          - Tens�o de escoamento no elasto-pl�stico perfeito em situa��o de compress�o
% epsilonEscComp        - Deforma��o que correspondente a tens�o de escoamento no elasto-pl�stico perfeito em situa��o de compress�o
% el                    - Contador interno do elemento analisado de um la�o
% deformacaoElemento    - Deforma��o do elemento (el)
% ------------------------------------------------------------------------
%
% Vetores
% ------------------------------------------------------------------------
% deformacoes           - Vetor que armazena as deforma��o locais dos elementos analisados
% tensoes               - Vetor que armazena as tens�es nos elementos
% modsE                 - Vetor que armazena o m�dulo de eleasticidade do elemento
% ------------------------------------------------------------------------
%
% Matrizes
% ------------------------------------------------------------------------
% materiais             - Matriz de materiais que comp�em a estrutura [ModuloE Poisson TensaoEscoamentoTrac DefEscoamentoTrac  TensaoEscoamentoComp DefEscoamentoComp]
% ------------------------------------------------------------------------
%
% <<<<<>>>>>

function [tensoes,modsE]=FENON01_v00_modelo_material(materiais,nElem,deformacoes)
%%
%
%
%=========================================================================%
% STEP 1: ESCOLHA DO MODELO DO MATERIAL ADOTADO
%=========================================================================%
%
%
% Step 1.1: Inicializa��o das vari�veis do m�todo

% Step 1.1.1: Escolha dos par�metros do material para efeitos de tra��o e compress�o
moduloYoung     = materiais(1,1);
sigmaEscTrac    = materiais(1,3);
epsilonEscTrac  = materiais(1,4);
sigmaEscComp    = materiais(1,5);
epsilonEscComp  = materiais(1,6);

% Step 1.1.2: Inicializando vetor de tens�es e deforma��es
tensoes         = zeros(nElem,1);
modsE           = zeros(nElem,1);

% Step 1.2: La�o para determinar o valor das deforma��es por elemento
for el=1:nElem
    
    % Step 1.2.1: Verificando a deforma��o do elemento (el)
    deformacaoElemento = deformacoes(el);
    
    % Step 1.2.2: Verifica��es de tens�es e m�dulos conforme curva do modelo
    
    % Step: 1.2.2.1: Em situa��o de tra��o
    if          (deformacaoElemento >= 0) && (deformacaoElemento < epsilonEscTrac)
        tensoes(el)  = -((moduloYoung^2)*(deformacaoElemento^2)/(4*sigmaEscTrac))+moduloYoung*deformacaoElemento;
        modsE(el)    = -((moduloYoung^2)*deformacaoElemento/(2*sigmaEscTrac))+moduloYoung;
        
    elseif      (deformacaoElemento >= epsilonEscTrac)
        tensoes(el)  = sigmaEscTrac;
        modsE(el)    = 1E-6;
        
    % Step: 1.2.2.2: Em situa��o de compress�o
    elseif      (deformacaoElemento < 0) && (deformacaoElemento > epsilonEscComp)
        tensoes(el)  = ((moduloYoung^2)*(deformacaoElemento^2)/(4*sigmaEscTrac))+moduloYoung*deformacaoElemento;
        modsE(el)    = ((moduloYoung^2)*deformacaoElemento/(2*sigmaEscTrac))+moduloYoung;
        
    elseif      (deformacaoElemento >= epsilonEscComp)
        tensoes(el)  = sigmaEscComp;
        modsE(el)    = 1E-6;
        
    end
    
end

end