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
% 01-05-20      - Versão inicial implementada por W M Pereira Junior e D C Borges
% <<<<<>>>>>

% <<<<<>>>>> Descrição
%
% Determina o deslocamentos nodais de uma treliça utilizando o Método dos
% Elementos Finitos
%
% <<<<<>>>>>

% <<<<<>>>>> Variáveis
%
% Escalares:
% nNos                  - Quantidade de nós da estrutura
% nElem                 - Quantidade de elementos da estrutura
% nMat                  - Quantidade de materiais da estrutura
% nSec                  - Quantidade de seções transversais
% nPres                 - Quantidade de nós com prescrições
% nCargNo               - Quantidade de cargas nodais externas
% nGrauLiberdadePorNo   - Total de graus de liberdade por nó
% nNosPorElemento       - Total de graus de liberdade por elemento
% nGrauLibTotal         - Número de graus de liberdade totais da estrutura
% nGrauLibRestrito      - Número de graus de liberdade restritos da estrutura
% nGrauLibLivre         - Número de graus de liberdade livres da estrutura
% elem                  - Contador interno para cada elemento da estrutura
% nPassos               - Número passos ou divisões de uma determinada prescrição
% nIter               	- Número de iterações para determinação do equilibrio da estrutura Fext=Fint por exemplo
% tipoDePrescricao      - Tipo de prescrição adotada na análise. Podendo ser DESLOCAMENTO ou FORCA
% tolerancia            - Critério de parada para o processo iterativo
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
% coordenadas           - Matriz de coordenadas da treliça [coordenadaX coordenadaY]
% elementos             - Matriz de elementos e suas conectividades [No1 No2 TipoMaterial TipoSecao]
% materiais             - Matriz de materiais que compõem a estrutura [ModuloE Poisson]
% secoes                - Matriz de seções transversais dos elementos  [AreaDaSecao]
% precricoes            - Matriz de nós prescritos [No PrescricaoEmX PrescricaoEmY]
% cargasNodais          - Matriz de cargas nodais [No ForçaEmX ForçaEmY]
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

% Step 1.2: Quantidade de nós da estrutura
sz              =   size(coordenadas); nNos=sz(1);

% Step 1.3: Matriz de conexões dos elementos
elementos       =  [1    2    1     1;
                    1    4    1     2;
                    3    2    1     2;
                    4    2    1     1;
                    3    4    1     1];

% Step 1.4: Quantidade de nós da estrutura
sz              =   size(elementos); nElem=sz(1);

% Step 1.5: Matriz de materiais
materiais       =   [300E9 0.00010];

% Step 1.6: Quantidade de materiais da estrutura
sz              =   size(materiais); nMat=sz(1);

% Step 1.7: Matriz de seções transversais
secoes          =   [0.0005;0.0002];

% Step 1.8: Quantidade de seções dos elementos
sz              =   size(secoes); nSec=sz(1);

% Step 1.9: Matriz de apoios
apoios      =   [1 1 1;3 1 1];

% Step 1.10: Quantidade de nós com algum tipo de prescrição
sz              =   size(apoios); nApo=sz(1);

% Step 1.11: Matriz de cargas nodais da estrutura
cargasNodais    =  [2  50  -100;
                    4  50  -100];
                
% Step 1.12: Quantidade de cargas nodais externas
sz              =   size(cargasNodais); nCargNo=sz(1);
                
% Step 1.13: Matriz de recalques nodais
recalqNodais    =   [0 0 0];

% Step 1.14: Quantidade de nós com algum tipo de prescrição
sz              =   size(recalqNodais); nRecalNo=sz(1);


% Step 1.15: Determinação das propriedades dos elementos do sistema estrutural

% Step 1.15.1: Inicializando variáveis
area            = zeros(1,nElem);
comprimento     = zeros(1,nElem);
cossenoDiretor  = zeros(1,nElem);
senoDiretor     = zeros(1,nElem);

