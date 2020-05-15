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
% el                    - Contador auxiliar do elemento analisado vêm laço externo
% no1                   - Nó 1 analisado
% no2                   - Nó 2 analisado
% secao                 - Código da seção analisada que corresponde a posição em secoes 
% area                  - Valor da área analisada que corresponde ao valor descrito pelo usuário 
% xNo1                  - Coordenada X do nó 1 analisado
% xNo2                  - Coordenada X do nó 2 analisado
% xNo1                  - Coordenada Y do nó 1 analisado
% xNo2                  - Coordenada Y do nó 2 analisado
% deltaX                - Distância Euclidiana na direção X	
% deltaY                - Distância Euclidiana na direção Y			
% comprimento           - Comprimento da barra ou elemento	
% ang                   - Ângulo em relação ao sistema global de coordenadas
% coss                  - Cosseno diretor
% senn                  - Seno diretor
% ------------------------------------------------------------------------
%
% Vetores
% ------------------------------------------------------------------------
%
%
% ------------------------------------------------------------------------
%
% Matrizes
% ------------------------------------------------------------------------
% coordenadas           - Matriz de coordenadas da treliça              [coordenadaX coordenadaY]
% elementos             - Matriz de elementos e suas conectividades 	[No1 No2 TipoMaterial TipoSecao]
% secoes                - Matriz de seções transversais dos elementos  	[AreaDaSecao]
% ------------------------------------------------------------------------
%
% <<<<<>>>>>                                 <<<<<>>>>> %

function [area,comprimento,coss,senn]=FENON01_v00_prop_elementos(coordenadas,elementos,secoes,el)
%%
%
%
%=========================================================================%
% STEP 1: VERIFICANDO ATRIBUIÇÕES DE NÓS, ÁREAS E DIREÇÕES DIRETORAS
%=========================================================================%
%
%
% Step 1.1: Atribuição dos dados da entrada principal
no1     = elementos(el,1);
no2     = elementos(el,2);
secao   = elementos(el,4);
area    = secoes(secao,1);
xNo1=coordenadas(no1,1);
yNo1=coordenadas(no1,2);
xNo2=coordenadas(no2,1);
yNo2=coordenadas(no2,2);


% Step 1.2: Definindo o comprimento do elemento estrutural

% Step 1.2.1: Distância Euclidiana nas direções
deltaX=xNo2-xNo1;
deltaY=yNo2-yNo1;

% Step 1.2.2: Comprimento total do elemento
comprimento=sqrt((xNo1-xNo2)^2+(yNo1-yNo2)^2);

% Step 1.3: Parâmetros referentes a inclinação do elemento
coss=deltaX/comprimento;
senn=deltaY/comprimento;

% if deltaX==0
%    ang=90;
% else
%    ang=atan(deltaY/deltaX);
% end


end