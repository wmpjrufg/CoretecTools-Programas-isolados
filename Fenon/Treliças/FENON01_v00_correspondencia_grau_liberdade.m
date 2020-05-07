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
% Cálcula um vetor de graus de liberdade global para um detemrinado elemento (el) da estrutura
%
% <<<<<>>>>>                                 <<<<<>>>>> %

% <<<<<>>>>>            Variáveis            <<<<<>>>>> %
% 
% Escalares:
% ------------------------------------------------------------------------
% el                    - Contador do elemento analisado de um laço
% nGrauLiberdadePorNo   - Total de graus de liberdade por nó
% nNosPorElemento       - Total de graus de liberdade por elemento
% contadorAux           - Contador auxiliar para montagem do vetor de graus restritos
% i                     - Contador auxiliar
% noAnalisado           - Código do nó analisado
% grauCorrespondeteEmX  - Grau de liberdade (no sistema global) do nó na direção x
% grauCorrespondeteEmY  - Grau de liberdade (no sistema global) do nó na direção y
% ------------------------------------------------------------------------
% 
% Vetores
% ------------------------------------------------------------------------
% grauElementoGlobal    - Vetor grau de liberdade global do elemento (el) analisado
% ------------------------------------------------------------------------
%
% Matrizes 
% ------------------------------------------------------------------------
% elementos             - Matriz de elementos e suas conectividades [No1 No2 TipoMaterial TipoSecao]
% ------------------------------------------------------------------------
%
% <<<<<>>>>>                                 <<<<<>>>>> %

function[grauElementoGlobal]=FENON01_v00_correspondencia_grau_liberdade(el,elementos,nGrauLiberdadePorNo,nNosPorElemento)
%%
%
%
%=========================================================================%
% STEP 1: DETERMINAÇÃO DO GRAU DE LIBERDADE GLOBAL DE UM ELEMENTO
%=========================================================================%
%
%
% Step 1.1: Criando variáveis para inicialização da função
contadorAux         = 0;
grauElementoGlobal  = zeros(1,nGrauLiberdadePorNo*nNosPorElemento);

% Step 1.2: Cálculo do vetor de Graus de Liberdade Total
for i=1:nNosPorElemento
    
    % Step 1.2.1: Nó analisado
    noAnalisado                         = elementos(el,i);
    
    % Step 1.2.2: Contador do grau de liberdade em X
    grauCorrespondeteEmX                = 2*noAnalisado - 1;
    
    % Step 1.2.2.1: Contabilizando o grau de liberdade global em relação ao nó analisado direção X
    contadorAux                         = contadorAux + 1;
    grauElementoGlobal(contadorAux)     = grauCorrespondeteEmX;
    
    % Step 1.2.3: Contador do grau de liberdade em Y
    grauCorrespondeteEmY                = 2*noAnalisado;
    
    % Step 1.2.3.1: Contabilizando o grau de liberdade global em relação ao nó analisado direção Y
    contadorAux                         = contadorAux + 1;
    grauElementoGlobal(contadorAux)     = grauCorrespondeteEmY;
    
end

end