% Step 1.15.2: Detemrinação das propriedades dos elementos da malha de MEF
for el=1:nElem
    
    [area(el),comprimento(el),cossenoDiretor(el),senoDiretor(el)]=Propriedades(coordenadas,secoes,elementos,el);
    
end


% Step 1.16: Configurações gerais para análise estrutural via MEF 
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
% STEP 2: DETERMINAÇÃO DOS GRAUS DE LIBERDADE DA ESTRUTURA
%=========================================================================%
%
%
% Etapa 2.1: Determinação dos tipos de graus de liberdade da estrutura
[grauLiberdadeTotal,nGrauLibTotal,grauLiberdadeRestrito,nGrauLibRestrito,grauLiberdadeLivre,nGrauLibLivre]=FENON01_v00_grau_de_liberdade_estrutura(nNos,nGrauLiberdadePorNo,nApo,apoios);
%%
%
%
%=========================================================================%
% STEP 3: FASE DE MONTAGEM DO VETOR DE PRESCRIÇÃO PARA PROCESSO ITERATIVO
%=========================================================================%
%
%
% Step 3.1: Monta o vetor de carregamentos nodais ou deslocamentos nodais de acordo com o tipo de pescrição adotada
if      strcmp(tipoDePrescricao,'FORCA')
    
    % Step 3.1.1: Monta o vetor de forças externas para a estrutura de acordo com o passo condicionado
    [forcaExterna]  = FENON01_v00_carga_dos_nos(nCargNo,cargasNodais,nGrauLiberdadePorNo,nNos);
    deltaForca      = forcaExterna/nPassos;
    
elseif  strcmp(tipoDePrescricao,'DESLOCAMENTO')
    
    % Step 3.1.2: Monta o vetor de forças externas para a estrutura de acordo com o passo condicionado
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
% Step 4.1: Cria o vetor de deslocamentos nodais e prescrições nodais
if      strcmp(tipoDePrescricao,'FORCA')
    
    % Step 4.1.1: Monta o vetor deslocamentos nodais para primeira rodada de carregamento para controle de força
    deslocNovo = zeros(1,nGrauLibTotal);
    
elseif  strcmp(tipoDePrescricao,'DESLOCAMENTO')
    
    % Step 4.1.2: Monta o vetor deslocamentos nodais para primeira rodada de carregamento para controle de deslocamentos
    deslocNovo = deltaRecal;
    
end

% Step 4.2: Início do processo incremental (rodada de carregamento da estrutura)

% Step 4.2.1: Inicializa o vetor de deslocamentos nodais e forças externas nodais
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
    
    % Step 4.2.2.2: Inicializando o vetor de forças externas do passo atual
    if      strcmp(tipoDePrescricao,'FORCA')
        
        forcaExtAtual = forcaExtAtual + deltaForca;
        
    elseif  strcmp(tipoDePrescricao,'DESLOCAMENTO')
        
        % forcaExtAtual=forcaExtAtual+deltaForca; % TEM QUE IMPLEMENTAR TEM
        % QUE IMPLEMENTAR
        
    end
    
    % Step 4.2.2.3: Montagem do vetor de deformações dos elementos
    [deformacoes]=FENON01_v00_deformacoes_barras(nElem,deslocAtual,elementos,nGrauLiberdadePorNo,nNosPorElemento,comprimento,cossenoDiretor,senoDiretor);
    
    % Step 4.2.2.4: Atualização das tensões internas nos elementos e módulo de elasticidade
    [deformacoes]=FENON01_v00_deformacoes_barras(nElem,deslocAtual,elementos,nGrauLiberdadePorNo,nNosPorElemento,comprimento,cossenoDiretor,senoDiretor);
    
    % Step 4.2.2.5: Atualização do vetor de forças internas
    fem= ConstitutiveModel(fem,deformacoes);
        
    % Newton-Raphson ou qualquer processo númerico para equilibro Fext=Fint
    while change>conv
        
        
        
    end
    
    
    
end


end