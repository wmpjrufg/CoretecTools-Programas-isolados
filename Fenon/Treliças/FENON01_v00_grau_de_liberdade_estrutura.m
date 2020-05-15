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
% Determina os graus de liberdade da estrutura completa. Totais, livres e restritos
%
% <<<<<>>>>>                                 <<<<<>>>>> %

% <<<<<>>>>>            Vari�veis            <<<<<>>>>> %
%
% Escalares:
% ------------------------------------------------------------------------
% nNos                  - Quantidade de n�s da estrutura
% nGrauLiberdadePorNo   - Total de grau de liberdade por n� da estrutura
% nPres                 - Quantidade de n�s com prescri��es
% i                     - Contador auxiliar para la�o do tipo for
% j                     - Contador auxiliar para la�o do tipo for
% nGrauLibTotal         - N�mero de graus de liberdade totais da estrutura
% contadorAux           - Contador auxiliar para montagem do vetor de graus restritos
% noAnalisado           - C�digo do n� analisado
% restricaoX            - Valor da prescri��o na dire��o X
% restricaoY            - Valor da prescri��o na dire��o Y
% nGrauLibRestrito      - N�mero de graus de liberdade restritos da estrutura
% nGrauLibLivre         - N�mero de graus de liberdade livres da estrutura
% ------------------------------------------------------------------------
%
% Vetores
% ------------------------------------------------------------------------
% grauLiberdadeTotal    - Vetor Grau de Liberdade Total da estrutura
% grauLiberdadeRestrito - Vetor Grau de Liberdade Restrito da estrutura
% grauLiberdadeLivre    - Vetor Grau de Liberdade Livre da estrutura
% ------------------------------------------------------------------------
%
% Matrizes
% ------------------------------------------------------------------------
% precricoes            - Matriz de n�s prescritos [No PrescricaoEmX PrescricaoEmY]
% ------------------------------------------------------------------------
%
% <<<<<>>>>>                                 <<<<<>>>>> %

function[grauLiberdadeTotal,nGrauLibTotal,grauLiberdadeRestrito,nGrauLibRestrito,grauLiberdadeLivre,nGrauLibLivre]=FENON01_v00_grau_de_liberdade_estrutura(nNos,nGrauLiberdadePorNo,nPres,precricoes)
%%
%
%
%=========================================================================%
% STEP 1: DETERMINA��O DOS GRAUS DE LIBERDADE TOTAL DA ESTRUTURA
%=========================================================================%
%
%
% Step 1.1: C�lculo do vetor de Graus de Liberdade Total
grauLiberdadeTotal = zeros(1,nGrauLiberdadePorNo*nNos);
for i = 1:nGrauLiberdadePorNo * nNos
    
    grauLiberdadeTotal(i)=i;
    
end

% Step 1.2:  C�lculo da quantidade do n�mero de Graus Liberdade Total
nGrauLibTotal = length(grauLiberdadeTotal);
%%
%
%
%=========================================================================%
% STEP 2: DETERMINA��O DOS GRAUS DE LIBERDADE RESTRITOS
%=========================================================================%
%
%
% Step 2.1:  C�lculo do vetor de Graus de Liberdade Restritos

% Step 2.1.1: Zerando o contador auxiliar de posi��es no vetor
contadorAux = 0;

% Step 2.1.2: Percorrendo os n�s prescritos
for j=1:nPres
    
    % Step 2.1.2.1: N� analisado
    noAnalisado = precricoes(j,1);
    
    % Step 2.1.2.2: Restri��o em X
    restricaoX      = precricoes(j,2);
    
    % Step 2.1.2.3: Armazenamento em caso positivo o grau de liberdade da restri��o em X
    if restricaoX ~= 0
        
        % Step 2.1.2.3.1: Contabilizando uma posi��o no contador em caso positivo
        contadorAux                          = contadorAux + 1;
        grauLiberdadeRestrito(contadorAux)   = 2*noAnalisado - 1;
        
    end
    
    % Step 2.1.2.4: Restri��o em Y
    restricaoY      = precricoes(j,3);
    
    % Step 2.1.2.5: Armazenamento em caso positivo o grau de liberdade da restri��o em Y
    if restricaoY ~= 0
        
        % Step 2.1.2.5.1: Contabilizando uma posi��o no contador em caso positivo
        contadorAux                          = contadorAux + 1;
        grauLiberdadeRestrito(contadorAux)   = 2*noAnalisado;
        
    end
    
end

% Step 2.2:  C�lculo da quantidade do n�mero de Graus Liberdade Restrito
nGrauLibRestrito    = length(grauLiberdadeRestrito);

%%
%
%
%=========================================================================%
% STEP 3: DETERMINA��O DOS GRAUS DE LIBERDADE LIVRE
%=========================================================================%
%
%
% Step 3.1:  C�lculo do vetor de Graus de Liberdade Livres
grauLiberdadeLivre  = setdiff(grauLiberdadeTotal,grauLiberdadeRestrito);

% Step 3.2:  C�lculo da quantidade do n�mero de Graus Liberdade Livre
nGrauLibLivre       = length(grauLiberdadeRestrito);

end