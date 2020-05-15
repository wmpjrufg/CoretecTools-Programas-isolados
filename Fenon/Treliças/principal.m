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
% Realiza a análise estrutural de elementos estruturais utilizando o Método dos Elementos Finitos
%
% <<<<<>>>>>      

% <<<<<>>>>>            Variáveis            <<<<<>>>>> %
%
% Escalares:
% ------------------------------------------------------------------------
% sz                  	- Contador auxiliar da dimensão (linhas) de uma matriz
% nNos                  - Quantidade de nós da estrutura
% nElem                 - Quantidade de elementos da estrutura
% nMat                  - Quantidade de materiais da estrutura
% nSec                  - Quantidade de seções transversais
% nApo                 	- Quantidade de nós com algum tipo de apoio externo
% nCargNo               - Quantidade de cargas nodais externas
% nRecalNo              - Quantidade de recalques nodais impostos
% nPres                 - Quantidade de prescrições impostas em nós
% estruturaDesenho      - String que qualifica se o desenho está correto (Pode ser SIM ou NAO)
% nGrauLiberdadePorNo   - Total de graus de liberdade por nó
% nNosPorElemento       - Total de graus de liberdade por elemento
% tipoDePrescricao      - Tipo de prescrição adotada na análise. Podendo ser DESLOCAMENTO ou FORCA
% nPassos               - Número passos ou divisões de uma determinada prescrição. Processo incremental
% tolerancia            - Critério de parada para o processo iterativo
% nIter               	- Número de iterações para determinação do equilibrio da estrutura Fext=Fint por exemplo
% nPlotagens 		- Número de plotagem de trajetórias de equilíbrio 
% ------------------------------------------------------------------------
%
% Vetores
% ------------------------------------------------------------------------
% nosTrajetoriaEqu      - Vetor que informa os nós onde se deseja visualizar a trajetória de equilibrio
% ------------------------------------------------------------------------
%
% Matrizes
% ------------------------------------------------------------------------
% coordenadas           - Matriz de coordenadas da treliça              [coordenadaX coordenadaY]
% elementos             - Matriz de elementos e suas conectividades 	[No1 No2 TipoMaterial TipoSecao]
% materiais             - Matriz de materiais que compõem a estrutura 	[ModuloE Poisson TensaoEscoamentoTrac DefEscoamentoTrac  TensaoEscoamentoComp DefEscoamentoComp]
% secoes                - Matriz de seções transversais dos elementos  	[AreaDaSecao]
% apoios                - Matriz de nós com apoios                      [No RestricaoEmX RestricaoEmY]
% cargasNodais          - Matriz de cargas nodais                       [No ForcaEmX ForcaEmY]
% recalqNodais          - Matriz de deslocamento nodais impostos        [No RecalqueEmX RecalqueEmY] Obs.: Em caso de controle de força (tipoDePrescricao = 'FORCA') utilizar recalqNodais = [0 0 0];
% prescricoes           - Matriz que armazena todos os tipos de prescrições para deslocamento nodais
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

% Step 1.2: Quantidade de nós da estrutura
sz                  =   size(config.coordenadas); config.nNos=sz(1);

% Step 1.3: Matriz de conexões dos elementos
config.elementos    =  [1    2    1     1;
                       1    4    1     2;
                       3    2    1     2;
                       4    2    1     1;
                       3    4    1     1];

% Step 1.4: Quantidade de nós da estrutura
sz                  =   size(config.elementos); config.nElem=sz(1);

% Step 1.5: Matriz de materiais
config.materiais    =   [300E9 0.00010 750000 0.005 -750000 -0.005];

% Step 1.6: Quantidade de materiais da estrutura
sz                  =   size(config.materiais); config.nMat=sz(1);

% Step 1.7: Matriz de seções transversais
config.secoes       =   [0.0005;0.0002];

% Step 1.8: Quantidade de seções dos elementos
sz                  =   size(config.secoes); config.nSec=sz(1);

% Step 1.9: Matriz de apoios
config.apoios       =   [1 1 1;3 1 1];

% Step 1.10: Quantidade de nós com algum tipo de prescrição
sz                  =   size(config.apoios); config.nApo=sz(1);

% Step 1.11: Matriz de cargas nodais da estrutura
config.cargasNodais =  [2  50  -100;
                       4  50  -100];
                
% Step 1.12: Quantidade de cargas nodais externas
sz                  =   size(config.cargasNodais); config.nCargNo=sz(1);
                
% Step 1.13: Matriz de recalques nodais
config.recalqNodais =   [0 0 0];

% Step 1.14: Quantidade de nós com algum tipo de prescrição
sz                  =   size(config.recalqNodais);

% Step 1.14.1: Cria um método para salvamento concatenado de todas restrições sejam ela apoios ou prescrições
if config.recalqNodais(1,1)==0
    config.nRecalNo    = 0;
    config.nPres       = config.nApo+config.nRecalNo;
    config.prescricoes = config.apoios;
else
    config.nRecalNo    = sz(1);
    config.nPres       = config.nApo+config.nRecalNo;
    config.prescricoes = vertcat(config.apoios,config.recalqNodais);
end

% Step 1.15: Verificação do desenho da estrutura
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

% Step 1.16: Configurações gerais para análise estrutural via MEF 
config.nGrauLiberdadePorNo     = 2;
config.nNosPorElemento         = 2;
config.tipoDePrescricao        = 'FORCA';
config.nPassos                 = 10;
config.tolerancia              = 1e-08;
config.iter                    = 0;
config.nosTrajetoriaEqu        = [ 2 4 3 ]; 
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
FENON01_v00_trelicas2D(config);
