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
% nGrauLiberdadePorNo   - Total de grau de liberdade por n� da estrutura
% nGrauLibTotal         - N�mero de graus de liberdade totais da estrutura
% nGrauLibRestrito      - N�mero de graus de liberdade restritos da estrutura
% nGrauLibLivre         - N�mero de graus de liberdade livres da estrutura
% elem                  - Contador interno para cada elemento da estrutura
% nPassos               - N�mero passos ou divis�es de uma determinada prescri��o
% nIter               	- N�mero de itera��es para determina��o do equilibrio da estrutura Fext=Fint por exemplo
% tipoDePrescricao      - Tipo de prescri��o adotada na an�lise. Podendo ser DESLOCAMENTO ou FORCA
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
% cargasNodais          - Matriz de cargas nodais
%
% <<<<<>>>>>

function FENON01_v00_trelicas2D
%%
%
%
%=========================================================================%
% STEP 1: DADOS INICIAIS DO SISTEMA ESTRUTURAL
%=========================================================================%
%
%
% Step 1.1: Matriz de coordenadas da estrutura
coordenadas     =   [0 0;
    0.75 0;
    1.5 0;
    2.25 0;
    3 0;
    3.75 0;
    4.5 0;
    5.25 0;
    6 0;
    6.75 0;
    7.5 0;
    8.25 0;
    9 0;
    9.75 0;
    10.5 0;
    11.25 0;
    12 0;
    12.75 0;
    13.5 0;
    14.25 0;
    15 0;
    0.75 0.75;
    1.5 0.75;
    2.25 0.75;
    3 0.75;
    3.75 0.75;
    4.5 0.75;
    5.25 0.75;
    6 0.75;
    6.75 0.75;
    7.5 0.75;
    8.25 0.75;
    9 0.75;
    9.75 0.75;
    10.5 0.75;
    11.25 0.75;
    12 0.75;
    12.75 0.75;
    13.5 0.75;
    14.25 0.75];
% Step 1.2: Quantidade de n�s da estrutura
[nNos lixo]=size(coordenadas);

% Step 1.3: Matriz de conex�es dos elementos
elementos  =    [1 2 1 1;
    2 3 1 1;
    3 4 1 1;
    4 5 1 1;
    5 6 1 1;
    6 7 1 1;
    7 8 1 1;
    8 9 1 1;
    9 10 1 1;
    10 11 1 1;
    11 12 1 1;
    12 13 1 1;
    13 14 1 1;
    14 15 1 1;
    15 16 1 1;
    16 17 1 1;
    17 18 1 1;
    18 19 1 1;
    19 20 1 1;
    20 21 1 1;
    22 23 1 1;
    23 24 1 1;
    24 25 1 1;
    25 26 1 1;
    26 27 1 1;
    27 28 1 1;
    28 29 1 1;
    29 30 1 1;
    30 31 1 1;
    31 32 1 1;
    32 33 1 1;
    33 34 1 1;
    34 35 1 1;
    35 36 1 1;
    36 37 1 1;
    37 38 1 1;
    38 39 1 1;
    39 40 1 1;
    1 22 1 1;
    2 23 1 2;
    3 24 1 2;
    4 25 1 2;
    5 26 1 2;
    6 27 1 2;
    7 28 1 2;
    8 29 1 2;
    9 30 1 2;
    10 31 1 2;
    31 12 1 2;
    32 13 1 2;
    33 14 1 2;
    34 15 1 2;
    35 16 1 2;
    36 17 1 2;
    37 18 1 2;
    38 19 1 2;
    39 20 1 2;
    40 21 1 1;
    2 22 1 3;
    3 23 1 3;
    4 24 1 3;
    5 25 1 3;
    6 26 1 3;
    7 27 1 3;
    8 28 1 3;
    9 29 1 3;
    10 30 1 3;
    11 31 1 3;
    12 32 1 3;
    13 33 1 3;
    14 34 1 3;
    15 35 1 3;
    16 36 1 3;
    17 37 1 3;
    18 38 1 3;
    19 39 1 3;
    20 40 1 3];

% Step 1.4: Quantidade de n�s da estrutura
[nElem lixo]    =   size(elementos);

% Step 1.5: Matriz de materiais
materiais       =   [200E9 22E-6];

% Step 1.6: Quantidade de materiais da estrutura
[nMat lixo]     =   size(materiais);

