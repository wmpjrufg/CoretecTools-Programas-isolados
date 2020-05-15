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
% el                    - Contador auxiliar do elemento analisado v�m la�o externo
% no1                   - N� 1 analisado
% no2                   - N� 2 analisado
% secao                 - C�digo da se��o analisada que corresponde a posi��o em secoes 
% area                  - Valor da �rea analisada que corresponde ao valor descrito pelo usu�rio 
% xNo1                  - Coordenada X do n� 1 analisado
% xNo2                  - Coordenada X do n� 2 analisado
% xNo1                  - Coordenada Y do n� 1 analisado
% xNo2                  - Coordenada Y do n� 2 analisado
% deltaX                - Dist�ncia Euclidiana na dire��o X	
% deltaY                - Dist�ncia Euclidiana na dire��o Y			
% comprimento           - Comprimento da barra ou elemento	
% ang                   - �ngulo em rela��o ao sistema global de coordenadas
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
% coordenadas           - Matriz de coordenadas da treli�a              [coordenadaX coordenadaY]
% elementos             - Matriz de elementos e suas conectividades 	[No1 No2 TipoMaterial TipoSecao]
% secoes                - Matriz de se��es transversais dos elementos  	[AreaDaSecao]
% ------------------------------------------------------------------------
%
% <<<<<>>>>>                                 <<<<<>>>>> %

function [area,comprimento,coss,senn]=FENON01_v00_prop_elementos(coordenadas,elementos,secoes,el)
%%
%
%
%=========================================================================%
% STEP 1: VERIFICANDO ATRIBUI��ES DE N�S, �REAS E DIRE��ES DIRETORAS
%=========================================================================%
%
%
% Step 1.1: Atribui��o dos dados da entrada principal
no1     = elementos(el,1);
no2     = elementos(el,2);
secao   = elementos(el,4);
area    = secoes(secao,1);
xNo1=coordenadas(no1,1);
yNo1=coordenadas(no1,2);
xNo2=coordenadas(no2,1);
yNo2=coordenadas(no2,2);


% Step 1.2: Definindo o comprimento do elemento estrutural

% Step 1.2.1: Dist�ncia Euclidiana nas dire��es
deltaX=xNo2-xNo1;
deltaY=yNo2-yNo1;

% Step 1.2.2: Comprimento total do elemento
comprimento=sqrt((xNo1-xNo2)^2+(yNo1-yNo2)^2);

% Step 1.3: Par�metros referentes a inclina��o do elemento
coss=deltaX/comprimento;
senn=deltaY/comprimento;

% if deltaX==0
%    ang=90;
% else
%    ang=atan(deltaY/deltaX);
% end


end