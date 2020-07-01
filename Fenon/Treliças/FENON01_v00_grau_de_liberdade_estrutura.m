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
% Determina os graus de liberdade da estrutura completa. Totais, livres e restritos
%
% <<<<<>>>>>                                 <<<<<>>>>> %

% <<<<<>>>>>            Variáveis            <<<<<>>>>> %
%
% Escalares:
% ------------------------------------------------------------------------
% nNos                  - Quantidade de nós da estrutura
% nGrauLiberdadePorNo   - Total de grau de liberdade por nó da estrutura
% nPres                 - Quantidade de nós com prescrições
% i                     - Contador auxiliar para laço do tipo for
% j                     - Contador auxiliar para laço do tipo for
% nGrauLibTotal         - Número de graus de liberdade totais da estrutura
% contadorAux           - Contador auxiliar para montagem do vetor de graus restritos
% noAnalisado           - Código do nó analisado
% restricaoX            - Valor da prescrição na direção X
% restricaoY            - Valor da prescrição na direção Y
% nGrauLibRestrito      - Número de graus de liberdade restritos da estrutura
% nGrauLibLivre         - Número de graus de liberdade livres da estrutura
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
% precricoes            - Matriz de nós prescritos [No PrescricaoEmX PrescricaoEmY]
% ------------------------------------------------------------------------
%
% <<<<<>>>>>                                 <<<<<>>>>> %

function[grauLiberdadeTotal,nGrauLibTotal,grauLiberdadeRestrito,nGrauLibRestrito,grauLiberdadeLivre,nGrauLibLivre]=FENON01_v00_grau_de_liberdade_estrutura(nNos,nGrauLiberdadePorNo,nPres,precricoes)
%%
%
%
%=========================================================================%
% STEP 1: DETERMINAÇÃO DOS GRAUS DE LIBERDADE TOTAL DA ESTRUTURA
%=========================================================================%
%
%
% Step 1.1: Cálculo do vetor de Graus de Liberdade Total
grauLiberdadeTotal = zeros(1,nGrauLiberdadePorNo*nNos);
for i = 1:nGrauLiberdadePorNo * nNos
    
    grauLiberdadeTotal(i)=i;
    
end

% Step 1.2:  Cálculo da quantidade do número de Graus Liberdade Total
nGrauLibTotal = length(grauLiberdadeTotal);
%%
%
%
%=========================================================================%
% STEP 2: DETERMINAÇÃO DOS GRAUS DE LIBERDADE RESTRITOS EM FUNÇÃO DO TIPO
%         DA QUANTIDADE DE GRAUS POR NÓR
%=========================================================================%
%
%
% Step 2.1:  Cálculo do vetor de Graus de Liberdade Restritos

% Step 2.1.1: Zerando o contador auxiliar de posições no vetor
contadorAux = 0;

% Step 2.1.2: Percorrendo os nós prescritos em função do tipo de nó

% Step 2.1.2.1: Para situações com um grau de liberdade por nó
if      nGrauLiberdadePorNo==1
    
    for j=1:nPres
        
        % Step 2.1.2.1.1: Nó analisado
        noAnalisado = precricoes(j,1);
        
        % Step 2.1.2.1.2: Restrição em X
        restricaoX      = precricoes(j,2);
        
        % Step 2.1.2.1.3: Armazenamento em caso positivo o grau de liberdade da restrição em X
        if restricaoX ~= 0
            
            % Step 2.1.2.1.3.1: Contabilizando uma posição no contador em caso positivo
            contadorAux                          = contadorAux + 1;
            grauLiberdadeRestrito(contadorAux)   = noAnalisado;
            
        end
        
    end
    
% Step 2.1.2.2: Para situações com dois graus de liberdade por nó
elseif  nGrauLiberdadePorNo==2
    
    for j=1:nPres
        
        % Step 2.1.2.2.1: Nó analisado
        noAnalisado = precricoes(j,1);
        
        % Step 2.1.2.2.2: Restrição em X
        restricaoX      = precricoes(j,2);
        
        % Step 2.1.2.2.3: Armazenamento em caso positivo o grau de liberdade da restrição em X
        if restricaoX ~= 0
            
            % Step 2.1.2.2.3.1: Contabilizando uma posição no contador em caso positivo
            contadorAux                          = contadorAux + 1;
            grauLiberdadeRestrito(contadorAux)   = 2*noAnalisado - 1;
            
        end
        
        % Step 2.1.2.2.4: Restrição em Y
        restricaoY      = precricoes(j,3);
        
        % Step 2.1.2.2.5: Armazenamento em caso positivo o grau de liberdade da restrição em Y
        if restricaoY ~= 0
            
            % Step 2.1.2.2.5.1: Contabilizando uma posição no contador em caso positivo
            contadorAux                          = contadorAux + 1;
            grauLiberdadeRestrito(contadorAux)   = 2*noAnalisado;
            
        end
        
    end
    
% Step 2.1.2.3: Para situações com três graus de liberdade por nó
elseif  nGrauLiberdadePorNo==3
    
    for j=1:nPres
        
        % Step 2.1.2.3.1: Nó analisado
        noAnalisado = precricoes(j,1);
        
        % Step 2.1.2.3.2: Restrição em X
        restricaoX      = precricoes(j,2);
        
        % Step 2.1.2.3.3: Armazenamento em caso positivo o grau de liberdade da restrição em X
        if restricaoX ~= 0
            
            % Step 2.1.2.3.3.1: Contabilizando uma posição no contador em caso positivo
            contadorAux                          = contadorAux + 1;
            grauLiberdadeRestrito(contadorAux)   = 3*noAnalisado - 2;
            
        end
        
        % Step 2.1.2.3.4: Restrição em Y
        restricaoY      = precricoes(j,3);
        
        % Step 2.1.2.3.5: Armazenamento em caso positivo o grau de liberdade da restrição em Y
        if restricaoY ~= 0
            
            % Step 2.1.2.3.5.1: Contabilizando uma posição no contador em caso positivo
            contadorAux                          = contadorAux + 1;
            grauLiberdadeRestrito(contadorAux)   = 3*noAnalisado-1;
            
        end
        
        % Step 2.1.2.3.6: Restrição em TETHA
        restricaoTetha      = precricoes(j,4);
        
        % Step 2.1.2.3.7: Armazenamento em caso positivo o grau de liberdade da restrição em Y
        if restricaoTetha ~= 0
            
            % Step 2.1.2.3.7.1: Contabilizando uma posição no contador em caso positivo
            contadorAux                          = contadorAux + 1;
            grauLiberdadeRestrito(contadorAux)   = 3*noAnalisado;
            
        end        
    end
    
    
end

% Step 2.2:  Cálculo da quantidade do número de Graus Liberdade Restrito
nGrauLibRestrito    = length(grauLiberdadeRestrito);

%%
%
%
%=========================================================================%
% STEP 3: DETERMINAÇÃO DOS GRAUS DE LIBERDADE LIVRE
%=========================================================================%
%
%
% Step 3.1:  Cálculo do vetor de Graus de Liberdade Livres
grauLiberdadeLivre  = setdiff(grauLiberdadeTotal,grauLiberdadeRestrito);

% Step 3.2:  Cálculo da quantidade do número de Graus Liberdade Livre
nGrauLibLivre       = length(grauLiberdadeRestrito);

end