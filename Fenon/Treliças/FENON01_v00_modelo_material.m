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
% Determina as tensões internas nos elementos de uma treliça de duas dimensões e também o módulo de elasticidade correspondente
%
% <<<<<>>>>>                                 <<<<<>>>>> %

% <<<<<>>>>>            Variáveis            <<<<<>>>>> %
%
% Escalares:
% ------------------------------------------------------------------------
% nElem                 - Quantidade de elementos da estrutura
% moduloYoung           - Módulo de elasticidade inicial do material
% sigmaEscTrac          - Tensão de escoamento no elasto-plástico perfeito em situação de tração
% epsilonEscTrac        - Deformação que correspondente a tensão de escoamento no elasto-plástico perfeito em situação de tração
% sigmaEscComp          - Tensão de escoamento no elasto-plástico perfeito em situação de compressão
% epsilonEscComp        - Deformação que correspondente a tensão de escoamento no elasto-plástico perfeito em situação de compressão
% el                    - Contador interno do elemento analisado de um laço
% deformacaoElemento    - Deformação do elemento (el)
% ------------------------------------------------------------------------
%
% Vetores
% ------------------------------------------------------------------------
% deformacoes           - Vetor que armazena as deformação locais dos elementos analisados
% tensoes               - Vetor que armazena as tensões nos elementos
% modsE                 - Vetor que armazena o módulo de eleasticidade do elemento
% ------------------------------------------------------------------------
%
% Matrizes
% ------------------------------------------------------------------------
% materiais             - Matriz de materiais que compõem a estrutura [ModuloE Poisson TensaoEscoamentoTrac DefEscoamentoTrac  TensaoEscoamentoComp DefEscoamentoComp]
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
% Step 1.1: Inicialização das variáveis do método

% Step 1.1.1: Escolha dos parâmetros do material para efeitos de tração e compressão
moduloYoung     = materiais(1,1);
sigmaEscTrac    = materiais(1,3);
epsilonEscTrac  = materiais(1,4);
sigmaEscComp    = materiais(1,5);
epsilonEscComp  = materiais(1,6);

% Step 1.1.2: Inicializando vetor de tensões e deformações
tensoes         = zeros(nElem,1);
modsE           = zeros(nElem,1);

% Step 1.2: Laço para determinar o valor das deformações por elemento
for el=1:nElem
    
    % Step 1.2.1: Verificando a deformação do elemento (el)
    deformacaoElemento = deformacoes(el);
    
    % Step 1.2.2: Verificações de tensões e módulos conforme curva do modelo
    
    % Step: 1.2.2.1: Em situação de tração
    if          (deformacaoElemento >= 0) && (deformacaoElemento < epsilonEscTrac)
        tensoes(el)  = -((moduloYoung^2)*(deformacaoElemento^2)/(4*sigmaEscTrac))+moduloYoung*deformacaoElemento;
        modsE(el)    = -((moduloYoung^2)*deformacaoElemento/(2*sigmaEscTrac))+moduloYoung;
        
    elseif      (deformacaoElemento >= epsilonEscTrac)
        tensoes(el)  = sigmaEscTrac;
        modsE(el)    = 1E-6;
        
    % Step: 1.2.2.2: Em situação de compressão
    elseif      (deformacaoElemento < 0) && (deformacaoElemento > epsilonEscComp)
        tensoes(el)  = ((moduloYoung^2)*(deformacaoElemento^2)/(4*sigmaEscTrac))+moduloYoung*deformacaoElemento;
        modsE(el)    = ((moduloYoung^2)*deformacaoElemento/(2*sigmaEscTrac))+moduloYoung;
        
    elseif      (deformacaoElemento >= epsilonEscComp)
        tensoes(el)  = sigmaEscComp;
        modsE(el)    = 1E-6;
        
    end
    
end

end