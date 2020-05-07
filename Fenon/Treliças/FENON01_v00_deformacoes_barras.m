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
% Determina as deforma��es em barras de treli�a de duas dimens�es
%
% <<<<<>>>>>

% <<<<<>>>>> Vari�veis
%
% Escalares:
%
% nElem                 - Quantidade de elementos da estrutura
% el                    - Contador interno para cada elemento da estrutura
% deformacoes           - Deforma��o local dos elementos analisados
% L                     - Comprimento do elemento (el)
% nGrauLiberdadePorNo   - Total de graus de liberdade por n�
% nNosPorElemento       - Total de graus de liberdade por elemento
%
%
% Vetores
%
% comprimento 		- Vetor que armazena o comprimento dos elementos da estrutura
% vetorB            - ????
% cossenoDiretor 	- Vetor que armazena o cos diretor dos elementos da estrutura
% senoDiretor	 	- Vetor que armazena o sin diretor dos elementos da estrutura
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
function [deformacoes]=FENON01_v00_deformacoes_barras(nElem,deslocamentoNodais,elementos,nGrauLiberdadePorNo,nNosPorElemento,comprimento,cossenoDiretor,senoDiretor)
%%
%
%
%=========================================================================%
% STEP 1: CRIA��O DO VETOR DE DEFORMA��ES DA ESTRUTURA
%=========================================================================%
%
%
% Step 1.1: Matriz de coordenadas da estrutura
deformacoes=zeros(nElem,1);

% Step 1.2: La�o para determinar o valor das deforma��es por elemento
for el=1:nElem
    
    % Step 1.2.1: Inicializando vetor de deslocamentos nodais em um estado local
    deslocLocal = zeros(4,1);
    
    % Step 1.2.2: Criando correspond�ncia entre vetor graus de liberdade locais e globais
    
    % Step 1.2.2.1: Graus locais
    grauElementoLocal       = [1 2 3 4];
    
    % Step 1.2.2.3: Graus globais
    [grauElementoGlobal]    = FENON01_v00_correspondencia_grau_liberdade(el,elementos,nGrauLiberdadePorNo,nNosPorElemento);
    
    % Step 1.2.2.4: Atribui��o da correspond�ncia de grau de liberdade
    deslocLocal(grauElementoLocal) = deslocamentoNodais(grauElementoGlobal);
    
    % Step 1.2.3: Determina��es do vetor de deforma��es dos elementos
    
    % Step 1.2.3.1: Montagem do vetor B
    L = comprimento(el);
    vetorB = [-1/L 0 1/L 0];
    
    % Montagem da matriz de rota��o (Rel) do elemento (el)
    coss = cossenoDiretor(el);
    senn = senoDiretor(el);
    [Rel]=MatrizRotacao(coss,senn);
    
    deformacoes(el)=vetorB*Rel*deslocLocal;
end
end