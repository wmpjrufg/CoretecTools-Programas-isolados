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
% Realiza a an�lise estrutural de elementos estruturais utilizando o M�todo dos Elementos Finitos
%
% <<<<<>>>>>      

% <<<<<>>>>>            Vari�veis            <<<<<>>>>> %
%
% Escalares:
% ------------------------------------------------------------------------
% sz                  	- Contador auxiliar da dimens�o (linhas) de uma matriz
% nNos                  - Quantidade de n�s da estrutura
% nElem                 - Quantidade de elementos da estrutura
% nMat                  - Quantidade de materiais da estrutura
% nSec                  - Quantidade de se��es transversais
% nApo                 	- Quantidade de n�s com algum tipo de apoio externo
% nCargNo               - Quantidade de cargas nodais externas
% nRecalNo              - Quantidade de recalques nodais impostos
% nPres                 - Quantidade de prescri��es impostas em n�s
% estruturaDesenho      - String que qualifica se o desenho est� correto                                (Pode ser SIM ; NAO)
% tipoElemento          - Tipo de elemento finito utilizado na an�lise estrutural                       (Pode ser BAR1D ; TRUSS2D1 ; BEAM2D ; PORTICO2D)
% nGrauLiberdadePorNo   - Total de graus de liberdade por n�
% nNosPorElemento       - Total de graus de liberdade por elemento
% tipoMaterial          - Modelo do comportamento do material                                           (Pode ser NLEP1)
% tipoDePrescricao      - Tipo de prescri��o adotada na an�lise.                                        (Pode ser DESLOCAMENTO ; FORCA ; ARC1)
% nPassos               - N�mero passos ou divis�es de uma determinada prescri��o.                      Processo incremental
% tolerancia            - Crit�rio de parada para o processo iterativo
% nIter               	- N�mero de itera��es para determina��o do equilibrio da estrutura Fext=Fint.   Processo iterativo
% nPlotagens            - N�mero de plotagem de trajet�rias de equil�brio 
% ------------------------------------------------------------------------
%
% Vetores
% ------------------------------------------------------------------------
% nosTrajetoriaEqu      - Vetor que informa os n�s onde se deseja visualizar a trajet�ria de equilibrio
% ------------------------------------------------------------------------
%
% Matrizes
% ------------------------------------------------------------------------
% coordenadas           - Matriz de coordenadas da treli�a              [coordenadaX coordenadaY]
% elementos             - Matriz de elementos e suas conectividades 	[No1 No2 TipoMaterial TipoSecao]
% materiais             - Matriz de materiais que comp�em a estrutura 	[ModuloE Poisson TensaoEscoamentoTrac DefEscoamentoTrac  TensaoEscoamentoComp DefEscoamentoComp]
% secoes                - Matriz de se��es transversais dos elementos  	[AreaDaSecao]
% apoios                - Matriz de n�s com apoios                      [No RestricaoEmX RestricaoEmY]
% cargasNodais          - Matriz de cargas nodais                       [No ForcaEmX ForcaEmY]
% recalqNodais          - Matriz de deslocamento nodais impostos        [No RecalqueEmX RecalqueEmY] Obs.: Em caso de controle de for�a (tipoDePrescricao = 'FORCA') utilizar recalqNodais = [0 0 0];
% prescricoes           - Matriz que armazena todos os tipos de prescri��es para deslocamento nodais
% ------------------------------------------------------------------------
%
% <<<<<>>>>>

clc; clear global; close all;
%%
%
%
%=========================================================================%
% STEP 1: DADOS INICIAIS DO SISTEMA ESTRUTURAL
%=========================================================================%
%
%
% Step 1.1: Matriz de coordenadas da estrutura
config.coordenadas  = [0.0   5.0;
                      5.0   5.0;
                      0.0   0.0;
                      5.0   0.0];

% Step 1.2: Quantidade de n�s da estrutura
sz                  =   size(config.coordenadas); config.nNos=sz(1);

% Step 1.3: Matriz de conex�es dos elementos
config.elementos    =  [1    2    1     1;
                       1    4    1     2;
                       3    2    1     2;
                       4    2    1     1;
                       3    4    1     1];

% Step 1.4: Quantidade de n�s da estrutura
sz                  =   size(config.elementos); config.nElem=sz(1);

% Step 1.5: Matriz de materiais
config.materiais    =   [300E9 0.00010 750000 0.005 -750000 -0.005];

% Step 1.6: Quantidade de materiais da estrutura
sz                  =   size(config.materiais); config.nMat=sz(1);

% Step 1.7: Matriz de se��es transversais
config.secoes       =   [0.0005;0.0002];

% Step 1.8: Quantidade de se��es dos elementos
sz                  =   size(config.secoes); config.nSec=sz(1);

% Step 1.9: Matriz de apoios
config.apoios       =   [1 1 1;3 1 1];

% Step 1.10: Quantidade de n�s com algum tipo de prescri��o
sz                  =   size(config.apoios); config.nApo=sz(1);

% Step 1.11: Matriz de cargas nodais da estrutura
config.cargasNodais =  [2  50  -100;
                       4  50  -100];
                
% Step 1.12: Quantidade de cargas nodais externas
sz                  =   size(config.cargasNodais); config.nCargNo=sz(1);
                
% Step 1.13: Matriz de recalques nodais
config.recalqNodais =   [0 0 0];

% Step 1.14: Quantidade de n�s com algum tipo de prescri��o
sz                  =   size(config.recalqNodais);

% Step 1.14.1: Cria um m�todo para salvamento concatenado de todas restri��es sejam ela apoios ou prescri��es
if config.recalqNodais(1,1)==0
    config.nRecalNo    = 0;
    config.nPres       = config.nApo+config.nRecalNo;
    config.prescricoes = config.apoios;
else
    config.nRecalNo    = sz(1);
    config.nPres       = config.nApo+config.nRecalNo;
    config.prescricoes = vertcat(config.apoios,config.recalqNodais);
end

% Step 1.15: Verifica��o do desenho da estrutura
FENON01_v00_desenho_do_modelo_2D(config.coordenadas,config.elementos,config.nNos,config.nElem)
estruturaDesenho=input('A Estrutura foi desenhada corretamente (digite SIM ou NAO):','s');
if strcmp(estruturaDesenho,'SIM')
    
    fprintf('%s\n','Inicializando processamento da estrutura, aguarde...');
    pause(3);
    
else
    fprintf('%s\n','Erro no desenho verificar entrada de dados');
    fprintf('%s\n','Finalizando processamento !!!');
    pause(2);
    return
end

% Step 1.16: Configura��es gerais para an�lise estrutural via MEF
config.tipoElemento            = 'TRUSS2D1';
config.nGrauLiberdadePorNo     = 2;
config.nNosPorElemento         = 2;
config.tipoMaterial            = 'NLEP1';  
config.tipoDePrescricao        = 'FORCA';
config.nPassos                 = 10;
config.tolerancia              = 1e-08;
config.iter                    = 0;
config.nosTrajetoriaEqu        = [ 1 2 3 4 5 6 7 8 ]; 
config.nPlotagens              = numel(config.nosTrajetoriaEqu); 
%%
%
%
%=========================================================================%
% STEP 2: DADOS INICIAIS DO SISTEMA ESTRUTURAL
%=========================================================================%
%
%
% Step 2.1: Processamento da estrutura
FENON01_v00(config);

