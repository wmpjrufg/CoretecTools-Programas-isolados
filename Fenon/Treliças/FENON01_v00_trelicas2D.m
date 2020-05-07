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
% 01-05-20      - Vers�o inicial implementada por W M Pereira Junior e D C Borges
% <<<<<>>>>>

% <<<<<>>>>> Descri��o
%
% Determina o deslocamentos nodais de uma treli�a utilizando o M�todo dos
% Elementos Finitos
%
% <<<<<>>>>>

% <<<<<>>>>> Vari�veis
%
% Escalares:
% nNos                  - Quantidade de n�s da estrutura
% nElem                 - Quantidade de elementos da estrutura
% nMat                  - Quantidade de materiais da estrutura
% nSec                  - Quantidade de se��es transversais
% nPres                 - Quantidade de n�s com prescri��es
% nCargNo               - Quantidade de cargas nodais externas
% nGrauLiberdadePorNo   - Total de graus de liberdade por n�
% nNosPorElemento       - Total de graus de liberdade por elemento
% nGrauLibTotal         - N�mero de graus de liberdade totais da estrutura
% nGrauLibRestrito      - N�mero de graus de liberdade restritos da estrutura
% nGrauLibLivre         - N�mero de graus de liberdade livres da estrutura
% elem                  - Contador interno para cada elemento da estrutura
% nPassos               - N�mero passos ou divis�es de uma determinada prescri��o
% nIter               	- N�mero de itera��es para determina��o do equilibrio da estrutura Fext=Fint por exemplo
% tipoDePrescricao      - Tipo de prescri��o adotada na an�lise. Podendo ser DESLOCAMENTO ou FORCA
% tolerancia            - Crit�rio de parada para o processo iterativo
% alphaPasso            - Passo da busca no processo iterativo para X(t+1) = X(t) + alpha.Sk(t)
%
%
% Vetores
%
% grauLiberdadeTotal    - Vetor Grau de Liberdade Total da estrutura
% grauLiberdadeRestrito - Vetor Grau de Liberdade Restrito da estrutura
% grauLiberdadeLivre    - Vetor Grau de Liberdade Livre da estrutura
%
% Matrizes
%
% coordenadas           - Matriz de coordenadas da treli�a [coordenadaX coordenadaY]
% elementos             - Matriz de elementos e suas conectividades [No1 No2 TipoMaterial TipoSecao]
% materiais             - Matriz de materiais que comp�em a estrutura [ModuloE Poisson]
% secoes                - Matriz de se��es transversais dos elementos  [AreaDaSecao]
% precricoes            - Matriz de n�s prescritos [No PrescricaoEmX PrescricaoEmY]
% cargasNodais          - Matriz de cargas nodais [No For�aEmX For�aEmY]
%
% <<<<<>>>>>

function FENON01_v00_trelicas2D
clc; clear all;
%%
%
%
%=========================================================================%
% STEP 1: DADOS INICIAIS DO SISTEMA ESTRUTURAL
%=========================================================================%
%
%
% Step 1.1: Matriz de coordenadas da estrutura
coordenadas     =  [0.0   5.0;
                    5.0   5.0;
                    0.0   0.0;
                    5.0   0.0];

% Step 1.2: Quantidade de n�s da estrutura
sz              =   size(coordenadas); nNos=sz(1);

% Step 1.3: Matriz de conex�es dos elementos
elementos       =  [1    2    1     1;
                    1    4    1     2;
                    3    2    1     2;
                    4    2    1     1;
                    3    4    1     1];

% Step 1.4: Quantidade de n�s da estrutura
sz              =   size(elementos); nElem=sz(1);

% Step 1.5: Matriz de materiais
materiais       =   [300E9 0.00010];

% Step 1.6: Quantidade de materiais da estrutura
sz              =   size(materiais); nMat=sz(1);

% Step 1.7: Matriz de se��es transversais
secoes          =   [0.0005;0.0002];

% Step 1.8: Quantidade de se��es dos elementos
sz              =   size(secoes); nSec=sz(1);

% Step 1.9: Matriz de apoios
apoios      =   [1 1 1;3 1 1];

% Step 1.10: Quantidade de n�s com algum tipo de prescri��o
sz              =   size(apoios); nApo=sz(1);

% Step 1.11: Matriz de cargas nodais da estrutura
cargasNodais    =  [2  50  -100;
                    4  50  -100];
                
% Step 1.12: Quantidade de cargas nodais externas
sz              =   size(cargasNodais); nCargNo=sz(1);
                
% Step 1.13: Matriz de recalques nodais
recalqNodais    =   [0 0 0];

% Step 1.14: Quantidade de n�s com algum tipo de prescri��o
sz              =   size(recalqNodais); nRecalNo=sz(1);


% Step 1.15: Determina��o das propriedades dos elementos do sistema estrutural

% Step 1.15.1: Inicializando vari�veis
area            = zeros(1,nElem);
comprimento     = zeros(1,nElem);
cossenoDiretor  = zeros(1,nElem);
senoDiretor     = zeros(1,nElem);

