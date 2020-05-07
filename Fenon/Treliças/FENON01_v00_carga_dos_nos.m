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

% <<<<<>>>>> FENON (Finite Element Non Linear Analysis)
% Vers�o:   v00
% Notas
% 01-05-20  - Vers�o inicial implementada por W M Pereira Junior e D C Borges
% <<<<<>>>>>

% <<<<<>>>>> Descri��o
%
% Determina as for�as nodais de uma treli�a utilizando o M�todo dos
% Elementos Finitos
%
% <<<<<>>>>>

% <<<<<>>>>> Vari�veis
%
% Escalares:
% i                     - Contador auxiliar
% nCargNo               - Quantidade de n�s carregados
% noAnalisado           - C�digo do n� analisado
% forcaX                - Valor da prescri��o na dire��o X
% forcaY                - Valor da prescri��o na dire��o Y
% nGrauLiberdadePorNo   - Total de grau de liberdade por n� da estrutura
% nNos                  - Quantidade de n�s da estrutura
%
% Vetores
%
% forcaNodal            - Vetor de for�as nodais da estrutura
%
% Matrizes
%
% cargasNodais          - Matriz de cargas nodais [No For�aEmX For�aEmY]
%
% <<<<<>>>>>

function [forcaNodal]=FENON01_v00_carga_dos_nos(nCargNo,cargasNodais,nGrauLiberdadePorNo,nNos)
%%
%
%
%=========================================================================%
% STEP 1: POSICIONANDO AS FOR�AS NOS DEVIDOS GRAUS DE LIBERDADE
%=========================================================================%
%
%
% Step 1.1:  C�lculo do vetor que posicina o carregamento nodal

% Step 1.1.1: Zerando o contador auxiliar de posi��es no vetor e o vetor
% incial das for�as externas nodais
forcaNodal  = zeros(nGrauLiberdadePorNo*nNos,1);
contadorAux = 0;

% Step 1.1.2: Percorrendo os n�s prescritos
for i=1:nCargNo
    
    % Step 1.1.2.1: N� analisado
    noAnalisado = cargasNodais(i,1);
    
    % Step 1.1.2.2: For�a em X
    forcaX      = cargasNodais(i,2);
    
    % Step 1.1.2.3: Armazenamento em caso positivo o grau de liberdade da for�a em X
    if forcaX ~= 0
        
        % Step 1.1.2.3.1: Contabilizando uma posi��o no contador em caso positivo
        forcaNodal(2*noAnalisado - 1)   = forcaX;
        
    end
    
    % Step 1.1.2.4: Restri��o em Y
    forcaY      = cargasNodais(i,3);
    
    % Step 1.1.2.5: Armazenamento em caso positivo o grau de liberdade da for�a em Y
    if forcaY ~= 0
        
        % Step 1.1.2.5.1: Contabilizando uma posi��o no contador em caso positivo
        forcaNodal(2*noAnalisado)       = forcaY;
        
    end
    
end


end