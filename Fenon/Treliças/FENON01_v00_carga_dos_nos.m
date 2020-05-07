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

% <<<<<>>>>> FENON (Finite Element Non Linear Analysis)
% Versão:   v00
% Notas
% 01-05-20  - Versão inicial implementada por W M Pereira Junior e D C Borges
% <<<<<>>>>>

% <<<<<>>>>> Descrição
%
% Determina as forças nodais de uma treliça utilizando o Método dos
% Elementos Finitos
%
% <<<<<>>>>>

% <<<<<>>>>> Variáveis
%
% Escalares:
% i                     - Contador auxiliar
% nCargNo               - Quantidade de nós carregados
% noAnalisado           - Código do nó analisado
% forcaX                - Valor da prescrição na direção X
% forcaY                - Valor da prescrição na direção Y
% nGrauLiberdadePorNo   - Total de grau de liberdade por nó da estrutura
% nNos                  - Quantidade de nós da estrutura
%
% Vetores
%
% forcaNodal            - Vetor de forças nodais da estrutura
%
% Matrizes
%
% cargasNodais          - Matriz de cargas nodais [No ForçaEmX ForçaEmY]
%
% <<<<<>>>>>

function [forcaNodal]=FENON01_v00_carga_dos_nos(nCargNo,cargasNodais,nGrauLiberdadePorNo,nNos)
%%
%
%
%=========================================================================%
% STEP 1: POSICIONANDO AS FORÇAS NOS DEVIDOS GRAUS DE LIBERDADE
%=========================================================================%
%
%
% Step 1.1:  Cálculo do vetor que posicina o carregamento nodal

% Step 1.1.1: Zerando o contador auxiliar de posições no vetor e o vetor
% incial das forças externas nodais
forcaNodal  = zeros(nGrauLiberdadePorNo*nNos,1);
contadorAux = 0;

% Step 1.1.2: Percorrendo os nós prescritos
for i=1:nCargNo
    
    % Step 1.1.2.1: Nó analisado
    noAnalisado = cargasNodais(i,1);
    
    % Step 1.1.2.2: Força em X
    forcaX      = cargasNodais(i,2);
    
    % Step 1.1.2.3: Armazenamento em caso positivo o grau de liberdade da força em X
    if forcaX ~= 0
        
        % Step 1.1.2.3.1: Contabilizando uma posição no contador em caso positivo
        forcaNodal(2*noAnalisado - 1)   = forcaX;
        
    end
    
    % Step 1.1.2.4: Restrição em Y
    forcaY      = cargasNodais(i,3);
    
    % Step 1.1.2.5: Armazenamento em caso positivo o grau de liberdade da força em Y
    if forcaY ~= 0
        
        % Step 1.1.2.5.1: Contabilizando uma posição no contador em caso positivo
        forcaNodal(2*noAnalisado)       = forcaY;
        
    end
    
end


end