function [A,L,coss,senn]=Propriedades(Mcoor,Mpsec,Mpele,el)

no1=Mpele(el,1);
no2=Mpele(el,2);
mat=Mpele(el,3);
sec=Mpele(el,4);
%E=Mpmat(mat,1);
A=Mpsec(sec,1);

Xno1=Mcoor(no1,1);
Yno1=Mcoor(no1,2);
Xno2=Mcoor(no2,1);
Yno2=Mcoor(no2,2);
deltax=Xno2-Xno1;
deltay=Yno2-Yno1;

%Definindo o Comprimento do Elemento
L=sqrt((Xno1-Xno2)^2+(Yno1-Yno2)^2);

% angulo theta
ang=atan(deltay/deltax);
coss=deltax/L;
senn=deltay/L;

deltax=Xno2-Xno1;
deltay=Yno2-Yno1;

% %Definindo o Comprimento do Elemento
L=sqrt((Xno1-Xno2)^2+(Yno1-Yno2)^2);

if deltax==0
   ang=90;
else
    
end


end