% Step 1.7: Matriz de se��es transversais
secoes          =   [0.01;0.2];

% Step 1.9: Quantidade de se��es dos elementos
[nSec lixo]     =   size(secoes);

% Step 1.7: Matriz de condi��es prescritas (Apoios e recalques nodais)
precricoes      =   [1 1 1;21 0 0.0005];

% Step 1.9: Quantidade de n�s prescritos
[nPres lixo]    =   size(precricoes);

% Step 1.10: Quantidade de materiais da estrutura
cargasNodais    =  [22  0  -1125;
    23  0  -1125;
    24  0  -1125;
    25  0  -1125;
    26  0  -1125;
    27  0  -1125;
    28  0  -1125;
    29  0  -1125;
    30  0  -1125;
    31  0  -1125;
    32  0  -1125;
    33  0  -1125;
    34  0  -1125;
    35  0  -1125;
    36  0  -1125;
    37  0  -1125;
    38  0  -1125;
    39  0  -1125;
    40  0  -1125];

% Step 1.11: Quantidade de cargas nodais externas
[nCargNo lixo]    =   size(cargasNodais);

% Step 1.12: Configura��es gerais para an�lise estrutural
nGrauLiberdadePorNo     = 2;
tipoDePrescricao        = 'DESLOCAMENTOS';
nPassos                 = 150;
%%
%
%
%=========================================================================%
% STEP 2: DETERMINA��O DOS GRAUS DE LIBERDADE DA ESTRUTURA
%=========================================================================%
%
%
% Etapa 2.1: Determina��o dos tipos de graus de liberdade da estrutura
[grauLiberdadeTotal,nGrauLibTotal,grauLiberdadeRestrito,nGrauLibRestrito,grauLiberdadeLivre,nGrauLibLivre]=FENON01_v00_grau_de_liberdade_estrutura(nNos,nGrauLiberdadePorNo,nPres,precricoes);
%%
%
%
%=========================================================================%
% STEP 3: FASE DE PROCESSAMENTO DA ESTRUTURA
%=========================================================================%
%
%
% Step 3.1: Monta o vetor de carregamentos nodais ou deslocamentos nodais de acordo com o tipo de pescri��o adotada
if      strcmp(tipoDePrescricao,'FORCA')
    
    % Step 3.1.1: Monta o vetor de for�as externas para a estrutura de acordo com o passo condicionado
    [Fest]          = CargaNos(Mnoscar,nNos,t_elem,nnoscar,Mcoor);
    deltaForca      = Fest/nPassos;
    deltaPrescrito  = deltaForca;
    
elseif  strcmp(tipoDePrescricao,'DESLOCAMENTO')
    
    % Step 3.1.2: Monta o vetor de deslocamentos externos para a estrutura de acordo com o passo condicionado
    [Uest]              = CargaNos(Mnoscar,nNos,t_elem,nnoscar,Mcoor);
    deltaDeslocamento   = Uest/nPassos;
    deltaPrescrito      = deltaDeslocamento;
    
end

% Step 3.2: Cria o vetor para armazenar as prescri��es sejam elas de carga ou deslocamento
pescritoCargaouDesloc = zeros(1,max(nGrauLibTotal));

% Step 3.3: Solicitanto a estrutura de acordo com o n�mero de passos para montagem da trajet�ria de equilibrio
for i=1:nPassos
    
    % Step 3.3.1: Montagem do vetor de prescri��es no passo el�stico
    pescritoCargaouDesloc = pescritoCargaouDesloc + deltaPrescrito;
    
    % Step 3.1: Inicializando a matriz de rigidez tangente da estrutura
    rigidezEstrutura=zeros(max(nGrauLibTotal));
    for elem=1:nElem
        [rigidezEstrutura]=RigidezEstrutura(coordenadas,materiais,secoes,elementos,elem,t_elem,nNoEL,rigidezEstrutura);
    end
    
    for j=1:nIter
        
        
        
    end
    
    
    
end




%=========================================================================%
% ETAPA 5: RESOLVENDO O PROBLEMA F=K.U --> U = K-1 x F
%=========================================================================%

[Uestqq]=Deslocamento(gllivre,rigidezEstrutura,Fest,glimp,nNos);

[Nqq]=Esforcos(Uestqq,nNoEL,t_elem,nele,Mcoor,Mpmat,Mpsec,Mpele);


end