% Step 1.15.2: Detemrina��o das propriedades dos elementos da malha de MEF
for el=1:nElem
    
    [area(el),comprimento(el),cossenoDiretor(el),senoDiretor(el)]=Propriedades(coordenadas,secoes,elementos,el);
    
end


% Step 1.16: Configura��es gerais para an�lise estrutural via MEF 
nGrauLiberdadePorNo     = 2;
nNosPorElemento         = 2;
tipoDePrescricao        = 'FORCA';
nPassos                 = 150;
tolerancia              = 1e-08;
alphaPasso              = 1e-4;
%%
%
%
%=========================================================================%
% STEP 2: DETERMINA��O DOS GRAUS DE LIBERDADE DA ESTRUTURA
%=========================================================================%
%
%
% Etapa 2.1: Determina��o dos tipos de graus de liberdade da estrutura
[grauLiberdadeTotal,nGrauLibTotal,grauLiberdadeRestrito,nGrauLibRestrito,grauLiberdadeLivre,nGrauLibLivre]=FENON01_v00_grau_de_liberdade_estrutura(nNos,nGrauLiberdadePorNo,nApo,apoios);
%%
%
%
%=========================================================================%
% STEP 3: FASE DE MONTAGEM DO VETOR DE PRESCRI��O PARA PROCESSO ITERATIVO
%=========================================================================%
%
%
% Step 3.1: Monta o vetor de carregamentos nodais ou deslocamentos nodais de acordo com o tipo de pescri��o adotada
if      strcmp(tipoDePrescricao,'FORCA')
    
    % Step 3.1.1: Monta o vetor de for�as externas para a estrutura de acordo com o passo condicionado
    [forcaExterna]  = FENON01_v00_carga_dos_nos(nCargNo,cargasNodais,nGrauLiberdadePorNo,nNos);
    deltaForca      = forcaExterna/nPassos;
    
elseif  strcmp(tipoDePrescricao,'DESLOCAMENTO')
    
    % Step 3.1.2: Monta o vetor de for�as externas para a estrutura de acordo com o passo condicionado
    [recalExterna]  = FENON01_v00_carga_dos_nos(nRecalNo,recalqNodais,nGrauLiberdadePorNo,nNos);
    deltaRecal      = recalExterna/nPassos;
    
end
%%
%
%
%=========================================================================%
% STEP 4: FASE DE PROCESSAMENTO DA ESTRUTURA
%=========================================================================%
%
%
% Step 4.1: Cria o vetor de deslocamentos nodais e prescri��es nodais
if      strcmp(tipoDePrescricao,'FORCA')
    
    % Step 4.1.1: Monta o vetor deslocamentos nodais para primeira rodada de carregamento para controle de for�a
    deslocNovo = zeros(1,nGrauLibTotal);
    
elseif  strcmp(tipoDePrescricao,'DESLOCAMENTO')
    
    % Step 4.1.2: Monta o vetor deslocamentos nodais para primeira rodada de carregamento para controle de deslocamentos
    deslocNovo = deltaRecal;
    
end

% Step 4.2: In�cio do processo incremental (rodada de carregamento da estrutura)

% Step 4.2.1: Inicializa o vetor de deslocamentos nodais e for�as externas nodais
deslocAtual     = zeros(nGrauLibTotal,1);
forcaExtAtual   = zeros(nGrauLibTotal,1);

% Step 4.2.2: Processo incremental
for passoPres=1:nPassos
    
    % Step 4.2.2.1: Inicializando o vetor de deslocamentos do passo atual
    if      strcmp(tipoDePrescricao,'FORCA')
        
        deslocAtual=deslocNovo;
        
    elseif  strcmp(tipoDePrescricao,'DESLOCAMENTO')
        
        deslocAtual=deslocNovo+deltaRecal;
        
    end
    
    % Step 4.2.2.2: Inicializando o vetor de for�as externas do passo atual
    if      strcmp(tipoDePrescricao,'FORCA')
        
        forcaExtAtual = forcaExtAtual + deltaForca;
        
    elseif  strcmp(tipoDePrescricao,'DESLOCAMENTO')
        
        % forcaExtAtual=forcaExtAtual+deltaForca; % TEM QUE IMPLEMENTAR TEM
        % QUE IMPLEMENTAR
        
    end
    
    % Step 4.2.2.3: Montagem do vetor de deforma��es dos elementos
    [deformacoes]=FENON01_v00_deformacoes_barras(nElem,deslocAtual,elementos,nGrauLiberdadePorNo,nNosPorElemento,comprimento,cossenoDiretor,senoDiretor);
    
    % Step 4.2.2.4: Atualiza��o das tens�es internas nos elementos e m�dulo de elasticidade
    [deformacoes]=FENON01_v00_deformacoes_barras(nElem,deslocAtual,elementos,nGrauLiberdadePorNo,nNosPorElemento,comprimento,cossenoDiretor,senoDiretor);
    
    % Step 4.2.2.5: Atualiza��o do vetor de for�as internas
    fem= ConstitutiveModel(fem,deformacoes);
        
    % Newton-Raphson ou qualquer processo n�merico para equilibro Fext=Fint
    while change>conv
        
        
        
    end
    
    
    
end


end